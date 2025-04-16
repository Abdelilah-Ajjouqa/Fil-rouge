<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Posts;
use App\Models\SavedPost;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedPostController extends Controller
{
    public function save($postId)
    {
        try {
            $post = Posts::findOrFail($postId);

            $savedPost = SavedPost::create([
                'user_id' => Auth::id(),
                'post_id' => $postId
            ]);

            return response()->json(['message' => 'Post saved successfully', 'data' => $savedPost], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error saving post', 'error' => $e->getMessage()], 500);
        }
    }

    public function unsave($postId)
    {
        try {
            $savedPost = SavedPost::where('user_id', Auth::id())
                ->where('post_id', $postId)
                ->firstOrFail();

            $savedPost->delete();

            return response()->json(['message' => 'Post unsaved successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error unsaving post', 'error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        try {
            $savedPosts = SavedPost::with('post')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();

            return response()->json([
                'data' => $savedPosts
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error retrieving saved posts', 'error' => $e->getMessage()], 500);
        }
    }
}
