<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;
    public function test_admin_can_view_all_tasks()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Task::factory()->count(3)->create();

        $this->actingAs($admin);

        $response = $this->get('/tasks', ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonCount(3); // assuming API
    }


    public function test_employee_cannot_create_tasks()
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $this->actingAs($employee);

        $response = $this->get('/tasks/create');
        $response->assertStatus(403);
    }

    public function test_employee_cannot_create_events()
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $this->actingAs($employee);

        $response = $this->get('/events/create');
        $response->assertStatus(403);
    }

    public function test_employee_can_view_assigned_tasks_only()
    {
        $employee = User::factory()->create(['role' => 'employee']);
        Task::factory()->count(2)->create(); // tasks not assigned to employee
        Task::factory()->create(['assigned_to' => $employee->id]); // task assigned to employee

        $this->actingAs($employee);

        $response = $this->get('/tasks', ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonCount(1); // only 1 task assigned to employee
    }
}
