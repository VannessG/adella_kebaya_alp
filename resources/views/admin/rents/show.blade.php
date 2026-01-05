@extends('layouts.app')

@section('title', 'Detail Sewa ' . $rent->rent_number)

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">Detail Penyewaan</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">No. Sewa: <span class="text-black fw-bold">{{ $rent->rent_number }}</span></p>
        </div>
        <div class="col-12 col-md-4 text-center text-md-end">
            <span class="badge rounded-0 border text-uppercase px-3 py-2" 
                    style="font-size: 0.75rem; letter-spacing: 0.1em;
                    @if($rent->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                    @elseif($rent->status == 'payment_check') background-color: #ffc107; color: #000; border-color: #ffc107;
                    @elseif($rent->status == 'confirmed') background-color: #17a2b8; color: #fff; border-color: #17a2b8;
                    @elseif($rent->status == 'active') background-color: #28a745; color: #fff; border-color: #28a745;
                    @elseif($rent->status == 'returned') background-color: #6c757d; color: #fff; border-color: #6c757d;
                    @elseif($rent->status == 'overdue') background-color: #dc3545; color: #fff; border-color: #dc3545;
                    @elseif($rent->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                    @elseif($rent->status == 'cancelled') background-color: #fff; color: #d9534f; border-color: #d9534f;
                    @else background-color: #fff; color: #000; border-color: #000; @endif">
                {{ $statusOptions[$rent->status] }}
            </span>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5 px-0 px-md-3">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-6">
                    <div class="card border rounded-0 h-100 bg-white p-4 shadow-sm" style="border-color: #E0E0E0;">
                        <h6 class="fw-bold text-black text-uppercase mb-3 small border-bottom pb-2" style="letter-spacing: 0.1em;">Informasi Penyewaan</h6>
                        <div class="small text-muted text-uppercase" style="line-height: 2;">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Cabang</span>
                                <span class="text-black fw-bold text-end">{{ $rent->branch->name }}</span>
                            </div>
                            <div class="mb-2 text-end">
                                <span class="d-block text-muted" style="font-size: 0.65rem;">{{ $rent->branch->address }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2 mb-1">
                                <span>Periode</span>
                                <span class="text-black text-end">{{ $rent->start_date->format('d M') }} - {{ $rent->end_date->format('d M Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Durasi</span>
                                <span class="text-black text-end">{{ $rent->calculateRentalDays() }} Hari</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Pengiriman</span>
                                <span class="text-black text-end">{{ $rent->delivery_type == 'pickup' ? 'AMBIL DI TOKO' : 'DIANTAR' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card border rounded-0 h-100 bg-white p-4 shadow-sm" style="border-color: #E0E0E0;">
                        <h6 class="fw-bold text-black text-uppercase mb-3 small border-bottom pb-2" style="letter-spacing: 0.1em;">Informasi Pelanggan</h6>
                        <div class="small text-muted text-uppercase" style="line-height: 2;">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Nama</span>
                                <span class="text-black fw-bold text-end">{{ $rent->customer_name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Telepon</span>
                                <span class="text-black text-end">{{ $rent->customer_phone }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Email</span>
                                <span class="text-black text-end text-lowercase">{{ $rent->user->email ?? '-' }}</span>
                            </div>
                            <div class="mt-2">
                                <span class="d-block mb-1">Alamat Pengiriman</span>
                                <span class="text-black d-block p-2 bg-light border border-light-subtle text-capitalize" style="line-height: 1.4;">{{ $rent->customer_address ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border rounded-0 bg-white p-4 mb-4 shadow-sm" style="border-color: #E0E0E0;">
                <h6 class="fw-bold text-black text-uppercase mb-4 small pb-2 border-bottom border-black" style="letter-spacing: 0.1em;">Detail Produk</h6>
                @if($rent->products->isEmpty())
                    <p class="text-muted small fst-italic text-center py-4">Tidak ada produk dalam penyewaan ini.</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3 py-3 text-uppercase small text-muted font-weight-bold" style="min-width: 200px;">Produk</th>
                                    <th class="py-3 text-uppercase small text-muted font-weight-bold text-center">Qty</th>
                                    <th class="py-3 text-uppercase small text-muted font-weight-bold text-end" style="min-width: 100px;">Harga/Hari</th>
                                    <th class="pe-3 py-3 text-uppercase small text-muted font-weight-bold text-end" style="min-width: 100px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rent->products as $product)
                                <tr style="border-bottom: 1px solid #f0f0f0;">
                                    <td class="ps-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="border p-1 bg-white me-3 d-none d-sm-block" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <div class="fw-bold text-black small text-uppercase">{{ $product->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-3 small">{{ $product->pivot->quantity }}</td>
                                    <td class="text-end py-3 small">Rp {{ number_format($product->pivot->price_per_day, 0, ',', '.') }}</td>
                                    <td class="pe-3 text-end py-3 fw-bold text-black small">Rp {{ number_format($product->pivot->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end py-3 text-muted small text-uppercase">Subtotal Item</td>
                                    <td class="pe-3 text-end py-3 fw-bold text-black small">Rp {{ number_format($rent->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if($rent->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="text-end py-3 text-success small text-uppercase">Diskon</td>
                                    <td class="pe-3 text-end py-3 fw-bold text-success small">- Rp {{ number_format($rent->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                @if($rent->shipping_cost > 0)
                                <tr>
                                    <td colspan="3" class="text-end py-3 text-muted small text-uppercase">Biaya Pengantaran</td>
                                    <td class="pe-3 text-end py-3 fw-bold text-black small">Rp {{ number_format($rent->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr class="bg-black text-white">
                                    <td colspan="3" class="text-end py-3 text-uppercase small" style="letter-spacing: 0.1em;">Total Bayar</td>
                                    <td class="pe-3 text-end py-3 fw-bold fs-6">Rp {{ number_format($rent->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>

            @if($rent->payment)
            <div class="card border rounded-0 bg-white p-4 mb-4 shadow-sm" style="border-color: #E0E0E0;">
                <h6 class="fw-bold text-black text-uppercase mb-4 small pb-2 border-bottom border-black" style="letter-spacing: 0.1em;">Verifikasi Pembayaran</h6>
                <div class="row">
                    <div class="col-12 col-md-6 mb-4 mb-md-0">
                        <div class="small text-uppercase" style="line-height: 2;">
                            <div class="mb-2">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Metode Pembayaran</span>
                                <span class="fw-bold text-black">{{ $rent->payment->paymentMethod->name ?? 'Manual' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Status Pembayaran</span>
                                <span class="badge rounded-0 border px-2 py-1 
                                    {{ $rent->payment->status == 'approved' ? 'bg-black text-white border-black' : 'bg-white text-black border-black' }}">
                                    {{ strtoupper($rent->payment->status) }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Nominal</span>
                                <span class="fw-bold text-black fs-5">Rp {{ number_format($rent->payment->amount, 0, ',', '.') }}</span>
                            </div>

                            @if($rent->payment->status === 'processing')
                            <div class="p-3 bg-light border border-secondary-subtle">
                                <p class="small fw-bold mb-2 text-black">Tindakan Admin:</p>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.payments.verify', $rent->payment->id) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-primary-custom w-100 rounded-0 btn-sm text-uppercase fw-bold py-2" onclick="return confirm('Verifikasi pembayaran ini?')">
                                            <i class="bi bi-check-lg me-1"></i> Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.payments.verify', $rent->payment->id) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn btn-outline-danger w-100 rounded-0 btn-sm text-uppercase fw-bold py-2" onclick="return confirm('Tolak pembayaran ini?')">
                                            <i class="bi bi-x-lg me-1"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($rent->payment->proof_image)
                    <div class="col-12 col-md-6 text-md-end">
                        <p class="small text-muted text-uppercase mb-2" style="letter-spacing: 0.1em;">Bukti Transfer</p>
                        <a href="{{ asset('storage/' . $rent->payment->proof_image) }}" target="_blank" class="d-inline-block border p-1 bg-white">
                            <img src="{{ asset('storage/' . $rent->payment->proof_image) }}" alt="Bukti Pembayaran" class="img-fluid d-block" style="max-height: 250px; object-fit: contain;">
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 pt-3 border-top" style="border-color: #eee !important;">
                <a href="{{ route('admin.rents.index') }}" class="btn btn-outline-dark rounded-0 px-4 py-2 text-uppercase fw-bold small w-100 w-md-auto" style="letter-spacing: 0.1em;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                
                <form action="{{ route('admin.rents.update-status', $rent) }}" method="POST" class="w-100 w-md-auto d-flex gap-2">
                    @csrf
                    @method('PUT')
                    
                    <select name="status" class="form-select rounded-0 text-uppercase fw-bold small border-black" style="min-width: 150px;">
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ $rent->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary-custom rounded-0 px-4 py-2 text-uppercase fw-bold small text-nowrap" style="letter-spacing: 0.1em;">Update Status</button>
                </form>
            </div>
                
                @if($rent->canBeCancelled() && (!$rent->payment || $rent->payment->status != 'approved'))
                    <form action="{{ route('admin.rents.update-status', $rent) }}" method="POST" class="w-100 w-md-auto">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-danger rounded-0 px-4 py-2 text-uppercase fw-bold small w-100" 
                                style="letter-spacing: 0.1em;" onclick="return confirm('Yakin ingin membatalkan paksa penyewaan ini?')">
                            Batalkan Sewa
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection