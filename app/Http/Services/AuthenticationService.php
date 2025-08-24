<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Spatie\Permission\Models\Role;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class AuthenticationService
{
    public function createAdmin(Request $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_status' => 'ACTIVE',
        ]);

        $user->assignRole('super admin');

        return $user;
    }

    public function registerUser(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_status' => 'ACTIVE'
            ]);

            $user->assignRole(Role::findByName('Normal User'));  

            return [
                'user' => $user
            ];
        });
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed. Invalid credentials.'
            ], 401);
        }
    
        $user = auth()->user();
        
        // Generate refresh token for the user
        $refreshToken = JWTAuth::fromUser($user);
        
        // Set TTL for access token
        JWTAuth::factory()->setTTL((int) env('JWT_TTL', 60));
        
        $permissions = $user->getAllPermissions()
            ->merge($user->getPermissionsViaRoles())
            ->pluck('name')
            ->unique();
    
        $role = $user->roles->isNotEmpty() ? $user->roles[0]->name : '';
    
        // Create secure HttpOnly cookies for both access and refresh tokens
        $accessCookieName = env('COOKIE_NAME', 'duplication_auth_token');
        $refreshCookieName = env('REFRESH_COOKIE_NAME', 'duplication_refresh_token');

        $accessCookie = cookie(
            name: $accessCookieName,
            value: $token,
            minutes: (int) env('JWT_TTL', 60),
            path: '/',
            domain: null,
            secure: true,     // true in production
            httpOnly: true,
            sameSite: 'Lax'
        );
        
        $refreshCookie = cookie(
            name: $refreshCookieName,
            value: $refreshToken,
            minutes: (int) env('JWT_REFRESH_TTL', 20160), // 14 days default
            path: '/',
            domain: null,
            secure: true,
            httpOnly: true,
            sameSite: 'Lax'
        );
    
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'role' => $role,
            'permissions' => $permissions,
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
            ],
            'token_expires_in' => (int) env('JWT_TTL', 60) * 60, // Convert to seconds
            'refresh_token_expires_in' => (int) env('JWT_REFRESH_TTL', 20160) * 60, // Convert to seconds
        ])->withCookie($accessCookie)->withCookie($refreshCookie);
    }
    
    /**
     * Refresh the access token using a refresh token
     */
    public function refresh(Request $request)
    {
        try {
            // Get refresh token from cookie
            $refreshCookieName = env('REFRESH_COOKIE_NAME', 'duplication_refresh_token');
            $refreshToken = $request->cookie($refreshCookieName);
            
            if (!$refreshToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Refresh token not found'
                ], 401);
            }
            
            // Set the refresh token as the current token for JWTAuth
            JWTAuth::setToken($refreshToken);
            
            // Get the user from the refresh token
            $user = JWTAuth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid refresh token'
                ], 401);
            }
            
            // Generate new access token
            $newToken = JWTAuth::fromUser($user);
            
            // Generate new refresh token
            $newRefreshToken = JWTAuth::fromUser($user);
            
            // Set TTL for access token
            JWTAuth::factory()->setTTL((int) env('JWT_TTL', 60));
            
            $permissions = $user->getAllPermissions()
                ->merge($user->getPermissionsViaRoles())
                ->pluck('name')
                ->unique();
            
            $role = $user->roles->isNotEmpty() ? $user->roles[0]->name : '';
            
            // Create new secure HttpOnly cookies
            $accessCookieName = env('COOKIE_NAME', 'duplication_auth_token');
            $refreshCookieName = env('REFRESH_COOKIE_NAME', 'duplication_refresh_token');

            $accessCookie = cookie(
                name: $accessCookieName,
                value: $newToken,
                minutes: (int) env('JWT_TTL', 60),
                path: '/',
                domain: null,
                secure: true,
                httpOnly: true,
                sameSite: 'Lax'
            );
            
            $refreshCookie = cookie(
                name: $refreshCookieName,
                value: $newRefreshToken,
                minutes: (int) env('JWT_REFRESH_TTL', 20160),
                path: '/',
                domain: null,
                secure: true,
                httpOnly: true,
                sameSite: 'Lax'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Token refreshed successfully',
                'role' => $role,
                'permissions' => $permissions,
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'middle_name' => $user->middle_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                ],
                'token_expires_in' => (int) env('JWT_TTL', 60) * 60,
                'refresh_token_expires_in' => (int) env('JWT_REFRESH_TTL', 20160) * 60,
            ])->withCookie($accessCookie)->withCookie($refreshCookie);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token refresh failed: ' . $e->getMessage()
            ], 401);
        }
    }
    
    /**
     * Logout user and blacklist tokens
     */
    public function logout(Request $request)
    {
        try {
            // Get tokens from cookies
            $accessCookieName = env('COOKIE_NAME', 'duplication_auth_token');
            $refreshCookieName = env('REFRESH_COOKIE_NAME', 'duplication_refresh_token');
            
            $accessToken = $request->cookie($accessCookieName);
            $refreshToken = $request->cookie($refreshCookieName);
            
            // Blacklist tokens if they exist
            if ($accessToken) {
                try {
                    JWTAuth::setToken($accessToken);
                    JWTAuth::invalidate();
                } catch (\Exception $e) {
                    // Token might already be invalid, continue with logout
                }
            }
            
            if ($refreshToken) {
                try {
                    JWTAuth::setToken($refreshToken);
                    JWTAuth::invalidate();
                } catch (\Exception $e) {
                    // Token might already be invalid, continue with logout
                }
            }
            
            // Create expired cookies to remove them (regardless of token validity)
            $expiredAccessCookie = cookie(
                name: $accessCookieName,
                value: '',
                minutes: -1,
                path: '/',
                domain: null,
                secure: true,
                httpOnly: true,
                sameSite: 'Lax'
            );
            
            $expiredRefreshCookie = cookie(
                name: $refreshCookieName,
                value: '',
                minutes: -1,
                path: '/',
                domain: null,
                secure: true,
                httpOnly: true,
                sameSite: 'Lax'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ])->withCookie($expiredAccessCookie)->withCookie($expiredRefreshCookie);
            
        } catch (\Exception $e) {
            // Even if there's an error, we should still remove the cookies
            $accessCookieName = env('COOKIE_NAME', 'duplication_auth_token');
            $refreshCookieName = env('REFRESH_COOKIE_NAME', 'duplication_refresh_token');
            
            $expiredAccessCookie = cookie(
                name: $accessCookieName,
                value: '',
                minutes: -1,
                path: '/',
                domain: null,
                secure: true,
                httpOnly: true,
                sameSite: 'Lax'
            );
            
            $expiredRefreshCookie = cookie(
                name: $refreshCookieName,
                value: '',
                minutes: -1,
                path: '/',
                domain: null,
                secure: true,
                httpOnly: true,
                sameSite: 'Lax'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully (tokens cleared)'
            ])->withCookie($expiredAccessCookie)->withCookie($expiredRefreshCookie);
        }
    }

    public function testPushNotifications(array $tokens)
    {
        $messaging = app('firebase.messaging');

        $notification = Notification::create("Hello from Laravel", "This is a test notification");
        $cloudMessage = CloudMessage::new()
            ->withNotification($notification)
            ->withData(['custom_key' => 'custom_value']);

        return $messaging->sendMulticast($cloudMessage, $tokens);
    }
}
