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

class PaymentController extends Controller{
    public function processOrderPayment(Request $request, $orderId){
        return $this->processPayment($request, 'order', $orderId);
    }

    public function processRentPayment(Request $request, $rentId){
        return $this->processPayment($request, 'rent', $rentId);
    }

    private function processPayment(Request $request, $type, $id){
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_proof' => 'required_if:payment_method_id,2|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($type === 'order') {
            $payable = Order::where('user_id', Auth::id())->findOrFail($id);
        } else {
            $payable = Rent::where('user_id', Auth::id())->findOrFail($id);
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

        // Penanganan upload bukti transfer
        if ($paymentMethod->type === 'transfer' && $request->hasFile('payment_proof')) {
            // Hapus file lama jika ada (untuk fitur re-upload)
            if ($payable->payment && $payable->payment->proof_image) {
                Storage::disk('public')->delete($payable->payment->proof_image);
            }

            $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
            $paymentData['proof_image'] = $proofPath;
            $paymentData['status'] = 'processing'; // Menandakan sedang dicek admin
        }

        // Simpan atau Update (Polymorphic creation)
        $payment = $payable->payments()->updateOrCreate(
            ['transaction_id' => $payable->id, 'transaction_type' => get_class($payable)],
            $paymentData
        );

        // Update status transaksi SETELAH payment berhasil di-save
        if ($paymentMethod->type === 'transfer' && $request->hasFile('payment_proof')) {
            $payable->update(['status' => 'payment_check']);
        }

        // LOGIKA MIDTRANS
        if ($paymentMethod->type === 'qris') {
            try {
                $midtransService = new MidtransService();
                $snapToken = $midtransService->createTransaction($payment);
                $payment->update(['snap_token' => $snapToken]);
                return redirect()->route('payment.pay', $payment->payment_number);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal memproses Midtrans: ' . $e->getMessage());
            }
        }

        $redirectRoute = $type === 'order' ? 'pesanan.show' : 'rent.show';
        $redirectParam = $type === 'order' ? $payable->order_number : $payable->rent_number;

        return redirect()->route($redirectRoute, $redirectParam)
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    // LOGIKA VERIFIKASI ADMIN (LENGKAP)
    public function verifyPayment(Request $request, Payment $payment)
    {
        $action = $request->input('action'); // 'approve' atau 'reject'
        
        if ($action === 'approve') {
            $payment->update(['status' => 'success']);
            
            // Otomatis update status transaksi terkait
            $transaction = $payment->transaction;
            if ($transaction instanceof Order) {
                $transaction->update(['status' => 'processing']);
            } elseif ($transaction instanceof Rent) {
                $transaction->update(['status' => 'active']);
            }
            
            return back()->with('success', 'Pembayaran disetujui! Status transaksi diperbarui.');
        } else {
            // Jika Reject
            $payment->update(['status' => 'failed']);
            
            // Update status transaksi kembali ke pending
            $transaction = $payment->transaction;
            if ($transaction) {
                $transaction->update(['status' => 'pending']);
            }
            
            return back()->with('error', 'Pembayaran ditolak. User diminta upload ulang.');
        }
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

    public function adminIndex(){
        // 1. Fetch data with relationships
        $payments = Payment::with(['paymentMethod', 'transaction'])
            ->latest()
            ->paginate(10);

        // 2. Transform data for the View (Move PHP logic here)
        $payments->getCollection()->transform(function ($payment) {
            
            // Logic: Check Transaction Type (Order or Rent)
            // Check Full Class Name string stored in database
            $isOrder = $payment->transaction_type === 'App\Models\Order';

            // Add custom properties for View
            $payment->view_type_label = $isOrder ? 'Jual' : 'Sewa';
            $payment->view_type_is_order = $isOrder; // Boolean for styling

            // Logic: Get Transaction Number safely
            if ($payment->transaction) {
                $payment->view_transaction_number = $isOrder 
                    ? ($payment->transaction->order_number ?? '-') 
                    : ($payment->transaction->rent_number ?? '-');
            } else {
                $payment->view_transaction_number = '-';
            }

            // Logic: Format Amount
            $payment->view_amount_formatted = 'Rp ' . number_format($payment->amount, 0, ',', '.');

            // Logic: Payment Method Name
            $payment->view_method_name = $payment->paymentMethod->name ?? 'Manual';

            return $payment;
        });

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
        
        $transaction = $payment->transaction;
        
        if ($validated['status'] === 'success') {
            if ($transaction instanceof Order) {
                $transaction->update(['status' => 'processing']);
            } elseif ($transaction instanceof Rent) {
                $transaction->update(['status' => 'active']);
            }
        } elseif ($validated['status'] === 'processing') {
            // Saat payment processing (ada bukti), set order/rent ke payment_check
            if ($transaction) {
                $transaction->update(['status' => 'payment_check']);
            }
        } elseif (in_array($validated['status'], ['pending', 'failed'])) {
            // Saat payment pending/failed, set order/rent kembali ke pending
            if ($transaction) {
                $transaction->update(['status' => 'pending']);
            }
        }
        
        return redirect()->route('admin.payments.index')->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}