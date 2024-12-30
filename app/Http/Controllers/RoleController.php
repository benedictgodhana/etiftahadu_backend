<?php
namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function fetchRoles()
    {
        try {
            $roles = Role::all(); // Fetch all roles using Spatie Roles

            return response()->json([
                'success' => true,
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching roles',
                'error' => $e->getMessage()
            ]);
        }
    }
}
