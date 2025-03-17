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
            'title' => 'required|string|max:225',
            'description' => 'nullable|string|max:225',
            'user_id' => 'required|exists:users,id',
            'media' => 'nullable', 
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $post = Posts::create([
            'title' => $validate['title'],
            'description' => $validate['description'] ?? null,
            'user_id' => $validate['user_id'],
        ]);

        if ($request->hasFile('media')) {
            $files = is_array($request->file('media')) ? $request->file('media') : [$request->file('media')];

            foreach ($files as $file) {
                $filePath = $file->store('media', 'public');
                $post->mediaFiles()->create([
                    'user_id' => $post->user_id,
                    'path' => $filePath,
                    'type' => $file->getClientMimeType(),
                ]);
            }
        }

        return response()->json($post->load('mediaFiles'), 201);
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
            'title' => 'required|string|max:225',
            'description' => 'nullable|string',
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
        $media = $post->mediaFiles;

        return response()->json($media, 200);
    }


    public function uploadMedia(Request $request, $id)
    {
        $request->validate([
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $post = Posts::findOrFail($id);

        if ($request->hasFile('media')) {
            $filePath = $request->file('media')->store('media', 'public');

            $media = $post->mediaFiles()->create([
                'user_id' => $post->user_id,
                'path' => $filePath,
                'type' => $request->file('media')->getClientMimeType(),
            ]);

            return response()->json($media, 201);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }
}
