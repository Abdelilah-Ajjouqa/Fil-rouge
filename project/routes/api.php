<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register'])->name("auth.register");
Route::post('/login', [AuthController::class, 'login'])->name("auth.login");
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Public routes for posts and comments
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/post/{id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');

Route::middleware(['auth:sanctum', 'userStatus'])->group(function () {
    // user's routes
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/profile/{id}', [UserController::class, 'show'])->name('user.show');
    Route::post('/profile/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/profile/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    // post's routes
    Route::post('/post', [PostController::class, 'store'])->name('post.store');
    Route::put('/post/{id}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{id}', [PostController::class, 'destroy'])->name('post.destroy');

    // comment's routes
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::put('/comment/{id}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');

    Route::middleware('is_admin:admin')->group(function () {
        // Admin dashboard
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // User management routes
        Route::get('/admin/users/active', [AdminController::class, 'getAllActiveUsers'])->name('admin.users.active');
        Route::get('/admin/users/inactive', [AdminController::class, 'getAllInactiveUsers'])->name('admin.users.inactive');
        Route::put('/admin/users/{id}/activate', [AdminController::class, 'activateUser'])->name('admin.users.activate');
        Route::put('/admin/users/{id}/deactivate', [AdminController::class, 'deactivateUser'])->name('admin.users.deactivate');

        // Post management routes
        Route::get('/admin/posts/{id}/archive', [AdminController::class, 'getAllArchivePosts'])->name('admin.archive');
        Route::post('/admin/posts/{id}/archive', [AdminController::class, 'archivePost'])->name('admin.posts.archive');
        Route::post('/admin/posts/{id}/restore', [AdminController::class, 'restorePost'])->name('admin.posts.restore');
        Route::delete('/admin/posts/{id}', [AdminController::class, 'deletePost'])->name('admin.posts.delete');
    });
});
