@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Detail Pesanan</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">No. Pesanan: <span class="text-black fw-bold">{{ $order->order_number }}</span></p>
        </div>
        <div class="col-md-4 text-center text-md-end mt-3 mt-md-0">
            <span class="badge rounded-0 fw-normal text-uppercase px-3 py-2 small border" 
                style="letter-spacing: 0.05em; font-size: 0.7rem;
                @if($order->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                @elseif($order->status == 'payment_check') background-color: #ffc107; color: #000; border-color: #ffc107; {{-- Warna Kuning untuk Pengecekan --}}
                @elseif($order->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                @elseif($order->status == 'active') background-color: #fff; color: #000; border-color: #000;
                @elseif($order->status == 'cancelled') background-color: #fff; color: #d9534f; border-color: #d9534f;
                @else background-color: #fff; color: #000; border-color: #000; @endif">
                {{ $statusOptions[$order->status] ?? $order->status }}
            </span>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="card h-100 border rounded-0 bg-white p-4" style="border-color: var(--border-color);">
                        <h5 class="fw-normal text-uppercase text-black mb-4 border-bottom border-black pb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em; font-size: 1rem;">Informasi Pelanggan</h5>
                        
                        <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Nama</span>
                            <span class="text-black fw-bold">{{ $order->customer_name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Telepon</span>
                            <span class="text-black">{{ $order->customer_phone }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Alamat</span>
                            <span class="text-black text-end" style="max-width: 60%;">{{ $order->customer_address }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border rounded-0 bg-white p-4" style="border-color: var(--border-color);">
                        <h5 class="fw-normal text-uppercase text-black mb-4 border-bottom border-black pb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em; font-size: 1rem;">Informasi Pesanan</h5>
                        
                        <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Tanggal Pesan</span>
                            <span class="text-black">{{ $order->order_date }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">No. Pesanan</span>
                            <span class="text-black fw-bold">{{ $order->order_number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border rounded-0 shadow-sm mb-5" style="border-color: var(--border-color);">
                <div class="card-header bg-white border-bottom p-4" style="border-color: var(--border-color) !important;">
                    <h5 class="card-title mb-0 fw-normal text-uppercase text-black" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em; font-size: 1rem;">Detail Produk</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-subtle border-bottom border-black">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Produk</th>
                                    <th class="text-center py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Jumlah</th>
                                    <th class="text-end py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Harga</th>
                                    <th class="text-end pe-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->products as $product)
                                <tr style="border-bottom: 1px solid #F0F0F0;">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-black text-uppercase small" style="letter-spacing: 0.05em;">{{ $product->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-3 text-black">{{ $product->pivot->quantity }}</td>
                                    <td class="text-end py-3 text-muted small">Rp {{ number_format($product->pivot->price, 0, ',', '.') }}</td>
                                    <td class="text-end pe-4 py-3 fw-bold text-black">Rp {{ number_format($product->pivot->price * $product->pivot->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end py-3 text-uppercase small text-muted pe-3" style="letter-spacing: 0.1em;">Subtotal Produk:</td>
                                    <td class="text-end pe-4 py-3 fw-bold text-black">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="text-end py-2 text-uppercase small text-danger pe-3" style="letter-spacing: 0.1em;">Potongan Diskon:</td>
                                    <td class="text-end pe-4 py-2 text-danger">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                @if($order->shipping_cost > 0)
                                <tr>
                                    <td colspan="3" class="text-end py-2 text-uppercase small text-muted pe-3" style="letter-spacing: 0.1em;">Biaya Pengiriman:</td>
                                    <td class="text-end pe-4 py-2 text-black">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr class="bg-subtle border-top border-black">
                                    <td colspan="3" class="text-end py-3 text-uppercase fw-bold text-black pe-3" style="letter-spacing: 0.1em;">Total Pembayaran:</td>
                                    <td class="text-end pe-4 py-3 fw-bold text-black fs-5" style="font-family: 'Marcellus', serif;">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($order->payment)
            <div class="card border rounded-0 shadow-sm mb-5" style="border-color: var(--border-color);">
                <div class="card-header bg-white border-bottom p-4" style="border-color: var(--border-color) !important;">
                    <h5 class="card-title mb-0 fw-normal text-uppercase text-black" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em; font-size: 1rem;">Status Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em; max-width: 400px;">
                                <span class="text-muted">Metode</span>
                                <span class="text-black fw-bold">{{ $order->payment->paymentMethod->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4 small text-uppercase align-items-center" style="letter-spacing: 0.05em; max-width: 400px;">
                                <span class="text-muted">Status</span>
                                <span class="badge rounded-0 fw-normal text-uppercase px-3 py-2 small border" 
                                    style="letter-spacing: 0.05em; font-size: 0.7rem;
                                    @if($order->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                                    @elseif($order->status == 'payment_check') background-color: #ffc107; color: #000; border-color: #ffc107;
                                    @elseif($order->status == 'processing') background-color: #17a2b8; color: #fff; border-color: #17a2b8;
                                    @elseif($order->status == 'shipping') background-color: #007bff; color: #fff; border-color: #007bff;
                                    @elseif($order->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                                    @elseif($order->status == 'cancelled') background-color: #fff; color: #d9534f; border-color: #d9534f;
                                    @else background-color: #fff; color: #000; border-color: #000; @endif">
                                    {{ $statusOptions[$order->status] ?? $order->status }}
                                </span>
                            </div>

                            @if($order->payment->status === 'pending' && $order->payment->paymentMethod->type !== 'transfer')
                                <div class="alert rounded-0 bg-subtle border border-black text-black small mb-3 p-3">
                                    <i class="bi bi-info-circle me-2"></i> Silakan selesaikan pembayaran Anda.
                                </div>
                                <a href="{{ route('payment.pay', $order->payment->payment_number) }}" class="btn btn-primary-custom rounded-0 w-100 py-3 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.1em; max-width: 300px;">
                                    <i class="bi bi-qr-code-scan me-2"></i> Bayar Sekarang
                                </a>
                            @endif

                            @if($order->payment->paymentMethod->type === 'transfer' && ($order->payment->status === 'pending' || $order->payment->status === 'failed'))
                                <form action="{{ route('payment.order.process', $order->id) }}" method="POST" enctype="multipart/form-data" class="mt-3 p-4 bg-subtle border" style="border-color: #eee;">
                                    @csrf
                                    <input type="hidden" name="payment_method_id" value="{{ $order->payment->payment_method_id }}">
                                    <div class="mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Upload Bukti Transfer</label>
                                        <input type="file" name="payment_proof" class="form-control rounded-0 border-0 bg-white" required style="font-size: 0.8rem;">
                                    </div>
                                    <button type="submit" class="btn btn-primary-custom rounded-0 w-100 py-3 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                                        <i class="bi bi-upload me-2"></i> Upload Bukti
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <div class="col-md-5 mt-4 mt-md-0">
                            @if($order->payment_proof)
                                <div class="p-3 border bg-light text-center">
                                    <p class="small text-uppercase text-muted mb-2 fw-bold" style="letter-spacing: 0.1em;">Bukti Pembayaran</p>
                                    <img src="{{ asset('storage/' . $order->payment_proof) }}" 
                                         alt="Bukti Transfer" 
                                         class="img-fluid border mb-2" 
                                         style="max-height: 300px; object-fit: contain;">
                                    <br>
                                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-link btn-sm text-muted text-decoration-none small">
                                        <i class="bi bi-zoom-in"></i> Lihat Ukuran Penuh
                                    </a>
                                </div>
                            @else
                                <div class="p-4 border border-dashed bg-light text-center text-muted">
                                    <i class="bi bi-image fs-1 mb-2 d-block opacity-25"></i>
                                    <small class="d-block">Belum ada bukti pembayaran.</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 pt-4 border-top border-black">
                <a href="{{ url('/pesanan') }}" class="btn btn-link text-decoration-none text-muted text-uppercase small" style="letter-spacing: 0.1em;"><i class="bi bi-arrow-left me-2"></i> Kembali</a>
                <div class="d-flex gap-3">
                    @if($order->canBeCancelled())
                        <form action="{{ route('pesanan.cancel', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger rounded-0 px-4 py-2 text-uppercase fw-bold" 
                                    style="font-size: 0.75rem; letter-spacing: 0.1em;"
                                    onclick="return confirm('Batalkan pesanan ini?')">
                                Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                    <a href="https://wa.me/62898051110211?text=Halo%20Admin,%20saya%20tanya%20pesanan%20{{ $order->order_number }}" target="_blank" class="btn btn-outline-custom rounded-0 px-4 py-2 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.1em;"><i class="bi bi-whatsapp me-2"></i> Hubungi CS</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection