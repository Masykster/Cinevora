<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Studio;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['movie', 'studio.cinema']);

        if ($request->filled('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        if ($request->filled('cinema_id')) {
            $query->whereHas('studio', fn ($q) => $q->where('cinema_id', $request->cinema_id));
        }

        if ($request->filled('date')) {
            $query->where('show_date', $request->date);
        }

        $schedules = $query->orderBy('show_date', 'desc')->orderBy('show_time')->paginate(20);

        $movies = Movie::whereIn('status', ['now_playing', 'coming_soon'])->orderBy('title')->get();
        $cinemas = Cinema::active()->orderBy('name')->get();

        return view('admin.schedules.index', compact('schedules', 'movies', 'cinemas'));
    }

    public function create()
    {
        $movies = Movie::where('status', 'now_playing')->orderBy('title')->get();
        $cinemas = Cinema::active()->with('studios')->orderBy('name')->get();

        return view('admin.schedules.create', compact('movies', 'cinemas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'studio_id' => 'required|exists:studios,id',
            'show_dates' => 'required|array|min:1',
            'show_dates.*' => 'date|after_or_equal:today',
            'show_times' => 'required|array|min:1',
            'show_times.*' => 'date_format:H:i',
            'price_weekday' => 'required|integer|min:0',
            'price_weekend' => 'required|integer|min:0',
        ]);

        $created = 0;
        foreach ($validated['show_dates'] as $date) {
            foreach ($validated['show_times'] as $time) {
                // Check for conflict
                $exists = Schedule::where('studio_id', $validated['studio_id'])
                    ->where('show_date', $date)
                    ->where('show_time', $time)
                    ->exists();

                if (!$exists) {
                    Schedule::create([
                        'movie_id' => $validated['movie_id'],
                        'studio_id' => $validated['studio_id'],
                        'show_date' => $date,
                        'show_time' => $time,
                        'price_weekday' => $validated['price_weekday'],
                        'price_weekend' => $validated['price_weekend'],
                        'is_active' => true,
                    ]);
                    $created++;
                }
            }
        }

        return redirect()->route('admin.schedules.index')
            ->with('success', "{$created} jadwal berhasil ditambahkan.");
    }

    public function edit(Schedule $schedule)
    {
        $movies = Movie::where('status', 'now_playing')->orderBy('title')->get();
        $cinemas = Cinema::active()->with('studios')->orderBy('name')->get();

        return view('admin.schedules.edit', compact('schedule', 'movies', 'cinemas'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'studio_id' => 'required|exists:studios,id',
            'show_date' => 'required|date',
            'show_time' => 'required|date_format:H:i',
            'price_weekday' => 'required|integer|min:0',
            'price_weekend' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
