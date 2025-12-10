<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\HtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the products (public).
     */
    public function publicIndex(Request $request)
    {
        $query = Product::where('is_active', true);

        // Search by name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('categories', function ($q) {
                $q->where('category_id', request('category'));
            });
        }

        // Price range filter
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        if ($sort === 'price') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'name') {
            $query->orderBy('name', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = Category::where('type', 'product')->where('is_active', true)->get();

        return view('Content.product', compact('products', 'categories'));
    }

    /**
     * Display the specified product (public).
     */
    public function publicShow($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('Content.product-detail', compact('product'));
    }

    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::all();
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::where('type', 'product')->where('is_active', true)->get();
        return view('admin.product.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Log incoming request for debugging featured_image
        Log::info('Product store request input', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'featured_image' => 'nullable|string',
            'is_active' => 'boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        Log::info('Product store validated', $validated);

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => !empty($validated['description']) ? HtmlSanitizer::purify($validated['description']) : null,
            'price' => $validated['price'],
            'featured_image' => $validated['featured_image'] ?? null,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        // Attach categories
        if (!empty($validated['categories'])) {
            $product->categories()->attach($validated['categories']);
        }

        return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified product.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $categories = Category::where('type', 'product')->where('is_active', true)->get();
        $selectedCategories = $product->categories()->pluck('categories.id')->toArray();

        return view('admin.product.edit', compact('product', 'categories', 'selectedCategories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $slug): RedirectResponse
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        // Log incoming request for debugging featured_image on update
        Log::info('Product update request input', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $product->id . '|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'featured_image' => 'nullable|string',
            'is_active' => 'boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        Log::info('Product update validated', $validated);

        $product->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => !empty($validated['description']) ? HtmlSanitizer::purify($validated['description']) : null,
            'price' => $validated['price'],
            'featured_image' => $validated['featured_image'] ?? $product->featured_image,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        // Sync categories
        if (!empty($validated['categories'])) {
            $product->categories()->sync($validated['categories']);
        } else {
            $product->categories()->detach();
        }

        return redirect()->route('product.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($slug): RedirectResponse
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus.');
    }
}
