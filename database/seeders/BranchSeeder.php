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
                'name' => 'Adella Kebaya Sidoarjo',
                'address' => 'Pondok Tjandra Indah, Jl. Manggis VIII No.664',
                'city' => 'Sidoarjo',
                'phone' => '+6289678956340',
                'is_active' => true
            ],
            [
                'name' => 'Adella Kebaya Bojonegoro',
                'address' => 'Ruko Central Point, Jl. Veteran No.19',
                'city' => 'Bojonegoro',
                'phone' => '+6289678956340',
                'is_active' => true
            ]
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}