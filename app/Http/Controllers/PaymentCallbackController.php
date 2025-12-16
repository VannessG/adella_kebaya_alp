<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        try {
            // 1. Log Data Masuk (Biar kita tahu Midtrans nyambung)
            Log::info('Midtrans Callback Masuk:', $request->all());

            // 2. Cari Data Pembayaran
            $payment = Payment::where('payment_number', $request->order_id)->first();

            // Jika data tidak ditemukan (misal karena habis migrate:fresh)
            if (!$payment) {
                Log::warning('Pembayaran tidak ditemukan untuk Order ID: ' . $request->order_id);
                return response()->json(['message' => 'Payment not found in DB'], 404);
            }

            // 3. Update Status
            $status = $request->transaction_status;
            
            if ($status == 'capture' || $status == 'settlement') {
                $payment->update(['status' => 'success']);
                
                // Update Order (Asumsi relasi manual)
                if ($payment->transaction_type == 'App\Models\Order' || $payment->order_id) {
                     $orderId = $payment->transaction_id ?? $payment->order_id;
                     $order = Order::find($orderId);
                     if($order) $order->update(['status' => 'processing']);
                }

            } elseif ($status == 'expire') {
                $payment->update(['status' => 'expired']);
            } elseif ($status == 'cancel' || $status == 'deny') {
                $payment->update(['status' => 'failed']);
            }

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            // INI BAGIAN PENTING: Tangkap errornya biar ga 500
            Log::error('ERROR FATAL CALLBACK: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}