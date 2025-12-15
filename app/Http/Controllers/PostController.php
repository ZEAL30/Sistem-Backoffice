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

    // ==================== API METHODS ====================

    /**
     * API List Posts (Public)
     */
    public function apiIndex(Request $request)
    {
        $query = Post::where('status', 'published')->with('author', 'categories');

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $perPage = $request->get('per_page', 9);
        $posts = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $posts->items(),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    /**
     * API Show Post (Public)
     */
    public function apiShow($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with('author', 'categories', 'comments')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $post,
        ]);
    }

    /**
     * API Store Post
     */
    public function apiStore(Request $request)
    {
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

        $post->load('author', 'categories');

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post,
        ], 201);
    }

    /**
     * API Update Post
     */
    public function apiUpdate(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|unique:posts,slug,' . $post->id . '|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'type' => 'sometimes|required|in:post,page',
            'status' => 'sometimes|required|in:draft,published',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $post->update([
            'title' => $validated['title'] ?? $post->title,
            'slug' => $validated['slug'] ?? $post->slug,
            'excerpt' => $validated['excerpt'] ?? $post->excerpt,
            'content' => isset($validated['content']) ? (!empty($validated['content']) ? HtmlSanitizer::purify($validated['content']) : null) : $post->content,
            'featured_image' => $validated['featured_image'] ?? $post->featured_image,
            'type' => $validated['type'] ?? $post->type,
            'status' => $validated['status'] ?? $post->status,
            'published_at' => $validated['published_at'] ?? $post->published_at,
        ]);

        if (isset($validated['categories'])) {
            if (!empty($validated['categories'])) {
                $post->categories()->sync($validated['categories']);
            } else {
                $post->categories()->detach();
            }
        }

        $post->load('author', 'categories');

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $post,
        ]);
    }

    /**
     * API Destroy Post
     */
    public function apiDestroy($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully',
        ]);
    }

    /**
     * API List Categories
     */
    public function apiCategories(Request $request)
    {
        $query = \App\Models\Category::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $categories = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * API List Categories by Type
     */
    public function apiCategoriesByType($type)
    {
        $categories = \App\Models\Category::where('type', $type)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * API Store Category
     */
    public function apiCategoryStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:product,post',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $slug = $validated['slug'] ? \Illuminate\Support\Str::slug($validated['slug']) : \Illuminate\Support\Str::slug($validated['name']);

        $base = $slug;
        $i = 1;
        while (\App\Models\Category::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $category = \App\Models\Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'type' => $validated['type'],
            'image' => $validated['image'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    /**
     * API Update Category
     */
    public function apiCategoryUpdate(Request $request, $id)
    {
        $category = \App\Models\Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|in:product,post',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['slug'])) {
            $slug = \Illuminate\Support\Str::slug($validated['slug']);
            if ($slug !== $category->slug && \App\Models\Category::where('slug', $slug)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slug already exists',
                ], 422);
            }
            $validated['slug'] = $slug;
        }

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category,
        ]);
    }

    /**
     * API Destroy Category
     */
    public function apiCategoryDestroy($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        \Illuminate\Support\Facades\DB::table('categorizables')->where('category_id', $id)->delete();
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }
}
