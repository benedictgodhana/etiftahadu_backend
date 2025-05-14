<?php
namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

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



    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:roles,name',
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,id',
    ]);

    // Create the role
    $role = Role::create(['name' => $request->name]);

    // Assign permissions if selected
    if ($request->filled('permissions')) {
        $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name');
        $role->syncPermissions($permissionNames);
    }

    return redirect()->back()->with('success', 'Role created successfully!');
}



public function update(Request $request, Role $role)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,id',
    ]);

    // Update the role name
    $role->update(['name' => $request->name]);

    // Sync permissions if provided
    if ($request->filled('permissions')) {
        $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name');
        $role->syncPermissions($permissionNames);
    } else {
        $role->syncPermissions([]); // Remove all permissions if none are provided
    }

    return redirect()->back()->with('success', 'Role updated successfully!');
}


public function destroy(Role $role)
{
    // Optionally remove all permissions first (Spatie handles this on delete too)
    $role->syncPermissions([]);

    // Delete the role
    $role->delete();

    return redirect()->back()->with('success', 'Role deleted successfully!');
}


}
