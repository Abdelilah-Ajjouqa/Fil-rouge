<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Posts;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class PostController extends Controller
{
    public function index()
    {
        $post = Posts::with('mediaContent', 'tags')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($post, 200);
    }

    public function store(PostRequest $request)
    {
        try {
            $validate = $request->validated();

            $post = Posts::create([
                'title' => $validate['title'],
                'description' => $validate['description'] ?? null,
                'user_id' => $request->user()->id,
            ]);

            // Sync Tags
            if (isset($validate['tags'])) {
                $this->syncTags($post, $validate['tags']);
            }

            // Handle Media
            if ($request->hasFile('media')) {
                $files = is_array($request->file('media')) ? $request->file('media') : [$request->file('media')];
                foreach ($files as $file) {
                    $filePath = $file->store('media', 'public');
                    $post->mediaContent()->create([
                        'user_id' => $post->user_id,
                        'path' => $filePath,
                        'type' => $file->getClientMimeType(),
                    ]);
                }
            }

            return response()->json($post->load('mediaContent', 'tags'), 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function show(string $id)
    {
        $post = Posts::with('mediaContent', 'tags')->findOrFail($id);
        return response()->json($post, 200);
    }

    public function update(PostRequest $request, string $id)
    {
        try {
            $validate = $request->validated();
            $post = Posts::findOrFail($id);

            if ($request->hasFile('media')) {
                // delete old media
                foreach ($post->mediaContent as $media) {
                    Storage::delete($media->path);
                    $media->delete();
                }

                // store the new media
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

            // Sync Tags
            if (isset($validate['tags'])) {
                $this->syncTags($post, $validate['tags']);
            }

            return response()->json([
                "message" => "The post has been updated successfully",
                "post" => $post->load('mediaContent', 'tags')
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "The update has failed",
                "error" => $e->getMessage()
            ], 400);
        }
    }

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

    private function syncTags(Posts $post, string $tagsString)
    {
        $tagNames = array_map('trim', explode(' ', $tagsString));

        $tagIds = [];
        foreach ($tagNames as $tagName) {
            if (!empty($tagName)) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }
        }
        $post->tags()->sync($tagIds);
    }
}
