<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = Posts::all();

        return response()->json($post, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title'=>'required|string|max:225',
            'description'=>'nullable|string|max:225',
            'user_id'=>'required|exists:users,id',
            'media'=>'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('media')) {
            $filePath = $request->file('media')->store('media', 'public');
            $validate['media'] = $filePath;
        }

        $post = Posts::create($validate);
        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Posts::findOrFail($id);

        return response()->json($post, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'title'=>'required|string|max:225',
            'description'=>'nullable|string',
            'media'=>'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $post = Posts::findOrFail($id);
        if ($request->hasFile('media')) {
            $filePath = $request->file('media')->store('media', 'public');
            $validate['media'] = $filePath;
        }

        $post->update($validate);

        return response()->json($post, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Posts::findOrFail($id);
        $post->delete();

        return response()->json(null, 204);
    }

    public function getMedia(string $id)
    {
        $post = Posts::findOrFail($id);
        $media = $post->media;

        return response()->json($media, 200);
    }

    public function uploadMedia(Request $request, $id)
    {
        $validate = $request->validate([
            'media'=>'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $post = Posts::findOrFail($id);
        if ($request->hasFile('media')) {
            $filePath = $request->file('media')->store('media', 'public');
            $validate['media'] = $filePath;
        }

        $post->media()->create($validate);

        return response()->json($post, 201);
    }
}
