<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('posts')->get();

        if($users->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }

        return response()->json($users, 200);
    }

    public function show(string $userId)
    {
        $user = User::findOrFail($userId);

        // return the user with their posts
        $user->load('posts');

        if ($user->posts->isEmpty()) {
            return response()->json(['message' => 'No posts found for this user'], 200);
        }
        return response()->json([
            'user' => $user,
            'posts' => $user->posts,
            'posts_count' => $user->posts->count
        ], 200);
    }

    public function update(UserRequest $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $data = $request->validated();

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatarName = time() . '_' . $request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->storeAs('public/avatars', $avatarName);
                $data['avatar'] = 'avatars/' . $avatarName;
            }

            // Handle cover upload
            if ($request->hasFile('cover')) {
                $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
                $request->file('cover')->storeAs('public/covers', $coverName);
                $data['cover'] = 'covers/' . $coverName;
            }

            $user->update($data);
            return response()->json([
                "message" => "Profile updated successfully",
                "user" => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'User not updated',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['message' => 'User not deleted', 'error' => $e->getMessage()], 500);
        }
    }

}
