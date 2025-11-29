<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function index(Request $request)
    {
        try {
            // Get sorting parameters
            $sortColumn = $request->get('sort', 'code'); // Default sort by code
            $sortDirection = $request->get('direction', 'asc'); // Default ascending
            
            // Validate sort parameters
            $allowedColumns = ['code', 'name', 'email', 'phone'];
            if (!in_array($sortColumn, $allowedColumns)) {
                $sortColumn = 'code';
            }
            
            if (!in_array($sortDirection, ['asc', 'desc'])) {
                $sortDirection = 'asc';
            }
            
            $filter = $request->get('filter', 'active'); // Default to active admins
            
            // Get Super Admin display setting
            $showSuperAdmin = \App\Models\SystemSetting::getValue('super_admin_role_display', 0);
            
            if ($filter === 'archived') {
                $admins = User::onlyTrashed()
                    ->orderBy($sortColumn, $sortDirection)
                    ->paginate(20)
                    ->appends(['sort' => $sortColumn, 'direction' => $sortDirection, 'filter' => $filter]);
                $filterLabel = 'Archived';
            } else {
                $admins = User::orderBy($sortColumn, $sortDirection)
                    ->paginate(20)
                    ->appends(['sort' => $sortColumn, 'direction' => $sortDirection, 'filter' => $filter]);
                $filterLabel = 'Active';
            }
            
            // Filter out Super Admin users based on system setting
            if (!$showSuperAdmin) {
                // Get the collection and filter it
                $filteredCollection = $admins->getCollection()->filter(function ($admin) {
                    return !$admin->hasRole('Super Admin');
                });
                
                // Count total Super Admin users to adjust pagination
                $superAdminCount = $admins->getCollection()->filter(function ($admin) {
                    return $admin->hasRole('Super Admin');
                })->count();
                
                // Create a new paginator with the filtered collection
                $admins = new \Illuminate\Pagination\LengthAwarePaginator(
                    $filteredCollection,
                    $admins->total() - $superAdminCount, // Adjust total count
                    20,
                    $request->get('page', 1),
                    ['path' => $request->url()]
                );
            }
            
            Log::info($filterLabel . ' admin users loaded: ' . $admins->count());
            return view('admin.admin-users.index', compact('admins', 'filter', 'filterLabel', 'showSuperAdmin', 'sortColumn', 'sortDirection'));
        } catch (\Exception $e) {
            Log::error('Error loading admin users: ' . $e->getMessage());
            $admins = collect([]);
            $filter = 'active';
            $filterLabel = 'Active';
            $showSuperAdmin = 0;
            return view('admin.admin-users.index', compact('admins', 'filter', 'filterLabel', 'showSuperAdmin'))->with('error', 'Error loading admin users: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function create()
    {
        // Get Super Admin display setting
        $showSuperAdmin = \App\Models\SystemSetting::getValue('super_admin_role_display', 0);
        
        // Get roles based on system setting
        $roles = Role::withCount('users')->orderBy('name');
        
        if (!$showSuperAdmin) {
            $roles = $roles->where('name', '!=', 'Super Admin');
        }
        
        $roles = $roles->get();
        
        return view('admin.admin-users.create', compact('roles', 'showSuperAdmin'));
    }

    /**
     * Store a newly created admin user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:users,code',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'required|string|min:1',
        ]);

        // Convert roles string to array of IDs, then get role names
        $roleIds = [];
        if ($request->filled('roles')) {
            $roleIds = explode(',', $request->roles);
            $roleIds = array_filter($roleIds); // Remove empty values
        }

        // Validate that at least one role is selected
        if (empty($roleIds)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['roles' => 'At least one role must be selected.']);
        }

        // Get role names from IDs
        $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

        // Handle profile photo upload
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        // Create admin user
        $adminData = [
            'code' => $request->code,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'profile_photo_path' => $profilePhotoPath,
            'status' => 'active', // Default to active
        ];

        $user = User::create($adminData);
        
        // Assign roles by name
        $user->syncRoles($roles);

        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin user created successfully.');
    }

    /**
     * Display the specified admin user.
     */
    public function show(User $adminRecord)
    {
        $adminRecord->load('roles');
        return view('admin.admin-users.show', compact('adminRecord'));
    }

    /**
     * Show the form for editing the specified admin user.
     */
    public function edit(User $adminRecord)
    {
        // Get Super Admin display setting
        $showSuperAdmin = \App\Models\SystemSetting::getValue('super_admin_role_display', 0);
        
        // Get roles based on system setting
        $roles = Role::withCount('users')->orderBy('name');
        
        if (!$showSuperAdmin) {
            $roles = $roles->where('name', '!=', 'Super Admin');
        }
        
        $roles = $roles->get();
        
        return view('admin.admin-users.edit', compact('adminRecord', 'roles', 'showSuperAdmin'));
    }

    /**
     * Update the specified admin user in storage.
     */
    public function update(Request $request, User $adminRecord)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:users,code,' . $adminRecord->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $adminRecord->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $adminRecord->id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'required|string|min:1',
        ]);

        // Convert roles string to array of IDs, then get role names
        $roleIds = [];
        if ($request->filled('roles')) {
            $roleIds = explode(',', $request->roles);
            $roleIds = array_filter($roleIds); // Remove empty values
        }

        // Validate that at least one role is selected
        if (empty($roleIds)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['roles' => 'At least one role must be selected.']);
        }

        // Get role names from IDs
        $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

        // Handle profile photo upload
        $profilePhotoPath = $adminRecord->profile_photo_path;
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($profilePhotoPath && Storage::disk('public')->exists($profilePhotoPath)) {
                Storage::disk('public')->delete($profilePhotoPath);
            }
            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        // Update admin user data
        $adminData = [
            'code' => $request->code,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'profile_photo_path' => $profilePhotoPath,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $adminData['password'] = Hash::make($request->password);
        }

        $adminRecord->update($adminData);
        
        // Sync roles by name
        $adminRecord->syncRoles($roles);

        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin user updated successfully.');
    }

    /**
     * Remove the specified admin user from storage.
     */
    public function destroy(User $adminRecord)
    {
        // Delete profile photo if exists
        if ($adminRecord->profile_photo_path && Storage::disk('public')->exists($adminRecord->profile_photo_path)) {
            Storage::disk('public')->delete($adminRecord->profile_photo_path);
        }

        $adminRecord->delete();

        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin user moved to archive successfully.');
    }

    /**
     * Restore the specified admin user from archive.
     */
    public function restore($id)
    {
        try {
            $admin = User::onlyTrashed()->findOrFail($id);
            $admin->restore();
            
            return redirect()->route('admin.admin-users.index', ['filter' => 'archived'])
                ->with('success', 'Admin user restored successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.admin-users.index', ['filter' => 'archived'])
                ->with('error', 'Error restoring admin user: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete the specified admin user.
     */
    public function forceDelete($id)
    {
        try {
            $admin = User::onlyTrashed()->findOrFail($id);
            
            // Delete profile photo if exists
            if ($admin->profile_photo_path && Storage::disk('public')->exists($admin->profile_photo_path)) {
                Storage::disk('public')->delete($admin->profile_photo_path);
            }
            
            $admin->forceDelete();
            
            return redirect()->route('admin.admin-users.index', ['filter' => 'archived'])
                ->with('success', 'Admin user permanently deleted.');
        } catch (\Exception $e) {
            return redirect()->route('admin.admin-users.index', ['filter' => 'archived'])
                ->with('error', 'Error permanently deleting admin user: ' . $e->getMessage());
        }
    }
}
