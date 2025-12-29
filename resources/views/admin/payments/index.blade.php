@extends('layouts.app')

@section('title', 'Manajemen Pembayaran')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Daftar Pembayaran</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Verifikasi dan pantau transaksi masuk</p>
        </div>
    </div>
    <div class="d-md-none" style="width: 60px; height: 1px; background-color: #000; margin: 15px auto;"></div>
</div>

<div class="container pb-5">
    
    {{-- TABEL DATA --}}
    <div class="card border rounded-0 shadow-none bg-white" style="border-color: #E0E0E0;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 950px;">
                    <thead class="bg-subtle">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">No. Pembayaran</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tipe</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">No. Transaksi</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Metode</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Jumlah</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Pembayar</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Status</th>
                            <th class="pe-4 py-3 text-uppercase small text-muted font-weight-bold text-end" style="letter-spacing: 0.1em; font-size: 0.7rem;">Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            {{-- NO PEMBAYARAN --}}
                            <td class="ps-4 py-3 fw-bold text-black" style="font-family: 'Jost', sans-serif;">
                                {{ $payment->payment_number }}
                            </td>

                            {{-- TIPE TRANSAKSI --}}
                            <td class="py-3">
                                <span class="badge rounded-0 border text-uppercase px-2 py-1" 
                                      style="font-size: 0.6rem; letter-spacing: 0.05em;
                                      @if($payment->view_type_is_order) background-color: #000; color: #fff; border-color: #000;
                                      @else background-color: #fff; color: #000; border-color: #000; @endif">
                                    {{ $payment->view_type_label }}
                                </span>
                            </td>

                            {{-- NO TRANSAKSI --}}
                            <td class="py-3 small text-muted font-monospace">
                                {{ $payment->view_transaction_number }}
                            </td>

                            {{-- METODE --}}
                            <td class="py-3 small text-uppercase fw-bold text-black" style="letter-spacing: 0.05em;">
                                {{ $payment->view_method_name }}
                            </td>

                            {{-- JUMLAH --}}
                            <td class="py-3 fw-bold text-black small">
                                {{ $payment->view_amount_formatted }}
                            </td>

                            {{-- PEMBAYAR --}}
                            <td class="py-3 small text-uppercase">{{ $payment->payer_name }}</td>

                            {{-- STATUS DROPDOWN --}}
                            <td class="py-3">
                                <form action="{{ route('admin.payments.update-status', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm rounded-0 text-uppercase fw-bold small clean-dropdown" 
                                            onchange="this.form.submit()">
                                        <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $payment->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="success" {{ $payment->status == 'success' ? 'selected' : '' }}>Success</option>
                                        <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="expired" {{ $payment->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                    </select>
                                </form>
                            </td>

                            {{-- AKSI (BUKTI) --}}
                            <td class="pe-4 py-3 text-end">
                                @if($payment->proof_image)
                                    <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank" 
                                       class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold" 
                                       style="font-size: 0.65rem; letter-spacing: 0.05em;" title="Lihat Bukti">
                                        <i class="bi bi-card-image me-1"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-muted small fst-italic" style="font-size: 0.65rem;">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $payments->links() }}
    </div>
</div>
@endsection