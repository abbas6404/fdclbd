<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'permission:setup.role']);
    }

    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        $showArchived = $request->get('show_archived', false);
        $showSuperAdmin = Auth::user()->hasRole('Super Admin');
        
        $query = Role::withCount(['permissions', 'users']);
        
        if ($showArchived) {
            // For archived roles, we'd need to implement soft deletes on roles
            // For now, just show all roles
            $roles = $query->orderBy('name')->paginate(20);
        } else {
            $roles = $query->orderBy('name')->paginate(20);
        }
        
        return view('admin.setup.role.index', compact('roles', 'showArchived', 'showSuperAdmin'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        });
        
        return view('admin.setup.role.create', compact('permissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            DB::beginTransaction();
            
            $role = Role::create(['name' => $request->name]);
            
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }
            
            DB::commit();
            
            return redirect()->route('admin.setup.role.index')
                ->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating role: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        $role->load('permissions', 'users');
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        });
        
        return view('admin.setup.role.show', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        if ($role->name === 'Super Admin' && !Auth::user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admin can edit Super Admin role.');
        }
        
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        });
        
        $role->load('permissions');
        
        // Get role permissions as array of IDs
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        // Check if user can show/edit Super Admin
        $showSuperAdmin = Auth::user()->hasRole('Super Admin');
        
        return view('admin.setup.role.edit', compact('role', 'permissions', 'rolePermissions', 'showSuperAdmin'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        if ($role->name === 'Super Admin' && !Auth::user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admin can update Super Admin role.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            DB::beginTransaction();
            
            $role->update(['name' => $request->name]);
            
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.setup.role.index')
                ->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating role: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        if (in_array($role->name, ['Super Admin', 'Admin'])) {
            return redirect()->back()
                ->with('error', 'Cannot delete system roles.');
        }
        
        try {
            $role->delete();
            return redirect()->route('admin.setup.role.index')
                ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }

    /**
     * Restore a soft-deleted role.
     */
    public function restore($id)
    {
        // This would require soft deletes on roles
        // For now, just return an error
        return redirect()->back()
            ->with('error', 'Role restoration not implemented.');
    }

    /**
     * Permanently delete a role.
     */
    public function forceDelete($id)
    {
        // This would require soft deletes on roles
        // For now, just return an error
        return redirect()->back()
            ->with('error', 'Force delete not implemented.');
    }
}

