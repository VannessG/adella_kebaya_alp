@extends('layouts.app')

@section('title', 'Rekapan Pemasukan')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Rekapan Laporan</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">
                Periode: <span class="text-black fw-bold">{{ date('d M Y', strtotime($stats['start_date'])) }}</span> s/d <span class="text-black fw-bold">{{ date('d M Y', strtotime($stats['end_date'])) }}</span>
            </p>
        </div>
    </div>
    <div class="d-md-none" style="width: 60px; height: 1px; background-color: #000; margin: 15px auto;"></div>
</div>

<div class="container pb-5">

    {{-- FILTER SECTION --}}
    <div class="card border rounded-0 bg-white mb-5" style="border-color: #E0E0E0;">
        <div class="card-body p-4">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tanggal Awal</label>
                    <input type="date" name="start_date" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ $stats['start_date'] }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ $stats['end_date'] }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary-custom w-100 rounded-0 py-3 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                        <i class="bi bi-filter me-2"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-4 mb-5">
        {{-- Total Pemasukan (Highlight Hitam) --}}
        <div class="col-md-4">
            <div class="card border rounded-0 h-100 bg-white p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small text-uppercase opacity-75 mb-1" style="letter-spacing: 0.1em;">Total Pemasukan</p>
                        <h3 class="fw-normal mb-0" style="font-family: 'Marcellus', serif;">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</h3>
                    </div>
                    <i class="bi bi-wallet2 fs-3 opacity-50"></i>
                </div>
            </div>
        </div>
        
        {{-- Total Order --}}
        <div class="col-md-4">
            <div class="card border rounded-0 h-100 bg-white p-4" style="border-color: #E0E0E0;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small text-uppercase text-muted mb-1" style="letter-spacing: 0.1em;">Total Order (Jual)</p>
                        <h3 class="fw-normal text-black mb-0" style="font-family: 'Marcellus', serif;">{{ $stats['count_order'] }} <span class="fs-6 text-muted">Transaksi</span></h3>
                    </div>
                    <i class="bi bi-bag-check fs-3 text-muted"></i>
                </div>
            </div>
        </div>

        {{-- Total Sewa --}}
        <div class="col-md-4">
            <div class="card border rounded-0 h-100 bg-white p-4" style="border-color: #E0E0E0;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small text-uppercase text-muted mb-1" style="letter-spacing: 0.1em;">Total Sewa</p>
                        <h3 class="fw-normal text-black mb-0" style="font-family: 'Marcellus', serif;">{{ $stats['count_rent'] }} <span class="fs-6 text-muted">Transaksi</span></h3>
                    </div>
                    <i class="bi bi-hourglass-split fs-3 text-muted"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="card border rounded-0 shadow-none bg-white" style="border-color: #E0E0E0;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-subtle">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tanggal</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">No. Referensi</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Jenis</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Pelanggan</th>
                            <th class="text-end pe-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allTransactions as $item)
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td class="ps-4 py-3 text-muted small">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3">
                                <span class="font-monospace text-black fw-bold small">{{ $item->order_number ?? $item->rent_number }}</span>
                            </td>
                            <td class="py-3">
                                <span class="badge rounded-0 border text-uppercase px-2 py-1 
                                    {{ $item->type == 'Jual' ? 'bg-white text-black border-black' : 'bg-black text-white border-black' }}"
                                    style="font-size: 0.6rem; letter-spacing: 0.05em;">
                                    {{ $item->type }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="text-black small text-uppercase" style="letter-spacing: 0.05em;">{{ $item->customer_name }}</span>
                            </td>
                            <td class="text-end pe-4 py-3">
                                <span class="fw-bold text-black" style="font-family: 'Jost', sans-serif;">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted small text-uppercase" style="letter-spacing: 0.1em;">Tidak ada data pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection