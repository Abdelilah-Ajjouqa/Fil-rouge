<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Posts;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(string $post_id)
    {
        Posts::findOrFail($post_id);

        $comments = Comments::with(['user'])
            ->where('post_id', $post_id)
            ->latest()
            ->get();

        if ($comments->isEmpty()) {
            return response()->json(['message' => 'No comments found for this post'], 200);
        }

        return response()->json($comments, 200);
    }

    public function store(CommentRequest $request, string $post_id)
    {
        Posts::findOrFail($post_id);

        $comment = Comments::create([
            ...$request->validated(),
            'user_id' => Auth::id(),
            'post_id' => $post_id
        ]);

        return response()->json($comment, 201);
    }

    public function update(CommentRequest $request, string $post_id, string $comment_id)
    {
        Posts::findOrFail($post_id);

        $comment = Comments::where('post_id', $post_id)
            ->where('id', $comment_id)
            ->firstOrFail();

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->update($request->validated());
        return response()->json($comment, 200);
    }

    public function destroy(string $post_id, string $comment_id)
    {
        Posts::findOrFail($post_id);

        $comment = Comments::where('post_id', $post_id)
            ->where('id', $comment_id)
            ->firstOrFail();

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(null, 204);
    }
}
