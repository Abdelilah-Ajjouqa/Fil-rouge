<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Public routes
Route::get('posts', [PostController::class, 'index'])->name('posts.index');
Route::get('posts/{id}', [PostController::class, 'show'])->name('posts.show');
Route::get('comments', [CommentController::class, 'index'])->name('comments.index');

Route::middleware(['auth:sanctum', 'userStatus'])->group(function () {
    // User routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Post routes
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Comment routes
    Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Admin routes
    Route::middleware('is_admin:admin')->group(function () {
        // Dashboard
        Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // User management
        Route::get('admin/users/active', [AdminController::class, 'getAllActiveUsers'])->name('admin.users.active');
        Route::get('admin/users/inactive', [AdminController::class, 'getAllInactiveUsers'])->name('admin.users.inactive');
        Route::put('admin/users/{id}/activate', [AdminController::class, 'activateUser'])->name('admin.users.activate');
        Route::put('admin/users/{id}/deactivate', [AdminController::class, 'deactivateUser'])->name('admin.users.deactivate');

        // Post management
        Route::get('admin/posts/archived', [AdminController::class, 'getAllArchivePosts'])->name('admin.posts.archived');
        Route::put('admin/posts/{id}/archive', [AdminController::class, 'archivePost'])->name('admin.posts.archive');
        Route::put('admin/posts/{id}/restore', [AdminController::class, 'restorePost'])->name('admin.posts.restore');
        Route::delete('admin/posts/{id}/force', [AdminController::class, 'deletePost'])->name('admin.posts.force-delete');
    });
});
