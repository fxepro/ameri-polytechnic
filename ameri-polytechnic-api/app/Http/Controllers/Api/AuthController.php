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
        // Custom validation for email uniqueness in shared schema
        $emailExists = false;
        try {
            DB::statement('SET search_path TO shared');
            $emailExists = DB::table('auth_users')->where('email', $request->email)->exists();
            DB::statement('SET search_path TO public');
        } catch (\Exception $e) {
            DB::statement('SET search_path TO public');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($emailExists) {
                    if ($emailExists) {
                        $fail('The email has already been taken.');
                    }
                },
            ],
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
                'email_verified_at' => null,
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

            // Generate verification token and store in auth_users
            $token = Str::random(64);
            DB::table('auth_users')
                ->where('id', $userId)
                ->update([
                    'verification_token' => $token,
                    'verification_token_expires_at' => now()->addHours(24),
                ]);

            // Get user for email
            $user = DB::table('auth_users')->where('id', $userId)->first();

            if (!$user) {
                DB::statement('SET search_path TO public');
                return response()->json([
                    'error' => 'Registration failed',
                    'message' => 'User was created but could not be retrieved'
                ], 500);
            }

            DB::statement('SET search_path TO public');

            // Prepare response data
            $responseData = [
                'message' => 'Registration successful! Please check your email to verify your account.',
                'user' => [
                    'id' => $userId,
                    'email' => $user->email ?? $request->email,
                    'first_name' => $user->first_name ?? $request->first_name,
                    'last_name' => $user->last_name ?? $request->last_name,
                    'email_verified' => false,
                ],
            ];

            // Send verification email (non-blocking) - don't let email failure break registration
            try {
                $frontendUrl = env('FRONTEND_URL', config('app.frontend_url', 'http://localhost:4200'));
                $verificationUrl = rtrim($frontendUrl, '/') . '/verify-email?token=' . $token;
                
                if (config('mail.default') !== 'log') {
                    Mail::to($user->email)->send(new VerifyEmail($user, $token));
                    $responseData['email_sent'] = true;
                } else {
                    // For log driver, log the verification URL for easy testing
                    \Log::info('Verification email (LOG MODE) - Email: ' . $user->email);
                    \Log::info('Verification URL: ' . $verificationUrl);
                    $responseData['email_sent'] = true;
                    $responseData['verification_url'] = $verificationUrl; // Include in response for local testing
                }
            } catch (\Exception $e) {
                \Log::error('Email send failed: ' . $e->getMessage());
                \Log::error('Email error: ' . $e->getFile() . ':' . $e->getLine());
                $responseData['email_sent'] = false;
            }

            return response()->json($responseData, 201);

        } catch (\Exception $e) {
            DB::statement('SET search_path TO public');
            \Log::error('Registration exception: ' . $e->getMessage());
            \Log::error('Registration exception trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Registration failed',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
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

            // Find user by verification token
            $user = DB::table('auth_users')
                ->where('verification_token', $request->token)
                ->where('verification_token_expires_at', '>', now())
                ->whereNull('email_verified_at')
                ->first();

            if (!$user) {
                DB::statement('SET search_path TO public');
                return response()->json([
                    'error' => 'Invalid or expired verification token'
                ], 400);
            }

            // Update user email_verified_at and clear token
            DB::table('auth_users')
                ->where('id', $user->id)
                ->update([
                    'email_verified_at' => now(),
                    'verification_token' => null,
                    'verification_token_expires_at' => null,
                ]);

            // Get updated user
            $user = DB::table('auth_users')->where('id', $user->id)->first();

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

            // Generate new verification token and store in auth_users
            $token = Str::random(64);
            DB::table('auth_users')
                ->where('id', $user->id)
                ->update([
                    'verification_token' => $token,
                    'verification_token_expires_at' => now()->addHours(24),
                ]);

            DB::statement('SET search_path TO public');

            // Send verification email
            try {
                $frontendUrl = env('FRONTEND_URL', config('app.frontend_url', 'http://localhost:4200'));
                $verificationUrl = rtrim($frontendUrl, '/') . '/verify-email?token=' . $token;
                
                $responseData = ['message' => 'Verification email sent successfully'];
                
                if (config('mail.default') !== 'log') {
                    Mail::to($user->email)->send(new VerifyEmail($user, $token));
                } else {
                    \Log::info('Verification email (LOG MODE) - Email: ' . $user->email);
                    \Log::info('Verification URL: ' . $verificationUrl);
                    $responseData['verification_url'] = $verificationUrl; // Include in response for local testing
                }
                
                return response()->json($responseData);
            } catch (\Exception $e) {
                \Log::error('Failed to send verification email: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Failed to send verification email'
                ], 500);
            }

        } catch (\Exception $e) {
            DB::statement('SET search_path TO public');
            return response()->json([
                'error' => 'Failed to resend verification email',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

