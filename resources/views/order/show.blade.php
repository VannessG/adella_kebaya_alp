@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold text mb-1">Detail Pesanan</h1>
                    <p class="text-muted mb-0">No. Pesanan: {{ $order->order_number }}</p>
                </div>
                <span class="badge fs-6 
                    @if($order->status == 'completed') bg-success
                    @elseif($order->status == 'shipping') bg-info
                    @elseif($order->status == 'processing') bg-warning
                    @elseif($order->status == 'pending') bg-secondary
                    @elseif($order->status == 'cancelled') bg-danger
                    @endif">
                    {{ $statusOptions[$order->status] }}
                </span>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0 fw-semibold">Informasi Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Nama:</strong> {{ $order->customer_name }}</p>
                            <p class="mb-2"><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                            <p class="mb-0"><strong>Alamat:</strong> {{ $order->customer_address }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0 fw-semibold">Informasi Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Tanggal Pesan:</strong> {{ $order->order_date }}</p>
                            <p class="mb-2"><strong>No. Pesanan:</strong> {{ $order->order_number }}</p>
                            <p class="mb-0"><strong>Status:</strong> 
                                <span class="badge 
                                    @if($order->status == 'completed') bg-success
                                    @elseif($order->status == 'shipping') bg-info
                                    @elseif($order->status == 'processing') bg-warning
                                    @elseif($order->status == 'pending') bg-secondary
                                    @elseif($order->status == 'cancelled') bg-danger
                                    @endif">
                                    {{ $statusOptions[$order->status] }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0 fw-semibold">Detail Produk</h5>
                </div>
                <div class="card-body">
                    @if($order->products->isEmpty())
                        <p class="text-muted">Tidak ada produk dalam pesanan ini.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->products as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    {{-- PERBAIKAN: Gunakan image_url --}}
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="me-3 rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div>
                                                        <b>{{ $product->name }}</b>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $product->pivot->quantity }}</td>
                                            <td class="text-end">Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($product->pivot->price * $product->pivot->quantity, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td class="text-end fw-bold text fs-5">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Cari bagian Informasi Pembayaran dan pastikan logic re-upload aktif --}}
{{-- Cari bagian Informasi Pembayaran dan ganti loop-nya dengan ini --}}
@if($order->payments && $order->payments->isNotEmpty())
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0 fw-semibold">Status Pembayaran</h5>
    </div>
    <div class="card-body">
        @foreach($order->payments as $payment)
            <div class="row align-items-center">
                <div class="col-md-7">
                    <p class="mb-2"><strong>Metode:</strong> {{ $payment->paymentMethod->name }}</p>
                    <p class="mb-2"><strong>Status:</strong> 
                        <span class="badge 
                            @if($payment->status == 'success') bg-success 
                            @elseif($payment->status == 'processing') bg-warning text-dark 
                            @elseif($payment->status == 'failed') bg-danger 
                            @else bg-secondary @endif">
                            {{ \App\Models\Payment::getStatusOptions()[$payment->status] }}
                        </span>
                    </p>
                    
                    {{-- Form Upload/Re-upload jika metode Transfer --}}
                    @if($payment->paymentMethod->type === 'transfer' && ($payment->status === 'pending' || $payment->status === 'failed'))
                        @if($payment->status === 'failed')
                        <div class="alert alert-danger py-2 mt-3 small">
                            <i class="bi bi-exclamation-triangle"></i> Bukti ditolak admin. Silakan upload bukti baru yang valid.
                        </div>
                        @endif

                        <form action="{{ route('payment.order.process', $order->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                            @csrf
                            <input type="hidden" name="payment_method_id" value="{{ $payment->payment_method_id }}">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Upload Bukti Transfer Baru:</label>
                                <input type="file" name="payment_proof" class="form-control form-control-sm" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 shadow-sm">
                                <i class="bi bi-upload"></i> {{ $payment->status === 'failed' ? 'Upload Ulang Bukti' : 'Kirim Bukti Pembayaran' }}
                            </button>
                        </form>
                    @endif
                </div>

                @if($payment->proof_image)
                <div class="col-md-5 text-end">
                    <p class="small text-muted mb-1">Bukti Terkirim:</p>
                    <img src="{{ asset('storage/' . $payment->proof_image) }}" 
                         class="img-fluid rounded border shadow-sm @if($payment->status === 'failed') border-danger border-3 @endif" 
                         style="max-height: 120px; @if($payment->status === 'failed') filter: grayscale(1); @endif">
                </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif

            <div class="d-flex justify-content-between">
                <a href="{{ url('/pesanan') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>
                <div class="d-flex gap-2">
                    @if($order->canBeCancelled())
                        <form action="{{ route('pesanan.cancel', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                    <button class="btn btn-success">
                        <i class="bi bi-whatsapp"></i> Hubungi CS
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection