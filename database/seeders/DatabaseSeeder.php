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
        // Seed roles first
        $this->call(RoleSeeder::class);

        // Get admin role for test user
        $adminRole = \App\Models\Role::where('slug', 'admin')->first();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone_number' => '0812345678',
            'role_id' => $adminRole?->id,
            'status' => 'active',
        ]);

        // Seed example posts
        $this->call(PostSeeder::class);

        // Seed categories and attach to posts
        $this->call(CategorySeeder::class);
    }
}
