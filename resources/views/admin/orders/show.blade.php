@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)

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
                            @if($order->user)
                                <p class="mb-0 mt-2"><strong>Email:</strong> {{ $order->user->email }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0 fw-semibold">Informasi Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Tanggal Pesan:</strong> {{ date('d M Y', strtotime($order->order_date)) }}</p>
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

            {{-- PERBAIKAN: Tambah blok Informasi Pembayaran (sama seperti detail sewa) --}}
            {{-- Cari bagian Informasi Pembayaran dan ganti isinya dengan ini --}}
@if($order->payments && $order->payments->isNotEmpty())
<div class="card shadow-sm mb-4 border-primary">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0 fw-semibold">Informasi Pembayaran & Verifikasi Admin</h5>
    </div>
    <div class="card-body">
        @foreach($order->payments as $payment)
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-2"><strong>Metode:</strong> {{ $payment->paymentMethod->name ?? 'N/A' }}</p>
                <p class="mb-2"><strong>Status Pembayaran:</strong> 
                    <span class="badge 
                        @if($payment->status == 'success') bg-success 
                        @elseif($payment->status == 'processing') bg-warning text-dark 
                        @elseif($payment->status == 'failed') bg-danger 
                        @else bg-secondary @endif">
                        {{ \App\Models\Payment::getStatusOptions()[$payment->status] ?? $payment->status }}
                    </span>
                </p>
                <p class="mb-2"><strong>Total Dibayar:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>

                {{-- TOMBOL VERIFIKASI KHUSUS ADMIN --}}
                @if($payment->status === 'processing' && $payment->proof_image)
                <div class="mt-4 p-3 bg-light rounded border">
                    <p class="small fw-bold mb-2">Tindakan Admin:</p>
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-success btn-sm px-4">
                                <i class="bi bi-check-lg"></i> Approve
                            </button>
                        </form>
                        <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="btn btn-outline-danger btn-sm px-4">
                                <i class="bi bi-x-lg"></i> Reject
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            @if($payment->proof_image)
            <div class="col-md-6 text-md-end">
                <p class="mb-2 small fw-bold">Bukti Transfer:</p>
                <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank">
                    <img src="{{ asset('storage/' . $payment->proof_image) }}" 
                         class="img-fluid rounded border shadow-sm @if($payment->status === 'failed') border-danger border-4 @endif" 
                         style="max-height: 250px; cursor: zoom-in;">
                </a>
                @if($payment->status === 'failed')
                    <div class="text-danger small mt-1 fw-bold">BUKTI DITOLAK - Menunggu Re-upload</div>
                @endif
            </div>
            @endif
        </div>
        @if(!$loop->last) <hr> @endif
        @endforeach
    </div>
</div>
@endif

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
                </a>
                
                <div class="d-flex gap-2">
                    @if($order->status == 'pending' || $order->status == 'processing')
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success" 
                                    onclick="return confirm('Tandai pesanan sebagai selesai?')">
                                <i class="bi bi-check-circle"></i> Tandai Selesai
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select shadow-none" onchange="this.form.submit()">
                            <option value="">Ubah Status</option>
                            @foreach($statusOptions as $value => $label)
                                @if($value != $order->status)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection