@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="fw-bold text mb-4">Riwayat Pesanan</h1>
            @if(count($orders) > 0)
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table">
                                    <tr>
                                        <th>No. Pesanan</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="fw-semibold">{{ $order['order_number'] }}</td>
                                            <td>{{ $order['customer_name'] }}</td>
                                            <td>{{ $order['order_date'] }}</td>
                                            <td class="fw-semibold text">Rp {{ $order['total_amount'] }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($order['status'] == 'completed') bg-success
                                                    @elseif($order['status'] == 'processing') bg-warning
                                                    @elseif($order['status'] == 'pending') bg-secondary
                                                    @elseif($order['status'] == 'cancelled') bg-danger
                                                    @endif">
                                                    {{ $statusOptions[$order['status']] }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ url('/pesanan/' . $order['order_number']) }}" class="btn btn-sm">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-receipt display-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Belum Ada Pesanan</h4>
                    <p class="text-muted mb-4">Anda belum memiliki riwayat pesanan.</p>
                    <a href="{{ url('/katalog') }}" class="btn">Mulai Belanja</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection