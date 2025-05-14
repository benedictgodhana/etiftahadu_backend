<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SystemManagementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('can:access admin panel');
    }

    /**
     * Display the system management dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Check if user has any of the required permissions
        if (!auth()->user()->can('manage roles') &&
            !auth()->user()->can('configure settings') &&
            !auth()->user()->can('manage notifications') &&
            !auth()->user()->can('view logs') &&
            !auth()->user()->can('manage database')) {
            abort(403, 'Unauthorized action.');
        }

        // Get system health information
        $systemHealth = $this->getSystemHealth();

        // Get recent system activities
        $recentActivities = $this->getRecentActivities();

        return view('admin.system-management', compact('systemHealth', 'recentActivities'));
    }

    /**
     * Get system health information.
     *
     * @return array
     */
    private function getSystemHealth()
    {
        // In a real application, you would gather this information using system commands
        // This is a simplified example with mock data
        return [
            'cpu_usage' => rand(10, 85),
            'memory_usage' => rand(30, 90),
            'disk_usage' => rand(20, 75),
            'uptime' => '3 days, 7 hours',
            'last_reboot' => Carbon::now()->subDays(3)->format('Y-m-d H:i'),
        ];
    }

    /**
     * Get recent system activities.
     *
     * @return array
     */
    private function getRecentActivities()
    {
        // In a real application, you would retrieve this from a system_logs table
        // This is mock data for the example
        $activities = [
            [
                'type' => 'login',
                'description' => 'User login successful',
                'user' => 'admin@example.com',
                'ip_address' => '192.168.1.1',
                'timestamp' => Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s'),
                'status' => 'success'
            ],
            [
                'type' => 'settings',
                'description' => 'System settings updated',
                'user' => 'admin@example.com',
                'ip_address' => '192.168.1.1',
                'timestamp' => Carbon::now()->subHours(2)->format('Y-m-d H:i:s'),
                'status' => 'success'
            ],
            [
                'type' => 'database',
                'description' => 'Database backup created',
                'user' => 'admin@example.com',
                'ip_address' => '192.168.1.1',
                'timestamp' => Carbon::now()->subHours(12)->format('Y-m-d H:i:s'),
                'status' => 'success'
            ],
            [
                'type' => 'login',
                'description' => 'Failed login attempt',
                'user' => 'unknown',
                'ip_address' => '203.0.113.1',
                'timestamp' => Carbon::now()->subHours(24)->format('Y-m-d H:i:s'),
                'status' => 'error'
            ],
            [
                'type' => 'settings',
                'description' => 'Email configuration updated',
                'user' => 'admin@example.com',
                'ip_address' => '192.168.1.1',
                'timestamp' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
                'status' => 'success'
            ],
        ];

        return $activities;
    }

    /**
     * Refresh application cache.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshCache(Request $request)
    {
        try {
            // Clear various caches
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            // Log the action
            Log::info('Cache refreshed by user: ' . auth()->user()->email);

            return response()->json([
                'success' => true,
                'message' => 'Application cache refreshed successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Cache refresh failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh cache: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Create a database backup.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createBackup(Request $request)
    {
        // Validate the request
        $request->validate([
            'backup_name' => 'required|string|max:255',
            'include_files' => 'nullable|boolean'
        ]);

        try {
            // In a real application, you would use a package like spatie/laravel-backup
            // This is a simplified example
            $backupCommand = 'backup:run';
            if ($request->include_files) {
                $backupCommand .= ' --only-db';
            }

            Artisan::call($backupCommand);

            // Log the action
            Log::info('Database backup created by user: ' . auth()->user()->email);

            return redirect()->route('admin.system-management.index')
                ->with('success', 'Database backup created successfully!');
        } catch (\Exception $e) {
            Log::error('Database backup failed: ' . $e->getMessage());

            return redirect()->route('admin.system-management.index')
                ->with('error', 'Failed to create database backup: ' . $e->getMessage());
        }
    }

    /**
     * Display the role management page.
     *
     * @return \Illuminate\View\View
     */
    public function rolesIndex()
    {
        $this->authorize('manage roles');

        // Load roles with the count of permissions for each role
        $roles = Role::withCount('permissions')->paginate(3);

        // Group permissions by group_name
        $permissionGroups = Permission::all()->groupBy('group_name');

        // Create a count array if needed separately
        $permissionCounts = $permissionGroups->map->count();

        return view('admin.roles.index', compact('roles', 'permissionGroups', 'permissionCounts'));
    }
    /**
     * Display the settings management page.
     *
     * @return \Illuminate\View\View
     */
    public function settingsIndex()
    {
        $this->authorize('configure settings');

        // Implement settings management interface...
        // For now, we'll return a placeholder
        return view('admin.settings.index');
    }

    /**
     * Display the notification management page.
     *
     * @return \Illuminate\View\View
     */
    public function notificationsIndex()
    {
        $this->authorize('manage notifications');

        // Implement notification management interface...
        // For now, we'll return a placeholder
        return view('admin.notifications.index');
    }

    /**
     * Display the logs management page.
     *
     * @return \Illuminate\View\View
     */
    public function logsIndex()
    {
        $this->authorize('view logs');

        // Implement logs management interface...
        // For now, we'll return a placeholder
        return view('admin.logs.index');
    }

    /**
     * Display the database management page.
     *
     * @return \Illuminate\View\View
     */
    public function databaseIndex()
    {
        $this->authorize('manage database');

        // Implement database management interface...
        // For now, we'll return a placeholder
        return view('admin.database.index');
    }
}
