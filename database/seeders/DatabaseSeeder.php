<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Stellar',
            'email' => 'admin@stellar.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create Sample Member Users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'member',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'member',
        ]);

        // Call other seeders
        $this->call([
            CategorySeeder::class,
            UnitSeeder::class,
        ]);
    }
}
