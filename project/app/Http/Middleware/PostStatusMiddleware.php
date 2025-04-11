<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Posts;
use Illuminate\Http\Request;

class CheckPostAccess
{
    public function handle(Request $request, Closure $next)
    {
        $postId = $request->route('id'); // Assuming the route parameter is 'id'
        $post = Posts::findOrFail($postId);
        $user = $request->user();

        switch ($post->status) {
            case Posts::is_public:
                return $next($request);

            case Posts::is_private:
                if ($user && ($user->id === $post->user_id)) {
                    return $next($request);
                }
                return response()->json(['message' => 'Unauthorized access'], 403);

            case Posts::is_archived:
                if ($user && $user->getRole('admin')) {
                    return $next($request);
                }
                return response()->json(['message' => 'Post is archived'], 403);

            default:
                return response()->json(['message' => 'Invalid post status'], 400);
        }
    }
}
