<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Adella',
            'email' => 'admin@adellakebaya.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+6281234567890',
            'address' => 'Jl. Mayjend Sungkono No 98, Surabaya',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'User Demo', 
            'email' => 'user@adellakebaya.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '+6289876543210',
            'address' => 'Jl. Jenderal Sudirman No 55, Bojonegoro',
            'email_verified_at' => now(),
        ]);
    }
}