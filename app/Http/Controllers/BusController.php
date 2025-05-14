<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::with('route')->paginate(10); // Adjust the number as needed
        $routes= Route::all();
        return view('buses.index', compact('buses', 'routes'));
    }

    public function create()
    {
        $routes = Route::all();
        return view('buses.create', compact('routes'));
    }

    public function store(Request $request)
    {
        try {
            // Validate incoming data
            $request->validate([
                'plate_number' => 'required|unique:buses',
                'capacity' => 'required|integer|min:1',
                'route_id' => 'required|exists:routes,id',
                'driver_name' => 'nullable|string',
                'conductor_name' => 'nullable|string',
                'status' => 'required|in:active,inactive,maintenance',
            ]);

            // Manually pass data to the Bus model
            Bus::create([
                'plate_number' => $request->input('plate_number'),
                'capacity' => $request->input('capacity'),
                'route_id' => $request->input('route_id'),
                'driver_name' => $request->input('driver_name'),
                'conductor_name' => $request->input('conductor_name'),
                'status' => $request->input('status'),
            ]);

            return redirect()->route('buses.index')->with('success', 'Bus added successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to add bus: ' . $e->getMessage());

            // Redirect back with an error message
            return back()->with('error', 'There was an error adding the bus. Please try again.');
        }
    }

    public function show(Bus $bus)
    {
        return view('buses.show', compact('bus'));
    }

    public function edit(Bus $bus)
    {
        $routes = Route::all();
        return view('buses.edit', compact('bus', 'routes'));
    }

    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'plate_number' => 'required|unique:buses,plate_number,' . $bus->id,
            'capacity' => 'required|integer|min:1',
            'route_id' => 'required|exists:routes,id',
            'driver_name' => 'nullable|string',
            'conductor_name' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        $bus->update($request->all());

        return redirect()->route('buses.index')->with('success', 'Bus updated successfully.');
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect()->route('buses.index')->with('success', 'Bus deleted successfully.');
    }
}
