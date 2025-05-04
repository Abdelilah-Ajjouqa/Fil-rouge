<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return redirect()->back();
        }

        $posts = Posts::where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
        })
            ->orWhereHas('tags', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->where('status', '!=', Posts::is_archived)
            ->with('mediaContent', 'user', 'tags')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('search.results', compact('posts', 'query'));
    }
}
