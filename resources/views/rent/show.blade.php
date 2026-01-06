@extends('layouts.app')

@section('title', 'Detail Sewa ' . $rent->rent_number)

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">Detail Penyewaan</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">No. Sewa: <span class="text-black fw-bold text-break">{{ $rent->rent_number }}</span></p>
        </div>
        <div class="col-12 col-md-4 text-center text-md-end">
            <span class="badge rounded-0 fw-normal text-uppercase px-3 py-2 small border d-inline-block" 
                style="letter-spacing: 0.05em; font-size: 0.7rem;
                @if($rent->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                @elseif($rent->status == 'payment_check') background-color: #ffc107; color: #000; border-color: #ffc107;
                @elseif($rent->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                @elseif($rent->status == 'active') background-color: #fff; color: #000; border-color: #000;
                @elseif($rent->status == 'cancelled') background-color: #fff; color: #d9534f; border-color: #d9534f;
                @else background-color: #fff; color: #000; border-color: #000; @endif">
                {{ $statusOptions[$rent->status] ?? $rent->status }}
            </span>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5 px-0 px-md-3">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="row g-4 mb-5 mx-0 mx-md-0">
                <div class="col-12 col-md-6">
                    <div class="card h-100 border rounded-0 bg-white p-4">
                        <h5 class="fw-normal text-uppercase text-black mb-4 border-bottom border-black pb-2 font-serif h6">Informasi Pelanggan</h5>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase">
                            <span class="text-muted">Nama</span>
                            <span class="text-black fw-bold text-end">{{ $rent->customer_name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase">
                            <span class="text-muted">Telepon</span>
                            <span class="text-black text-end">{{ $rent->customer_phone }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0 small text-uppercase">
                            <span class="text-muted flex-shrink-0 me-3">Alamat</span>
                            <span class="text-black text-end text-break">{{ $rent->customer_address }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card h-100 border rounded-0 bg-white p-4">
                        <h5 class="fw-normal text-uppercase text-black mb-4 border-bottom border-black pb-2 font-serif h6">Informasi Sewa</h5>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase">
                            <span class="text-muted">Cabang</span>
                            <span class="text-black fw-bold text-end">{{ $rent->branch->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase">
                            <span class="text-muted">Periode</span>
                            <span class="text-black text-end">{{ $rent->start_date->format('d M') }} - {{ $rent->end_date->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase">
                            <span class="text-muted">Durasi</span>
                            <span class="text-black text-end">{{ $rent->total_days }} Hari</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0 small text-uppercase">
                            <span class="text-muted">Metode</span>
                            <span class="text-black text-end">{{ $rent->delivery_type == 'pickup' ? 'Ambil di Tempat' : 'Antar ke Alamat' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border rounded-0 shadow-sm mb-5 mx-0 mx-md-0">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="card-title mb-0 fw-normal text-uppercase text-black font-serif h6">Detail Produk</h5>
                </div>
                
                <div class="card-body p-0 d-none d-md-block">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light border-bottom border-black">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold">Produk</th>
                                <th class="text-center py-3 text-uppercase small text-muted font-weight-bold">Qty</th>
                                <th class="text-end py-3 text-uppercase small text-muted font-weight-bold">Sewa/Hari</th>
                                <th class="text-end pe-4 py-3 text-uppercase small text-muted font-weight-bold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rent->products as $product)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="border p-1 me-3 bg-white">
                                            <img src="{{ $product->image_url }}" style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                        <span class="fw-bold text-black text-uppercase small">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center py-3 text-black small">{{ $product->pivot->quantity }}</td>
                                <td class="text-end py-3 text-muted small">Rp {{ number_format($product->pivot->price_per_day, 0, ',', '.') }}</td>
                                <td class="text-end pe-4 py-3 fw-bold text-black small">Rp {{ number_format($product->pivot->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-body p-4 d-md-none">
                    @foreach($rent->products as $product)
                    <div class="d-flex align-items-start border-bottom pb-3 mb-3">
                        <div class="flex-shrink-0 me-3">
                            <img src="{{ $product->image_url }}" class="border p-1" style="width: 60px; height: 60px; object-fit: cover; border-color: #eee;">
                        </div>
                        <div class="flex-grow-1 pe-2">
                            <h6 class="fw-bold text-black text-uppercase small mb-1" style="line-height: 1.3;">{{ $product->name }}</h6>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $product->pivot->quantity }} x Rp {{ number_format($product->pivot->price_per_day, 0, ',', '.') }} / hari</div>
                        </div>
                        <div class="flex-shrink-0 text-end"><span class="fw-bold text-black small">Rp {{ number_format($product->pivot->subtotal, 0, ',', '.') }}</span></div>
                    </div>
                    @endforeach
                </div>

                <div class="card-footer bg-light border-top border-black p-4">
                    <div class="d-flex justify-content-between mb-2 small text-uppercase">
                        <span class="text-muted">Subtotal Sewa</span>
                        <span class="fw-bold text-black">Rp {{ number_format($rent->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($rent->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2 small text-uppercase">
                        <span class="text-danger">Diskon</span>
                        <span class="text-danger">- Rp {{ number_format($rent->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($rent->shipping_cost > 0)
                    <div class="d-flex justify-content-between mb-2 small text-uppercase">
                        <span class="text-muted">Ongkir</span>
                        <span class="text-black">Rp {{ number_format($rent->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between border-top border-secondary pt-3 mt-2">
                        <span class="fw-bold text-uppercase h6 mb-0">Total Pembayaran</span>
                        <span class="fw-bold h5 mb-0 text-black font-serif">Rp {{ number_format($rent->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($rent->payment)
            <div class="card border rounded-0 shadow-sm mb-5 mx-0 mx-md-0">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="card-title mb-0 fw-normal text-uppercase text-black font-serif h6">Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center g-4">
                        <div class="col-12 col-md-7">
                            <div class="d-flex justify-content-between mb-3 small text-uppercase border-bottom pb-2">
                                <span class="text-muted">Metode</span>
                                <span class="text-black fw-bold">{{ $rent->payment->paymentMethod->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4 small text-uppercase border-bottom pb-2">
                                <span class="text-muted">Status</span>
                                <span class="badge rounded-0 fw-normal text-uppercase px-3 py-2 small border d-inline-block" 
                                    style="letter-spacing: 0.05em; font-size: 0.7rem;
                                    @if($rent->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                                    @elseif($rent->status == 'payment_check') background-color: #ffc107; color: #000; border-color: #ffc107;
                                    @elseif($rent->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                                    @elseif($rent->status == 'active') background-color: #fff; color: #000; border-color: #000;
                                    @elseif($rent->status == 'cancelled') background-color: #fff; color: #d9534f; border-color: #d9534f;
                                    @else background-color: #fff; color: #000; border-color: #000; @endif">
                                    {{ $statusOptions[$rent->status] ?? $rent->status }}
                                </span>
                            </div>

                            @if($rent->payment->status === 'pending')
                                @if($rent->payment->paymentMethod->type !== 'transfer')
                                    <div class="alert rounded-0 bg-light border border-dark text-black small mb-3 p-3"><i class="bi bi-info-circle me-2"></i> Silakan selesaikan pembayaran.</div>
                                    <a href="{{ route('payment.pay', $rent->payment->payment_number) }}" class="btn btn-primary-custom rounded-0 w-100 py-3 fw-bold text-uppercase">Bayar Sekarang</a>
                                @else
                                    <form action="{{ route('payment.rent.process', $rent->id) }}" method="POST" enctype="multipart/form-data" class="mt-3 p-3 bg-light border">
                                        @csrf
                                        <input type="hidden" name="payment_method_id" value="{{ $rent->payment->payment_method_id }}">
                                        <div class="mb-3">
                                            <label class="form-label small text-uppercase fw-bold text-muted">Upload Bukti Transfer</label>
                                            <input type="file" name="payment_proof" class="form-control rounded-0 border bg-white" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary-custom rounded-0 w-100 py-2 fw-bold text-uppercase">Upload Bukti</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                        
                        @if($rent->payment->proof_image)
                        <div class="col-12 col-md-5 text-center border-start-md ps-md-4">
                            <p class="small text-muted text-uppercase mb-2">Bukti Terkirim</p>
                            <img src="{{ asset('storage/' . $rent->payment->proof_image) }}" class="img-fluid border p-1 bg-white shadow-sm" style="max-height: 200px;">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 pt-4 border-top border-black mx-0 mx-md-0">
                <a href="{{ route('rent.index') }}" class="btn btn-link text-decoration-none text-muted text-uppercase small w-100 w-md-auto text-center text-md-start mb-2 mb-md-0"><i class="bi bi-arrow-left me-2"></i> Kembali</a>
                <div class="d-flex flex-column flex-md-row gap-2 w-100 w-md-auto">
                    @if($rent->canBeCancelled())
                        <form action="{{ route('rent.cancel', $rent) }}" method="POST" class="w-100 w-md-auto">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger rounded-0 px-4 py-2 fw-bold text-uppercase w-100" onclick="return confirm('Batalkan sewa ini?')">Batalkan</button>
                        </form>
                    @endif
                    <a href="https://wa.me/6289678956340?text=Halo%20Admin,%20saya%20tanya%20sewa%20{{ $rent->rent_number }}" target="_blank" class="btn btn-outline-custom rounded-0 px-4 py-2 fw-bold text-uppercase w-100 w-md-auto">
                        <i class="bi bi-whatsapp me-2"></i> Hubungi CS
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection