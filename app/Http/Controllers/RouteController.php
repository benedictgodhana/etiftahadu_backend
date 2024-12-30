<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Fetch a route between two locations with its fare.
     */
    public function findRoute(Request $request)
    {
        $request->validate([
            'from' => 'required|string',
            'to' => 'required|string',
        ]);

        $route = Route::getRouteWithFare($request->input('from'), $request->input('to'));

        if ($route) {
            return response()->json([
                'success' => true,
                'message' => 'Route found',
                'data' => $route,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Route not found',
        ], 404);
    }

    /**
     * List all available routes.
     */
    public function index()
    {
        $routes = Route::with('user') // Eager load the user relationship
            ->where('user_id', auth()->id()) // Ensure the authenticated user can only view their own routes
            ->get(); // Get all the routes for the authenticated user

        return response()->json([
            'success' => true,
            'data' => $routes->map(function ($route) {
                return [
                    'id' => $route->id,
                    'from' => $route->from,
                    'to' => $route->to,
                    'fare' => $route->fare,
                    'user_name' => $route->user->name,
                ];
            }),
        ]);
    }

public function getRoutesCount()
{
    // Fetch the total count of all routes from the database
    $routesCount = Route::count();

    return response()->json([
        'success' => true,
        'data' => $routesCount,
    ]);
}



    /**
     * Create a new route.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|string',
            'to' => 'required|string',
            'fare' => 'required|numeric|min:0',
        ]);

        // Create the route and associate it with the authenticated user
        $route = Route::create([
            'from' => $validated['from'],
            'to' => $validated['to'],
            'fare' => $validated['fare'],
            'user_id' => auth()->id(), // Use the authenticated user's ID
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Route created successfully',
            'data' => $route,
        ], 201);
    }

    /**
     * Update an existing route.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'from' => 'required|string',
            'to' => 'required|string',
            'fare' => 'required|numeric|min:0',
        ]);

        $route = Route::findOrFail($id);

        // Ensure the authenticated user is the one who created the route
        if ($route->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this route',
            ], 403);
        }

        $route->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Route updated successfully',
            'data' => $route,
        ]);
    }

    /**
     * Delete a route.
     */
    public function destroy($id)
    {
        $route = Route::findOrFail($id);

        // Ensure the authenticated user is the one who created the route
        if ($route->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this route',
            ], 403);
        }

        $route->delete();

        return response()->json([
            'success' => true,
            'message' => 'Route deleted successfully',
        ]);
    }
}
