<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Posts;
use App\Models\User;
use Exception;

class AdminController extends Controller
{
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

    public function deletePost($id)
    {
        try {
            $post = Posts::findOrFail($id);
            $post->delete();

            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(["message" => "Error", "error" => $e->getMessage()], 500);
        }
    }
}
