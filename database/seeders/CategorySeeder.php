<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Appetizers',
            'Main Courses',
            'Pasta',
            'Pizza',
            'Salads',
            'Soups',
            'Desserts',
            'Beverages',
            'Breakfast',
            'Sandwiches',
            'Burgers',
            'Seafood',
            'Vegetarian',
            'Sides',
            'Specials'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category)
            ]);
        }
    }
} 