<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Services\HtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('author')->get();
        return view('admin.post.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::where('type', 'post')->where('is_active', true)->get();
        return view('admin.post.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Log incoming request for debugging featured_image
        Log::info('Post store request input', $request->all());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'type' => 'required|in:post,page',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        Log::info('Post store validated', $validated);

        $post = Post::create([
            'author_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => !empty($validated['content']) ? HtmlSanitizer::purify($validated['content']) : null,
            'featured_image' => $validated['featured_image'] ?? null,
            'type' => $validated['type'],
            'status' => $validated['status'],
            'published_at' => $validated['published_at'] ?? null,
        ]);

        if (!empty($validated['categories'])) {
            $post->categories()->attach($validated['categories']);
        }

        return redirect()->route('post.index')->with('success', 'Post created.');
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->with('author','categories')->firstOrFail();
        return view('admin.post.show', compact('post'));
    }

    public function edit($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $categories = Category::where('type', 'post')->where('is_active', true)->get();
        $selectedCategories = $post->categories()->pluck('categories.id')->toArray();
        return view('admin.post.edit', compact('post', 'categories', 'selectedCategories'));
    }

    public function update(Request $request, $slug): RedirectResponse
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug,' . $post->id . '|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'type' => 'required|in:post,page',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $post->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => !empty($validated['content']) ? HtmlSanitizer::purify($validated['content']) : null,
            'featured_image' => $validated['featured_image'] ?? $post->featured_image,
            'type' => $validated['type'],
            'status' => $validated['status'],
            'published_at' => $validated['published_at'] ?? null,
        ]);

        if (!empty($validated['categories'])) {
            $post->categories()->sync($validated['categories']);
        } else {
            $post->categories()->detach();
        }

        return redirect()->route('post.index')->with('success', 'Post updated.');
    }

    public function destroy($slug): RedirectResponse
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $post->delete();
        return redirect()->route('post.index')->with('success', 'Post deleted.');
    }
}
