<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Food', 'slug' => 'food', 'icon' => '🍔'],
            ['name' => 'Beverage', 'slug' => 'beverage', 'icon' => '🥤'],
            ['name' => 'Snack', 'slug' => 'snack', 'icon' => '🍿'],
            ['name' => 'Combo', 'slug' => 'combo', 'icon' => '🎬'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
