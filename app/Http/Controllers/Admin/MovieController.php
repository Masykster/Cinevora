<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $movies = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.movies.index', compact('movies'));
    }

    public function create()
    {
        return view('admin.movies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'genre' => 'required|string|max:255',
            'director' => 'required|string|max:255',
            'cast' => 'nullable|string|max:500',
            'duration' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'trailer_url' => 'nullable|url',
            'rating' => 'required|numeric|min:0|max:10',
            'release_date' => 'required|date',
            'status' => 'required|in:now_playing,coming_soon,ended',
            'age_rating' => 'required|string|in:SU,13+,17+,21+',
        ]);

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('movies/posters', 'public');
        }
        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('movies/banners', 'public');
        }

        Movie::create($validated);

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil ditambahkan.');
    }

    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'genre' => 'required|string|max:255',
            'director' => 'required|string|max:255',
            'cast' => 'nullable|string|max:500',
            'duration' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'trailer_url' => 'nullable|url',
            'rating' => 'required|numeric|min:0|max:10',
            'release_date' => 'required|date',
            'status' => 'required|in:now_playing,coming_soon,ended',
            'age_rating' => 'required|string|in:SU,13+,17+,21+',
        ]);

        if ($request->hasFile('poster')) {
            if ($movie->poster) {
                Storage::disk('public')->delete($movie->poster);
            }
            $validated['poster'] = $request->file('poster')->store('movies/posters', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($movie->banner) {
                Storage::disk('public')->delete($movie->banner);
            }
            $validated['banner'] = $request->file('banner')->store('movies/banners', 'public');
        }

        $movie->update($validated);

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil diperbarui.');
    }

    public function destroy(Movie $movie)
    {
        if ($movie->poster) {
            Storage::disk('public')->delete($movie->poster);
        }
        if ($movie->banner) {
            Storage::disk('public')->delete($movie->banner);
        }

        $movie->delete();

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil dihapus.');
    }
}
