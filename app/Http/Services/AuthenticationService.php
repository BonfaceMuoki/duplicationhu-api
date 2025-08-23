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
    
        JWTAuth::factory()->setTTL((int) env('JWT_TTL', 60));
        $user = auth()->user();
    
        $permissions = $user->getAllPermissions()
            ->merge($user->getPermissionsViaRoles())
            ->pluck('name')
            ->unique();
    
        $role = $user->roles->isNotEmpty() ? $user->roles[0]->name : '';
    
        // Create secure HttpOnly cookie
        $cookieName = env('COOKIE_NAME', 'duplication_auth_token');

        $cookie = cookie(
            name: $cookieName,
            value: $token,
            minutes: (int) env('JWT_TTL', 60),
            path: '/',
            domain: null,
            secure: true,     // true in production
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
        ])->withCookie($cookie);
    }
    
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
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
