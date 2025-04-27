<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\SavedPost;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedPostController extends Controller
{
    public function save($postId)
    {
        try {
            $post = Posts::findOrFail($postId);

            $existingSave = SavedPost::where('user_id', Auth::id())
                ->where('post_id', $postId)
                ->first();
            if ($existingSave) {
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Post already saved',
                        'saved' => true
                    ]);
                }
                return redirect()->back()->with('info', 'Post already saved.');
            }
            SavedPost::create([
                'user_id' => Auth::id(),
                'post_id' => $postId
            ]);
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Post saved successfully',
                    'saved' => true
                ]);
            }
            return redirect()->back()->with('success', 'Post saved successfully.');
        } catch (Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error saving post: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error saving post: ' . $e->getMessage());
        }
    }

    public function unsave($postId)
    {
        try {
            $savedPost = SavedPost::where('user_id', Auth::id())
                ->where('post_id', $postId)
                ->first();
            if (!$savedPost) {
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Post not found in saved items',
                        'saved' => false
                    ], 404);
                }
                return redirect()->back()->with('error', 'Post not found in saved items.');
            }
            $savedPost->delete();
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Post unsaved successfully',
                    'saved' => false
                ]);
            }
            return redirect()->back()->with('success', 'Post unsaved successfully.');
        } catch (Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error unsaving post: ' . $e->getMessage()
                ], 500);
            }
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
