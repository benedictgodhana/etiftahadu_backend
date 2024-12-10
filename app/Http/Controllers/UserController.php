<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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

        // Eager load creator (created_by) relationship
        $query->with('creator');  // Assuming 'creator' is the relationship name for the user who created the record

        // Pagination
        $perPage = $request->input('perPage', 10); // Default: 10 items per page
        $users = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users->items(),
            'totalPages' => $users->lastPage(),
            'currentPage' => $users->currentPage(),
        ]);
    }


    /**
     * Add a new user.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',  // Adjust max length as necessary
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'status' => 'required|in:active,inactive',  // Validates status field
        ]);

        // Create the user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'username' => $validatedData['username'],
            'password' => bcrypt($validatedData['password']),
            'status' => $validatedData['status'],
            'created_by' => auth()->id(), // Assuming you're using authentication and the logged-in user is the creator
        ]);

        // Return success response
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    /**
     * Update a user's details.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        $user->update($validatedData);

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
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
