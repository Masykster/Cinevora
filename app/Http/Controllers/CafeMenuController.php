<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CafeMenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with(['products' => function ($q) {
            $q->available()->orderBy('name');
        }])->get();

        $selectedCategory = $request->get('category');
        $cinemas = \App\Models\Cinema::orderBy('name')->get();

        return view('cafe.menu', compact('categories', 'selectedCategory', 'cinemas'));
    }
}
