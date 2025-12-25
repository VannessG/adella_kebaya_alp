@extends('layouts.app')

@section('title', 'Rekapan Pemasukan')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Rekapan Laporan</h2>
        <span class="badge bg-primary px-3 py-2">Periode: {{ date('d M Y', strtotime($stats['start_date'])) }} - {{ date('d M Y', strtotime($stats['end_date'])) }}</span>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Tanggal Awal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $stats['start_date'] }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $stats['end_date'] }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="bi bi-filter me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white rounded-4 p-3">
                <p class="mb-1 opacity-75">Total Pemasukan</p>
                <h3 class="fw-bold mb-0">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white rounded-4 p-3">
                <p class="mb-1 opacity-75">Total Order (Penjualan)</p>
                <h3 class="fw-bold mb-0">{{ $stats['count_order'] }} Transaksi</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info text-white rounded-4 p-3">
                <p class="mb-1 opacity-75">Total Sewa</p>
                <h3 class="fw-bold mb-0">{{ $stats['count_rent'] }} Transaksi</h3>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Tanggal</th>
                            <th>No. Referensi</th>
                            <th>Jenis</th>
                            <th>Pelanggan</th>
                            <th class="text-end pe-4">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allTransactions as $item)
                        <tr>
                            <td class="ps-4">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-bold text-primary">{{ $item->order_number ?? $item->rent_number }}</td>
                            <td>
                                <span class="badge {{ $item->type == 'Order' ? 'bg-soft-success text-success border border-success' : 'bg-soft-info text-info border border-info' }} rounded-pill px-3">
                                    {{ $item->type }}
                                </span>
                            </td>
                            <td>{{ $item->customer_name }}</td>
                            <td class="text-end pe-4 fw-bold">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Tidak ada data pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-success { background-color: #e6f7ef; }
    .bg-soft-info { background-color: #e7f3ff; }
</style>
@endsection