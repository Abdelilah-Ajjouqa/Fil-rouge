<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register'])->name("register");
Route::post('/login', [AuthController::class, 'login'])->name("login");
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Public routes for posts and comments
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');
Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');

Route::middleware('auth:sanctum')->group(function(){
    // user's routes
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('destroy');

    // post's routes
    Route::post('/post', [PostController::class, 'store'])->name('store');
    Route::put('/post/{id}', [PostController::class, 'update'])->name('update');
    Route::delete('/post/{id}', [PostController::class, 'destroy'])->name('destroy');

    // comment's routes
    Route::post('/comment', [CommentController::class, 'store'])->name('store');
    Route::put('/comment/{id}', [CommentController::class, 'update'])->name('update');
    Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->name('destroy');
});