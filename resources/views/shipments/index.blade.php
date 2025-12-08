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
                            <th>No. Pengiriman</th>
                            <th>Driver</th>
                            <th>Tipe</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th>Chat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipments as $shipment)
                            <tr>
                                <td>{{ $shipment->id }}</td>
                                <td>{{ $shipment->driver->name ?? '-' }}</td>
                                <td>
                                    @if($shipment->shipmentable_type === 'App\\Models\\Order')
                                        <span class="badge bg-primary">Order</span>
                                    @else
                                        <span class="badge bg-success">Rent</span>
                                    @endif
                                </td>
                                <td>{{ $shipment->shipmentable->customer_name }}</td>
                                <td>
                                    <span class="badge 
                                        @if($shipment->status == 'delivered') bg-success
                                        @elseif($shipment->status == 'picked_up') bg-info
                                        @elseif($shipment->status == 'driver_assigned') bg-warning
                                        @elseif($shipment->status == 'finding_driver') bg-secondary
                                        @else bg-light text-dark
                                        @endif">
                                        {{ \App\Models\Shipment::getStatusOptions()[$shipment->status] }}
                                    </span>
                                </td>
                                <td>
                                    @if($shipment->chatRoom)
                                        <a href="{{ route('admin.chat.link', $shipment) }}" 
                                           class="btn btn-sm btn-success">
                                            <i class="bi bi-chat"></i> Dapatkan Link
                                        </a>
                                    @else
                                        <form action="{{ route('admin.chat.create', $shipment) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="bi bi-plus-circle"></i> Buat Chat
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection