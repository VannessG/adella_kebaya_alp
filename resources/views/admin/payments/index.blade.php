@extends('layouts.app')

@section('title', 'Manajemen Pembayaran')

@section('content')

{{-- Custom CSS untuk merapikan layout --}}
<style>
    /* Default (Mobile): Card View */
    .payment-list-item {
        padding: 1.25rem;
        border-bottom: 1px solid #eee;
    }
    .payment-list-item:last-child {
        border-bottom: none;
    }
    
    /* Desktop View: Table Row Style */
    @media (min-width: 992px) {
        .payment-list-header {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #eee;
        }

        .payment-list-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            font-size: 0.85rem;
            transition: background-color 0.2s;
        }

        .payment-list-item:hover {
            background-color: #fafafa;
        }

        /* Definisi Lebar Kolom Desktop */
        .col-payment-no { width: 12%; padding-right: 10px; }
        .col-type       { width: 10%; padding-right: 10px; }
        .col-ref        { width: 12%; padding-right: 10px; }
        .col-method     { width: 10%; padding-right: 10px; }
        
        /* Jarak agar kolom jumlah tidak menempel */
        .col-amount     { 
            width: 15%; 
            padding-right: 2.5rem; 
        }
        
        .col-payer      { width: 18%; padding-right: 10px; }
        .col-status     { width: 13%; padding-right: 10px; }
        .col-actions    { width: 10%; }
    }
</style>

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">Daftar Pembayaran</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Verifikasi dan pantau transaksi masuk</p>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5 px-0 px-md-3">
    <div class="card border rounded-0 shadow-sm bg-white mx-0 mx-md-0">
        <div class="payment-list-container">
            {{-- Header Desktop --}}
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
                        <div class="col-payment-no">
                            <span class="d-lg-none text-muted small text-uppercase d-block mb-1">No. Bayar</span>{{ $payment->payment_number }}
                        </div>

                        <div class="col-type">
                            <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Tipe</span>
                            <span class="badge rounded-0 border text-uppercase px-2 py-1" style="font-size: 0.6rem; letter-spacing: 0.05em;
                                    @if($payment->view_type_is_order) background-color: #000; color: #fff; border-color: #000;
                                    @else background-color: #fff; color: #000; border-color: #000; @endif">
                                {{ $payment->view_type_label }}
                            </span>
                        </div>

                        <div class="col-ref">
                            <span class="d-lg-none text-muted small text-uppercase d-block mb-1">No. Ref</span>{{ $payment->view_transaction_number }}
                        </div>

                        <div class="col-method">
                            <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Metode</span>
                            <span class="text-uppercase small fw-bold text-black" style="letter-spacing: 0.05em;">{{ $payment->view_method_name }}</span>
                        </div>

                        {{-- Kolom Jumlah --}}
                        <div class="col-amount">
                            <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Jumlah</span>{{ $payment->view_amount_formatted }}
                        </div>

                        {{-- Kolom Pembayar --}}
                        <div class="col-payer">
                            <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Pembayar</span>
                            <span class="text-uppercase small text-truncate d-block">{{ $payment->payer_name }}</span>
                        </div>

                        <div class="col-status">
                            <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Status</span>
                            <form action="{{ route('admin.payments.update-status', $payment) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" 
                                        class="form-select form-select-sm rounded-0 text-uppercase fw-bold small clean-dropdown bg-light border-0" 
                                        style="font-size: 0.7rem; padding: 0.4rem 2rem 0.4rem 0.7rem; cursor: pointer;"
                                        onchange="this.form.submit()">
                                    <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $payment->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="approved" {{ $payment->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="expired" {{ $payment->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                            </form>
                        </div>

                        <div class="col-actions">
                            @if($payment->proof_image)
                                <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank" class="btn btn-proof">
                                    <i class="bi bi-card-image me-1"></i> <span class="d-lg-none">Lihat Bukti</span><span class="d-none d-lg-inline">Lihat</span>
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
    @if($payments->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection