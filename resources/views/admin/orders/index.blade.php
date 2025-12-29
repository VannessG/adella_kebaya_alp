@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Daftar Penjualan</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Kelola transaksi penjualan masuk</p>
        </div>
    </div>
    <div class="d-md-none" style="width: 60px; height: 1px; background-color: #000; margin: 15px auto;"></div>
</div>

<div class="container pb-5">
    <div class="card border rounded-0 shadow-none bg-white" style="border-color: #E0E0E0;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                    <thead class="bg-subtle">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">ID Pesanan</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">Pelanggan</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">Tanggal</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">Total</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">Status</th>
                            <th class="pe-4 py-3 text-end text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
    @forelse($orders as $order)
    <tr style="border-bottom: 1px solid #f0f0f0;">
        <td class="ps-4 py-3 fw-bold text-black" style="font-family: 'Jost', sans-serif;">
            #{{ substr($order->order_number, -6) }}
        </td>
        <td class="py-3">
            <div class="d-flex flex-column">
                <span class="fw-bold text-black text-uppercase small" style="letter-spacing: 0.05em;">{{ $order->customer_name }}</span>
                <small style="font-size: 0.8rem;">{{ $order->customer_phone }}</small>
            </div>
        </td>
        <td class="py-3  text-uppercase">{{ $order->created_at->format('d M Y') }}</td>
        <td class="py-3 fw-bold text-black">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        <td class="py-3">
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PUT')
                
                <select name="status" 
                        class="form-select form-select-sm rounded-0 text-uppercase fw-bold small clean-dropdown" 
                        onchange="this.form.submit()">
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </form>
        </td>
        <td class="pe-4 py-3 text-end">
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.05em;">Detail</a>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="text-center py-5 text-muted small text-uppercase" style="letter-spacing: 0.1em;">Data pesanan tidak ditemukan</td>
    </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
        
        @if($orders->hasPages())
        <div class="card-footer bg-white border-0 py-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection