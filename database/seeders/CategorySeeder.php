<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kebaya Basic', 'description' => 'Kebaya dengan desain sederhana dan nyaman'],
            ['name' => 'Kebaya Classic', 'description' => 'Kebaya dengan desain klasik dan elegan'],
            ['name' => 'Kebaya Premium', 'description' => 'Kebaya dengan bahan dan desain premium'],
            ['name' => 'Kebaya Standard', 'description' => 'Kebaya dengan desain standar untuk berbagai acara'],
            ['name' => 'Kebaya Graduation', 'description' => 'Kebaya khusus untuk acara wisuda'],
            ['name' => 'Kebaya Classic and Hijab', 'description' => 'Kebaya klasik yang dipadukan dengan hijab'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}