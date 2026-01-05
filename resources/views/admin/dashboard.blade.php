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

    <div class="mb-5">
        <h5 class="fw-normal text-uppercase text-black mb-3 small opacity-75" style="letter-spacing: 0.1em;">Aksi Cepat</h5>
        <div class="row g-2 g-md-3">
            
            <div class="col-3 col-lg">
                <a href="{{ route('admin.products.index') }}" class="card bg-white border rounded-0 h-100 text-decoration-none hover-black transition-all p-2 text-center" style="border-color: #e0e0e0;">
                    <i class="bi bi-bag-plus fs-4 mb-1 d-block text-black"></i>
                    <span class="fw-bold text-black text-uppercase d-block text-nowrap" style="font-size: 0.55rem; letter-spacing: 0.05em;">Produk</span>
                </a>
            </div>
            
            <div class="col-3 col-lg">
                <a href="{{ route('admin.categories.index') }}" class="card bg-white border rounded-0 h-100 text-decoration-none hover-black transition-all p-2 text-center" style="border-color: #e0e0e0;">
                    <i class="bi bi-collection fs-4 mb-1 d-block text-black"></i>
                    <span class="fw-bold text-black text-uppercase d-block text-nowrap" style="font-size: 0.55rem; letter-spacing: 0.05em;">Kategori</span>
                </a>
            </div>

            <div class="col-3 col-lg">
                <a href="{{ route('admin.discounts.index') }}" class="card bg-white border rounded-0 h-100 text-decoration-none hover-black transition-all p-2 text-center" style="border-color: #e0e0e0;">
                    <i class="bi bi-tag fs-4 mb-1 d-block text-black"></i>
                    <span class="fw-bold text-black text-uppercase d-block text-nowrap" style="font-size: 0.55rem; letter-spacing: 0.05em;">Diskon</span>
                </a>
            </div>

            <div class="col-3 col-lg">
                <a href="{{ route('admin.payments.index') }}" class="card bg-white border rounded-0 h-100 text-decoration-none hover-black transition-all p-2 text-center" style="border-color: #e0e0e0;">
                    <i class="bi bi-wallet2 fs-4 mb-1 d-block text-black"></i>
                    <span class="fw-bold text-black text-uppercase d-block text-nowrap" style="font-size: 0.55rem; letter-spacing: 0.05em;">Bayar</span>
                </a>
            </div>

            <div class="col-3 col-lg">
                <a href="{{ route('admin.orders.index') }}" class="card bg-white border rounded-0 h-100 text-decoration-none hover-black transition-all p-2 text-center" style="border-color: #e0e0e0;">
                    <i class="bi bi-cart fs-4 mb-1 d-block text-black"></i>
                    <span class="fw-bold text-black text-uppercase d-block text-nowrap" style="font-size: 0.55rem; letter-spacing: 0.05em;">Jual</span>
                </a>
            </div>

            <div class="col-3 col-lg">
                <a href="{{ route('admin.rents.index') }}" class="card bg-white border rounded-0 h-100 text-decoration-none hover-black transition-all p-2 text-center" style="border-color: #e0e0e0;">
                    <i class="bi bi-hourglass-split fs-4 mb-1 d-block text-black"></i>
                    <span class="fw-bold text-black text-uppercase d-block text-nowrap" style="font-size: 0.55rem; letter-spacing: 0.05em;">Sewa</span>
                </a>
            </div>

            <div class="col-3 col-lg">
                <a href="{{ route('admin.shifts.index') }}" class="card bg-white border rounded-0 h-100 text-decoration-none hover-black transition-all p-2 text-center" style="border-color: #e0e0e0;">
                    <i class="bi bi-clock fs-4 mb-1 d-block text-black"></i>
                    <span class="fw-bold text-black text-uppercase d-block text-nowrap" style="font-size: 0.55rem; letter-spacing: 0.05em;">Absen</span>
                </a>
            </div>

            <div class="col-3 col-lg">
                <a href="{{ route('admin.reports.index') }}" class="card bg-white border rounded-0 h-100 text-decoration-none hover-black transition-all p-2 text-center" style="border-color: #e0e0e0;">
                    <i class="bi bi-file-earmark-bar-graph fs-4 mb-1 d-block text-black"></i>
                    <span class="fw-bold text-black text-uppercase d-block text-nowrap" style="font-size: 0.55rem; letter-spacing: 0.05em;">Laporan</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 g-md-4 mb-5">

        <div class="col-6 col-md-3">
            <a href="{{ route('admin.categories.index') }}" class="text-decoration-none h-100">
                <div class="card border rounded-0 h-100 bg-white p-3 p-md-4 transition-all" style="border-color: #E0E0E0;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="small text-uppercase text-muted mb-1" style="font-size: 0.7rem; letter-spacing: 0.1em;">Produk</p>
                            <h2 class="display-6 fw-normal text-black mb-0" style="font-family: 'Marcellus', serif;">{{ $stats['total_products'] }}</h2>
                        </div>
                        <i class="bi bi-box-seam fs-4 text-muted"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border rounded-0 h-100 bg-white p-3 p-md-4" style="border-color: #E0E0E0;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small text-uppercase text-muted mb-1" style="font-size: 0.7rem; letter-spacing: 0.1em;">Jual</p>
                        <h2 class="display-6 fw-normal mb-0" style="font-family: 'Marcellus', serif;">{{ $stats['total_orders'] }}</h2>
                    </div>
                    <i class="bi bi-bag-check fs-4 text-muted"></i>
                </div>
                <div class="mt-2 pt-2 border-top border-light-subtle text-muted" style="font-size: 0.65rem;">
                    <i class="bi bi-clock me-1"></i> {{ $stats['pending_orders'] }} Pending
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border rounded-0 h-100 bg-white p-3 p-md-4" style="border-color: #E0E0E0;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small text-uppercase text-muted mb-1" style="font-size: 0.7rem; letter-spacing: 0.1em;">Sewa</p>
                        <h2 class="display-6 fw-normal text-black mb-0" style="font-family: 'Marcellus', serif;">{{ $stats['total_rents'] }}</h2>
                    </div>
                    <i class="bi bi-calendar-check fs-4 text-muted"></i>
                </div>
                <div class="mt-2 pt-2 border-top border-light-subtle text-muted" style="font-size: 0.65rem;">
                    <i class="bi bi-clock me-1"></i> {{ $stats['pending_rents'] }} Pending
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border rounded-0 h-100 bg-white p-3 p-md-4" style="border-color: #E0E0E0;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small text-uppercase text-muted mb-1" style="font-size: 0.7rem; letter-spacing: 0.1em;">User</p>
                        <h2 class="display-6 fw-normal text-black mb-0" style="font-family: 'Marcellus', serif;">{{ $stats['total_users'] }}</h2>
                    </div>
                    <i class="bi bi-people fs-4 text-muted"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-6">
            <div class="card border rounded-0 shadow-none bg-white h-100" style="border-color: #E0E0E0;">
                <div class="card-header bg-white border-bottom border-black py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-uppercase text-black mb-0 small" style="letter-spacing: 0.1em;">Penjualan Terbaru</h6>
                        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-muted small" style="font-size: 0.7rem;">LIHAT SEMUA</a>
                    </div>
                </div>

                <div class="table-responsive d-none d-md-block">
                    <table class="table align-middle mb-0">
                        <thead class="bg-subtle">
                            <tr>
                                <th class="ps-4 py-2 text-uppercase small text-muted font-weight-bold" style="font-size: 0.7rem;">ID</th>
                                <th class="py-2 text-uppercase small text-muted font-weight-bold text-center" style="font-size: 0.7rem;">Status</th>
                                <th class="text-end pe-4 py-2 text-uppercase small text-muted font-weight-bold" style="font-size: 0.7rem;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders->take(5) as $order)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="ps-4 py-3">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none text-black fw-bold small">#{{ $order->order_number }}</a>
                                    <div class="text-muted" style="font-size: 0.65rem;">{{ $order->customer_name }}</div>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge rounded-0 fw-normal text-uppercase px-2 py-1 border" style="font-size: 0.6rem; letter-spacing: 0.05em;
                                            @if($order->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                                            @elseif($order->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                                            @else background-color: #f8f9fa; color: #000; border-color: #ddd; @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="text-end pe-4 py-3 fw-bold small">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted small">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-md-none p-3 bg-light">
                    @forelse($recentOrders->take(5) as $order)
                        <div class="card border rounded-0 mb-2 p-3 bg-white shadow-sm" style="border-color: #eee;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-black text-decoration-none d-block">#{{ $order->order_number }}</a>
                                    <div class="text-muted small text-uppercase" style="font-size: 0.7rem;">{{ $order->customer_name }}</div>
                                </div>
                                <span class="badge rounded-0 fw-normal text-uppercase px-2 py-1 border" style="font-size: 0.6rem; letter-spacing: 0.05em;
                                        @if($order->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                                        @elseif($order->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                                        @else background-color: #f8f9fa; color: #000; border-color: #ddd; @endif">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <div class="border-top border-light-subtle pt-2 mt-1 d-flex justify-content-between align-items-center">
                                <span class="text-muted small text-uppercase" style="font-size: 0.65rem;">Total</span>
                                <span class="fw-bold text-black small">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border rounded-0 shadow-none bg-white h-100" style="border-color: #E0E0E0;">
                <div class="card-header bg-white border-bottom border-black py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-uppercase text-black mb-0 small" style="letter-spacing: 0.1em;">Penyewaan Terbaru</h6>
                        <a href="{{ route('admin.rents.index') }}" class="text-decoration-none text-muted small" style="font-size: 0.7rem;">LIHAT SEMUA</a>
                    </div>
                </div>

                <div class="table-responsive d-none d-md-block">
                    <table class="table align-middle mb-0">
                        <thead class="bg-subtle">
                            <tr>
                                <th class="ps-4 py-2 text-uppercase small text-muted font-weight-bold" style="font-size: 0.7rem;">ID</th>
                                <th class="py-2 text-uppercase small text-muted font-weight-bold text-center" style="font-size: 0.7rem;">Status</th>
                                <th class="text-end pe-4 py-2 text-uppercase small text-muted font-weight-bold" style="font-size: 0.7rem;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRents->take(5) as $rent)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="ps-4 py-3">
                                    <a href="{{ route('admin.rents.show', $rent) }}" class="text-decoration-none text-black fw-bold small">
                                        {{ $rent->rent_number }}
                                    </a>
                                    <div class="text-muted" style="font-size: 0.65rem;">{{ $rent->customer_name }}</div>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge rounded-0 fw-normal text-uppercase px-2 py-1 border" style="font-size: 0.6rem; letter-spacing: 0.05em;
                                            @if($rent->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                                            @elseif($rent->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                                            @else background-color: #f8f9fa; color: #000; border-color: #ddd; @endif">
                                        {{ $rent->status }}
                                    </span>
                                </td>
                                <td class="text-end pe-4 py-3 fw-bold small">Rp {{ number_format($rent->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted small">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-md-none p-3 bg-light">
                    @forelse($recentRents->take(5) as $rent)
                        <div class="card border rounded-0 mb-2 p-3 bg-white shadow-sm" style="border-color: #eee;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <a href="{{ route('admin.rents.show', $rent) }}" class="fw-bold text-black text-decoration-none d-block">{{ $rent->rent_number }}</a>
                                    <div class="text-muted small text-uppercase" style="font-size: 0.7rem;">{{ $rent->customer_name }}</div>
                                </div>
                                <span class="badge rounded-0 fw-normal text-uppercase px-2 py-1 border" style="font-size: 0.6rem; letter-spacing: 0.05em;
                                        @if($rent->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                                        @elseif($rent->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                                        @else background-color: #f8f9fa; color: #000; border-color: #ddd; @endif">
                                    {{ $rent->status }}
                                </span>
                            </div>
                            <div class="border-top border-light-subtle pt-2 mt-1 d-flex justify-content-between align-items-center">
                                <span class="text-muted small text-uppercase" style="font-size: 0.65rem;">Total</span>
                                <span class="fw-bold text-black small">Rp {{ number_format($rent->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection