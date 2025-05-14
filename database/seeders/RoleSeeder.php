<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Transportation System Specific Permissions
        $permissions = [
            // Dashboard access
            'view dashboard',

            // Commute Schedule Permissions
            'view schedules',
            'create schedules',
            'edit schedules',
            'delete schedules',


            // Bus Management
            'view buses',
            'create buses',
            'edit buses',
            'delete buses',
            'assign buses',
            'manage buses',


            // Booking Permissions
            'view bookings',
            'create bookings',
            'edit bookings',
            'cancel bookings',
            'export bookings',

            // Route Permissions
            'view routes',
            'create routes',
            'edit routes',
            'delete routes',

            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
            'edit roles',
            'delete roles',
            

            // Card Management
            'view cards',
            'register cards',
            'top-up cards',
            'block cards',
            'unblock cards',
            'transfer cards',
            'edit cards',
            'delete cards',

            // Offer Management
            'view offers',
            'create offers',
            'edit offers',
            'delete offers',
            'apply offers',

            // Transaction Management
            'view transactions',
            'refund transactions',
            'reconcile transactions',
            'export transactions',

            // Report & Analytics
            'view reports',
            'generate reports',
            'view analytics',
            'export reports',

            // System Management
            'manage roles',
            'configure settings',
            'manage notifications',
            'view logs',
            'access admin panel',
            'manage database',

            // Support Functions
            'handle support tickets',
            'resolve disputes',
            'send notifications',
        ];

        // Create Permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles with appropriate permissions

        // Ticket Agent - Front desk staff who help customers book tickets
        $ticketAgent = Role::create(['name' => 'Ticket Agent']);
        $ticketAgent->syncPermissions([
            'view dashboard',
            'view schedules',
            'view bookings',
            'create bookings',
            'view routes',
            'view cards',
            'register cards',
            'top-up cards',
            'view offers',
            'apply offers',
            'view transactions',
        ]);

        // Cashier - Handles financial transactions and card operations
        $cashier = Role::create(['name' => 'Cashier']);
        $cashier->syncPermissions([
            'view dashboard',
            'view bookings',
            'view cards',
            'register cards',
            'top-up cards',
            'block cards',
            'unblock cards',
            'view transactions',
            'refund transactions',
            'view offers',
            'apply offers',
        ]);

        // Route Manager - Manages routes and schedules
        $routeManager = Role::create(['name' => 'Route Manager']);
        $routeManager->syncPermissions([
            'view dashboard',
            'view schedules',
            'create schedules',
            'edit schedules',
            'delete schedules',
            'view routes',
            'create routes',
            'edit routes',
            'delete routes',
            'view bookings',
            'export bookings',
            'view reports',
            'generate reports',
            'view analytics',
        ]);

        // Operations Manager - Overall service operations
        $opsManager = Role::create(['name' => 'Operations Manager']);
        $opsManager->syncPermissions([
            'view dashboard',
            'view schedules',
            'create schedules',
            'edit schedules',
            'view routes',
            'create routes',
            'edit routes',
            'view bookings',
            'create bookings',
            'edit bookings',
            'cancel bookings',
            'export bookings',
            'view users',
            'view cards',
            'view offers',
            'create offers',
            'edit offers',
            'view transactions',
            'export transactions',
            'view reports',
            'generate reports',
            'view analytics',
            'export reports',
            'handle support tickets',
            'resolve disputes',
            'send notifications',
        ]);

        // Marketing Manager - Manages offers and promotions
        $marketingManager = Role::create(['name' => 'Marketing Manager']);
        $marketingManager->syncPermissions([
            'view dashboard',
            'view offers',
            'create offers',
            'edit offers',
            'delete offers',
            'view reports',
            'generate reports',
            'view analytics',
            'send notifications',
        ]);

        // Support Agent - Customer support staff
        $supportAgent = Role::create(['name' => 'Support Agent']);
        $supportAgent->syncPermissions([
            'view dashboard',
            'view bookings',
            'edit bookings',
            'cancel bookings',
            'view cards',
            'block cards',
            'unblock cards',
            'view transactions',
            'refund transactions',
            'handle support tickets',
            'resolve disputes',
            'send notifications',
        ]);

        // Admin - Full system access
        $admin = Role::create(['name' => 'Admin']);
        $admin->syncPermissions(Permission::all());

        // Super Admin - Full system access including role management
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Assign roles to sample users
        User::find(1)?->assignRole('Super Admin');
        User::find(2)?->assignRole('Admin');
        User::find(3)?->assignRole('Operations Manager');
        User::find(4)?->assignRole('Route Manager');
        User::find(5)?->assignRole('Marketing Manager');
        User::find(6)?->assignRole('Cashier');
        User::find(7)?->assignRole('Ticket Agent');
        User::find(8)?->assignRole('Support Agent');
    }
}
