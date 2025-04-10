<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = Posts::with('mediaContent')
            ->orderBy('created_at', 'desc')
            // ->pagination(20)
            ->get();

        return response()->json($post, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        try {
            $validate = $request->validated();

            $post = Posts::create([
                'title' => $validate['title'],
                'description' => $validate['description'] ?? null,
                'user_id' => $request->user()->id,
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
                    $post->mediaContent()->create([
                        'user_id' => $post->user_id,
                        'path' => $filePath,
                        'type' => $file->getClientMimeType(),
                    ]);
                }

                return response()->json($post->load("mediaContent"), 201);
            } else {
                return response()->json(['message' => 'No file uploaded'], 400);
            }

            return response()->json($post->load('mediaContent'), 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $search = Posts::findOrFail($id);
        $post = $search->load('mediaContent');

        return response()->json($post, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id)
    {
        try {
            $validate = $request->validated();

            $post = Posts::findOrFail($id);

            if ($request->hasFile('media')) {
                // Delete old media files
                foreach ($post->mediaContent as $media) {
                    Storage::delete($media->path);
                    $media->delete();
                }

                // add the media updated
                foreach ($request->file('media') as $file) {
                    $filePath = $file->store('media', 'public');
                    $post->mediaContent()->create([
                        'user_id' => $post->user_id,
                        'path' => $filePath,
                        'type' => $file->getClientMimeType(),
                    ]);
                }
            }

            $post->update($validate);

            return response()->json(["message" => "The post has been updated successfully", "post" => $post->load('mediaContent')], 200);
        } catch (Exception $e) {
            return response()->json(["message" => "The update has failed", "error" => $e->getMessage()], 400);
        }
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
        $media = $post->mediaContent;

        return response()->json($media, 200);
    }


    public function uploadMedia(Request $request, $id)
    {
        try {
            $request->validate([
                'media' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,mkv|max:10240',
            ]);

            $post = Posts::findOrFail($id);

            if ($request->hasFile('media')) {
                $filePath = $request->file('media')->store('media', 'public');

                $media = $post->mediaContent()->create([
                    'user_id' => $post->user_id,
                    'path' => $filePath,
                    'type' => $request->file('media')->getClientMimeType(),
                ]);

                return response()->json($media, 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'No file uploaded', 'error' => $e->getMessage()], 400);
        }
    }
}
