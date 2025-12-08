<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Adella Kebaya Surabaya',
                'address' => 'Jl. Mayjend Sungkono No 98',
                'city' => 'Surabaya',
                'phone' => '+6281234567890',
                'is_active' => true
            ],
            [
                'name' => 'Adella Kebaya Bojonegoro',
                'address' => 'Jl. Jenderal Sudirman No 55',
                'city' => 'Bojonegoro',
                'phone' => '+6289876543210',
                'is_active' => true
            ]
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}