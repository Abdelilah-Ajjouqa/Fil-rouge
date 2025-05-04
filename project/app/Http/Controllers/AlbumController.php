<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::where('user_id', Auth::id())
            ->withCount('posts')
            ->latest()
            ->get();

        return view('albums.index', compact('albums'));
    }

    public function create()
    {
        return view('albums.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_private' => 'boolean',
                'cover_image' => 'nullable|image|max:2048',
            ]);

            $album = new Album([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'is_private' => $validated['is_private'] ?? false,
                'user_id' => Auth::id(),
            ]);

            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('album_covers', 'public');
                $album->cover_image = $path;
            }

            $album->save();

            return redirect()
                ->route('albums.show', $album->id)
                ->with('success', 'Album created successfully!');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create album: ' . $e->getMessage());
        }
    }

    
}
