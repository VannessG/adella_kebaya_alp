@extends('layouts.app')

@section('title', 'Pembayaran Pesanan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold text mb-4">Pembayaran Pesanan</h1>

            <!-- Ringkasan Order -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0 fw-semibold">Detail Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>No. Pesanan:</strong> {{ $order->order_number }}</p>
                            <p><strong>Tanggal:</strong> {{ $order->order_date->format('d M Y') }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-warning">{{ $order->status }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Cabang:</strong> {{ $order->branch->name }}</p>
                            <p><strong>Pengiriman:</strong> 
                                {{ $order->shipping_method == 'pickup' ? 'Ambil di Tempat' : 'Antar ke Alamat' }}
                            </p>
                        </div>
                    </div>

                    <!-- Daftar Produk -->
                    <h6 class="fw-bold mb-3">Produk Dipesan:</h6>
                    @foreach($order->products as $product)
                    <div class="row align-items-center mb-2">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $product->name }}</p>
                                    <small class="text-muted">Rp {{ number_format($product->pivot->price, 0, ',', '.') }} × {{ $product->pivot->quantity }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <strong>Rp {{ number_format($product->pivot->price * $product->pivot->quantity, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @endforeach

                    <!-- Ringkasan Biaya -->
                    <div class="border-top pt-3 mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1">Subtotal Produk</p>
                                @if($order->shipping_cost > 0)
                                <p class="mb-1">Biaya Pengiriman</p>
                                @endif
                                <p class="mb-0 fw-bold">Total Pembayaran</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="mb-1">Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</p>
                                @if($order->shipping_cost > 0)
                                <p class="mb-1">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                                @endif
                                <p class="mb-0 fw-bold fs-5 text">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pembayaran -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0 fw-semibold">Pilih Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payment.order.process', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            @foreach($paymentMethods as $method)
                            <div class="form-check mb-3 border rounded p-3">
                                <input class="form-check-input" type="radio" name="payment_method_id" 
                                       id="method{{ $method->id }}" value="{{ $method->id }}" 
                                       data-type="{{ $method->type }}" required>
                                <label class="form-check-label w-100" for="method{{ $method->id }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="d-block">{{ $method->name }}</strong>
                                            @if($method->instructions)
                                                <small class="text-muted">{{ $method->instructions }}</small>
                                            @endif
                                        </div>
                                        @if($method->type === 'qris')
                                            <span class="badge bg-success">Instant</span>
                                        @else
                                            <span class="badge bg-warning">Manual</span>
                                        @endif
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <div id="transfer-proof" style="display: none;">
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label">Upload Bukti Transfer</label>
                                <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*">
                                <div class="form-text">Upload screenshot/photo bukti transfer Anda</div>
                            </div>
                        </div>

                        <div id="qris-payment" style="display: none;">
                            <div class="alert alert-success">
                                <i class="bi bi-qr-code"></i> 
                                <strong>Pembayaran QRIS</strong>
                                <p class="mb-0 mt-2">Scan QR code berikut menggunakan aplikasi e-wallet atau mobile banking Anda.</p>
                            </div>
                            <div class="text-center">
                                <!-- QR Code placeholder -->
                                <div class="bg-light p-4 rounded d-inline-block mb-3">
                                    <i class="bi bi-qr-code-scan display-1 text-muted"></i>
                                </div>
                                <p class="text-muted">QR code akan digenerate setelah memilih metode ini</p>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Informasi Penting:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Pembayaran QRIS akan otomatis terkonfirmasi</li>
                                <li>Pembayaran Transfer membutuhkan konfirmasi manual oleh admin</li>
                                <li>Pesanan akan diproses setelah pembayaran dikonfirmasi</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg" id="submitBtn">
                                <i class="bi bi-credit-card"></i> Bayar Sekarang
                            </button>
                            <a href="{{ route('pesanan.show', $order->order_number) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Detail Pesanan
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method_id"]');
    const transferProof = document.getElementById('transfer-proof');
    const qrisPayment = document.getElementById('qris-payment');
    const submitBtn = document.getElementById('submitBtn');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.dataset.type === 'transfer') {
                transferProof.style.display = 'block';
                qrisPayment.style.display = 'none';
                document.getElementById('payment_proof').required = true;
                submitBtn.innerHTML = '<i class="bi bi-upload"></i> Upload Bukti & Bayar';
            } else if (this.dataset.type === 'qris') {
                transferProof.style.display = 'none';
                qrisPayment.style.display = 'block';
                document.getElementById('payment_proof').required = false;
                submitBtn.innerHTML = '<i class="bi bi-qr-code"></i> Bayar dengan QRIS';
            } else {
                transferProof.style.display = 'none';
                qrisPayment.style.display = 'none';
            }
        });
    });

    // Set default jika ada
    const firstPaymentMethod = document.querySelector('input[name="payment_method_id"]');
    if (firstPaymentMethod) {
        firstPaymentMethod.checked = true;
        firstPaymentMethod.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection