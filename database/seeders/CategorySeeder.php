<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Promo', 'slug' => 'promo', 'icon' => '🏷️', 'is_active' => true],
            ['name' => 'Combo', 'slug' => 'combo', 'icon' => '🍱', 'is_active' => true],
            ['name' => 'Popcorn', 'slug' => 'popcorn', 'icon' => '🍿', 'is_active' => true],
            ['name' => 'Fritters', 'slug' => 'fritters', 'icon' => '🍘', 'is_active' => true],
            ['name' => 'Light Meal', 'slug' => 'light-meal', 'icon' => '🍟', 'is_active' => true],
            ['name' => 'Bakery', 'slug' => 'bakery', 'icon' => '🥐', 'is_active' => true],
            ['name' => 'Drinks', 'slug' => 'drinks', 'icon' => '🥤', 'is_active' => true],
            ['name' => 'Merchandise', 'slug' => 'merchandise', 'icon' => '🧸', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
