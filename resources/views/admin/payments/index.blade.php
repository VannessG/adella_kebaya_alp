@extends('layouts.app')

@section('title', 'Manajemen Pembayaran')

@section('content')
<div class="container">
    <h1 class="fw-bold text mb-4">Manajemen Pembayaran</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pembayaran</th>
                            <th>Tipe</th>
                            <th>No. Transaksi</th>
                            <th>Metode</th>
                            <th>Jumlah</th>
                            <th>Pembayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td class="fw-semibold">{{ $payment->payment_number }}</td>
                                <td>
                                    <span class="badge {{ $payment->transaction_type == 'order' ? 'bg-info' : 'bg-warning' }}">
                                        {{ $payment->transaction_type == 'order' ? 'Pembelian' : 'Sewa' }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->transaction_type == 'order')
                                        {{ $payment->transaction->order_number ?? '-' }}
                                    @else
                                        {{ $payment->transaction->rent_number ?? '-' }}
                                    @endif
                                </td>
                                <td>{{ $payment->paymentMethod->name }}</td>
                                <td class="fw-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>{{ $payment->payer_name }}</td>
                                <td>
                                    <form action="{{ route('admin.payments.update-status', $payment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $payment->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="success" {{ $payment->status == 'success' ? 'selected' : '' }}>Success</option>
                                            <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="expired" {{ $payment->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    @if($payment->proof_image)
                                        <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank" 
                                           class="btn btn-sm btn-outline-info" title="Lihat Bukti">
                                            <i class="bi bi-image"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection