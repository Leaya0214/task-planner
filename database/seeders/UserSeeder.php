<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password1234'), // Change this in production!
            'role'     => 'admin',
        ]);

        // Create Employee
        User::create([
            'name'     => 'Employee One',
            'email'    => 'employee@example.com',
            'password' => Hash::make('password1234'),
            'role'     => 'employee',
        ]);
    }
}
