<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

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

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    // Fetch user and validate credentials
    $user = User::where('username', $request->username)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'error' => ['Invalid credentials']
        ]);
    }

    // Load roles
    $user->load('roles');

    // Generate token
    $token = $user->createToken('AppName')->plainTextToken;

    // Get role names
    $userRoles = $user->roles->pluck('name'); // Get the role names

    return response()->json(['token' => $token, 'user' => $user, 'roles' => $userRoles], 200);
}

public function user(Request $request)
{
    $user = Auth::user()->load('roles'); // Ensure roles are loaded if necessary
    return response()->json(['success' => true, 'user' => $user]);
}

    // Logout user
    public function logout(Request $request)
    {
        // Revoke the token of the authenticated user
        $request->user()->currentAccessToken()->delete();

        // Return response
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
