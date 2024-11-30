<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // User Registration
    public function register(Request $request)
    {
        // Validate the input directly
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:15',
            'device_code' => 'required|string|max:255',
            'acc_type' => 'required|string|max:255',
        ]);

        try {
            // Create a new user
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->device_code = $request->device_code;
            $user->acc_type = $request->acc_type;
            $user->save();

            return response()->json(['message' => 'User registered successfully!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed: ' . $e->getMessage()], 500);
        }
    }

    // User Login
    public function login(Request $request)
    {
        // Validate the input directly
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        try {
            $token = JWTAuth::fromUser($user);
            return response()->json(['token' => $token, 'user' => $user], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    // Get authenticated user details
    public function user()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(['user' => $user], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to fetch user details'], 400);
        }
    }

    // Logout user
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to log out, token invalid or expired'], 400);
        }
    }
}
