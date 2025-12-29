@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-12 text-center">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Pembayaran</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Selesaikan transaksi Anda</p>
        </div>
    </div>
    <div class="d-none d-md-block mx-auto" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border rounded-0 bg-white p-4 p-md-5" style="border-color: var(--border-color);">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-lock display-4 text-black mb-3 d-block"></i>
                    <h5 class="fw-bold text-uppercase text-black small" style="letter-spacing: 0.15em;">Transaksi Aman</h5>
                </div>

                <div class="card-body p-0 text-center">
                    <p class="text-muted small text-uppercase mb-2" style="letter-spacing: 0.1em;">Total Tagihan</p>
                    <h1 class="fw-normal text-black mb-5 display-4" style="font-family: 'Marcellus', serif;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</h1>
                    <div class="bg-subtle p-4 border border-light-subtle mb-5 text-start">
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2" style="border-color: #eee !important;">
                            <span class="text-muted small text-uppercase" style="letter-spacing: 0.05em;">No. Referensi</span>
                            <span class="fw-bold text-black font-monospace">{{ $payment->payment_number }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small text-uppercase" style="letter-spacing: 0.05em;">Metode</span>
                            <span class="fw-bold text-black text-uppercase">{{ $payment->paymentMethod->name }}</span>
                        </div>
                    </div>

                    <button id="pay-button" class="btn btn-primary-custom w-100 rounded-0 py-3 text-uppercase fw-bold shadow-none" style="letter-spacing: 0.1em; font-size: 0.9rem;">
                        <i class="bi bi-credit-card me-2"></i> Bayar Sekarang
                    </button>
                    
                    <p class="mt-4 text-muted small fst-italic" style="font-size: 0.75rem;">Anda akan diarahkan ke sistem pembayaran aman Midtrans.</p>
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