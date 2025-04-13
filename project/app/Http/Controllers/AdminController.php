<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Posts;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->getRole('admin')) {
                return response()->json(['message' => 'Only admin can access to this page'], 403);
            }

            // Admin dashboard logic goes here
            return response()->json(["message" => "this is admin's dashboard"], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAllActiveUsers()
    {
        try {
            $users = User::where('is_active', true)->get();

            if ($users->isEmpty()) {
                return response()->json(['message' => 'No active users found'], 404);
            }

            return response()->json($users, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAllInactiveUsers()
    {
        try {
            $users = User::where('is_active', false)->get();

            if ($users->isEmpty()) {
                return response()->json(['message' => 'No inactive users found'], 404);
            }

            return response()->json($users, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function activateUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_active = true;
            $user->save();

            return response()->json(['message' => 'User activated successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error activating user', 'error' => $e->getMessage()], 500);
        }
    }

    public function deactivateUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_active = false;
            $user->save();

            return response()->json(['message' => 'User desactivated successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function archivePost($id)
    {
        try {
            $post = Posts::findOrFail($id);
            $post->status = Posts::is_archived;
            $post->save();

            return response()->json([
                'message' => 'Post archived successfully',
                'post' => $post
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error archiving post', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAllArchivePosts(){
        try {
            $posts = Posts::where('status', Posts::is_archived)->get();

            if ($posts->isEmpty()) {
                return response()->json(['message' => 'No archived posts found'], 200);
            }

            return response()->json($posts, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function restorePost($id)
    {
        try {
            $post = Posts::findOrFail($id);
            if ($post->status !== Posts::is_archived) {
                return response()->json(['message' => 'Post is not archived'], 400);
            }

            $post->status = Posts::is_public;
            $post->save();

            return response()->json([
                'message' => 'Post restored successfully',
                'post' => $post
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error restoring post', 'error' => $e->getMessage()], 500);
        }
    }

    public function deletePost($id)
    {
        try {
            $post = Posts::findOrFail($id);
            $user = Auth::user();

            // Admin can only delete archived posts
            if ($user->getRole('admin')) {
                if ($post->status !== Posts::is_archived) {
                    return response()->json(['message' => 'Admin can only delete archived posts'], 400);
                }
            }
            // Regular users can delete their own posts
            else if ($post->user_id !== $user->id) {
                return response()->json(['message' => 'Unauthorized to delete this post'], 403);
            }

            $post->delete();
            return response()->json(['message' => 'Post deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(["message" => "Error", "error" => $e->getMessage()], 500);
        }
    }
}
