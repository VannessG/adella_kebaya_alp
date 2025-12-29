@extends('layouts.app')

@section('title', 'Detail Sewa ' . $rent->rent_number)

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Detail Penyewaan</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">No. Sewa: <span class="text-black fw-bold">{{ $rent->rent_number }}</span></p>
        </div>
        <div class="col-md-4 text-center text-md-end mt-3 mt-md-0">
            <span class="badge rounded-0 px-3 py-2 text-uppercase border fw-normal" 
                  style="letter-spacing: 0.1em; font-size: 0.8rem;
                  @if($rent->status == 'returned') background-color: #000; color: #fff; border-color: #000;
                  @elseif($rent->status == 'active') background-color: #fff; color: #000; border-color: #000;
                  @elseif($rent->status == 'paid') background-color: #000; color: #fff; border-color: #000;
                  @elseif($rent->status == 'pending') background-color: #f8f9fa; color: #6c757d; border-color: #dee2e6;
                  @elseif($rent->status == 'overdue') background-color: #fff; color: #dc3545; border-color: #dc3545;
                  @elseif($rent->status == 'cancelled') background-color: #343a40; color: #fff; border-color: #343a40;
                  @endif">
                {{ $statusOptions[$rent->status] }}
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
                        <h5 class="fw-normal text-uppercase text-black mb-4 border-bottom border-black pb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em; font-size: 1rem;">Informasi Penyewaan</h5>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Cabang</span>
                            <span class="text-black fw-bold">{{ $rent->branch->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Alamat Cabang</span>
                            <span class="text-black text-end" style="max-width: 60%;">{{ $rent->branch->address }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Periode Sewa</span>
                            <span class="text-black">{{ $rent->start_date->format('d M Y') }} - {{ $rent->end_date->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Lama Sewa</span>
                            <span class="text-black">{{ $rent->total_days }} Hari</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Metode</span>
                            <span class="text-black">{{ $rent->delivery_type == 'pickup' ? 'Ambil di Tempat' : 'Antar ke Alamat' }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border rounded-0 bg-white p-4" style="border-color: var(--border-color);">
                        <h5 class="fw-normal text-uppercase text-black mb-4 border-bottom border-black pb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em; font-size: 1rem;">Informasi Pelanggan</h5>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Nama</span>
                            <span class="text-black fw-bold">{{ $rent->customer_name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Telepon</span>
                            <span class="text-black">{{ $rent->customer_phone }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Alamat</span>
                            <span class="text-black text-end" style="max-width: 60%;">{{ $rent->customer_address }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border rounded-0 shadow-sm mb-5" style="border-color: var(--border-color);">
                <div class="card-header bg-white border-bottom p-4" style="border-color: var(--border-color) !important;">
                    <h5 class="card-title mb-0 fw-normal text-uppercase text-black" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em; font-size: 1rem;">Detail Produk</h5>
                </div>
                <div class="card-body p-0">
                    @if($rent->products->isEmpty())
                        <div class="p-4 text-center">
                            <p class="text-muted mb-0 small text-uppercase" style="letter-spacing: 0.1em;">Tidak ada produk dalam penyewaan ini.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-subtle border-bottom border-black">
                                    <tr>
                                        <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Produk</th>
                                        <th class="text-center py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Jumlah</th>
                                        <th class="text-end py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Harga Sewa/Hari</th>
                                        <th class="text-end pe-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rent->products as $product)
                                    <tr style="border-bottom: 1px solid #F0F0F0;">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="border p-1 me-3 bg-white" style="border-color: #eee !important;">
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-black text-uppercase small" style="letter-spacing: 0.05em;">{{ $product->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center py-3 text-black">{{ $product->pivot->quantity }}</td>
                                        <td class="text-end py-3 text-muted small">Rp {{ number_format($product->pivot->price_per_day, 0, ',', '.') }}</td>
                                        <td class="text-end pe-4 py-3 fw-bold text-black">Rp {{ number_format($product->pivot->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="text-end py-3 text-uppercase small text-muted pe-3" style="letter-spacing: 0.1em;">Subtotal Produk:</td>
                                        <td class="text-end pe-4 py-3 fw-bold text-black">Rp {{ number_format($rent->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($rent->discount_amount > 0)
                                    <tr>
                                        <td colspan="3" class="text-end py-2 text-uppercase small text-danger pe-3" style="letter-spacing: 0.1em;">Potongan Diskon:</td>
                                        <td class="text-end pe-4 py-2 text-danger">- Rp {{ number_format($rent->discount_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    @if($rent->shipping_cost > 0)
                                    <tr>
                                        <td colspan="3" class="text-end py-2 text-uppercase small text-muted pe-3" style="letter-spacing: 0.1em;">Biaya Pengantaran:</td>
                                        <td class="text-end pe-4 py-2 text-black">Rp {{ number_format($rent->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr class="bg-subtle border-top border-black">
                                        <td colspan="3" class="text-end py-3 text-uppercase fw-bold text-black pe-3" style="letter-spacing: 0.1em;">Total Pembayaran:</td>
                                        <td class="text-end pe-4 py-3 fw-bold text-black fs-5" style="font-family: 'Marcellus', serif;">Rp {{ number_format($rent->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            @if($rent->payment)
            <div class="card border rounded-0 shadow-sm mb-5" style="border-color: var(--border-color);">
                <div class="card-header bg-white border-bottom p-4" style="border-color: var(--border-color) !important;">
                    <h5 class="card-title mb-0 fw-normal text-uppercase text-black" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em; font-size: 1rem;">Informasi Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em; max-width: 400px;">
                                <span class="text-muted">Metode</span>
                                <span class="text-black fw-bold">{{ $rent->payment->paymentMethod->name }} ({{ strtoupper($rent->payment->paymentMethod->type) }})</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4 small text-uppercase align-items-center" style="letter-spacing: 0.05em; max-width: 400px;">
                                <span class="text-muted">Status</span>
                                <span class="badge rounded-0 px-3 py-1 text-uppercase border fw-normal" style="letter-spacing: 0.05em; font-size: 0.8rem; background-color: #000000; color: #ffffff; border-color: #000;">{{ $rent->payment->status }}</span>
                            </div>

                            @if($rent->payment->status === 'pending' && $rent->payment->paymentMethod->type !== 'transfer')
                                <div class="alert rounded-0 bg-subtle border border-black text-black small mb-3 p-3">
                                    <i class="bi bi-info-circle me-2"></i> Selesaikan pembayaran untuk memproses penyewaan.
                                </div>
                                <a href="{{ route('payment.pay', $rent->payment->payment_number) }}" class="btn btn-primary-custom rounded-0 w-100 py-3 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.1em; max-width: 300px;">
                                    <i class="bi bi-qr-code-scan me-2"></i> Bayar Sekarang
                                </a>
                            @endif

                            @if($rent->payment->paymentMethod->type === 'transfer' && ($rent->payment->status === 'pending' || $rent->payment->status === 'failed'))
                                <form action="{{ route('payment.rent.process', $rent->id) }}" method="POST" enctype="multipart/form-data" class="mt-3 p-4 bg-subtle border" style="border-color: #eee;">
                                    @csrf
                                    <input type="hidden" name="payment_method_id" value="{{ $rent->payment->payment_method_id }}">
                                    <div class="mb-3">
                                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Upload Bukti Transfer Baru</label>
                                        <input type="file" name="payment_proof" class="form-control rounded-0 border-0 bg-white" required style="font-size: 0.8rem;">
                                    </div>
                                    <button type="submit" class="btn btn-primary-custom rounded-0 w-100 py-3 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                                        <i class="bi bi-upload me-2"></i> Kirim Bukti
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if($rent->payment->proof_image)
                        <div class="col-md-5 text-center mt-4 mt-md-0 border-start ps-md-5" style="border-color: #F0F0F0 !important;">
                            <p class="small text-muted text-uppercase mb-3" style="letter-spacing: 0.1em;">Bukti Terkirim</p>
                            <img src="{{ asset('storage/' . $rent->payment->proof_image) }}" class="img-fluid border p-1 bg-white shadow-sm" style="max-height: 250px; object-fit: contain;">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 pt-4 border-top border-black">
                <a href="{{ route('rent.index') }}" class="btn btn-link text-decoration-none text-muted text-uppercase small" style="letter-spacing: 0.1em;"><i class="bi bi-arrow-left me-2"></i> Kembali</a>
                <div class="d-flex gap-3">
                    @if($rent->canBeCancelled())
                        <form action="{{ route('rent.cancel', $rent) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger rounded-0 px-4 py-2 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.1em;"onclick="return confirm('Yakin ingin membatalkan penyewaan ini?')">Batalkan Sewa</button>
                        </form>
                    @endif
                    
                    <a href="https://wa.me/62898051110211?text=Halo%20Admin%20Adella%20Kebaya,%20saya%20ingin%20bertanya%20tentang%20penyewaan%20{{ $rent->rent_number }}" target="_blank" class="btn btn-outline-custom rounded-0 px-4 py-2 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.1em;"><i class="bi bi-whatsapp me-2"></i> Hubungi CS</a>

                    @if(!$rent->payment && $rent->status == 'pending')
                        <a href="#payment-section" class="btn btn-primary-custom rounded-0 px-4 py-2 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                            <i class="bi bi-credit-card me-2"></i> Pilih Metode & Bayar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection