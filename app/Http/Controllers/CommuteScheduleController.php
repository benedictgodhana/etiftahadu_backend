<?php

namespace App\Http\Controllers;

use App\Models\CommuteSchedule;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommuteScheduleController extends Controller
{
    public function index()
{
    // Your logic to fetch and display commute schedules
    // For example, you might want to fetch schedules from a database
     $schedules = CommuteSchedule::paginate(5); // Fetch schedules with pagination
     $routes = Route::all(); // Fetch all routes
     $commuteschedules = CommuteSchedule::with('route')
     ->whereBetween('departure_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
     ->get()
     ->map(function ($schedule) {
         $schedule->day_of_week = Carbon::parse($schedule->departure_time)->format('l'); // e.g., 'Monday'
         $hour = Carbon::parse($schedule->departure_time)->format('H');

         if ($hour >= 6 && $hour < 12) {
             $schedule->time_of_day = 'Morning';
         } elseif ($hour >= 12 && $hour < 17) {
             $schedule->time_of_day = 'Afternoon';
         } elseif ($hour >= 17 && $hour < 23) {
             $schedule->time_of_day = 'Evening';
         } else {
             $schedule->time_of_day = 'Other';
         }

         return $schedule;
     });

    $getTimeRange = function ($timeOfDay) {
                                    return match ($timeOfDay) {
                                        'Morning' => '6:00 - 12:00',
                                        'Afternoon' => '12:00 - 17:00',
                                        'Evening' => '17:00 - 23:00',
                                        default => 'N/A',
                                    };
                                };
                                $totalRoutes = Route::count();

                                $scheduledToday = CommuteSchedule::whereDate('departure_time', Carbon::today())->count();

                                $delayedTrips = CommuteSchedule::where('status', 'delayed')
                                    ->whereDate('departure_time', Carbon::today())
                                    ->count();

                                $cancelledTrips = CommuteSchedule::where('status', 'cancelled')
                                    ->whereDate('departure_time', Carbon::today())
                                    ->count();
                                $onTimeTrips = CommuteSchedule::where('status', 'on time');



    return view('commute.schedules', compact('schedules', 'routes', 'commuteschedules', 'getTimeRange', 'totalRoutes', 'scheduledToday', 'delayedTrips', 'cancelledTrips', 'onTimeTrips'));
}


public function getTimeRange($timeOfDay)
{
    $timeRanges = [
        'Morning' => '6:00 AM - 12:00 PM',
        'Afternoon' => '12:00 PM - 5:00 PM',
        'Evening' => '5:00 PM - 11:00 PM',
    ];

    return $timeRanges[$timeOfDay] ?? 'Unknown';
}


public function store(Request $request)
{
    // Validate incoming request
    $validated = $request->validate([
        'route_id' => 'required|exists:routes,id',
        'route_number' => 'required|string|max:255',
        'departure_time' => 'required|date_format:H:i',
        'arrival_time' => 'required|date_format:H:i',
        'status' => 'required|string|in:On Time,Delayed,Cancelled',
        'fare' => 'required|numeric|min:0',
    ]);

    // Create the new schedule
    CommuteSchedule::create([
        'route_id' => $validated['route_id'],
        'route_number' => $validated['route_number'],
        'departure_time' => $validated['departure_time'],
        'arrival_time' => $validated['arrival_time'],
        'status' => $validated['status'],
        'fare' => $validated['fare'],
    ]);

    // Redirect back with a success message
    return redirect()->route('commute.schedules.index')->with('success', 'Schedule added successfully!');
}


}
