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

        if ($users->isEmpty()) {
            return view('users.index')
                ->with('warning', 'No users found');
        }

        return view('users.index', compact('users'));
    }

    public function show(string $userId)
    {
        $user = User::findOrFail($userId);
        $user->load('posts');

        return view('users.show', [
            'user' => $user,
            'posts' => $user->posts,
            'posts_count' => $user->posts->count()
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
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
                $data['avatar'] = 'storage/avatars/' . $avatarName;
            }

            // Handle cover upload
            if ($request->hasFile('cover')) {
                $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
                $request->file('cover')->storeAs('public/covers', $coverName);
                $data['cover'] = 'storage/covers/' . $coverName;
            }

            $user->update($data);

            return redirect()
                ->route('users.show', $user->id)
                ->with('success', 'Profile updated successfully');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()
                ->route('users.index')
                ->with('success', 'User deleted successfully');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
