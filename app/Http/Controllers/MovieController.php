<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Schedule;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['now_playing', 'coming_soon']);
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->where('genre', 'like', '%' . $request->genre . '%');
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('director', 'like', '%' . $request->search . '%');
            });
        }

        $movies = $query->orderBy('release_date', 'desc')->paginate(12);

        // Get unique genres for filter
        $genres = Movie::distinct()->pluck('genre')->flatMap(fn ($g) => explode(', ', $g))->unique()->sort()->values();

        return view('movies.index', compact('movies', 'genres'));
    }

    public function show(Movie $movie)
    {
        // Get cinemas that have schedules for this movie
        $cinemas = Cinema::active()
            ->whereHas('studios.schedules', function ($q) use ($movie) {
                $q->where('movie_id', $movie->id)
                  ->where('show_date', '>=', now()->toDateString())
                  ->where('is_active', true);
            })
            ->with(['studios' => function ($q) use ($movie) {
                $q->whereHas('schedules', function ($sq) use ($movie) {
                    $sq->where('movie_id', $movie->id)
                       ->where('show_date', '>=', now()->toDateString())
                       ->where('is_active', true);
                });
                $q->with(['schedules' => function ($sq) use ($movie) {
                    $sq->where('movie_id', $movie->id)
                       ->where('show_date', '>=', now()->toDateString())
                       ->where('is_active', true)
                       ->orderBy('show_date')
                       ->orderBy('show_time');
                }]);
            }])
            ->get();

        // Get available dates
        $dates = Schedule::where('movie_id', $movie->id)
            ->where('show_date', '>=', now()->toDateString())
            ->where('is_active', true)
            ->distinct()
            ->orderBy('show_date')
            ->pluck('show_date')
            ->take(7);

        return view('movies.show', compact('movie', 'cinemas', 'dates'));
    }
}
