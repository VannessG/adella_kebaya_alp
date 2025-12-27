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

            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-semibold">Detail Produk</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            {{-- ... thead tetap ... --}}
                            <tbody>
                                @foreach($order->products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td class="text-center">{{ $product->pivot->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($product->pivot->price * $product->pivot->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Subtotal Produk:</td>
                                    <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-danger">Potongan Diskon:</td>
                                    <td class="text-end text-danger">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                @if($order->shipping_cost > 0)
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Biaya Pengiriman:</td>
                                    <td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total Pembayaran:</td>
                                    <td class="text-end fw-bold text-primary fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($order->payment)
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-semibold">Status Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <p class="mb-2"><strong>Metode:</strong> {{ $order->payment->paymentMethod->name }}</p>
                            <p class="mb-3"><strong>Status:</strong> 
                                <span class="badge @if($order->payment->status == 'success') bg-success @elseif($order->payment->status == 'failed') bg-danger @else bg-warning text-dark @endif">
                                    {{ \App\Models\Payment::getStatusOptions()[$order->payment->status] }}
                                </span>
                            </p>

                            @if($order->payment->status === 'pending' && $order->payment->paymentMethod->type !== 'transfer')
                                <div class="alert alert-info small">Silakan selesaikan pembayaran Anda.</div>
                                <a href="{{ route('payment.pay', $order->payment->payment_number) }}" class="btn btn-primary">
                                    <i class="bi bi-qr-code-scan"></i> Bayar Sekarang
                                </a>
                            @endif

                            @if($order->payment->paymentMethod->type === 'transfer' && ($order->payment->status === 'pending' || $order->payment->status === 'failed'))
                                <form action="{{ route('payment.order.process', $order->id) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="payment_method_id" value="{{ $order->payment->payment_method_id }}">
                                    <input type="file" name="payment_proof" class="form-control form-control-sm mb-2" required>
                                    <button type="submit" class="btn btn-primary btn-sm">Upload Bukti</button>
                                </form>
                            @endif
                        </div>
                        @if($order->payment->proof_image)
                        <div class="col-md-5 text-end">
                            <img src="{{ asset('storage/' . $order->payment->proof_image) }}" class="img-fluid rounded border" style="max-height: 120px;">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ url('/pesanan') }}" class="btn btn-outline-secondary">Kembali</a>
                <div class="d-flex gap-2">
                    @if($order->canBeCancelled())
                        <form action="{{ route('pesanan.cancel', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Batalkan?')">Batalkan Pesanan</button>
                        </form>
                    @endif
                    {{-- TOMBOL WHATSAPP CS --}}
                    <a href="https://wa.me/62898051110211?text=Halo%20Admin,%20saya%20tanya%20pesanan%20{{ $order->order_number }}" 
                    target="_blank" class="btn btn-success">
                        <i class="bi bi-whatsapp"></i> Hubungi CS
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection