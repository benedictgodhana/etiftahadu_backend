<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function getUserCount()
{
    $userCount = User::count(); // Count all users in the database
    return response()->json(['success' => true, 'count' => $userCount]);
}

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Apply search filter
        if ($searchQuery = $request->input('searchQuery')) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%$searchQuery%")
                  ->orWhere('email', 'like', "%$searchQuery%")
                  ->orWhere('username', 'like', "%$searchQuery%");
            });
        }

        // Apply status filter
        if ($filterByStatus = $request->input('filterByStatus')) {
            $query->where('status', $filterByStatus);
        }

        // Optional: Apply sorting
        if ($sortField = $request->input('sortField')) {
            $sortDirection = $request->input('sortDirection', 'asc'); // Default sort direction: ascending
            $query->orderBy($sortField, $sortDirection);
        }

        // Eager load roles and creator relationships using Spatie's trait
        $query->with('roles', 'creator');  // Eager load roles and creator relationships

        // Fetch all users
        $users = $query->get(); // Retrieve all records

        return response()->json([
            'success' => true,
            'data' => $users->map(function($user) {
                // Map roles and creator name to the user object
                $user->roles = $user->roles->pluck('name'); // Extract role names from the roles relationship
                $user->creator_name = $user->creator->name ?? 'N/A'; // Set creator name or 'N/A' if not available
                return $user;
            }),
        ]);
    }


    /**
     * Add a new user.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'status' => 'required|in:active,inactive',
            'role' => 'required|exists:roles,name', // Validate role name exists in roles table
        ]);

        // Find role by name
        $role = Role::where('name', $validatedData['role'])->first();

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'username' => $validatedData['username'],
            'password' => bcrypt($validatedData['password']),
            'status' => $validatedData['status'],
            'role_id' => $role->id,  // Store the role ID
            'created_by' => auth()->id(), // Assuming you're using authentication
        ]);

        // Assign the role to the user
        $user->assignRole($role->name);

        return response()->json(['success' => true, 'user' => $user], 201);
    }


    /**
     * Update a user's details.
     */
    public function update(Request $request, $id)
{
    // Validate the input data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        'phone' => 'required|string|max:15',
        'username' => 'required|string|max:255|unique:users,username,' . $id,
        'password' => 'nullable|string|min:8', // Password can be optional on update
        'status' => 'required|in:active,inactive',
        'role' => 'required|exists:roles,name', // Validate role name exists in roles table
    ]);

    // Find the user by ID
    $user = User::findOrFail($id);

    // Find the role by name
    $role = Role::where('name', $validatedData['role'])->first();

    // Update the user details
    $user->update([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'phone' => $validatedData['phone'],
        'username' => $validatedData['username'],
        'status' => $validatedData['status'],
        'role_id' => $role->id,  // Update the role ID
    ]);

    // If password is provided, hash and update it
    if (!empty($validatedData['password'])) {
        $user->update(['password' => bcrypt($validatedData['password'])]);
    }

    // Reassign the role to ensure it's updated
    $user->syncRoles($role->name);

    return response()->json(['success' => true, 'user' => $user], 200);
}


    /**
     * Deactivate a user.
     */
    public function deactivate($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update(['active' => false]);

        return response()->json(['message' => 'User deactivated successfully'], 200);
    }
}
