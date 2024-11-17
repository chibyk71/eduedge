<?php

namespace App\Http\Controllers\Settings\School;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * RolesController
 *
 *  This controller handles the creation, updating, and deletion of roles within the application.
 *  Roles define a set of permissions that can be assigned to users.
 *
 *  @package App\Http\Controllers\Settings\School
 *  @author  Alfredo Chibuike <alfredo@alfredchibuike71.com.mx>
 *  @version 1.0
 *  @since   2023-09-12
 */

class RolesController extends Controller
{
    public function index()
    {
        $tenant = tenant('id');

        $teams = Team::where('tenant_id', $tenant)->get(['id,name']);
        $permissions = Permission::all(['name','id']);
        $roles = Role::where('tenant_id', $tenant)->get(['id,name']);

        return Inertia::render('Settings/School/Roles', [
            'permissions' => $permissions,
            'roles' => $roles,
            'teams' => $teams,
        ]);
    }

    public function storeRole(Request $request)
    {
        // Save Roles settings
        $request->validate([
            'role' => 'required|string',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
            'team' => 'sometimes|string|nullable',
            'display_name' => 'required|string',
            'description' => 'required|string',
        ]);

        $role = Role::updateOrCreate(['name' => $request->name, 'tenant_id' => tenant('id')], [
            'display_name' => $request->display_name,
             'description' => $request->description,
        ]);
        // Sync the permissions for the role and team (if provided)
        $role->syncPermissions($request->permissions, $request->team);

        return redirect()->route('settings.school.roles.index')->with('success', 'Role created successfully.');
    }

    // create a function to assign role to user
    public function assignUserToRole(Request $request) {
        // Validate the request data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $user = User::find($validated['user_id']);
        $user->addRole($validated['role_id'], $validated['team_id'] ?? null);

        return redirect()->route('settings.school.roles.index')->with('success', 'Role assigned to user successfully.');
    }

    // create a function to remove role from user
    public function detatchUserFromRole(Request $request) {
        // Validate the request data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $user = User::find($validated['user_id'])->first();
        // Check if the user belongs to the currently active tenant before removing the role
        if (!$user->belongsToSchool()) {
            return redirect()->route('settings.school.roles.index')->with('error', 'You can only remove roles from users of the current tenant.');
        }

        $user->removeRole($validated['role_id'], $validated['team_id'] ?? null);

        return redirect()->route('settings.school.roles.index')->with('success', 'Role removed from user successfully.');
    }

    // create a function to add permision to user
    public function addPermissionToUser(Request $request) {
        // Validate the request data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission_id' => 'required|exists:permissions,id',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $user = User::find($validated['user_id']);
        $user->givePermissionTo($validated['permission_id'], $validated['team_id'] ?? null);

        return redirect()->route('settings.school.roles.index')->with('success', 'Permission added to user successfully.');
    }

    // a function to remove permission from user. it syncs user permissions with the provided permissions.
    public function removePermissionFromUser(Request $request) {
        // Validate the request data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission_id' => 'required|exists:permissions,id',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $user = User::find($validated['user_id']);
        $user->syncPermissions($validated['permission_id'], $validated['team_id'] ?? null);

        return redirect()->route('settings.school.roles.index')->with('success', 'Permission synced from user successfully.');
    }

    public function update(Request $request)
    {
        // Update Roles settings
        $request->validate([
            'name' => 'required|string',
            'permissions' => 'required|array|min:1',
        ]);

        $role = Role::find($request->id);
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('settings.school.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Request $request, Role $role)
    {
        // Delete Roles settings
        $role->delete();

        return redirect()->route('settings.school.roles.index')->with('success', 'Role deleted successfully.');
    }
}
