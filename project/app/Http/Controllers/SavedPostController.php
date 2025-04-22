<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\SavedPost;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class SavedPostController extends Controller
{
    public function save($postId)
    {
        try {
            $post = Posts::findOrFail($postId);

            SavedPost::create([
                'user_id' => Auth::id(),
                'post_id' => $postId
            ]);

            return redirect()->back()->with('success', 'Post saved successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error saving post: ' . $e->getMessage());
        }
    }

    public function unsave($postId)
    {
        try {
            $savedPost = SavedPost::where('user_id', Auth::id())
                ->where('post_id', $postId)
                ->firstOrFail();

            $savedPost->delete();

            return redirect()->back()->with('success', 'Post unsaved successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error unsaving post: ' . $e->getMessage());
        }
    }

    public function getSavedPosts($userId)
    {
        try {
            $user = User::findOrFail($userId);

            $savedPosts = SavedPost::with(['post', 'user'])
                ->where('user_id', $userId)
                ->latest()
                ->get();

            return view('saved-posts.index', compact('savedPosts', 'user'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving saved posts: ' . $e->getMessage());
        }
    }
}
