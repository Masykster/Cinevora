<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $nowPlaying = Movie::nowPlaying()
            ->orderBy('release_date', 'desc')
            ->take(8)
            ->get();

        $comingSoon = Movie::comingSoon()
            ->orderBy('release_date', 'asc')
            ->take(6)
            ->get();

        $cinemas = \App\Models\Cinema::orderBy('name')->get();

        $promos = \App\Models\Promo::active()
            ->orderBy('sort_order')
            ->get();

        return view('home', compact('nowPlaying', 'comingSoon', 'cinemas', 'promos'));
    }
}
