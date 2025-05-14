<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\Cardholder;
use App\Models\CardTopUp;
use App\Models\CardTransaction;
use App\Models\CommuteSchedule;
use App\Models\Route;
use App\Models\TicketTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Total bookings (combination of card transactions and ticket transactions)
        $totalBookings = CardTransaction::count() + TicketTransaction::count();

        // Get monthly revenue (combining card and ticket transactions)
        $monthlyCardRevenue = CardTransaction::where('created_at', '>=', now()->startOfMonth())->sum('amount');
        $monthlyTicketRevenue = TicketTransaction::where('created_at', '>=', now()->startOfMonth())->sum('amount');
        $monthlyRevenue = $monthlyCardRevenue + $monthlyTicketRevenue;

        // Get today's revenue
        $todayCardRevenue = CardTransaction::whereDate('created_at', today())->sum('amount');
        $todayTicketRevenue = TicketTransaction::whereDate('created_at', today())->sum('amount');
        $todayRevenue = $todayCardRevenue + $todayTicketRevenue;

        // Get total card top-ups
        $topUpTransactions = CardTopUp::count();

        $totalCardHolders = Cardholder::count();

        // Get total trips (assuming CardTransaction represents trips)
        $totalTrips = CardTransaction::count();

        $availableBusRoutes=Route::count();


        $buses=Bus::count();

        // Find most popular route
        $popularRoute = CardTransaction::select('card_id', DB::raw('count(*) as trip_count'))
            ->groupBy('card_id')
            ->orderByDesc('trip_count')
            ->first();

            $registeredUsers=User::count();

        $popularRouteNumber = $popularRoute ?
            Route::find($popularRoute->route_id)?->number ?? '—' :
            '—';

        // Fetch recent card transactions
        $cardTransactions = CardTransaction::with([ 'nfcCard'])
            ->select(
                'id',
                'card_id',
                'created_at',
                'amount',
            )
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => '#CT-' . $transaction->id,
                    'route' => $transaction->route ? $transaction->route->number : '—',
                    'date' => $transaction->created_at,
                    'amount' => $transaction->amount,
                    'status' => $transaction->status
                ];
            });

        // Fetch recent ticket transactions
        $ticketTransactions = TicketTransaction::with(['card', 'route'])
            ->select(
                'id',
                'card_id',
                'route_id',
                'created_at',
                'amount',
            )
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => '#TT-' . $transaction->id,
                    'user' => $transaction->user->name ?? 'Unknown User',
                    'route' => $transaction->route ? $transaction->route->number : '—',
                    'date' => $transaction->created_at,
                    'amount' => $transaction->amount,
                    'status' => $transaction->status
                ];
            });

        // Combine and sort recent transactions by date
        $recentTransactions = $cardTransactions->concat($ticketTransactions)
            ->sortByDesc('date')
            ->take(5)
            ->values()
            ->toArray();

        // Return view with all data passed directly
        return view('dashboard',compact('totalBookings','monthlyCardRevenue','recentTransactions','ticketTransactions','cardTransactions','popularRouteNumber','totalTrips','registeredUsers','totalCardHolders','monthlyRevenue','todayRevenue','topUpTransactions','availableBusRoutes','buses'));


    }

    /**
     * Get dashboard metrics data
     *
     * @return array
     */
    private function getMetricsData()
    {
        // Total bookings (combination of card transactions and ticket transactions)
        $totalBookings = CardTransaction::count() + TicketTransaction::count();

        // Get monthly revenue (combining card and ticket transactions)
        $monthlyCardRevenue = CardTransaction::where('created_at', '>=', now()->startOfMonth())->sum('amount');
        $monthlyTicketRevenue = TicketTransaction::where('created_at', '>=', now()->startOfMonth())->sum('amount');
        $monthlyRevenue = $monthlyCardRevenue + $monthlyTicketRevenue;

        // Get today's revenue
        $todayCardRevenue = CardTransaction::whereDate('created_at', today())->sum('amount');
        $todayTicketRevenue = TicketTransaction::whereDate('created_at', today())->sum('amount');
        $todayRevenue = $todayCardRevenue + $todayTicketRevenue;

        // Get total card top-ups
        $topUpTransactions = CardTopUp::count();

        // Get total trips (assuming CardTransaction represents trips)
        $totalTrips = CardTransaction::count();

        // Find most popular route
        $popularRoute = CardTransaction::select('card_id', DB::raw('count(*) as trip_count'))
            ->groupBy('card_id')
            ->orderByDesc('trip_count')
            ->first();

        $popularRouteNumber = $popularRoute ?
            Route::find($popularRoute->route_id)?->number ?? '—' :
            '—';

        return [
            'totalBookings' => $totalBookings,
            'registeredUsers' => User::count(),
            'totalCardHolders' => Cardholder::count(),
            'monthlyRevenue' => $monthlyRevenue,
            'todayRevenue' => $todayRevenue,
            'totalTrips' => $totalTrips,
            'topUpTransactions' => $topUpTransactions,
            'availableRoutes' => Route::count(),
            'popularRoute' => $popularRouteNumber,
            'buses'=> Bus::count(),
        ];
    }

    /**
     * Get recent transactions combining card and ticket transactions
     *
     * @return array
     */
    private function getRecentTransactions()
    {
        // Get recent card transactions
        $cardTransactions = CardTransaction::with([ 'nfcCard'])
            ->select(
                'id',
                'card_id',
                'created_at',
                'amount',
            )
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => '#CT-' . $transaction->id,
                    'route' => $transaction->route ? $transaction->route->number : '—',
                    'date' => $transaction->created_at,
                    'amount' => $transaction->amount,
                    'status' => $transaction->status
                ];
            });

        // Get recent ticket transactions
        $ticketTransactions = TicketTransaction::with(['card', 'route'])
            ->select(
                'id',
                'card_id',
                'route_id',
                'created_at',
                'amount',
            )
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => '#TT-' . $transaction->id,
                    'user' => $transaction->user->name ?? 'Unknown User',
                    'route' => $transaction->route ? $transaction->route->number : '—',
                    'date' => $transaction->created_at,
                    'amount' => $transaction->amount,
                    'status' => $transaction->status
                ];
            });

        // Combine and sort by date
        $allTransactions = $cardTransactions->concat($ticketTransactions)
            ->sortByDesc('date')
            ->take(5)
            ->values()
            ->toArray();

        return $allTransactions;
    }

    /**
     * API endpoint for dashboard metrics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMetrics()
    {
        return response()->json($this->getMetricsData());
    }

    /**
     * API endpoint for revenue chart data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRevenueChartData()
    {
        // Get card transaction revenue by date
        $cardRevenueData = CardTransaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as daily_total')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // Get ticket transaction revenue by date
        $ticketRevenueData = TicketTransaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as daily_total')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // Generate dates for the last 30 days
        $dates = [];
        $values = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $formattedDate = now()->subDays($i)->format('M d');

            $cardRevenue = isset($cardRevenueData[$date]) ? $cardRevenueData[$date]->daily_total : 0;
            $ticketRevenue = isset($ticketRevenueData[$date]) ? $ticketRevenueData[$date]->daily_total : 0;

            $dates[] = $formattedDate;
            $values[] = $cardRevenue + $ticketRevenue;
        }

        return response()->json([
            'labels' => $dates,
            'values' => $values
        ]);
    }

    /**
     * API endpoint for user activity chart data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserActivityData()
    {
        $days = 7;
        $userActivity = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $formattedDate = $date->format('M d');
            $dbDate = $date->format('Y-m-d');

            // Count users active on this day (based on transactions or logins)
            $activeUsersCount = User::whereDate('last_login_at', $dbDate)->count();

            // Count new users registered on this day
            $newUsersCount = User::whereDate('created_at', $dbDate)->count();

            $userActivity[] = [
                'date' => $formattedDate,
                'active_users' => $activeUsersCount,
                'new_users' => $newUsersCount
            ];
        }

        return response()->json([
            'labels' => collect($userActivity)->pluck('date')->toArray(),
            'activeUsers' => collect($userActivity)->pluck('active_users')->toArray(),
            'newUsers' => collect($userActivity)->pluck('new_users')->toArray()
        ]);
    }

    /**
     * API endpoint for recent transactions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactions()
    {
        return response()->json($this->getRecentTransactions());
    }
}
