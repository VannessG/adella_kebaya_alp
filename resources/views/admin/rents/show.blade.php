@extends('layouts.app')

@section('title', 'Detail Sewa ' . $rent->rent_number)

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Detail Penyewaan</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">No. Sewa: <span class="text-black fw-bold">{{ $rent->rent_number }}</span></p>
        </div>
        <div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
            <span class="badge rounded-0 border text-uppercase px-3 py-2" 
                  style="font-size: 0.7rem; letter-spacing: 0.1em;
                  @if($rent->status == 'returned') background-color: #000; color: #fff; border-color: #000;
                  @elseif($rent->status == 'active') background-color: #fff; color: #000; border-color: #000;
                  @elseif($rent->status == 'paid') background-color: #000; color: #fff; border-color: #000;
                  @elseif($rent->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                  @elseif($rent->status == 'overdue') background-color: #fff; color: #dc3545; border-color: #dc3545;
                  @elseif($rent->status == 'cancelled') background-color: #eee; color: #999; border-color: #eee;
                  @endif">
                {{ $statusOptions[$rent->status] }}
            </span>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="row g-4 mb-5">
                {{-- INFO PENYEWAAN --}}
                <div class="col-md-6">
                    <div class="card border rounded-0 h-100 bg-white p-4" style="border-color: #E0E0E0;">
                        <h6 class="fw-bold text-black text-uppercase mb-3 small" style="letter-spacing: 0.1em;">Informasi Penyewaan</h6>
                        <div class="small text-muted text-uppercase" style="line-height: 2;">
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2" style="border-color: #f5f5f5 !important;">
                                <span>Cabang</span>
                                <span class="text-black fw-bold">{{ $rent->branch->name }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="d-block mb-1">Alamat Cabang</span>
                                <span class="text-black">{{ $rent->branch->address }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2" style="border-color: #f5f5f5 !important;">
                                <span>Periode</span>
                                <span class="text-black">{{ $rent->start_date->format('d M') }} - {{ $rent->end_date->format('d M Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2" style="border-color: #f5f5f5 !important;">
                                <span>Durasi</span>
                                <span class="text-black">{{ $rent->calculateRentalDays() }} Hari</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Metode</span>
                                <span class="text-black">{{ $rent->shipping_method == 'pickup' ? 'AMBIL DI TEMPAT' : 'ANTAR KE ALAMAT' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INFO PELANGGAN --}}
                <div class="col-md-6">
                    <div class="card border rounded-0 h-100 bg-white p-4" style="border-color: #E0E0E0;">
                        <h6 class="fw-bold text-black text-uppercase mb-3 small" style="letter-spacing: 0.1em;">Informasi Pelanggan</h6>
                        <div class="small text-muted text-uppercase" style="line-height: 2;">
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2" style="border-color: #f5f5f5 !important;">
                                <span>Nama</span>
                                <span class="text-black fw-bold">{{ $rent->customer_name }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2" style="border-color: #f5f5f5 !important;">
                                <span>Telepon</span>
                                <span class="text-black">{{ $rent->customer_phone }}</span>
                            </div>
                            <div>
                                <span class="d-block mb-1">Alamat</span>
                                <span class="text-black">{{ $rent->customer_address }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DETAIL PRODUK --}}
            <div class="card border rounded-0 bg-white p-4 mb-5" style="border-color: #E0E0E0;">
                <h6 class="fw-bold text-black text-uppercase mb-4 small pb-2 border-bottom border-black" style="letter-spacing: 0.1em;">Detail Produk</h6>
                
                @if($rent->products->isEmpty())
                    <p class="text-muted small fst-italic text-center py-4">Tidak ada produk dalam penyewaan ini.</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-subtle">
                                <tr>
                                    <th class="ps-0 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.65rem;">Produk</th>
                                    <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.65rem;">Jumlah</th>
                                    <th class="py-3 text-uppercase small text-muted font-weight-bold text-end" style="letter-spacing: 0.1em; font-size: 0.65rem;">Harga Sewa/Hari</th>
                                    <th class="py-3 text-uppercase small text-muted font-weight-bold text-end" style="letter-spacing: 0.1em; font-size: 0.65rem;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rent->products as $product)
                                <tr style="border-bottom: 1px solid #f0f0f0;">
                                    <td class="ps-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="border p-1 bg-white me-3" style="width: 50px; height: 50px; border-color: #eee !important;">
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                                            </div>
                                            <div>
                                                <div class="fw-bold text-black small text-uppercase" style="letter-spacing: 0.05em;">{{ $product->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-3 small">{{ $product->pivot->quantity }}</td>
                                    <td class="text-end py-3 small">Rp {{ number_format($product->pivot->price_per_day, 0, ',', '.') }}</td>
                                    <td class="text-end py-3 fw-bold text-black small">Rp {{ number_format($product->pivot->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-subtle">
                                <tr>
                                    <td colspan="3" class="text-end py-3 text-muted small text-uppercase">Subtotal Sewa</td>
                                    <td class="text-end py-3 fw-bold text-black small">Rp {{ number_format($rent->total_amount - $rent->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                @if($rent->shipping_cost > 0)
                                <tr>
                                    <td colspan="3" class="text-end py-3 text-muted small text-uppercase">Biaya Pengantaran</td>
                                    <td class="text-end py-3 fw-bold text-black small">Rp {{ number_format($rent->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr class="bg-black text-white">
                                    <td colspan="3" class="text-end py-3 text-uppercase small" style="letter-spacing: 0.1em;">Total Keseluruhan</td>
                                    <td class="text-end py-3 fw-bold fs-6">Rp {{ number_format($rent->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>

            {{-- VERIFIKASI PEMBAYARAN --}}
            @if($rent->payment)
            <div class="card border rounded-0 bg-white p-4 mb-5" style="border-color: #E0E0E0;">
                <h6 class="fw-bold text-black text-uppercase mb-4 small pb-2 border-bottom border-black" style="letter-spacing: 0.1em;">Verifikasi Pembayaran</h6>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="small text-uppercase" style="line-height: 2; letter-spacing: 0.05em;">
                                <div class="mb-2">
                                    <span class="text-muted d-block mb-1">Metode Pembayaran</span>
                                    <span class="fw-bold text-black">{{ $rent->payment->paymentMethod->name }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted d-block mb-1">Status</span>
                                    <span class="badge rounded-0 border px-2 py-1 bg-black text-white border-black">
                                        {{ $rent->payment->status }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <span class="text-muted d-block mb-1">Total Bayar</span>
                                    <span class="fw-bold text-black fs-5">Rp {{ number_format($rent->payment->amount, 0, ',', '.') }}</span>
                                </div>

                                {{-- AKSI ADMIN --}}
                                @if($rent->payment->status === 'processing' && $rent->payment->proof_image)
                                <div class="p-3 bg-subtle border border-black mt-3">
                                    <p class="small fw-bold mb-3 text-black">Tindakan Admin:</p>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('admin.payments.verify', $rent->payment->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-black rounded-0 btn-sm px-4 text-uppercase">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.payments.verify', $rent->payment->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-outline-danger rounded-0 btn-sm px-4 text-uppercase">Reject</button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- BUKTI TRANSFER --}}
                        @if($rent->payment->proof_image)
                        <div class="col-md-6 text-md-end">
                            <p class="small text-muted text-uppercase mb-2" style="letter-spacing: 0.1em;">Bukti Transfer</p>
                            <a href="{{ asset('storage/' . $rent->payment->proof_image) }}" target="_blank" class="d-inline-block border p-1 bg-white" style="border-color: #eee !important;">
                                <img src="{{ asset('storage/' . $rent->payment->proof_image) }}" 
                                     alt="Bukti Pembayaran" 
                                     class="img-fluid d-block" 
                                     style="max-height: 250px;">
                            </a>
                            @if($rent->payment->status === 'failed')
                                <div class="text-danger small mt-2 fw-bold text-uppercase" style="letter-spacing: 0.05em;">Bukti Ditolak - Menunggu Upload Ulang</div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- FOOTER ACTIONS --}}
            <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: #eee !important;">
                <a href="{{ route('admin.rents.index') }}" class="btn btn-link text-decoration-none text-muted text-uppercase p-0 small hover-text-black" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
                
                @if($rent->canBeCancelled() && !$rent->payment)
                    <form action="{{ route('rent.cancel', $rent) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger rounded-0 px-4 py-2 text-uppercase fw-bold small" 
                                style="letter-spacing: 0.1em;"
                                onclick="return confirm('Yakin ingin membatalkan penyewaan ini?')">
                            Batalkan Sewa
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection