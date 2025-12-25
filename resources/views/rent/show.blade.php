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
                                    {{-- Cari bagian loop @foreach($rent->products as $product) --}}
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
        
        {{-- PERBAIKAN DI SINI: Ganti price_at_rent menjadi price_per_day --}}
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

            @if($rent->payment)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0 fw-semibold">Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Metode Pembayaran:</strong> {{ $rent->payment->paymentMethod->name }}</p>
                            <p class="mb-2"><strong>Status:</strong> 
                                <span class="badge 
                                    @if($rent->payment->status == 'success') bg-success
                                    @elseif($rent->payment->status == 'pending') bg-warning
                                    @elseif($rent->payment->status == 'failed') bg-danger
                                    @elseif($rent->payment->status == 'expired') bg-secondary
                                    @endif">
                                    {{ \App\Models\Payment::getStatusOptions()[$rent->payment->status] }}
                                </span>
                            </p>
                            <p class="mb-2"><strong>Total Bayar:</strong> Rp {{ number_format($rent->payment->amount, 0, ',', '.') }}</p>
                            @if($rent->payment->paid_at)
                                <p class="mb-0"><strong>Tanggal Bayar:</strong> {{ $rent->payment->paid_at->format('d M Y H:i') }}</p>
                            @endif
                        </div>
                        @if($rent->payment->payment_proof)
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Bukti Pembayaran:</strong></p>
                            <img src="{{ asset('storage/' . $rent->payment->payment_proof) }}" 
                                 alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
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
                    
                    @if(!$rent->payment && $rent->status == 'pending')
                        <a href="#payment-section" class="btn btn-success">
                            <i class="bi bi-credit-card"></i> Bayar Sekarang
                        </a>
                    @endif
                    @if($rent->shipment && $rent->shipment->chatRoom)
                        <a href="{{ route('chat.show', $rent->shipment->chatRoom->token) }}" 
                        class="btn btn-info" target="_blank">
                            <i class="bi bi-chat"></i> Chat Driver
                        </a>
                    @endif
                </div>
            </div>

            @if(!$rent->payment && $rent->status == 'pending')
            <div class="card shadow-sm mt-4" id="payment-section">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0 fw-semibold">Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payment.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payable_type" value="rent">
                        <input type="hidden" name="payable_id" value="{{ $rent->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label">Pilih Metode Pembayaran</label>
                            @foreach($paymentMethods as $method)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method_id" 
                                       id="method{{ $method->id }}" value="{{ $method->id }}" 
                                       data-type="{{ $method->type }}" required>
                                <label class="form-check-label" for="method{{ $method->id }}">
                                    <strong>{{ $method->name }}</strong>
                                    @if($method->instructions)
                                        <br><small class="text-muted">{{ $method->instructions }}</small>
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <div id="transfer-proof" style="display: none;">
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label">Upload Bukti Transfer</label>
                                <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*">
                                <div class="form-text">Upload screenshot bukti transfer Anda</div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Setelah pembayaran, admin akan memverifikasi pembayaran Anda. Status akan diperbarui secara otomatis.
                        </div>

                        <button type="submit" class="btn btn-lg w-100">
                            <i class="bi bi-credit-card"></i> Proses Pembayaran
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method_id"]');
    const transferProof = document.getElementById('transfer-proof');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.dataset.type === 'transfer') {
                transferProof.style.display = 'block';
                document.getElementById('payment_proof').required = true;
            } else {
                transferProof.style.display = 'none';
                document.getElementById('payment_proof').required = false;
            }
        });
    });
});
</script>
@endsection