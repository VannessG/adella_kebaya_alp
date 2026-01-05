@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">Detail Pesanan</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">No. Pesanan: <span class="text-black fw-bold">{{ $order->order_number }}</span></p>
        </div>
        <div class="col-12 col-md-4 text-center text-md-end">
            <span class="badge rounded-0 border text-uppercase px-3 py-2" style="font-size: 0.75rem; letter-spacing: 0.1em;
                    @if($order->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                    @elseif($order->status == 'shipping') background-color: #007bff; color: #fff; border-color: #007bff;
                    @elseif($order->status == 'processing') background-color: #17a2b8; color: #fff; border-color: #17a2b8;
                    @elseif($order->status == 'payment_check') background-color: #ffc107; color: #000; border-color: #ffc107;
                    @elseif($order->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                    @elseif($order->status == 'cancelled') background-color: #fff; color: #dc3545; border-color: #dc3545;
                    @else background-color: #fff; color: #000; border-color: #000; @endif">
                {{ $statusOptions[$order->status] ?? $order->status }}
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
                        <h6 class="fw-bold text-black text-uppercase mb-3 small border-bottom pb-2" style="letter-spacing: 0.1em;">Informasi Pesanan</h6>
                        <div class="small text-muted text-uppercase" style="line-height: 2;">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Tanggal Pesan</span>
                                <span class="text-black fw-bold text-end">{{ $order->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Cabang</span>
                                <span class="text-black text-end">{{ $order->branch->name ?? '-' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Metode</span>
                                <span class="text-black text-end">{{ $order->delivery_type == 'pickup' ? 'AMBIL DI TOKO' : 'DIANTAR' }}</span>
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
                                <span class="text-black fw-bold text-end">{{ $order->customer_name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Telepon</span>
                                <span class="text-black text-end">{{ $order->customer_phone }}</span>
                            </div>
                            <div class="mt-2">
                                <span class="d-block mb-1">Alamat Pengiriman</span>
                                <span class="text-black d-block p-2 bg-light border border-light-subtle text-capitalize" style="line-height: 1.4;">
                                    {{ $order->customer_address ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border rounded-0 bg-white p-4 mb-4 shadow-sm" style="border-color: #E0E0E0;">
                <h6 class="fw-bold text-black text-uppercase mb-4 small pb-2 border-bottom border-black" style="letter-spacing: 0.1em;">Detail Produk</h6>
                
                @if($order->products->isEmpty())
                    <p class="text-muted small fst-italic text-center py-4">Tidak ada produk dalam pesanan ini.</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3 py-3 text-uppercase small text-muted font-weight-bold" style="min-width: 200px;">Produk</th>
                                    <th class="py-3 text-uppercase small text-muted font-weight-bold text-center">Qty</th>
                                    <th class="py-3 text-uppercase small text-muted font-weight-bold text-end" style="min-width: 100px;">Harga</th>
                                    <th class="pe-3 py-3 text-uppercase small text-muted font-weight-bold text-end" style="min-width: 100px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->products as $product)
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
                                    <td class="text-end py-3 small">Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</td>
                                    <td class="pe-3 text-end py-3 fw-bold text-black small">Rp {{ number_format($product->pivot->price * $product->pivot->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end py-3 text-muted small text-uppercase">Subtotal Item</td>
                                    <td class="pe-3 text-end py-3 fw-bold text-black small">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="text-end py-3 text-success small text-uppercase">Diskon</td>
                                    <td class="pe-3 text-end py-3 fw-bold text-success small">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                @if($order->shipping_cost > 0)
                                <tr>
                                    <td colspan="3" class="text-end py-3 text-muted small text-uppercase">Biaya Pengantaran</td>
                                    <td class="pe-3 text-end py-3 fw-bold text-black small">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr class="bg-black text-white">
                                    <td colspan="3" class="text-end py-3 text-uppercase small" style="letter-spacing: 0.1em;">Total Bayar</td>
                                    <td class="pe-3 text-end py-3 fw-bold fs-6">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>

            @if($order->payment)
            <div class="card border rounded-0 bg-white p-4 mb-4 shadow-sm" style="border-color: #E0E0E0;">
                <h6 class="fw-bold text-black text-uppercase mb-4 small pb-2 border-bottom border-black" style="letter-spacing: 0.1em;">Verifikasi Pembayaran</h6>
                
                <div class="row">
                    <div class="col-12 col-md-6 mb-4 mb-md-0">
                        <div class="small text-uppercase" style="line-height: 2;">
                            <div class="mb-2">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Metode Pembayaran</span>
                                <span class="fw-bold text-black">{{ $order->payment->paymentMethod->name ?? 'Manual' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Status Pembayaran</span>
                                <span class="badge rounded-0 border px-2 py-1 
                                    {{ $order->payment->status == 'approved' ? 'bg-black text-white border-black' : 'bg-white text-black border-black' }}">
                                    {{ strtoupper($order->payment->status) }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Nominal</span>
                                <span class="fw-bold text-black fs-5">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span>
                            </div>

                            @if($order->payment->status === 'processing')
                            <div class="p-3 bg-light border border-secondary-subtle">
                                <p class="small fw-bold mb-2 text-black">Tindakan Admin:</p>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.payments.verify', $order->payment->id) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-primary-custom w-100 rounded-0 btn-sm text-uppercase fw-bold py-2" onclick="return confirm('Verifikasi pembayaran ini?')">
                                            <i class="bi bi-check-lg me-1"></i> Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.payments.verify', $order->payment->id) }}" method="POST" class="flex-grow-1">
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

                    @if($order->payment->proof_image)
                    <div class="col-12 col-md-6 text-md-end">
                        <p class="small text-muted text-uppercase mb-2" style="letter-spacing: 0.1em;">Bukti Transfer</p>
                        <a href="{{ asset('storage/' . $order->payment->proof_image) }}" target="_blank" class="d-inline-block border p-1 bg-white">
                            <img src="{{ asset('storage/' . $order->payment->proof_image) }}" alt="Bukti Pembayaran" class="img-fluid d-block" style="max-height: 250px; object-fit: contain;">
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 pt-3 border-top" style="border-color: #eee !important;">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark rounded-0 px-4 py-2 text-uppercase fw-bold small w-100 w-md-auto" style="letter-spacing: 0.1em;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="w-100 w-md-auto d-flex gap-2">
                    @csrf
                    @method('PUT')
                    
                    <select name="status" class="form-select rounded-0 text-uppercase fw-bold small border-black" style="min-width: 150px;">
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary-custom rounded-0 px-4 py-2 text-uppercase fw-bold small text-nowrap" style="letter-spacing: 0.1em;">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection