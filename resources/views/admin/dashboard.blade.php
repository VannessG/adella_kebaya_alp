@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="font-family: 'Playfair Display', serif;">Dashboard Overview</h2>
        <p class="text-muted">Selamat datang kembali, Admin! Berikut ringkasan hari ini.</p>
    </div>
    <div class="d-flex align-items-center bg-white shadow-sm px-4 py-2 rounded-pill">
        <i class="bi bi-shop text-primary me-2 fs-5"></i>
        <span class="fw-semibold">{{ $branch->name ?? 'All Branches' }}</span>
    </div>
</div>

{{-- Row Statistik Utama --}}
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-primary text-white" style="background: linear-gradient(135deg, var(--primary-color), #5a3207);">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 opacity-75">Total Pesanan</p>
                        <h2 class="fw-bold mb-0">{{ $stats['total_orders'] }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-bag-check fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 small opacity-75">
                    <i class="bi bi-clock-history me-1"></i> {{ $stats['pending_orders'] }} Menunggu Pembayaran
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        {{-- Statistik Kategori Terhubung ke Index Kategori --}}
        <a href="{{ route('admin.categories.index') }}" class="text-decoration-none h-100">
            <div class="card border-0 shadow-sm h-100 bg-white hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0">Total Produk</p>
                            <h2 class="fw-bold mb-0 text-dark">{{ $stats['total_products'] }}</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 d-flex align-items-center justify-content-center text-warning" style="width: 50px; height: 50px;">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-muted">
                        Dalam <span class="fw-bold text-primary">{{ $stats['total_categories'] }} Kategori</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Penyewaan Aktif</p>
                        <h2 class="fw-bold mb-0 text-dark">{{ $stats['total_rents'] }}</h2>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 d-flex align-items-center justify-content-center text-success" style="width: 50px; height: 50px;">
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 small text-muted">
                    Transaksi Sewa Tercatat
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Pelanggan</p>
                        <h2 class="fw-bold mb-0 text-dark">{{ $stats['total_users'] }}</h2>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-circle p-3 d-flex align-items-center justify-content-center text-info" style="width: 50px; height: 50px;">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 small text-muted">
                    User Terdaftar
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Tabel Pesanan Terbaru --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Pesanan Terbaru</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-light text-muted">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Order ID</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td class="ps-4 fw-semibold">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none text-dark">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $order->customer_name }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }} bg-opacity-10 text-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }} px-3">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="text-end pe-4 fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada pesanan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid Navigasi Cepat (Quick Actions) --}}
    <div class="col-lg-6">
        <div class="row g-3">
            <div class="col-6">
                <a href="{{ route('admin.products.create') }}" class="card bg-white border-0 shadow-sm h-100 text-decoration-none hover-card">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="bg-primary text-white rounded-3 p-3 me-3">
                            <i class="bi bi-plus-lg fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Tambah Produk</h6>
                            <small class="text-muted">Update katalog baru</small>
                        </div>
                    </div>
                </a>
            </div>

            {{-- PERBAIKAN: Tombol CRUD Kategori mengarah ke admin.categories.index --}}
            <div class="col-6">
                <a href="{{ route('admin.categories.index') }}" class="card bg-white border-0 shadow-sm h-100 text-decoration-none hover-card">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="bg-info text-white rounded-3 p-3 me-3">
                            <i class="bi bi-tags fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Manajemen Kategori</h6>
                            <small class="text-muted">Tambah/Edit Kategori</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6">
                <a href="{{ route('admin.orders.index') }}" class="card bg-white border-0 shadow-sm h-100 text-decoration-none hover-card">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="bg-success text-white rounded-3 p-3 me-3">
                            <i class="bi bi-check2-square fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Verifikasi Order</h6>
                            <small class="text-muted">Cek pesanan masuk</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6">
                <a href="{{ route('admin.payments.index') }}" class="card bg-white border-0 shadow-sm h-100 text-decoration-none hover-card">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="bg-danger text-white rounded-3 p-3 me-3">
                            <i class="bi bi-credit-card fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Pembayaran</h6>
                            <small class="text-muted">Cek Bukti Transfer</small>
                        </div>
                    </div>
                </a>
            </div>

            @if($stats['active_discount'])
            <div class="col-12">
                <div class="card bg-warning bg-opacity-10 border-0 border-start border-warning border-4 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-warning mb-1">Promo Aktif: {{ $stats['active_discount']->name }}</h6>
                                <p class="mb-0 small text-muted">
                                    Berlaku hingga {{ $stats['active_discount']->end_date->format('d M Y') }}
                                </p>
                            </div>
                            <a href="{{ route('admin.discounts.index') }}" class="btn btn-sm btn-warning text-white rounded-pill px-3">Kelola</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection