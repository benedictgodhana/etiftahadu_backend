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
        $routes = Route::all();

        return response()->json([
            'success' => true,
            'data' => $routes,
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

        $route = Route::create([
            'from' => $validated['from'],
            'to' => $validated['to'],
            'fare' => $validated['fare'],
            'user_id' => 1, // Associate the route with the current authenticated user
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

        $route->delete();

        return response()->json([
            'success' => true,
            'message' => 'Route deleted successfully',
        ]);
    }
}
