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
        $permissions = [
            'manage users', 'view users', 'assign roles', 'manage roles',
            'view cards', 'top-up cards', 'block cards', 'unblock cards',
            'register cards', 'update cards', 'delete cards',
            'view transactions', 'refund transactions', 'reconcile transactions', 'export transactions',
            'view reports', 'generate reports', 'view analytics',
            'configure settings', 'manage notifications', 'manage database', 'view logs',
            'view dashboard', 'access admin panel',
            'handle support tickets', 'resolve disputes', 'send notifications',
        ];

        // Create Permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles
        $cashier = Role::create(['name' => 'Cashier']);
        $cashier->syncPermissions(['view cards', 'top-up cards', 'view transactions']);

        $manager = Role::create(['name' => 'Manager']);
        $manager->syncPermissions(['view cards', 'view transactions', 'view reports', 'generate reports']);

        $admin = Role::create(['name' => 'Admin']);
        $admin->syncPermissions(Permission::all()); // Grant all permissions to Admin

        // Optionally, assign roles to users
        User::find(1)?->assignRole('Admin');
        User::find(2)?->assignRole('Manager');
        User::find(3)?->assignRole('Cashier');
    }}
