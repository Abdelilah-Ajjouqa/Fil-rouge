<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Posts;
use Illuminate\Http\Request;

class PostStatusMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $postId = $request->route('id');
        $post = Posts::findOrFail($postId);
        $user = $request->user();

        switch ($post->status) {
            case Posts::is_archived:
                if ($user && $user->role === 'admin') {
                    return $next($request);
                }
                return redirect()
                    ->route('posts.index')
                    ->with('error', 'This post has been archived and is only accessible by administrators.');

            case Posts::is_public:
                return $next($request);

            case Posts::is_private:
                if ($user && ($user->id === $post->user_id)) {
                    return $next($request);
                }
                return redirect()
                    ->route('posts.index')
                    ->with('error', 'You do not have access to view this post');

            default:
                return redirect()
                    ->route('posts.index')
                    ->with('error', 'Invalid post status');
        }
    }
}
