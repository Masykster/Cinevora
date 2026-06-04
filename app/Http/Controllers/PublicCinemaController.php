<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use Illuminate\Http\Request;

class PublicCinemaController extends Controller
{
    public function index(Request $request)
    {
        $selectedCity = $request->cookie('cinevora_city');

        $cinemas = Cinema::orderBy('name')
            ->when($selectedCity, fn($q) => $q->where('city', $selectedCity))
            ->get();

        return view('cinemas.index', compact('cinemas', 'selectedCity'));
    }
}
