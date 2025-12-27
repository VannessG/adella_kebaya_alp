<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MidtransService{
    protected $serverKey;
    protected $clientKey;
    protected $isProduction;

    public function __construct(){
        $this->serverKey = config('services.midtrans.server_key');
        $this->clientKey = config('services.midtrans.client_key');
        $this->isProduction = config('services.midtrans.is_production');
        
        \Midtrans\Config::$serverKey = $this->serverKey;
        \Midtrans\Config::$clientKey = $this->clientKey;
        \Midtrans\Config::$isProduction = $this->isProduction;
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');
    }

    // Generate Snap Token
    public function createTransaction($payment){
        $transaction = $payment->transaction;         
        $itemDetails = [];

        foreach ($transaction->products as $product) {
            if ($payment->transaction_type == 'rent' || $payment->transaction_type == 'App\Models\Rent') {
                $price = $product->pivot->subtotal;
                $qty = 1; 
            } else {
                $price = $product->pivot->price;
                $qty = $product->pivot->quantity;
            }

            $itemDetails[] = [
                'id' => 'PROD-' . $product->id,
                'price' => (int) round($price),
                'quantity' => $qty,
                'name' => substr($product->name, 0, 50),
            ];
        }

        if ($transaction->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) round($transaction->shipping_cost),
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
        }

        $subtotalBeforeDiscount = 0;
        foreach ($itemDetails as $item) {
            $subtotalBeforeDiscount += $item['price'] * $item['quantity'];
        }

        $discountAmount = $subtotalBeforeDiscount - (int)$transaction->total_amount;
        if ($discountAmount > 0) {
            $itemDetails[] = [
                'id' => 'DISCOUNT',
                'price' => -(int) round($discountAmount),
                'quantity' => 1,
                'name' => 'Potongan Diskon',
            ];
        }

        $finalGrossAmount = 0;
        foreach ($itemDetails as $item) {
            $finalGrossAmount += $item['price'] * $item['quantity'];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $payment->payment_number,
                'gross_amount' => (int) $finalGrossAmount, 
            ],
            'customer_details' => [
                'first_name' => $payment->payer_name,
                'phone' => $payment->payer_phone,
            ],
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getTransactionStatus($orderId){
        try {
            $status = \Midtrans\Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            Log::error('Midtrans Status Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function handleNotification(){
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

    public function getClientKey(){
        return $this->clientKey;
    }
}