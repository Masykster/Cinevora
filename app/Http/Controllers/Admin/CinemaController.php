<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Studio;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CinemaController extends Controller
{
    public function index()
    {
        $cinemas = Cinema::withCount('studios')->orderBy('name')->paginate(15);
        return view('admin.cinemas.index', compact('cinemas'));
    }

    public function create()
    {
        return view('admin.cinemas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Cinema::create($validated);

        return redirect()->route('admin.cinemas.index')->with('success', 'Bioskop berhasil ditambahkan.');
    }

    public function edit(Cinema $cinema)
    {
        $cinema->load(['studios' => function ($q) {
            $q->withCount('seats');
        }]);
        return view('admin.cinemas.edit', compact('cinema'));
    }

    public function update(Request $request, Cinema $cinema)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $cinema->update($validated);

        return redirect()->route('admin.cinemas.index')->with('success', 'Bioskop berhasil diperbarui.');
    }

    public function destroy(Cinema $cinema)
    {
        $cinema->delete();
        return redirect()->route('admin.cinemas.index')->with('success', 'Bioskop berhasil dihapus.');
    }

    // === Studio Management ===

    public function storeStudio(Request $request, Cinema $cinema)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:regular,imax,vip',
            'rows' => 'required|integer|min:3|max:20',
            'cols' => 'required|integer|min:4|max:20',
        ]);

        $validated['cinema_id'] = $cinema->id;
        $validated['capacity'] = $validated['rows'] * $validated['cols'];
        $validated['is_active'] = true;

        $studio = DB::transaction(function () use ($validated) {
            $studio = Studio::create($validated);

            // Auto-generate seats
            $this->generateSeats($studio, $validated['rows'], $validated['cols']);

            return $studio;
        });

        return back()->with('success', "Studio {$studio->name} berhasil ditambahkan dengan {$studio->capacity} kursi.");
    }

    public function destroyStudio(Cinema $cinema, Studio $studio)
    {
        if ($studio->cinema_id !== $cinema->id) {
            abort(403);
        }

        $studio->delete();
        return back()->with('success', 'Studio berhasil dihapus.');
    }

    /**
     * Generate seats for a studio based on rows and columns.
     */
    private function generateSeats(Studio $studio, int $rows, int $cols): void
    {
        $seats = [];
        $now = now();

        for ($r = 0; $r < $rows; $r++) {
            $rowLabel = chr(65 + $r); // A, B, C...
            for ($c = 1; $c <= $cols; $c++) {
                $seats[] = [
                    'studio_id' => $studio->id,
                    'row_label' => $rowLabel,
                    'seat_number' => $c,
                    'code' => $rowLabel . $c,
                    'type' => 'regular',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        Seat::insert($seats);
    }
}
