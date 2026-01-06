@extends('layouts.app')

@section('title', 'Rekapan Pemasukan')

@section('content')

{{-- CSS Tambahan untuk Merapikan Tampilan Mobile --}}
<style>
    /* Mobile View Styling */
    .report-list-item {
        padding: 1.25rem;
        border-bottom: 1px solid #eee;
        background-color: #fff;
        position: relative; /* Penting untuk positioning badge */
    }
    
    @media (max-width: 991px) {
        /* Memisahkan Text Jual/Sewa ke pojok kanan atas agar tidak mepet kiri/numpuk dengan Ref */
        .report-list-item .col-type {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
        }
        
        /* Memastikan badge tidak tertutup elemen lain */
        .report-list-item .col-type .badge {
            font-size: 0.75rem !important;
            padding: 0.4em 0.8em !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Memberi jarak pada item lain agar tidak bertabrakan jika layar sangat kecil */
        .col-ref {
            margin-bottom: 0.5rem;
            padding-right: 60px; /* Space agar tidak menabrak badge */
        }
    }

    /* Desktop View Styling */
    @media (min-width: 992px) {
        .report-list-header {
            display: flex;
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #eee;
        }
        .report-list-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            font-size: 0.85rem;
        }
        .report-list-item:hover {
            background-color: #fafafa;
        }

        /* Lebar Kolom Desktop */
        .col-date     { width: 20%; }
        .col-ref      { width: 20%; }
        .col-type     { width: 10%; }
        .col-customer { width: 30%; }
        .col-amount   { width: 20%; }
    }
</style>

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">
                Rekapan Laporan 
                @if(session('selected_branch'))
                    <span class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">{{ session('selected_branch')->name }}</span>
                @endif
            </h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">
                Periode: <span class="text-black fw-bold">{{ date('d M Y', strtotime($stats['start_date'])) }}</span> s/d <span class="text-black fw-bold">{{ date('d M Y', strtotime($stats['end_date'])) }}</span>
            </p>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5 px-0 px-md-3">
    <div class="card border rounded-0 bg-white mb-5 mx-0 mx-md-0 shadow-sm" style="border-color: #E0E0E0;">
        <div class="card-body p-4">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tanggal Awal</label>
                    <input type="date" name="start_date" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ $stats['start_date'] }}">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ $stats['end_date'] }}">
                </div>
                <div class="col-12 col-md-4">
                    <button type="submit" class="btn btn-primary-custom w-100 rounded-0 py-3 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                        <i class="bi bi-filter me-2"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 g-md-4 mb-5 px-2 px-md-0">
        <div class="col-12 col-md-4">
            <div class="card border rounded-0 h-100 bg-white p-4 shadow-sm border-black">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small text-uppercase opacity-75 mb-1" style="letter-spacing: 0.1em;">Total Pemasukan</p>
                        <h3 class="fw-normal mb-0" style="font-family: 'Marcellus', serif;">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</h3>
                    </div>
                    <i class="bi bi-wallet2 fs-3 opacity-50"></i>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-4">
            <div class="card border rounded-0 h-100 bg-white p-4 shadow-sm" style="border-color: #E0E0E0;">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                    <div>
                        <p class="small text-uppercase text-muted mb-1" style="letter-spacing: 0.1em; font-size: 0.65rem;">Total Jual</p>
                        <h4 class="fw-normal text-black mb-0" style="font-family: 'Marcellus', serif;">{{ $stats['count_order'] }}</h4>
                    </div>
                    <i class="bi bi-bag-check fs-4 text-muted mt-2 mt-md-0"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card border rounded-0 h-100 bg-white p-4 shadow-sm" style="border-color: #E0E0E0;">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                    <div>
                        <p class="small text-uppercase text-muted mb-1" style="letter-spacing: 0.1em; font-size: 0.65rem;">Total Sewa</p>
                        <h4 class="fw-normal text-black mb-0" style="font-family: 'Marcellus', serif;">{{ $stats['count_rent'] }}</h4>
                    </div>
                    <i class="bi bi-hourglass-split fs-4 text-muted mt-2 mt-md-0"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border rounded-0 shadow-none bg-white mx-0 mx-md-0" style="border-color: #E0E0E0;">
        
        <div class="report-list-header d-none d-lg-flex">
            <div class="col-date">Tanggal</div>
            <div class="col-ref">No. Referensi</div>
            <div class="col-type">Jenis</div>
            <div class="col-customer">Pelanggan</div>
            <div class="col-amount text-end">Nominal</div>
        </div>

        <div class="report-list-body">
            @forelse($allTransactions as $item)
                <div class="report-list-item">
                    {{-- Badge Tipe (Jual/Sewa) dipindah ke sini agar bisa diatur posisinya --}}
                    <div class="col-type">
                        <span class="badge rounded-0 border text-uppercase px-2 py-1 
                            {{ $item->type == 'Jual' ? 'bg-white text-black border-black' : 'bg-black text-white border-black' }}"
                            style="font-size: 0.65rem; letter-spacing: 0.05em;">
                            {{ $item->type }}
                        </span>
                    </div>

                    <div class="col-date">
                        <span class="d-lg-none text-muted small d-block mb-1">{{ $item->created_at->format('d M Y, H:i') }}</span>
                        <span class="d-none d-lg-block">{{ $item->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div class="col-ref">
                        <span class="d-lg-none text-muted small text-uppercase">REF: </span>{{ $item->order_number ?? $item->rent_number }}
                    </div>
                    
                    <div class="col-customer">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1 mt-2">Pelanggan:</span>
                        <span class="text-black text-uppercase small" style="letter-spacing: 0.05em;">{{ $item->customer_name ?? 'Guest' }}</span>
                    </div>

                    <div class="col-amount">
                        <span class="d-lg-none text-muted small text-uppercase float-start mt-2">Total:</span>
                        <span class="fw-bold text-black d-block d-lg-inline text-end mt-2 mt-lg-0">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-clipboard-x fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted text-uppercase small" style="letter-spacing: 0.1em;">Tidak ada transaksi pada periode ini.</h6>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection