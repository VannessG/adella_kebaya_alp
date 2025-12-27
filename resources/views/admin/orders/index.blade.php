@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold" style="font-family: 'Playfair Display', serif;">Daftar Pesanan</h3>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">ID Pesanan</th>
                        <th class="py-3">Pelanggan</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Total</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="ps-4 fw-semibold">
                            #{{ substr($order->order_number, -6) }}
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">{{ $order->customer_name }}</span>
                                <small class="text-muted">{{ $order->customer_phone }}</small>
                            </div>
                        </td>
                        <td class="text-muted">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="fw-bold text-dark">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm border-0 bg-light fw-semibold" 
                                        onchange="this.form.submit()" 
                                        style="width: 140px; color: var(--primary-color);">
                                    @foreach($statusOptions as $key => $label)
                                        <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light rounded-circle" title="Detail">
                                <i class="bi bi-eye text-primary"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Data pesanan tidak ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection