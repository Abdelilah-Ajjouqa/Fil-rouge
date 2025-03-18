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
        try {
            $validate = $request->validate([
                'title' => 'required|string|max:225',
                'description' => 'nullable|string|max:225',
                'user_id' => 'required|exists:users,id',
                'media' => 'nullable|array',
                'media.*' => 'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $post = Posts::create([
                'title' => $validate['title'],
                'description' => $validate['description'] ?? null,
                'user_id' => $validate['user_id'],
            ]);

            // check $post
            if (!$post) {
                return response()->json(['message' => 'Post not created', 'post' => $post], 400);
            }

            // check if the files are uploaded
            if ($request->hasFile('media')) {
                $arr = $request->file('media');

                $files = is_array($arr) ? $arr : [$arr]; //make media as an array to use it in the loop

                foreach ($files as $file) {
                    $filePath = $file->store('media', 'public');
                    $post->mediaFiles()->create([
                        'user_id' => $post->user_id,
                        'path' => $filePath,
                        'type' => $file->getClientMimeType(),
                    ]);
                }
            } else {
                return response()->json(['message' => 'No file uploaded'], 400);
            }

            return response()->json($post->load('mediaFiles'), 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
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
        try {
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

                return response()->json($media, 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'No file uploaded', 'error' => $e->getMessage()], 400);
        }
    }
}
