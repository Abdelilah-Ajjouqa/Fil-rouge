<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SavedPostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Auth routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register.form');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login.form');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');


// Public routes
Route::get('/', [PostController::class, 'index'])->name('posts.index');
// Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post_id}/comments', [CommentController::class, 'index'])->name('comments.index');


Route::middleware(['auth', 'userStatus'])->group(function () {

    // User routes
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Post routes (without needing postStatus)
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    // Post routes that need postStatus
    Route::middleware('postStatus')->group(function () {
        Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
        Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::post('/posts/{id}/update', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    });

    // Comment routes
    Route::post('/posts/{post_id}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/posts/{post_id}/comments/{comment_id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/posts/{post_id}/comments/{comment_id}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Saved posts
    Route::get('/users/{id}/saved-posts', [SavedPostController::class, 'getSavedPosts'])->name('users.saved-posts');
    Route::post('/posts/{post_id}/save', [SavedPostController::class, 'save'])->name('save');

    // Admin-only routes
    Route::middleware('is_admin:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // User management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/active', [AdminController::class, 'getAllActiveUsers'])->name('users.active');
        Route::get('/users/inactive', [AdminController::class, 'getAllInactiveUsers'])->name('users.inactive');
        Route::put('/users/{id}/activate', [AdminController::class, 'activateUser'])->name('users.activate');
        Route::put('/users/{id}/deactivate', [AdminController::class, 'deactivateUser'])->name('users.deactivate');

        // Post management
        Route::get('/posts/archived', [AdminController::class, 'getAllArchivePosts'])->name('posts.archived');
        Route::put('/posts/{id}/archive', [AdminController::class, 'archivePost'])->name('posts.archive');
        Route::put('/posts/{id}/restore', [AdminController::class, 'restorePost'])->name('posts.restore');
        Route::delete('/posts/{id}/force', [AdminController::class, 'deletePost'])->name('posts.force-delete');

        // Comment management
        Route::delete('/comment/{id}/force', [AdminController::class, 'deleteComment'])->name('comments.force-delete');
    });
});
