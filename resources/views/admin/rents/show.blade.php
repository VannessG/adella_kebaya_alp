@extends('layouts.app')

@section('title', 'Detail Sewa ' . $rent->rent_number)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold text mb-1">Detail Penyewaan</h1>
                    <p class="text-muted mb-0">No. Sewa: {{ $rent->rent_number }}</p>
                </div>
                <span class="badge fs-6 
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

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0 fw-semibold">Informasi Penyewaan</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Cabang:</strong> {{ $rent->branch->name }}</p>
                            <p class="mb-2"><strong>Alamat Cabang:</strong> {{ $rent->branch->address }}</p>
                            <p class="mb-2"><strong>Periode Sewa:</strong> 
                                {{ $rent->start_date->format('d M Y') }} - {{ $rent->end_date->format('d M Y') }}
                            </p>
                            <p class="mb-2"><strong>Lama Sewa:</strong> {{ $rent->calculateRentalDays() }} hari</p>
                            <p class="mb-0"><strong>Metode:</strong> 
                                {{ $rent->shipping_method == 'pickup' ? 'Ambil di Tempat' : 'Antar ke Alamat' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
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

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
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
                                        <td colspan="3" class="text-end fw-bold">Subtotal Sewa:</td>
                                        <td class="text-end">Rp {{ number_format($rent->total_amount - $rent->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($rent->shipping_cost > 0)
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Biaya Pengantaran:</td>
                                        <td class="text-end">Rp {{ number_format($rent->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td class="text-end fw-bold text fs-5">
                                            Rp {{ number_format($rent->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- PERBAIKAN: BLOK VERIFIKASI PEMBAYARAN ADMIN --}}
            @if($rent->payment)
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 fw-semibold">Informasi Pembayaran & Verifikasi Admin</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Metode:</strong> {{ $rent->payment->paymentMethod->name }}</p>
                            <p class="mb-2"><strong>Status:</strong> 
                                <span class="badge 
                                    @if($rent->payment->status == 'success') bg-success
                                    @elseif($rent->payment->status == 'processing') bg-warning text-dark
                                    @elseif($rent->payment->status == 'failed') bg-danger
                                    @elseif($rent->payment->status == 'expired') bg-secondary
                                    @endif">
                                    {{ \App\Models\Payment::getStatusOptions()[$rent->payment->status] }}
                                </span>
                            </p>
                            <p class="mb-2"><strong>Total Bayar:</strong> Rp {{ number_format($rent->payment->amount, 0, ',', '.') }}</p>
                            
                            {{-- TOMBOL AKSI VERIFIKASI --}}
                            @if($rent->payment->status === 'processing' && $rent->payment->proof_image)
                            <div class="mt-4 p-3 bg-light rounded border">
                                <p class="small fw-bold mb-2">Tindakan Admin:</p>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.payments.verify', $rent->payment->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-success btn-sm px-4">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.payments.verify', $rent->payment->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn btn-outline-danger btn-sm px-4">Reject</button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        @if($rent->payment->proof_image)
                        <div class="col-md-6 text-md-end">
                            <p class="mb-2 small fw-bold">Bukti Transfer:</p>
                            <a href="{{ asset('storage/' . $rent->payment->proof_image) }}" target="_blank">
                                <img src="{{ asset('storage/' . $rent->payment->proof_image) }}" 
                                     alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm border @if($rent->payment->status === 'failed') border-danger border-4 @endif" 
                                     style="max-height: 250px; cursor: zoom-in;">
                            </a>
                            @if($rent->payment->status === 'failed')
                                <div class="text-danger small mt-1 fw-bold">BUKTI DITOLAK - Menunggu Re-upload</div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-between">
                <a href="{{ route('rent.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                
                <div class="d-flex gap-2">
                    @if($rent->canBeCancelled() && !$rent->payment)
                        <form action="{{ route('rent.cancel', $rent) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Yakin ingin membatalkan penyewaan ini?')">
                                Batalkan Sewa
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection