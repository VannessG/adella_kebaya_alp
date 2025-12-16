<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;


class MidtransService
{
    protected $serverKey;
    protected $clientKey;
    protected $isProduction;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->clientKey = config('services.midtrans.client_key');
        $this->isProduction = config('services.midtrans.is_production');
        
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = $this->serverKey;
        \Midtrans\Config::$clientKey = $this->clientKey;
        \Midtrans\Config::$isProduction = $this->isProduction;
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');
    }

    // Generate Snap Token
    // Generate Snap Token
    public function createTransaction($payment)
    {
        // Ambil transaksi asli (Order atau Rent)
        $transaction = $payment->transaction; 
        
        $itemDetails = [];

        // Ambil produk dari relasi transaction (Order/Rent -> products)
        foreach ($transaction->products as $product) {
            $price = $payment->transaction_type == 'rent' 
                ? $product->pivot->subtotal // Jika sewa, pakai subtotal per item
                : $product->pivot->price;   // Jika beli, pakai harga beli

            // Pastikan tidak ada desimal untuk Midtrans IDR
            $price = (int) round($price);

            $itemDetails[] = [
                'id' => $product->id,
                'price' => $price, // Harga satuan (atau total per item context sewa)
                'quantity' => 1, // Kita set 1 karena logikanya harga diatas sudah dikali qty/hari (untuk simplifikasi midtrans)
                'name' => substr($product->name, 0, 50), // Midtrans limit 50 chars
            ];
        }

        // Tambahkan Ongkir
        if ($transaction->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) round($transaction->shipping_cost),
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
        }

        // Tambahkan Diskon (Midtrans menerima nilai negatif untuk diskon)
        // Hitung selisih total item + ongkir dengan total_amount yang harus dibayar
        $calculatedTotal = array_sum(array_column($itemDetails, 'price'));
        $realTotal = (int) round($payment->amount);
        $discountDiff = $calculatedTotal - $realTotal;

        if ($discountDiff > 0) {
            $itemDetails[] = [
                'id' => 'DISCOUNT',
                'price' => -($discountDiff),
                'quantity' => 1,
                'name' => 'Potongan Diskon',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $payment->payment_number, // Pakai No Pembayaran, bukan No Order
                'gross_amount' => $realTotal,
            ],
            'customer_details' => [
                'first_name' => $payment->payer_name,
                'phone' => $payment->payer_phone,
            ],
            'item_details' => $itemDetails,
            // Paksa tampilkan QRIS jika metode pembayaran QRIS
            // 'enabled_payments' => ['gopay', 'shopeepay', 'qris'], // Opsional: batasi metode
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            throw $e;
        }
    }

    // Get transaction status from Midtrans
    public function getTransactionStatus($orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            Log::error('Midtrans Status Error: ' . $e->getMessage());
            throw $e;
        }
    }

    // Handle Notification dari Midtrans
    public function handleNotification()
    {
        try {
            $notification = new \Midtrans\Notification();
            
            return [
                'order_id' => $notification->order_id,
                'transaction_status' => $notification->transaction_status,
                'payment_type' => $notification->payment_type,
                'transaction_time' => $notification->transaction_time,
                'fraud_status' => $notification->fraud_status ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getClientKey()
    {
        return $this->clientKey;
    }
}