# Role and Permission Management System

This document provides an overview of the role and permission management system implemented in this Laravel application.

## Features

- Role-based access control (RBAC)
- Permission-based access control
- UI components that adapt based on user roles and permissions
- Route protection based on roles and permissions
- Blade directives for conditional rendering

## Roles

The system comes with the following predefined roles:

1. **Super Admin**: Has access to all features and permissions
2. **Admin**: Has access to most administrative features
3. **Moderator**: Has limited administrative access
4. **User**: Regular user with minimal permissions

## Permissions

The system includes various permissions that can be assigned to roles:

- User management: `view users`, `create users`, `edit users`, `delete users`
- Role management: `view roles`, `create roles`, `edit roles`, `delete roles`
- Permission management: `assign permissions`
- Dashboard access: `access admin dashboard`
- Other features: `manage plans`, `view reports`, `manage settings`

## Usage

### Checking Roles in Controllers

```php
// Check if user has a specific role
if ($user->hasRole('Admin')) {
    // Admin-specific logic
}

// Check if user has any of the specified roles
if ($user->hasAnyRole(['Admin', 'Super Admin'])) {
    // Logic for either Admin or Super Admin
}

// Check if user has all of the specified roles
if ($user->hasAllRoles(['Admin', 'Moderator'])) {
    // Logic for users with both Admin and Moderator roles
}
```

### Checking Permissions in Controllers

```php
// Check if user has a specific permission
if ($user->hasPermissionTo('edit users')) {
    // User can edit users
}

// Check if user has any of the specified permissions
if ($user->hasAnyPermission(['edit users', 'delete users'])) {
    // User can either edit or delete users
}
```

### Using Middleware for Route Protection

```php
// Protect routes with role middleware
Route::get('/admin/dashboard', function() {
    return view('admin.dashboard');
})->middleware(['auth', 'role:Admin|Super Admin']);

// Protect routes with permission middleware
Route::get('/admin/users', function() {
    return view('admin.users');
})->middleware(['auth', 'permission:view users']);
```

### Using Blade Directives in Templates

```blade
{{-- Role-based rendering --}}
@role('Admin')
    <div class="admin-panel">Admin Panel Content</div>
@endrole

{{-- Permission-based rendering --}}
@permission('edit users')
    <button class="btn btn-primary">Edit User</button>
@endpermission

{{-- Check for any role from a list --}}
@hasanyrole('Admin|Super Admin')
    <div class="management-tools">Management Tools</div>
@endhasanyrole
```

## Helper Functions

The application includes helper functions for checking roles and permissions:

```php
// Check roles
RolePermissionHelper::hasRole('Admin');
RolePermissionHelper::hasAnyRole(['Admin', 'Super Admin']);
RolePermissionHelper::hasAllRoles(['Admin', 'Moderator']);

// Check permissions
RolePermissionHelper::hasPermission('edit users');
RolePermissionHelper::hasAnyPermission(['edit users', 'delete users']);

// Get user roles and permissions
$roles = RolePermissionHelper::getUserRoles();
$permissions = RolePermissionHelper::getUserPermissions();
```

## Management Interface

The system includes a complete management interface for:

- Viewing and managing roles
- Creating and assigning permissions
- Assigning roles to users

Access the management interface at `/admin/roles` and `/admin/permissions`. 