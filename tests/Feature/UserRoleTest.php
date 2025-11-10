<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_has_role_method_handles_null_roles_gracefully()
    {
        // Create a user without any roles
        $user = User::factory()->create();

        // Test that hasRole returns false for non-existent role
        $this->assertFalse($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('nonexistent'));

        // Test that hasAnyRole returns false for non-existent roles
        $this->assertFalse($user->hasAnyRole('admin'));
        $this->assertFalse($user->hasAnyRole(['admin', 'user']));
    }

    /** @test */
    public function user_has_role_method_works_with_existing_roles()
    {
        // Create a user and a role
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => 'admin']);

        // Attach role to user
        $user->roles()->attach($role);

        // Test that hasRole returns true for existing role
        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('user'));
    }

    /** @test */
    public function user_has_any_role_method_works_with_multiple_roles()
    {
        // Create a user and roles
        $user = User::factory()->create();
        $adminRole = Role::factory()->create(['slug' => 'admin']);
        $userRole = Role::factory()->create(['slug' => 'user']);

        // Attach roles to user
        $user->roles()->attach([$adminRole->id, $userRole->id]);

        // Test that hasAnyRole returns true when user has at least one of the roles
        $this->assertTrue($user->hasAnyRole(['admin', 'nonexistent']));
        $this->assertTrue($user->hasAnyRole(['admin']));
        $this->assertFalse($user->hasAnyRole(['nonexistent', 'another']));
    }
}
