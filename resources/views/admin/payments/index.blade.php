@extends('layouts.app')

@section('title', 'Manajemen Pembayaran')

@section('content')

<style>
    /* =========================================
       1. BASE STYLING (DESKTOP DEFAULT)
       ========================================= */
    .payment-list-container {
        border: 1px solid #e0e0e0;
        background-color: #fff;
        overflow-x: auto; /* Scroll samping jika layar desktop sempit */
        -webkit-overflow-scrolling: touch;
    }

    /* Header Tabel (Hanya Desktop) */
    .payment-list-header {
        display: flex;
        padding: 15px 20px;
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 2px solid #e9ecef;
        min-width: 1000px; /* Menjaga struktur kolom tetap rapi */
    }

    /* Baris Data (Item) */
    .payment-list-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
        min-width: 1000px; /* Sama dengan header agar sejajar */
        background: #fff;
    }

    .payment-list-item:hover {
        background-color: #fafafa;
    }

    /* =========================================
       2. PENGATURAN KOLOM (FLEXBOX)
       ========================================= */
    .col-payment-no { flex: 0 0 14%; font-family: 'Courier New', monospace; font-weight: 700; color: #333; }
    .col-type       { flex: 0 0 10%; }
    .col-ref        { flex: 0 0 14%; font-family: 'Courier New', monospace; font-size: 0.9em; color: #555; word-break: break-all; padding-right: 15px; }
    .col-method     { flex: 0 0 12%; font-size: 0.85em; font-weight: 600; color: #444; }
    .col-amount     { flex: 0 0 15%; font-weight: bold; text-align: right; padding-right: 20px; font-size: 1rem; }
    .col-payer      { flex: 0 0 15%; font-size: 0.9em; padding-right: 10px; }
    .col-status     { flex: 0 0 12%; }
    .col-actions    { flex: 0 0 8%; text-align: right; }

    /* Tombol Bukti */
    .btn-proof {
        border: 1px solid #dee2e6; color: #495057; background: #fff;
        font-size: 0.7rem; padding: 6px 12px; text-transform: uppercase;
        letter-spacing: 0.05em; border-radius: 4px; text-decoration: none;
        font-weight: 600; display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s;
    }
    .btn-proof:hover { background: #343a40; color: #fff; border-color: #343a40; }

    /* Label Mobile (Hidden di Desktop) */
    .mobile-label { display: none; }


    /* =========================================
       3. RESPONSIVE / MOBILE VIEW (< 992px)
       ========================================= */
    @media (max-width: 991.98px) {
        
        /* Sembunyikan Header Tabel Desktop */
        .payment-list-header { display: none !important; }
        
        /* Reset Container agar tidak scroll samping lagi, tapi vertikal */
        .payment-list-container { 
            overflow-x: hidden; 
            border: none; 
            background: transparent; 
        }

        /* Ubah Item menjadi KARTU (CARD) */
        .payment-list-item {
            display: flex;
            flex-direction: column; /* Susun vertikal */
            align-items: flex-start;
            min-width: 0 !important; /* Reset width desktop */
            width: 100%;
            margin-bottom: 20px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px; /* Sudut melengkung */
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); /* Efek bayangan */
            padding: 20px;
        }

        /* Reset semua kolom agar lebar penuh */
        .payment-list-item > div {
            width: 100% !important;
            flex: 0 0 auto !important;
            max-width: 100% !important;
            text-align: left !important;
            padding-right: 0 !important;
            margin-bottom: 12px;
        }

        /* Tampilkan Label Mobile */
        .mobile-label {
            display: block;
            font-size: 0.7rem;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 4px;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        /* --- URUTAN TAMPILAN (REORDERING) --- */
        
        /* Header Kartu: Tipe & No Pembayaran */
        .col-type { 
            order: 1; 
            display: flex; flex-direction: column; align-items: flex-start;
            border-bottom: 1px dashed #eee; padding-bottom: 10px; margin-bottom: 10px !important;
        }
        .col-type .badge { font-size: 0.75rem; padding: 6px 10px; }

        .col-payment-no { 
            order: 2; 
            font-size: 1.25rem; margin-bottom: 5px; color: #000;
        }

        .col-amount { 
            order: 3; 
            font-size: 1.25rem; margin-bottom: 15px; color: #000;
        }

        /* Detail Kartu */
        .col-ref    { order: 4; background: #f9f9f9; padding: 8px; border-radius: 4px; font-size: 0.85rem; }
        .col-method { order: 5; }
        .col-payer  { order: 6; }
        
        /* Footer Kartu: Status & Aksi */
        .col-status { 
            order: 7; margin-top: 10px; 
        }
        .col-status select { width: 100%; height: 40px; }

        .col-actions { 
            order: 8; margin-top: 10px;
        }
        .btn-proof { width: 100%; padding: 10px; background: #333; color: #fff; }
    }
</style>

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">
                Daftar Pembayaran
                @if(session('selected_branch'))
                    <span class="text-muted fs-4">| {{ session('selected_branch')->name }}</span>
                @endif
            </h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Verifikasi dan pantau transaksi masuk</p>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5 px-0 px-md-3">
    
    {{-- LIST DATA --}}
    <div class="card border-0 border-md shadow-none shadow-sm-md bg-transparent bg-md-white">
        
        <div class="payment-list-container">
            
            {{-- Header Desktop Only --}}
            <div class="payment-list-header d-none d-lg-flex">
                <div class="col-payment-no">No. Bayar</div>
                <div class="col-type">Tipe</div>
                <div class="col-ref">No. Ref</div>
                <div class="col-method">Metode</div>
                <div class="col-amount">Jumlah</div>
                <div class="col-payer">Pembayar</div>
                <div class="col-status">Status</div>
                <div class="col-actions text-end">Bukti</div>
            </div>

            <div class="payment-list-body">
                @forelse($payments as $payment)
                    <div class="payment-list-item">
                        
                        {{-- 1. No. Pembayaran --}}
                        <div class="col-payment-no">
                            <span class="mobile-label">No. Bayar</span>
                            {{ $payment->payment_number }}
                        </div>

                        {{-- 2. Tipe --}}
                        <div class="col-type">
                            <span class="mobile-label">Tipe Transaksi</span>
                            <span class="badge rounded-1 border text-uppercase" 
                                  style="@if($payment->view_type_is_order) background-color: #212529; color: #fff; border-color: #212529;
                                         @else background-color: #fff; color: #212529; border-color: #212529; @endif">
                                {{ $payment->view_type_label }}
                            </span>
                        </div>

                        {{-- 3. No. Referensi --}}
                        <div class="col-ref">
                            <span class="mobile-label">No. Referensi</span>
                            {{ $payment->view_transaction_number }}
                        </div>

                        {{-- 4. Metode --}}
                        <div class="col-method">
                            <span class="mobile-label">Metode Pembayaran</span>
                            <span class="text-uppercase">{{ $payment->view_method_name }}</span>
                        </div>

                        {{-- 5. Jumlah --}}
                        <div class="col-amount">
                            <span class="mobile-label">Total Jumlah</span>
                            {{ $payment->view_amount_formatted }}
                        </div>

                        {{-- 6. Pembayar --}}
                        <div class="col-payer">
                            <span class="mobile-label">Nama Pembayar</span>
                            <span class="text-truncate d-block">{{ $payment->payer_name }}</span>
                        </div>

                        {{-- 7. Status --}}
                        <div class="col-status">
                            <span class="mobile-label">Status Verifikasi</span>
                            <form action="{{ route('admin.payments.update-status', $payment) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" 
                                        class="form-select form-select-sm rounded-1 text-uppercase fw-bold small border-secondary" 
                                        style="font-size: 0.75rem; cursor: pointer;"
                                        onchange="this.form.submit()">
                                    <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $payment->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="approved" {{ $payment->status == 'success' ? 'selected' : '' }}>Approved</option>
                                    <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="expired" {{ $payment->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                            </form>
                        </div>

                        {{-- 8. Aksi --}}
                        <div class="col-actions">
                            @if($payment->proof_image)
                                <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank" class="btn btn-proof w-100 w-lg-auto">
                                    <i class="bi bi-image me-2 d-lg-none"></i>
                                    <span class="d-lg-none">Lihat Bukti</span>
                                    <span class="d-none d-lg-inline">Lihat</span>
                                </a>
                            @else
                                <span class="text-muted small fst-italic">-</span>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-wallet2 fs-1 text-muted mb-3 d-block"></i>
                        <h6 class="text-muted text-uppercase small" style="letter-spacing: 0.1em;">Belum ada data pembayaran.</h6>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if($payments->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection