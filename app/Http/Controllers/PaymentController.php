<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rent;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function orderPaymentForm($orderId)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($orderId);
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        
        return view('payment.order', [
            'title' => 'Pembayaran Pesanan',
            'order' => $order,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function rentPaymentForm($rentId)
    {
        $rent = Rent::where('user_id', Auth::id())->findOrFail($rentId);
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        
        return view('payment.rent', [
            'title' => 'Pembayaran Sewa',
            'rent' => $rent,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function processOrderPayment(Request $request, $orderId)
    {
        return $this->processPayment($request, 'order', $orderId);
    }

    public function processRentPayment(Request $request, $rentId)
    {
        return $this->processPayment($request, 'rent', $rentId);
    }

    private function processPayment(Request $request, $type, $id)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_proof' => 'required_if:payment_method_id,2|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($type === 'order') {
            $payable = Order::where('user_id', Auth::id())->findOrFail($id);
            $redirectRoute = 'pesanan.show';
            $redirectParam = $payable->order_number;
        } else {
            $payable = Rent::where('user_id', Auth::id())->findOrFail($id);
            $redirectRoute = 'rent.show';
            $redirectParam = $payable->rent_number;
        }

        // Cek apakah sudah ada payment
        if ($payable->payment) {
            return redirect()->back()->with('error', 'Pembayaran untuk transaksi ini sudah ada.');
        }

        $paymentMethod = PaymentMethod::find($request->payment_method_id);
        $paymentData = [
            'payment_method_id' => $request->payment_method_id,
            'amount' => $payable->total_amount,
            'status' => 'pending',
        ];

        // Upload bukti untuk transfer
        if ($paymentMethod->type === 'transfer' && $request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
            $paymentData['payment_proof'] = $proofPath;
        }

        // Untuk QRIS (DEMO - langsung success)
        if ($paymentMethod->type === 'qris') {
            $paymentData['status'] = 'success';
            $paymentData['paid_at'] = now();
            $paymentData['midtrans_transaction_id'] = 'DEMO-' . time();
            
            // Update status order/rent
            if ($type === 'order') {
                $payable->update(['status' => 'processing']);
            } else {
                $payable->update(['status' => 'paid']);
            }
        }

        $payment = $payable->payment()->create($paymentData);

        if ($paymentMethod->type === 'qris') {
            return redirect()->route($redirectRoute, $redirectParam)
                ->with('success', 'Pembayaran berhasil! Status pesanan telah diperbarui.');
        } else {
            return redirect()->route($redirectRoute, $redirectParam)
                ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');
        }
    }
}