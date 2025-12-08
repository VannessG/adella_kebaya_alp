<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $discounts = [
            [
                'name' => 'Diskon Launching',
                'code' => 'LAUNCH20',
                'type' => 'percentage',
                'amount' => 20,
                'max_usage' => 100,
                'used_count' => 0,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(25),
                'is_active' => true
            ],
            [
                'name' => 'Diskon Member Baru',
                'code' => 'NEW10',
                'type' => 'percentage',
                'amount' => 10,
                'max_usage' => 50,
                'used_count' => 0,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'is_active' => true
            ],
            [
                'name' => 'Potongan 50K',
                'code' => 'CASH50',
                'type' => 'fixed',
                'amount' => 50000,
                'max_usage' => null,
                'used_count' => 0,
                'start_date' => now(),
                'end_date' => now()->addDays(60),
                'is_active' => true
            ]
        ];

        foreach ($discounts as $discount) {
            Discount::create($discount);
        }
    }
}