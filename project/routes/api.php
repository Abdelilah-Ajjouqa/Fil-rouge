<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register'])->name("auth.register");
Route::post('/login', [AuthController::class, 'login'])->name("auth.login");
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Public routes for posts and comments
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/post/{id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');

Route::middleware(['auth:sanctum', 'userStatus'])->group(function(){
    // user's routes
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    // post's routes
    Route::post('/post', [PostController::class, 'store'])->name('post.store');
    Route::put('/post/{id}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{id}', [PostController::class, 'destroy'])->name('post.destroy');

    // comment's routes
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::put('/comment/{id}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');

    Route::middleware('is_admin:admin')->group(function(){
        Route::get('/admin', function() {
            return response()->json(["message" => "this is admin's dashboard"], 200);
        });
    });
});
