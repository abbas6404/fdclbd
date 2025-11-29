<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run the seeders
        $this->seed([
            \Database\Seeders\PermissionSeeder::class,
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\RoleHasPermissionSeeder::class,
        ]);
    }

    /** @test */
    public function it_can_create_a_role()
    {
        $role = Role::create([
            'name' => 'test-role',
            'guard_name' => 'web'
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'test-role',
            'guard_name' => 'web'
        ]);
    }

    /** @test */
    public function it_can_assign_permissions_to_role()
    {
        $role = Role::create([
            'name' => 'test-role',
            'guard_name' => 'web'
        ]);

        $permission = Permission::where('name', 'system.dashboard')->first();
        
        $role->givePermissionTo($permission);

        $this->assertTrue($role->hasPermissionTo('system.dashboard'));
    }

    /** @test */
    public function it_can_assign_role_to_user()
    {
        $user = User::factory()->create();
        $role = Role::where('name', 'User')->first();

        $user->assignRole($role);

        $this->assertTrue($user->hasRole('User'));
    }

    /** @test */
    public function it_can_check_user_permissions()
    {
        $user = User::factory()->create();
        $role = Role::where('name', 'Admin')->first();
        
        $user->assignRole($role);

        $this->assertTrue($user->hasPermissionTo('role.view'));
        $this->assertTrue($user->hasPermissionTo('role.create'));
    }

    /**
     * Test that Super Admin role is hidden from role listing when setting is disabled
     */
    public function test_super_admin_role_is_hidden_from_listing_when_disabled()
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');

        $response = $this->actingAs($user)
            ->get(route('admin.setup.role.index'));

        $response->assertStatus(200);
        
        // Verify that Super Admin role is not in the listing
        $response->assertDontSee('Super Admin');
        
        // Verify that other roles are visible
        $response->assertSee('Admin');
    }

    /**
     * Test that Super Admin role is visible and editable when setting is enabled
     */
    public function test_super_admin_role_is_visible_when_enabled()
    {
        // Set the system setting to show Super Admin
        \App\Models\SystemSetting::setValue('super_admin_role_display', '1');
        
        $user = User::factory()->create();
        $user->assignRole('Admin');

        $response = $this->actingAs($user)
            ->get(route('admin.setup.role.index'));

        $response->assertStatus(200);
        
        // Verify that Super Admin role is now visible in the listing
        $response->assertSee('Super Admin');
        
        // Verify that other roles are visible
        $response->assertSee('Admin');
    }

    /**
     * Test soft delete functionality
     */
    public function test_role_soft_delete()
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');

        // Create a test role
        $role = Role::create([
            'name' => 'test-role',
            'guard_name' => 'web'
        ]);

        // Soft delete the role
        $role->delete();

        // Verify role is soft deleted
        $this->assertSoftDeleted($role);

        // Verify role is not visible in normal listing
        $response = $this->actingAs($user)
            ->get(route('admin.setup.role.index'));
        $response->assertStatus(200);
        $response->assertDontSee('test-role');

        // Verify role is visible in archived listing
        $response = $this->actingAs($user)
            ->get(route('admin.setup.role.index', ['show_archived' => true]));
        $response->assertStatus(200);
        $response->assertSee('test-role');
    }
}
