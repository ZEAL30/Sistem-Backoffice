<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Comments\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes
Route::prefix('v1')->group(function () {

    // Authentication
    Route::post('/auth/login', [AuthController::class, 'apiLogin']);
    Route::post('/auth/logout', [AuthController::class, 'apiLogout'])->middleware('auth:sanctum');
    Route::get('/auth/me', [AuthController::class, 'apiMe'])->middleware('auth:sanctum');

    // Public Posts
    Route::get('/posts', [PostController::class, 'apiIndex']);
    Route::get('/posts/{slug}', [PostController::class, 'apiShow']);

    // Public Products
    Route::get('/products', [ProductController::class, 'apiIndex']);
    Route::get('/products/{slug}', [ProductController::class, 'apiShow']);

    // Public Categories
    Route::get('/categories', [PostController::class, 'apiCategories']);
    Route::get('/categories/{type}', [PostController::class, 'apiCategoriesByType']);

    // Public Comments (for posts)
    Route::post('/posts/{slug}/comments', [CommentController::class, 'apiStore']);

    // Protected API Routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {

        // Posts Management
        Route::post('/posts', [PostController::class, 'apiStore']);
        Route::put('/posts/{slug}', [PostController::class, 'apiUpdate']);
        Route::patch('/posts/{slug}', [PostController::class, 'apiUpdate']);
        Route::delete('/posts/{slug}', [PostController::class, 'apiDestroy']);

        // Products Management
        Route::post('/products', [ProductController::class, 'apiStore']);
        Route::put('/products/{slug}', [ProductController::class, 'apiUpdate']);
        Route::patch('/products/{slug}', [ProductController::class, 'apiUpdate']);
        Route::delete('/products/{slug}', [ProductController::class, 'apiDestroy']);

        // Categories Management
        Route::post('/categories', [PostController::class, 'apiCategoryStore']);
        Route::put('/categories/{id}', [PostController::class, 'apiCategoryUpdate']);
        Route::patch('/categories/{id}', [PostController::class, 'apiCategoryUpdate']);
        Route::delete('/categories/{id}', [PostController::class, 'apiCategoryDestroy']);

        // Media Management
        Route::get('/media', [MediaController::class, 'apiIndex']);
        Route::post('/media', [MediaController::class, 'store']); // Already returns JSON
        Route::get('/media/{id}', [MediaController::class, 'show']); // Already returns JSON
        Route::put('/media/{id}', [MediaController::class, 'update']); // Already returns JSON
        Route::patch('/media/{id}', [MediaController::class, 'update']); // Already returns JSON
        Route::delete('/media/{id}', [MediaController::class, 'destroy']); // Already returns JSON

        // Comments Management
        Route::get('/comments', [CommentController::class, 'apiIndex']);
        Route::post('/comments/{comment}/approve', [CommentController::class, 'apiApprove']);
        Route::post('/comments/{comment}/reject', [CommentController::class, 'apiReject']);
        Route::post('/comments/{comment}/flag', [CommentController::class, 'apiFlag']);
        Route::delete('/comments/{comment}', [CommentController::class, 'apiDestroy']);

        // Users Management (Admin only)
        Route::middleware('role:Administrator')->group(function () {
            Route::get('/users', [AuthController::class, 'apiIndex']);
            Route::post('/users', [AuthController::class, 'apiStore']);
            Route::get('/users/{id}', [AuthController::class, 'apiShow']);
            Route::put('/users/{id}', [AuthController::class, 'apiUpdate']);
            Route::patch('/users/{id}', [AuthController::class, 'apiUpdate']);
            Route::delete('/users/{id}', [AuthController::class, 'apiDestroy']);
        });
    });
});

