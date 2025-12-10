<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there is at least one user to set as author
        $author = User::first();
        if (! $author) {
            $author = User::factory()->create([
                'name' => 'Post Author',
                'email' => 'author@example.com',
            ]);
        }

        $posts = [
            [
                'title' => 'Welcome to Gec-Groups',
                'slug' => 'welcome-to-gec-groups',
                'excerpt' => 'An introduction to the Gec-Groups site and what to expect.',
                'content' => '<p>This is the first post. Welcome!</p>',
                'type' => 'post',
                'status' => 'published',
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'How to use the admin panel',
                'slug' => 'how-to-use-admin-panel',
                'excerpt' => 'Quick guide for administrators',
                'content' => '<p>Use the sidebar to manage posts, products, and users.</p>',
                'type' => 'post',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'excerpt' => 'Information about the organization',
                'content' => '<p>About page content here.</p>',
                'type' => 'page',
                'status' => 'published',
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'Draft: New Features',
                'slug' => 'draft-new-features',
                'excerpt' => null,
                'content' => '<p>Notes for upcoming features.</p>',
                'type' => 'post',
                'status' => 'draft',
                'published_at' => null,
            ],
            [
                'title' => 'Product Launch Plan',
                'slug' => 'product-launch-plan',
                'excerpt' => 'Plan for launching the new product line',
                'content' => '<p>Planning details...</p>',
                'type' => 'post',
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ],
        ];

        foreach ($posts as $p) {
            Post::create(array_merge($p, [
                'author_id' => $author->id,
            ]));
        }
    }
}
