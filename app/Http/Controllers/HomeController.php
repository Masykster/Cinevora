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

        return view('home', compact('nowPlaying', 'comingSoon'));
    }
}
