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
            $sortDirection = $request->input('sortDirection', 'asc');
            $query->orderBy($sortField, $sortDirection);
        }

        // Eager load roles and creator relationships
        $query->with('roles', 'creator');

        $users = $query->paginate(10);

        // Get all available roles for dropdowns or assignment
        $roles = Role::all();

        return view('users.index', [
            'users' => $users,
            'roles' => $roles,
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
            'role' => 'required|exists:roles,name',
        ]);

        $role = Role::where('name', $validatedData['role'])->first();

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'username' => $validatedData['username'],
            'password' => bcrypt($validatedData['password']),
            'status' => $validatedData['status'],
            'role_id' => $role->id,
            'created_by' => auth()->id(),
        ]);

        $user->assignRole($role->name);

        return redirect()->back()->with('success', 'User created successfully.');
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
