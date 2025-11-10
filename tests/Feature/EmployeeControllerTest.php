<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin role
        $adminRole = \App\Models\Role::factory()->create([
            'name' => 'Admin',
            'slug' => 'admin'
        ]);

        // Create and authenticate a user with admin role
        $this->user = User::factory()->create();
        $this->user->roles()->attach($adminRole);
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_prevents_duplicate_name_creation()
    {
        // Create an existing employee
        $existingEmployee = Employee::factory()->create([
            'name' => 'John Doe'
        ]);

        // Attempt to create another employee with the same name
        $response = $this->post(route('employees.store'), [
            'name' => 'John Doe', // Same name
            'restaurant' => 'Test Restaurant',
            'member_since' => '2023-01-01',
            'schedule' => 'morning-shift'
        ]);

        // Should redirect back with error
        $response->assertRedirect();
        $response->assertSessionHasErrors();

        // Verify only one employee exists with this name
        $this->assertCount(1, Employee::where('name', 'John Doe')->get());
    }

    /** @test */
    public function it_allows_updating_employee_with_same_name()
    {
        // Create an employee
        $employee = Employee::factory()->create([
            'name' => 'Original Name'
        ]);

        // Update with same name (should be allowed)
        $response = $this->put(route('employees.update', $employee), [
            'name' => 'Original Name',
            'restaurant' => 'Updated Restaurant',
            'member_since' => '2023-01-01',
            'schedule' => 'morning-shift'
        ]);

        // Should succeed
        $response->assertRedirect(route('employees.index'));
        $response->assertSessionHas('success');

        // Verify employee was updated (name remains the same)
        $this->assertEquals('Original Name', $employee->fresh()->name);
    }

    /** @test */
    public function it_handles_schedule_assignment_during_creation()
    {
        // Create a schedule
        $schedule = Schedule::factory()->create(['slug' => 'morning-shift']);

        // Create employee with schedule
        $response = $this->post(route('employees.store'), [
            'name' => 'John Doe',
            'restaurant' => 'Test Restaurant',
            'member_since' => '2023-01-01',
            'schedule' => 'morning-shift'
        ]);

        // Should succeed
        $response->assertRedirect(route('employees.index'));
        $response->assertSessionHas('success');

        // Verify employee was created with schedule
        $employee = Employee::where('name', 'John Doe')->first();
        $this->assertNotNull($employee);
        $this->assertTrue($employee->schedules()->exists());
    }

    /** @test */
    public function it_handles_database_transaction_rollback_on_error()
    {
        // Create an existing employee
        Employee::factory()->create(['name' => 'Existing Employee']);

        // Attempt to create employee with duplicate name (this should trigger validation error)
        $response = $this->post(route('employees.store'), [
            'name' => 'Existing Employee', // Duplicate name
            'restaurant' => 'Test Restaurant',
            'member_since' => '2023-01-01',
            'schedule' => 'morning-shift'
        ]);

        // Should handle error gracefully
        $response->assertRedirect();
        $response->assertSessionHasErrors();

        // Verify no partial data was saved
        $this->assertCount(1, Employee::where('name', 'Existing Employee')->get());
    }


}
