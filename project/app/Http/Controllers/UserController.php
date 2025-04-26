<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Posts;
use App\Models\SavedPost;
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

    public function show(string $id)
    {
        $user = User::findOrFail($id);
        $posts = Posts::where('user_id', $id)
            ->get();
        $savedPosts = SavedPost::with(['post.mediaContent', 'post.user'])
            ->where('user_id', $id)
            ->get();

        return view('users.show', compact('user', 'posts', 'savedPosts'));
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


            if ($request->hasFile('avatar')){
                $avatarPath = '/storage/' . $request->file('avatar')->store('avatars');
                $data['avatar'] = $avatarPath;
            }

            if ($request->hasFile('cover')){
                $coverPath = '/storage/' . $request->file('cover')->store('covers');
                $data['cover'] = $coverPath;
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
