<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'permissions' => [
                    'products.',
                    'posts.',
                    'users.',
                ],
                'description' => 'Full access to all features',
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'permissions' => [
                    'products.view',
                    'products.edit',
                    'posts.',
                ],
                'description' => 'Can manage content and posts',
            ],
            [
                'name' => 'Customer',
                'slug' => 'customer',
                'permissions' => [],
                'description' => 'Regular user/customer',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
