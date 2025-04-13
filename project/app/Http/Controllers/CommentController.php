<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comments::with(['user', 'post'])->latest()->get();
        return response()->json($comments, 200);
    }

    public function store(CommentRequest $request)
    {
        $comment = Comments::create([
            ...$request->validated(),
            'user_id' => Auth::id(),
        ]);

        return response()->json($comment, 201);
    }

    public function show(string $id)
    {
        $comment = Comments::with(['user', 'post'])->findOrFail($id);
        return response()->json($comment, 200);
    }

    public function update(CommentRequest $request, string $id)
    {
        $comment = Comments::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->update($request->validated());
        return response()->json($comment, 200);
    }

    public function destroy(string $id)
    {
        $comment = Comments::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(null, 204);
    }
}
