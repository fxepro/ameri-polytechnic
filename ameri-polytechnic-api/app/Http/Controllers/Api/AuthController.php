<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VerifyEmail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:shared.auth_users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            DB::statement('SET search_path TO shared');

            // Create user in auth_users table (unverified initially)
            $userId = DB::table('auth_users')->insertGetId([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone ?? null,
                'status' => 'active',
                'email_verified_at' => null, // Not verified yet
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create password credential
            DB::table('auth_credentials')->insert([
                'auth_user_id' => $userId,
                'credential_type' => 'password',
                'credential_hash' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Generate verification token
            $token = Str::random(64);
            DB::table('email_verifications')->insert([
                'auth_user_id' => $userId,
                'token' => $token,
                'expires_at' => now()->addHours(24),
                'verified' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Get user for email
            $user = DB::table('auth_users')->where('id', $userId)->first();

            // Create user profile (if you have a users table with additional info)
            // This depends on your schema structure
            // For now, we'll just return the auth_user

            DB::statement('SET search_path TO public');

            // Send verification email
            try {
                Mail::to($user->email)->send(new VerifyEmail($user, $token));
            } catch (\Exception $e) {
                // Log error but don't fail registration
                \Log::error('Failed to send verification email: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Registration successful! Please check your email to verify your account.',
                'user' => [
                    'id' => $userId,
                    'email' => $request->email,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email_verified' => false,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::statement('SET search_path TO public');
            return response()->json([
                'error' => 'Registration failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            DB::statement('SET search_path TO shared');

            // Find user by email
            $user = DB::table('auth_users')
                ->where('email', $request->email)
                ->where('status', 'active')
                ->first();

            if (!$user) {
                DB::statement('SET search_path TO public');
                return response()->json([
                    'error' => 'Invalid credentials'
                ], 401);
            }

            // Get password credential
            $credential = DB::table('auth_credentials')
                ->where('auth_user_id', $user->id)
                ->where('credential_type', 'password')
                ->first();

            if (!$credential || !Hash::check($request->password, $credential->credential_hash)) {
                DB::statement('SET search_path TO public');
                return response()->json([
                    'error' => 'Invalid credentials'
                ], 401);
            }

            // Generate new API token
            $token = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);

            // Delete old API tokens for this user
            DB::table('auth_credentials')
                ->where('auth_user_id', $user->id)
                ->where('credential_type', 'api_token')
                ->delete();

            // Create new API token
            DB::table('auth_credentials')->insert([
                'auth_user_id' => $user->id,
                'credential_type' => 'api_token',
                'api_token' => $tokenHash,
                'token_expires_at' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::statement('SET search_path TO public');

            // Check if email is verified
            $emailVerified = !empty($user->email_verified_at);

            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'first_name' => $user->first_name ?? null,
                    'last_name' => $user->last_name ?? null,
                    'email_verified' => $emailVerified,
                ],
                'token' => $token,
            ]);

        } catch (\Exception $e) {
            DB::statement('SET search_path TO public');
            return response()->json([
                'error' => 'Login failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'error' => 'No token provided'
            ], 401);
        }

        try {
            DB::statement('SET search_path TO shared');
            
            $tokenHash = hash('sha256', $token);
            
            DB::table('auth_credentials')
                ->where('api_token', $tokenHash)
                ->where('credential_type', 'api_token')
                ->delete();

            DB::statement('SET search_path TO public');

            return response()->json([
                'message' => 'Logged out successfully'
            ]);

        } catch (\Exception $e) {
            DB::statement('SET search_path TO public');
            return response()->json([
                'error' => 'Logout failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify email address
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            DB::statement('SET search_path TO shared');

            $verification = DB::table('email_verifications')
                ->where('token', $request->token)
                ->where('expires_at', '>', now())
                ->where('verified', false)
                ->first();

            if (!$verification) {
                DB::statement('SET search_path TO public');
                return response()->json([
                    'error' => 'Invalid or expired verification token'
                ], 400);
            }

            // Mark verification as complete
            DB::table('email_verifications')
                ->where('id', $verification->id)
                ->update(['verified' => true]);

            // Update user email_verified_at
            DB::table('auth_users')
                ->where('id', $verification->auth_user_id)
                ->update(['email_verified_at' => now()]);

            $user = DB::table('auth_users')->where('id', $verification->auth_user_id)->first();

            DB::statement('SET search_path TO public');

            return response()->json([
                'message' => 'Email verified successfully',
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'email_verified' => true,
                ],
            ]);

        } catch (\Exception $e) {
            DB::statement('SET search_path TO public');
            return response()->json([
                'error' => 'Verification failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            DB::statement('SET search_path TO shared');

            $user = DB::table('auth_users')
                ->where('email', $request->email)
                ->first();

            if (!$user) {
                DB::statement('SET search_path TO public');
                return response()->json([
                    'error' => 'User not found'
                ], 404);
            }

            if ($user->email_verified_at) {
                DB::statement('SET search_path TO public');
                return response()->json([
                    'message' => 'Email already verified'
                ]);
            }

            // Generate new verification token
            $token = Str::random(64);
            
            // Invalidate old tokens
            DB::table('email_verifications')
                ->where('auth_user_id', $user->id)
                ->where('verified', false)
                ->delete();

            // Create new verification token
            DB::table('email_verifications')->insert([
                'auth_user_id' => $user->id,
                'token' => $token,
                'expires_at' => now()->addHours(24),
                'verified' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::statement('SET search_path TO public');

            // Send verification email
            try {
                Mail::to($user->email)->send(new VerifyEmail($user, $token));
            } catch (\Exception $e) {
                \Log::error('Failed to send verification email: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Failed to send verification email'
                ], 500);
            }

            return response()->json([
                'message' => 'Verification email sent successfully'
            ]);

        } catch (\Exception $e) {
            DB::statement('SET search_path TO public');
            return response()->json([
                'error' => 'Failed to resend verification email',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

