<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'QRIS',
                'type' => 'qris',
                'account_name' => 'Adella Kebaya',
                'account_number' => null,
                'qr_code_image' => 'payments/qris.jpg',
                'is_active' => true
            ],
            [
                'name' => 'Transfer Bank BCA',
                'type' => 'transfer',
                'account_name' => 'Adella Kebaya',
                'account_number' => '1234567890',
                'qr_code_image' => null,
                'is_active' => true
            ]
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}