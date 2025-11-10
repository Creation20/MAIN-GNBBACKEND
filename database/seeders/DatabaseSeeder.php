<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@ghla.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Classifications Manager',
            'email' => 'manager@ghla.com',
            'password' => Hash::make('password123'),
            'role' => 'classifications_manager',
        ]);

        User::create([
            'name' => 'Entry Manager',
            'email' => 'entry@ghla.com',
            'password' => Hash::make('password123'),
            'role' => 'entry_manager',
        ]);
    }
}
