@extends('layouts.app')

@section('title', 'Detail Sewa ' . $rent->rent_number)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Header Detail --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold text mb-1">Detail Penyewaan</h1>
                    <p class="text-muted mb-0">No. Sewa: {{ $rent->rent_number }}</p>
                </div>
                <div class="text-end">
                    <span class="badge fs-6 mb-2 d-block
                        @if($rent->status == 'returned') bg-success
                        @elseif($rent->status == 'active') bg-info
                        @elseif($rent->status == 'paid') bg-warning
                        @elseif($rent->status == 'pending') bg-secondary
                        @elseif($rent->status == 'overdue') bg-danger
                        @elseif($rent->status == 'cancelled') bg-dark
                        @endif">
                        {{ $statusOptions[$rent->status] }}
                    </span>
                </div>
            </div>

            <div class="row">
                {{-- Informasi Penyewaan --}}
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-light border-0">
                            <h5 class="card-title mb-0 fw-semibold">Informasi Penyewaan</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Cabang:</strong> {{ $rent->branch->name }}</p>
                            <p class="mb-2"><strong>Alamat Cabang:</strong> {{ $rent->branch->address }}</p>
                            <p class="mb-2"><strong>Periode Sewa:</strong> 
                                {{ $rent->start_date->format('d M Y') }} - {{ $rent->end_date->format('d M Y') }}
                            </p>
                            <p class="mb-2"><strong>Lama Sewa:</strong> {{ $rent->total_days }} hari</p>
                            <p class="mb-0"><strong>Metode:</strong> 
                                {{ $rent->delivery_type == 'pickup' ? 'Ambil di Tempat' : 'Antar ke Alamat' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Informasi Pelanggan --}}
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-light border-0">
                            <h5 class="card-title mb-0 fw-semibold">Informasi Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Nama:</strong> {{ $rent->customer_name }}</p>
                            <p class="mb-2"><strong>Telepon:</strong> {{ $rent->customer_phone }}</p>
                            <p class="mb-0"><strong>Alamat:</strong> {{ $rent->customer_address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Produk --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-semibold">Detail Produk</h5>
                </div>
                <div class="card-body">
                    @if($rent->products->isEmpty())
                        <p class="text-muted">Tidak ada produk dalam penyewaan ini.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Harga Sewa/hari</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rent->products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                                     class="me-3 rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <b>{{ $product->name }}</b>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $product->pivot->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($product->pivot->price_per_day, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($product->pivot->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Subtotal Produk:</td>
                                        <td class="text-end">Rp {{ number_format($rent->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($rent->discount_amount > 0)
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold text-danger">Potongan Diskon:</td>
                                        <td class="text-end text-danger">- Rp {{ number_format($rent->discount_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    @if($rent->shipping_cost > 0)
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Biaya Pengantaran:</td>
                                        <td class="text-end">Rp {{ number_format($rent->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end fw-bold">Total Pembayaran:</td>
                                        <td class="text-end fw-bold text-primary fs-5">
                                            Rp {{ number_format($rent->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Status Pembayaran & Verifikasi Admin --}}
            @if($rent->payment)
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-semibold">Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <p class="mb-2"><strong>Metode:</strong> {{ $rent->payment->paymentMethod->name }} ({{ strtoupper($rent->payment->paymentMethod->type) }})</p>
                            <p class="mb-3"><strong>Status:</strong> 
                                <span class="badge 
                                    @if($rent->payment->status == 'success') bg-success
                                    @elseif($rent->payment->status == 'processing') bg-warning text-dark
                                    @elseif($rent->payment->status == 'failed') bg-danger
                                    @elseif($rent->payment->status == 'expired') bg-secondary
                                    @endif">
                                    {{ \App\Models\Payment::getStatusOptions()[$rent->payment->status] }}
                                </span>
                            </p>
                            
                            {{-- Lanjut Bayar QRIS/Midtrans --}}
                            @if($rent->payment->status === 'pending' && $rent->payment->paymentMethod->type !== 'transfer')
                                <div class="alert alert-info small">
                                    <i class="bi bi-info-circle"></i> Selesaikan pembayaran untuk memproses penyewaan.
                                </div>
                                <a href="{{ route('payment.pay', $rent->payment->payment_number) }}" class="btn btn-primary shadow-sm">
                                    <i class="bi bi-qr-code-scan"></i> Bayar Sekarang
                                </a>
                            @endif

                            {{-- Form Re-upload Bukti Transfer --}}
                            @if($rent->payment->paymentMethod->type === 'transfer' && ($rent->payment->status === 'pending' || $rent->payment->status === 'failed'))
                                <form action="{{ route('payment.rent.process', $rent->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="payment_method_id" value="{{ $rent->payment->payment_method_id }}">
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Upload Bukti Transfer Baru:</label>
                                        <input type="file" name="payment_proof" class="form-control form-control-sm" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100 shadow-sm">
                                        <i class="bi bi-upload"></i> Kirim Bukti Pembayaran
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if($rent->payment->proof_image)
                        <div class="col-md-5 text-end text-md-end text-center mt-3 mt-md-0">
                            <p class="small text-muted mb-1 text-start">Bukti Terkirim:</p>
                            <img src="{{ asset('storage/' . $rent->payment->proof_image) }}" 
                                 class="img-fluid rounded border shadow-sm" 
                                 style="max-height: 150px; object-fit: contain;">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Navigasi Bawah --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('rent.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                
                <div class="d-flex gap-2">
                    {{-- Tombol Batalkan --}}
                    @if($rent->canBeCancelled())
                        <form action="{{ route('rent.cancel', $rent) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Yakin ingin membatalkan penyewaan ini?')">
                                Batalkan Sewa
                            </button>
                        </form>
                    @endif
                    <a href="https://wa.me/62898051110211?text=Halo%20Admin%20Adella%20Kebaya,%20saya%20ingin%20bertanya%20tentang%20penyewaan%20{{ $rent->rent_number }}" 
                       target="_blank" class="btn btn-success">
                        <i class="bi bi-whatsapp"></i> Hubungi CS
                    </a>

                    {{-- Tombol Bayar Sekarang (Jika belum ada payment record) --}}
                    @if(!$rent->payment && $rent->status == 'pending')
                        <a href="#payment-section" class="btn btn-success px-4 shadow-sm">
                            <i class="bi bi-credit-card"></i> Pilih Metode & Bayar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection