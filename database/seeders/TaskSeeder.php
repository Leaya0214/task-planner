<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = User::where('role', User::ROLE_EMPLOYEE)->get();

        // Create 5 tasks
        foreach (range(1, 5) as $i) {
            Task::create([
                'title' => "Task $i",
                'description' => "This is description for Task $i",
                'assigned_to' => $employees->random()->id, // random employee
                'status' => ['Pending', 'In Progress', 'Completed'][array_rand(['Pending', 'In Progress', 'Completed'])],
                'priority' => ['Low', 'Medium', 'High'][array_rand(['Low', 'Medium', 'High'])],
                'due_date' => now()->addDays(rand(1, 10)),
            ]);
        }
    }
}
