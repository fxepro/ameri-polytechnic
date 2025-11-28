<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'No authentication token provided'
            ], 401);
        }

        try {
            DB::statement('SET search_path TO shared');
            
            $tokenHash = hash('sha256', $token);
            
            $credential = DB::table('auth_credentials')
                ->where('api_token', $tokenHash)
                ->where('credential_type', 'api_token')
                ->where(function ($query) {
                    $query->whereNull('token_expires_at')
                          ->orWhere('token_expires_at', '>', now());
                })
                ->first();

            if (!$credential) {
                DB::statement('SET search_path TO public');
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'Invalid or expired token'
                ], 401);
            }

            // Get user
            $user = DB::table('auth_users')
                ->where('id', $credential->auth_user_id)
                ->where('status', 'active')
                ->first();

            if (!$user) {
                DB::statement('SET search_path TO public');
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'User not found or inactive'
                ], 401);
            }

            DB::statement('SET search_path TO public');

            // Attach user to request
            $request->merge(['auth_user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return (object) $user;
            });

            return $next($request);

        } catch (\Exception $e) {
            DB::statement('SET search_path TO public');
            return response()->json([
                'error' => 'Authentication error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

