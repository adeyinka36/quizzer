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
        $groupsAndCategories = config('categories'); // Load categories from config

        foreach ($groupsAndCategories as $group => $categories) {
            foreach ($categories as $category) {
                Category::factory()->create([
                    'group_name' => $group,
                    'category_name' => $category,
                ]);
            }
        }
    }
}
