@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Dashboard</h1>
        </div>
        <div class="col-md-4 text-center text-md-end mt-3 mt-md-0">
            <div class="d-inline-flex align-items-center border border-black px-4 py-2 bg-white">
                <i class="bi bi-geo-alt text-black me-2"></i>
                <span class="fw-bold text-uppercase small" style="letter-spacing: 0.1em;">{{ $branch->name ?? 'All Branches' }}</span>
            </div>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <a href="{{ route('admin.categories.index') }}" class="text-decoration-none h-100">
                <div class="card border rounded-0 h-100 bg-white p-4 transition-all" style="border-color: #E0E0E0;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="small text-uppercase text-muted mb-1" style="letter-spacing: 0.1em;">Total Produk</p>
                            <h2 class="display-4 fw-normal text-black mb-0" style="font-family: 'Marcellus', serif;">{{ $stats['total_products'] }}</h2>
                        </div>
                        <i class="bi bi-box-seam fs-3 text-muted"></i>
                    </div>
                    <div class="mt-4 pt-3 border-top small text-muted text-uppercase" style="border-color: #eee !important; font-size: 0.8rem; letter-spacing: 0.05em;">
                        Dalam {{ $stats['total_categories'] }} Kategori</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <div class="card border rounded-0 h-100 bg-white p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small text-uppercase opacity-75 mb-1" style="letter-spacing: 0.1em;">Total Penjualan</p>
                        <h2 class="display-4 fw-normal mb-0" style="font-family: 'Marcellus', serif;">{{ $stats['total_orders'] }}</h2>
                    </div>
                    <i class="bi bi-bag-check fs-3 opacity-50"></i>
                </div>
                <div class="mt-4 pt-3 border-top border-white border-opacity-25 small opacity-75 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.05em;">
                    <i class="bi bi-clock-history me-1"></i> {{ $stats['pending_orders'] }} Menunggu Pembayaran
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border rounded-0 h-100 bg-white p-4" style="border-color: #E0E0E0;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small text-uppercase text-muted mb-1" style="letter-spacing: 0.1em;">Total Penyewaan</p>
                        <h2 class="display-4 fw-normal text-black mb-0" style="font-family: 'Marcellus', serif;">{{ $stats['total_rents'] }}</h2>
                    </div>
                    <i class="bi bi-calendar-check fs-3 text-muted"></i>
                </div>
                <div class="mt-4 pt-3 border-top small text-muted text-uppercase" style="border-color: #eee !important; font-size: 0.8rem; letter-spacing: 0.05em;">
                    <i class="bi bi-clock-history me-1"></i> {{ $stats['pending_rents'] }} Menunggu Pembayaran
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border rounded-0 h-100 bg-white p-4" style="border-color: #E0E0E0;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small text-uppercase text-muted mb-1" style="letter-spacing: 0.1em;">Pelanggan</p>
                        <h2 class="display-4 fw-normal text-black mb-0" style="font-family: 'Marcellus', serif;">{{ $stats['total_users'] }}</h2>
                    </div>
                    <i class="bi bi-people fs-3 text-muted"></i>
                </div>
                <div class="mt-4 pt-3 border-top small text-muted text-uppercase" style="border-color: #eee !important; font-size: 0.8rem; letter-spacing: 0.05em;">
                    User Terdaftar
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5">
        <div class="col-lg-7">
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-end mb-3 border-bottom border-black pb-2">
                    <h5 class="fw-normal text-uppercase text-black mb-0" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Penjualan Terbaru</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-link text-decoration-none text-muted text-uppercase p-0 small hover-text-black" style="font-size: 0.7rem; letter-spacing: 0.1em;">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-subtle">
                            <tr>
                                <th class="ps-2 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">ID Penjualan</th>
                                <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.9rem;">Pelanggan</th>
                                <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.9rem;">Status</th>
                                <th class="text-end pe-2 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders->take(4) as $order)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="ps-2 py-3">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none text-black fw-bold" style="font-family: 'Jost', sans-serif;">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="py-3 small text-uppercase text-center">{{ $order->customer_name }}</td>
                                <td class="py-3 text-center">
                                    <span class="badge rounded-0 fw-normal text-uppercase px-2 py-1 border" 
                                          style="font-size: 0.7rem; letter-spacing: 0.05em;
                                          @if($order->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                                          @elseif($order->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                                          @else background-color: #f8f9fa; color: #000; border-color: #ddd; @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="text-end pe-2 py-3 fw-bold small">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted small text-uppercase">Belum ada pesanan terbaru</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <div class="d-flex justify-content-between align-items-end mb-3 border-bottom border-black pb-2">
                    <h5 class="fw-normal text-uppercase text-black mb-0" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Penyewaan Terbaru</h5>
                    <a href="{{ route('admin.rents.index') }}" class="btn btn-link text-decoration-none text-muted text-uppercase p-0 small hover-text-black" style="font-size: 0.7rem; letter-spacing: 0.1em;">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-subtle">
                            <tr>
                                <th class="ps-2 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">ID Penyewaan</th>
                                <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.9rem;">Pelanggan</th>
                                <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.9rem;">Status</th>
                                <th class="text-end pe-2 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRents->take(4) as $rent)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="ps-2 py-3">
                                    <a href="{{ route('admin.rents.show', $rent) }}" class="text-decoration-none text-black fw-bold" style="font-family: 'Jost', sans-serif;">
                                        {{ $rent->rent_number }}
                                    </a>
                                </td>
                                <td class="py-3 small text-uppercase text-center">{{ $rent->customer_name }}</td>
                                <td class="py-3 text-center">
                                    <span class="badge rounded-0 fw-normal text-uppercase px-2 py-1 border" 
                                          style="font-size: 0.7rem; letter-spacing: 0.05em;
                                          @if($rent->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                                          @elseif($rent->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                                          @else background-color: #f8f9fa; color: #000; border-color: #ddd; @endif">
                                        {{ $rent->status }}
                                    </span>
                                </td>
                                <td class="text-end pe-2 py-3 fw-bold small">Rp {{ number_format($rent->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted small text-uppercase">Belum ada penyewaan terbaru</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5 ps-lg-5 border-start" style="border-color: #f0f0f0 !important;">
            <h5 class="fw-normal text-uppercase text-black mb-4 pb-2 border-bottom border-black" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Aksi Cepat</h5>
            
            <div class="row g-4">
                <div class="col-6">
                    <a href="{{ route('admin.products.index') }}" class="card bg-subtle border-0 rounded-0 h-100 text-decoration-none hover-invert transition-all p-4 text-center">
                        <i class="bi bi-bag-plus fs-3 mb-3 d-block text-black icon-transition"></i>
                        <h6 class="fw-bold text-black text-uppercase mb-1 small text-transition" style="letter-spacing: 0.05em;">Produk</h6>
                        <small class="text-muted small text-transition" style="font-size: 0.65rem;">Kelola Katalog Produk</small>
                    </a>
                </div>

                <div class="col-6">
                    <a href="{{ route('admin.categories.index') }}" class="card bg-subtle border-0 rounded-0 h-100 text-decoration-none hover-invert transition-all p-4 text-center">
                        <i class="bi bi-collection fs-3 mb-3 d-block text-black icon-transition"></i>
                        <h6 class="fw-bold text-black text-uppercase mb-1 small text-transition" style="letter-spacing: 0.05em;">Kategori</h6>
                        <small class="text-muted small text-transition" style="font-size: 0.65rem;">Kelola Kategori Produk</small>
                    </a>
                </div>

                <div class="col-6">
                    <a href="{{ route('admin.discounts.index') }}" class="card bg-subtle border-0 rounded-0 h-100 text-decoration-none hover-invert transition-all p-4 text-center">
                        <i class="bi bi-tag fs-3 mb-3 d-block text-black icon-transition"></i>
                        <h6 class="fw-bold text-black text-uppercase mb-1 small text-transition" style="letter-spacing: 0.05em;">Diskon</h6>
                        <small class="text-muted small text-transition" style="font-size: 0.65rem;">Kelola Promo</small>
                    </a>
                </div>

                <div class="col-6">
                    <a href="{{ route('admin.payments.index') }}" class="card bg-subtle border-0 rounded-0 h-100 text-decoration-none hover-invert transition-all p-4 text-center">
                        <i class="bi bi-wallet2 fs-3 mb-3 d-block text-black icon-transition"></i>
                        <h6 class="fw-bold text-black text-uppercase mb-1 small text-transition" style="letter-spacing: 0.05em;">Pembayaran</h6>
                        <small class="text-muted small text-transition" style="font-size: 0.65rem;">Kelola Pembayaran Pelanggan</small>
                    </a>
                </div>

                <div class="col-6">
                    <a href="{{ route('admin.orders.index') }}" class="card bg-subtle border-0 rounded-0 h-100 text-decoration-none hover-invert transition-all p-4 text-center">
                        <i class="bi bi-cart fs-3 mb-3 d-block text-black icon-transition"></i>
                        <h6 class="fw-bold text-black text-uppercase mb-1 small text-transition" style="letter-spacing: 0.05em;">Penjualan</h6>
                        <small class="text-muted small text-transition" style="font-size: 0.65rem;">Daftar Penjualan</small>
                    </a>
                </div>

                <div class="col-6">
                    <a href="{{ route('admin.rents.index') }}" class="card bg-subtle border-0 rounded-0 h-100 text-decoration-none hover-invert transition-all p-4 text-center">
                        <i class="bi bi-hourglass-split fs-3 mb-3 d-block text-black icon-transition"></i>
                        <h6 class="fw-bold text-black text-uppercase mb-1 small text-transition" style="letter-spacing: 0.05em;">Penyewaan</h6>
                        <small class="text-muted small text-transition" style="font-size: 0.65rem;">Daftar Penyewaan</small>
                    </a>
                </div>

                <div class="col-6">
                    <a href="{{ route('admin.shifts.index') }}" class="card bg-subtle border-0 rounded-0 h-100 text-decoration-none hover-invert transition-all p-4 text-center">
                        <i class="bi bi-clock fs-3 mb-3 d-block text-black icon-transition"></i>
                        <h6 class="fw-bold text-black text-uppercase mb-1 small text-transition" style="letter-spacing: 0.05em;">Kehadiran</h6>
                        <small class="text-muted small text-transition" style="font-size: 0.65rem;">Daftar Kehadiran Pegawai</small>
                    </a>
                </div>

                <div class="col-6">
                    <a href="{{ route('admin.reports.index') }}" class="card bg-subtle border-0 rounded-0 h-100 text-decoration-none hover-invert transition-all p-4 text-center">
                        <i class="bi bi-file-earmark-bar-graph fs-3 mb-3 d-block text-black icon-transition"></i>
                        <h6 class="fw-bold text-black text-uppercase mb-1 small text-transition" style="letter-spacing: 0.05em;">Laporan</h6>
                        <small class="text-muted small text-transition" style="font-size: 0.65rem;">Rekapan Laporan Keuangan</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection