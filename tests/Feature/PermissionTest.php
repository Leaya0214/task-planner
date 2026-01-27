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

        $response = $this->get('/tasks');

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

    public function employee_cannot_create_events()
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $this->actingAs($employee);

        $response = $this->get('/events/create');
        $response->assertStatus(403);
    }
}
