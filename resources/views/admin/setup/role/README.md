# Role Setup Management

This folder contains the role management views for the real estate/construction management system using Laravel Spatie Permission package.

## File Structure

### Core Role Management
- **`index.blade.php`** - Main roles listing page with CRUD operations
- **`create.blade.php`** - Form for creating new roles with permission assignment
- **`edit.blade.php`** - Form for editing existing roles and permissions
- **`show.blade.php`** - Detailed view of role information and associated permissions

## Features

### Role Management
- Create, read, update, and delete roles
- Soft delete functionality with archive/restore capabilities
- Assign permissions to roles (using existing permissions from Spatie)
- Special handling for Super Admin role (protected from deletion)
- User count tracking per role
- Permission count display

### Security Features
- Super Admin role protection with configurable display and edit permissions
- Admin role protection (non-deletable but editable)
- Confirmation dialogs for destructive actions
- Input validation and error handling
- CSRF protection on all forms

## Usage

### Creating a Role
1. Navigate to Role Setup → Add Role
2. Enter role name (lowercase with hyphens)
3. Select guard (web/api)
4. Choose permissions from existing system permissions
5. Submit form

### Managing Roles
1. View all roles with their permission counts
2. Edit existing roles and modify permissions
3. Delete roles (with confirmation)
4. Archive roles (soft delete)
5. Restore archived roles
6. Permanently delete archived roles

### Role Assignment
1. Roles are automatically assigned to users during user creation
2. Use `$user->assignRole('role-name')` in code
3. Check permissions with `$user->hasPermissionTo('permission-name')`

## Routes Required

The following routes should be defined in your `routes/admin.php`:

```php
// Role Management
Route::resource('setup.role', RoleController::class);
```

## Dependencies

- Laravel Spatie Permission package
- Bootstrap 5 CSS framework
- FontAwesome icons
- Laravel Blade templating

## Notes

- The Super Admin role behavior is controlled by the system setting `super_admin_role_display`:
  - **When disabled (0)**: Hidden from listing, non-editable, non-deletable
  - **When enabled (1)**: Visible in listing, editable, but still non-deletable
- The Admin role cannot be deleted but can be edited and modified
- All destructive operations require user confirmation
- Soft delete functionality allows archiving roles instead of permanent deletion
- Archived roles can be restored or permanently deleted
- Permissions are managed through the Spatie package (not through custom views)
- The system supports both web and API guards for different authentication contexts
- Permission assignment is done through checkboxes in role create/edit forms
