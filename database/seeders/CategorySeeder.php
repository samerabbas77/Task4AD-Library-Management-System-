<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Scince'],
            ['name' => 'Fiction'],
            ['name' => 'Crime'],
            ['name' => 'Drama'],
            ['name' => 'kids'],
        ];

        // Insert categories into the database
        foreach ($categories as $category) {
            Category::create($category);
        }
            
    }
}
