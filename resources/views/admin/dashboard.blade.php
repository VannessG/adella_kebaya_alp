@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text mb-0">Dashboard Admin</h1>
        <div class="d-flex align-items-center">
            <span class="me-3">
                <i class="bi bi-shop me-1"></i> <strong>{{ $branch->city ?? 'All Branches' }}</strong>
            </span>
            @if($stats['active_discount'])
                <span class="badge bg-warning text-dark">
                    <i class="bi bi-percent"></i> Diskon Aktif: {{ $stats['active_discount']->name }}
                </span>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm dashboard-stat-card">
                <div class="card-body text-center py-4">
                    <i class="bi bi-box-seam display-6 text"></i>
                    <h3 class="mt-3 mb-1">{{ $stats['total_products'] }}</h3>
                    <p class="text-muted mb-0 small">Total Produk</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm dashboard-stat-card">
                <div class="card-body text-center py-4">
                    <i class="bi bi-tags display-6 text"></i>
                    <h3 class="mt-3 mb-1">{{ $stats['total_categories'] }}</h3>
                    <p class="text-muted mb-0 small">Kategori</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm dashboard-stat-card">
                <div class="card-body text-center py-4">
                    <i class="bi bi-receipt display-6 text"></i>
                    <h3 class="mt-3 mb-1">{{ $stats['total_orders'] }}</h3>
                    <p class="text-muted mb-0 small">Pesanan</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm dashboard-stat-card">
                <div class="card-body text-center py-4">
                    <i class="bi bi-cart-check display-6 text"></i>
                    <h3 class="mt-3 mb-1">{{ $stats['total_rents'] }}</h3>
                    <p class="text-muted mb-0 small">Sewa</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm dashboard-stat-card">
                <div class="card-body text-center py-4">
                    <i class="bi bi-people display-6 text"></i>
                    <h3 class="mt-3 mb-1">{{ $stats['total_users'] }}</h3>
                    <p class="text-muted mb-0 small">User</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm dashboard-stat-card">
                <div class="card-body text-center py-4">
                    <i class="bi bi-clock-history display-6 text"></i>
                    <h3 class="mt-3 mb-1">{{ $stats['pending_orders'] }}</h3>
                    <p class="text-muted mb-0 small">Pending</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0 fw-semibold d-flex align-items-center">
                        <i class="bi bi-box-seam me-2"></i> Manajemen Produk
                    </h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="text-muted mb-3 small">Kelola produk, kategori, dan stok.</p>
                    <div class="d-grid gap-2 mt-auto">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm">
                            <i class="bi bi-list"></i> Kelola Produk
                        </a>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0 fw-semibold d-flex align-items-center">
                        <i class="bi bi-receipt me-2"></i> Manajemen Pesanan
                    </h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="text-muted mb-3 small">Kelola pesanan dan status pengiriman.</p>
                    <div class="d-grid gap-2 mt-auto">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm">
                            <i class="bi bi-list-check"></i> Kelola Pesanan
                        </a>
                        <a href="{{ route('admin.orders.create') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0 fw-semibold d-flex align-items-center">
                        <i class="bi bi-cart-check me-2"></i> Manajemen Sewa
                    </h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="text-muted mb-3 small">Kelola penyewaan dan status pengembalian.</p>
                    <div class="d-grid gap-2 mt-auto">
                        <a href="{{ route('admin.rents.index') }}" class="btn btn-sm">
                            <i class="bi bi-list-ul"></i> Kelola Sewa
                        </a>
                        <a href="{{ route('rent.create') }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Sewa
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0 fw-semibold d-flex align-items-center">
                        <i class="bi bi-percent me-2"></i> Manajemen Diskon
                    </h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="text-muted mb-3 small">Kelola diskon dan promo periode.</p>
                    <div class="d-grid gap-2 mt-auto">
                        <a href="{{ route('admin.discounts.index') }}" class="btn btn-sm">
                            <i class="bi bi-tag"></i> Kelola Diskon
                        </a>
                        <a href="{{ route('admin.discounts.create') }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Diskon
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0 fw-semibold d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-receipt me-2"></i> Pesanan Terbaru</span>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentOrders->isEmpty())
                        <p class="text-muted text-center py-3">Belum ada pesanan</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Pesanan</th>
                                        <th>Pelanggan</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                                    <small class="fw-semibold">{{ $order->order_number }}</small>
                                                </a>
                                            </td>
                                            <td>
                                                <small>{{ $order->customer_name }}</small>
                                            </td>
                                            <td>
                                                <small class="fw-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'pending' => 'secondary',
                                                        'processing' => 'warning',
                                                        'shipping' => 'info',
                                                        'completed' => 'success',
                                                        'cancelled' => 'danger'
                                                    ][$order->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ \App\Models\Order::getStatusOptions()[$order->status] ?? $order->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Rents -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0 fw-semibold d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-cart-check me-2"></i> Sewa Terbaru</span>
                        <a href="{{ route('admin.rents.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentRents->isEmpty())
                        <p class="text-muted text-center py-3">Belum ada penyewaan</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Sewa</th>
                                        <th>Pelanggan</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRents as $rent)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.rents.show', $rent) }}" class="text-decoration-none">
                                                    <small class="fw-semibold">{{ $rent->rent_number }}</small>
                                                </a>
                                            </td>
                                            <td>
                                                <small>{{ $rent->customer_name }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $rent->start_date->format('d/m') }} - {{ $rent->end_date->format('d/m') }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'pending' => 'secondary',
                                                        'confirmed' => 'info',
                                                        'active' => 'warning',
                                                        'completed' => 'success',
                                                        'cancelled' => 'danger'
                                                    ][$rent->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ \App\Models\Rent::getStatusOptions()[$rent->status] ?? $rent->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Management Cards -->
    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0 fw-semibold d-flex align-items-center">
                        <i class="bi bi-truck me-2"></i> Manajemen Pengiriman
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3 small">Kelola pengiriman dan tracking order.</p>
                    <a href="{{ route('admin.shipments.index') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-box-arrow-up-right"></i> Kelola Pengiriman
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0 fw-semibold d-flex align-items-center">
                        <i class="bi bi-credit-card me-2"></i> Manajemen Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3 small">Verifikasi dan kelola pembayaran.</p>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-success btn-sm w-100">
                        <i class="bi bi-cash-stack"></i> Kelola Pembayaran
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0 fw-semibold d-flex align-items-center">
                        <i class="bi bi-star me-2"></i> Manajemen Review
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3 small">Moderasi review dari pelanggan.</p>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-warning btn-sm w-100">
                        <i class="bi bi-chat-left-text"></i> Kelola Review
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Diskon Aktif -->
    @if($stats['active_discount'])
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-warning shadow-sm">
                <div class="card-header bg-warning bg-opacity-25">
                    <h5 class="card-title mb-0 fw-semibold d-flex align-items-center">
                        <i class="bi bi-percent me-2"></i> Diskon Aktif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="text-warning">{{ $stats['active_discount']->name }}</h4>
                            <p class="mb-2">
                                <strong>
                                    @if($stats['active_discount']->type === 'percentage')
                                        {{ $stats['active_discount']->amount }}% OFF
                                    @else
                                        Rp {{ number_format($stats['active_discount']->amount, 0, ',', '.') }} OFF
                                    @endif
                                </strong>
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $stats['active_discount']->start_date->format('d M Y') }} - 
                                {{ $stats['active_discount']->end_date->format('d M Y') }}
                                • Digunakan: {{ $stats['active_discount']->used_count }}x
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.discounts.edit', $stats['active_discount']) }}" 
                               class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit Diskon
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.dashboard-stat-card {
    transition: transform 0.3s;
    height: 100%;
}
.dashboard-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(113, 63, 9, 0.15) !important;
}
.dashboard-stat-card .card-body {
    padding: 1.5rem 1rem;
}
.dashboard-stat-card i {
    color: #713f09;
}
</style>
@endsection