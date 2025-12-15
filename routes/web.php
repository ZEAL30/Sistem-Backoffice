<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\PageBuilderController;
use App\Http\Controllers\Comments\CommentController;
use App\Models\Post;

Route::get('/', function () {
    return view('Content.home');
});
Route::get('/about', function () {
    return view('Content.about');
});
Route::get('/blog', function () {
    $query = Post::where('status', 'published');

    // Search
    if (request('search')) {
        $query->where(function($q) {
            $q->where('title', 'like', '%' . request('search') . '%')
              ->orWhere('excerpt', 'like', '%' . request('search') . '%')
              ->orWhere('content', 'like', '%' . request('search') . '%');
        });
    }

    // Filter by category
    if (request('category')) {
        $query->whereHas('categories', function($q) {
            $q->where('slug', request('category'));
        });
    }

    // Sort
    $sort = request('sort', 'latest');
    if ($sort === 'oldest') {
        $query->oldest();
    } else {
        $query->latest();
    }

    $posts = $query->paginate(9);
    return view('Content.blog', compact('posts'));
})->name('blog.index');

Route::get('/blog/{slug}', function ($slug) {
    $post = Post::where('slug', $slug)->where('status', 'published')->firstOrFail();
    return view('Content.blog-detail', compact('post'));
})->name('blog.show');

// Comment routes (public)
Route::post('/blog/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

Route::get('/contact', function () {
    return view('Content.contact');
});
Route::get('/product', [ProductController::class, 'publicIndex'])->middleware('sanitize')->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'publicShow'])->middleware('sanitize')->name('products.show');


Route::get('/admin/login', function () {
    return view('admin.auth.login');
})->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);

