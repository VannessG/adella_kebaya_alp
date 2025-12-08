@extends('layouts.app')

@section('title', 'Manajemen Pengiriman')

@section('content')
<div class="container">
    <h1 class="fw-bold text mb-4">Manajemen Pengiriman</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tipe</th>
                            <th>No. Transaksi</th>
                            <th>Kurir</th>
                            <th>No. Tracking</th>
                            <th>Asal</th>
                            <th>Tujuan</th>
                            <th>Biaya</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipments as $shipment)
                            <tr>
                                <td>
                                    <span class="badge {{ $shipment->transaction_type == 'order' ? 'bg-info' : 'bg-warning' }}">
                                        {{ $shipment->transaction_type == 'order' ? 'Pembelian' : 'Sewa' }}
                                    </span>
                                </td>
                                <td>
                                    @if($shipment->transaction_type == 'order')
                                        {{ $shipment->transaction->order_number ?? '-' }}
                                    @else
                                        {{ $shipment->transaction->rent_number ?? '-' }}
                                    @endif
                                </td>
                                <td>{{ $shipment->courier_service ?? '-' }}</td>
                                <td>{{ $shipment->tracking_number ?? '-' }}</td>
                                <td>
                                    <small>{{ Str::limit($shipment->address_origin, 30) }}</small>
                                </td>
                                <td>
                                    <small>{{ Str::limit($shipment->address_destination, 30) }}</small>
                                </td>
                                <td>Rp {{ number_format($shipment->cost, 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('admin.shipments.update-status', $shipment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            @foreach($statusOptions as $value => $label)
                                                <option value="{{ $value }}" {{ $shipment->status == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-truck"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $shipments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection