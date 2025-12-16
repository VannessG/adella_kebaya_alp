@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow rounded-4 text-center overflow-hidden">
                <div class="card-header bg-primary text-white py-4 border-0">
                    <i class="bi bi-shield-check display-3 mb-2"></i>
                    <h4 class="fw-bold mb-0">Pembayaran Aman</h4>
                </div>
                <div class="card-body p-5">
                    <p class="text-muted mb-4">Total Tagihan Anda</p>
                    <h1 class="fw-bold text-dark mb-4 display-4">Rp {{ number_format($payment->amount, 0, ',', '.') }}</h1>
                    
                    <div class="alert alert-light border rounded-3 text-start mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">No. Referensi</span>
                            <span class="fw-bold">{{ $payment->payment_number }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Metode</span>
                            <span class="fw-bold">{{ $payment->paymentMethod->name }}</span>
                        </div>
                    </div>

                    <button id="pay-button" class="btn btn-primary-custom w-100 py-3 fw-bold shadow">
                        <i class="bi bi-credit-card-2-front me-2"></i> Bayar Sekarang
                    </button>
                    
                    <p class="mt-3 text-muted small">Anda akan diarahkan ke halaman pembayaran aman Midtrans.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                window.location.href = "{{ route('home') }}?payment=success";
            },
            onPending: function(result){
                location.reload();
            },
            onError: function(result){
                alert("Pembayaran gagal!");
            },
            onClose: function(){
                alert('Anda menutup popup tanpa menyelesaikan pembayaran');
            }
        });
    };
</script>
@endsection