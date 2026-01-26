<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = User::where('role', User::ROLE_ADMIN)->get();

        foreach (range(1, 5) as $i) {
            Event::create([
                'name' => "Event $i",
                'date' => now()->addDays(rand(0, 30))->format('Y-m-d'),
                'start_time' => now()->addHours(rand(8, 12))->format('H:i:s'),
                'end_time' => now()->addHours(rand(13, 18))->format('H:i:s'),
                'created_by' => $admins->random()->id, // created_by admin
                'task_id' => Task::inRandomOrder()->first()->id,
            ]);
        }
    }
}
