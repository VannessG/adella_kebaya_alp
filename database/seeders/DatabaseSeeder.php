<?php

namespace Database\Seeders;

use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void{
        $this->call([
            BranchSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            PaymentMethodSeeder::class,
            DiscountSeeder::class,
            ProductSeeder::class,
        ]);
    }
}