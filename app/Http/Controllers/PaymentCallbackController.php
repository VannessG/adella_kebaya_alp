<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Rent;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller{
    public function handle(Request $request){
        try {
            $payment = Payment::where('payment_number', $request->order_id)->first();

            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            $status = $request->transaction_status;
            
            if ($status == 'capture' || $status == 'settlement') {
                $payment->update(['status' => 'success']);
                $transaction = $payment->transaction;

                if ($transaction instanceof Order) {
                    $transaction->update(['status' => 'processing']);
                } elseif ($transaction instanceof Rent) {
                    $transaction->update(['status' => 'confirmed']);
                }
            } elseif (in_array($status, ['expire', 'cancel', 'deny'])) {
                $payment->update(['status' => 'failed']);

                if ($payment->transaction) {
                    $payment->transaction->update(['status' => 'cancelled']);
                }
            }
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('CALLBACK ERROR: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}