Route::middleware(['sanitize','auth'])->group(function () {
    // Dashboard (Admin & Editor only)
    Route::middleware('role:Administrator,Editor')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });

    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/admin/profile', function () {
        $user = \Auth::user();
        return view('admin.auth.profile', compact('user'));
    })->name('profile');

    // Categories routes (Admin, Editor)
    Route::middleware('role:Administrator,Editor')->group(function () {
        Route::get('/admin/categories', function (\Illuminate\Http\Request $request) {
            $type = $request->query('type');
            $query = \App\Models\Category::query();
            if ($type) {
                $query->where('type', $type);
            }
            $categories = $query->orderBy('name')->get();
            return view('admin.categories.index', compact('categories', 'type'));
        })->name('categories.index');

        Route::post('/admin/categories', function (\Illuminate\Http\Request $request) {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:product,post',
                'slug' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $slug = $data['slug'] ? \Illuminate\Support\Str::slug($data['slug']) : \Illuminate\Support\Str::slug($data['name']);

            $base = $slug;
            $i = 1;
            while (\App\Models\Category::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }

            $categoryData = [
                'name' => $data['name'],
                'slug' => $slug,
                'type' => $data['type'],
                'is_active' => true,
            ];

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('categories', 'public');
                $categoryData['image'] = $path;
            }

            \App\Models\Category::create($categoryData);

            return back()->with('success', 'Kategori berhasil ditambahkan.');
        })->name('categories.store');

        Route::delete('/admin/categories/{id}', function (\Illuminate\Http\Request $request, $id) {
            $category = \App\Models\Category::findOrFail($id);
            \Illuminate\Support\Facades\DB::table('categorizables')->where('category_id', $id)->delete();
            if (!empty($category->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image);
            }
            $category->delete();
            return back()->with('success', 'Kategori berhasil dihapus.');
        })->name('categories.destroy');
    });

    // Products routes (Admin, Editor)
    Route::middleware('role:Administrator,Editor')->group(function () {
        Route::get('/admin/products', [ProductController::class, 'index'])->name('product.index');
        Route::get('/admin/products/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/admin/products', [ProductController::class, 'store'])->name('product.store');
        Route::get('/admin/products/{slug}', [ProductController::class, 'show'])->name('product.show');
        Route::get('/admin/products/{slug}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/admin/products/{slug}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/admin/products/{slug}', [ProductController::class, 'destroy'])->name('product.destroy');
    });

    // Posts routes (Admin, Editor)
    Route::middleware('role:Administrator,Editor')->group(function () {
        Route::get('/admin/posts', [\App\Http\Controllers\PostController::class, 'index'])->name('post.index');
        Route::get('/admin/posts/create', [\App\Http\Controllers\PostController::class, 'create'])->name('post.create');
        Route::post('/admin/posts', [\App\Http\Controllers\PostController::class, 'store'])->name('post.store');
        Route::get('/admin/posts/{slug}', [\App\Http\Controllers\PostController::class, 'show'])->name('post.show');
        Route::get('/admin/posts/{slug}/edit', [\App\Http\Controllers\PostController::class, 'edit'])->name('post.edit');
        Route::put('/admin/posts/{slug}', [\App\Http\Controllers\PostController::class, 'update'])->name('post.update');
        Route::delete('/admin/posts/{slug}', [\App\Http\Controllers\PostController::class, 'destroy'])->name('post.destroy');
    });

    // Footer routes (Admin, Editor)
    Route::middleware('role:Administrator,Editor')->group(function () {
        Route::get('/admin/footer', [FooterController::class, 'index'])->name('footer.index');
        Route::get('/admin/footer/edit', [FooterController::class, 'edit'])->name('footer.edit');
        Route::put('/admin/footer', [FooterController::class, 'update'])->name('footer.update');
    });

    // Page Builder routes (Admin, Editor)
    Route::middleware('role:Administrator,Editor')->group(function () {
        Route::get('/admin/page-builder', [PageBuilderController::class, 'index'])->name('page-builder.index');

        // Hero routes
        Route::get('/admin/page-builder/hero', [PageBuilderController::class, 'heroIndex'])->name('page-builder.hero.index');
        Route::get('/admin/page-builder/hero/edit', [PageBuilderController::class, 'heroEdit'])->name('page-builder.hero.edit');
        Route::put('/admin/page-builder/hero', [PageBuilderController::class, 'heroUpdate'])->name('page-builder.hero.update');

        // Testimonial routes
        Route::get('/admin/page-builder/testimonial', [PageBuilderController::class, 'testimonialIndex'])->name('page-builder.testimonial.index');
        Route::get('/admin/page-builder/testimonial/create', [PageBuilderController::class, 'testimonialEdit'])->name('page-builder.testimonial.create');
        Route::get('/admin/page-builder/testimonial/{id}/edit', [PageBuilderController::class, 'testimonialEdit'])->name('page-builder.testimonial.edit');
        Route::post('/admin/page-builder/testimonial', [PageBuilderController::class, 'testimonialStore'])->name('page-builder.testimonial.store');
        Route::delete('/admin/page-builder/testimonial/{id}', [PageBuilderController::class, 'testimonialDestroy'])->name('page-builder.testimonial.destroy');

        // Navigation routes
        Route::get('/admin/page-builder/navigation', [PageBuilderController::class, 'navigationIndex'])->name('page-builder.navigation.index');
        Route::get('/admin/page-builder/navigation/create', [PageBuilderController::class, 'navigationEdit'])->name('page-builder.navigation.create');
        Route::get('/admin/page-builder/navigation/{id}/edit', [PageBuilderController::class, 'navigationEdit'])->name('page-builder.navigation.edit');
        Route::post('/admin/page-builder/navigation', [PageBuilderController::class, 'navigationStore'])->name('page-builder.navigation.store');
        Route::delete('/admin/page-builder/navigation/{id}', [PageBuilderController::class, 'navigationDestroy'])->name('page-builder.navigation.destroy');
        Route::post('/admin/page-builder/navigation/reorder', [PageBuilderController::class, 'navigationReorder'])->name('page-builder.navigation.reorder');
    });

    // Admin-only routes (Media, Users)
    Route::middleware('role:Administrator')->group(function () {
        // Media management routes
        Route::get('/admin/media', [MediaController::class, 'index'])->name('media.index');
        Route::get('/admin/media/all', [MediaController::class, 'getAll'])->name('media.all');
        Route::post('/admin/media', [MediaController::class, 'store'])->name('media.store');
        Route::get('/admin/media/{id}', [MediaController::class, 'show'])->name('media.show');
        Route::put('/admin/media/{id}', [MediaController::class, 'update'])->name('media.update');
        Route::delete('/admin/media/{id}', [MediaController::class, 'destroy'])->name('media.destroy');

        // Comment management routes
        Route::get('/admin/comments', [CommentController::class, 'index'])->name('comments.index');
        Route::post('/admin/comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
        Route::post('/admin/comments/{comment}/reject', [CommentController::class, 'reject'])->name('comments.reject');
        Route::post('/admin/comments/{comment}/flag', [CommentController::class, 'flag'])->name('comments.flag');
        Route::delete('/admin/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

        // User management routes
        
        Route::get('/admin/users', function () {
            $users = \App\Models\User::all();
            return view('admin.auth.show', compact('users'));
        })->name('auth.show');

        Route::get('/admin/users/create', function () {
            return view('admin.auth.create');
        })->name('auth.create');

        Route::post('/admin/users', [AuthController::class, 'store'])->name('auth.store');

        Route::get('/admin/users/{id}/edit', function ($id) {
            $user = \App\Models\User::findOrFail($id);
            return view('admin.auth.edit', compact('user'));
        })->name('auth.edit');

        Route::put('/admin/users/{id}', [AuthController::class, 'update'])->name('auth.update');
        Route::delete('/admin/users/{id}', [AuthController::class, 'destroy'])->name('auth.destroy');
    });
});

// Test routes untuk melihat error pages (hanya untuk development)
// if (config('app.debug')) {
//     Route::get('/test/403', function () {
//         throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
//     });

//     Route::get('/test/404', function () {
//         throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
//     });

//     Route::get('/test/500', function () {
//         throw new \Exception('Test error 500');
//     });
// }

// // Fallback route untuk 404
// Route::fallback(function () {
//     throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
// });
