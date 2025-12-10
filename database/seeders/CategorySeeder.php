<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some categories for posts and products
        $news = Category::create([
            'name' => 'News',
            'slug' => 'news',
            'type' => 'post',
            'is_active' => true,
        ]);

        $guides = Category::create([
            'name' => 'Guides',
            'slug' => 'guides',
            'type' => 'post',
            'is_active' => true,
        ]);

        $productsCat = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'type' => 'product',
            'is_active' => true,
        ]);

        // Attach categories to existing posts (if any)
        $post = Post::where('slug', 'welcome-to-gec-groups')->first();
        if ($post) {
            $post->categories()->attach($news->id);
        }

        $post2 = Post::where('slug', 'how-to-use-admin-panel')->first();
        if ($post2) {
            $post2->categories()->attach($guides->id);
        }
    }
}
