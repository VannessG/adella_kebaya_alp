<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rent;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\MidtransService;

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
        // --- JEBAKAN 1: CEK APAKAH TOMBOL BERFUNGSI ---
        // Kalau layar jadi putih menampilkan data ini, berarti Route benar.
        // Kalau layar tidak berubah (cuma reload), berarti tombol form HTML salah.
        // (Saya matikan dulu biar kita cek validasi)
        dd('JEBAKAN 1: Request Masuk', $request->all()); 

        // Validasi Form
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_proof' => 'required_if:payment_method_id,2|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($type === 'order') {
            $payable = Order::where('user_id', Auth::id())->findOrFail($id);
        } else {
            $payable = Rent::where('user_id', Auth::id())->findOrFail($id);
        }

        // Cek pembayaran ganda
        if ($payable->payment) {
             if($payable->payment->status == 'pending'){
                 // --- JEBAKAN 2: PEMBAYARAN SUDAH ADA ---
                 dd('JEBAKAN 2: STOP! Data pembayaran ternyata SUDAH ADA di database (Status Pending). Cek tabel payments!', $payable->payment);
             }
             dd('JEBAKAN 3: STOP! Pembayaran ini sudah lunas/selesai.', $payable->payment);
        }

        $paymentMethod = PaymentMethod::find($request->payment_method_id);
        
        $paymentData = [
            'payment_number' => 'PAY-' . time() . rand(100,999),
            'payment_method_id' => $request->payment_method_id,
            'amount' => $payable->total_amount,
            'status' => 'pending',
            'payer_name' => $request->customer_name ?? Auth::user()->name,
            'payer_phone' => $request->customer_phone ?? Auth::user()->phone,
        ];

        if ($paymentMethod->type === 'transfer' && $request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
            $paymentData['proof_image'] = $proofPath;
            $paymentData['status'] = 'processing'; 
        }

        // --- JEBAKAN 4 (UTAMA): SIAP SIMPAN ---
        // Jika layar putih muncul tulisan ini, berarti Validasi lolos dan siap simpan.
        dd('JEBAKAN 4: SIAP SIMPAN KE DATABASE', $paymentData); 

        try {
            // EKSEKUSI SIMPAN
            $payment = $payable->payments()->create($paymentData);
            
        } catch (\Exception $e) {
            // --- JEBAKAN 5: ERROR DATABASE ---
            dd('JEBAKAN 5: GAGAL SAAT CREATE! Pesan Error:', $e->getMessage());
        }

        // LOGIKA MIDTRANS
        if ($paymentMethod->type === 'qris') {
            try {
                $midtransService = new MidtransService();
                $snapToken = $midtransService->createTransaction($payment);
                $payment->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                // Jangan hapus data, tampilkan error saja
                dd('JEBAKAN 6: MIDTRANS ERROR', $e->getMessage());
            }
        }

        // Redirect
        if ($paymentMethod->type === 'qris') {
             return redirect()->route('payment.pay', $payment->payment_number);
        }

        $redirectRoute = $type === 'order' ? 'pesanan.show' : 'rent.show';
        $redirectParam = $type === 'order' ? $payable->order_number : $payable->rent_number;

        return redirect()->route($redirectRoute, $redirectParam)
            ->with('success', 'Bukti pembayaran berhasil diupload.');
    }
    public function pay($paymentNumber)
    {
        $payment = Payment::where('payment_number', $paymentNumber)->firstOrFail();
        
        if($payment->status == 'success'){
            return redirect()->route('home')->with('success', 'Pembayaran sudah lunas.');
        }

        return view('payment.pay', [
            'title' => 'Selesaikan Pembayaran',
            'payment' => $payment,
            'snapToken' => $payment->snap_token
        ]);
    }
    
    public function finish(Request $request)
    {
        return redirect()->route('home')->with('success', 'Transaksi sedang diproses.');
    }

    public function adminIndex()
    {
        $payments = Payment::with(['paymentMethod', 'transaction'])
            ->latest()
            ->paginate(10);
            
        return view('admin.payments.index', [
            'title' => 'Manajemen Pembayaran',
            'payments' => $payments
        ]);
    }
    
    public function updateStatus(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,success,failed,expired'
        ]);
        
        $payment->update($validated);
        
        // Jika pembayaran sukses, update status order/rent
        if ($validated['status'] === 'success') {
            $transaction = $payment->transaction;
            if ($transaction instanceof Order) {
                $transaction->update(['status' => 'processing']);
            } elseif ($transaction instanceof Rent) {
                $transaction->update(['status' => 'confirmed']);
            }
        }
        
        return redirect()->route('admin.payments.index')->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}