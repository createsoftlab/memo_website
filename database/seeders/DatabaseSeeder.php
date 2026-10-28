<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'dpmalinda99@gmail.com'],
            [
                'name' => 'Super Admin',
                'role' => 'super_admin',
                'password' => Hash::make('Admin@12345'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'memorycreator@gmail.com'],
            [
                'name' => 'Memory Creator',
                'role' => 'memory_user',
                'password' => Hash::make('Admin@12345'),
                'email_verified_at' => now(),
            ]
        );
    }
}
