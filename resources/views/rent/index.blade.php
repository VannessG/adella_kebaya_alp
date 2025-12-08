@extends('layouts.app')

@section('title', 'Riwayat Penyewaan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="fw-bold text mb-4">Riwayat Penyewaan</h1>
            
            @if(count($rents) > 0)
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Sewa</th>
                                        <th>Cabang</th>
                                        <th>Periode</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rents as $rent)
                                        <tr>
                                            <td class="fw-semibold">{{ $rent->rent_number }}</td>
                                            <td>{{ $rent->branch->name }}</td>
                                            <td>
                                                <div>{{ $rent->start_date->format('d M Y') }}</div>
                                                <div class="text-muted small">s/d {{ $rent->end_date->format('d M Y') }}</div>
                                            </td>
                                            <td class="fw-semibold text">Rp {{ number_format($rent->total_amount, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($rent->status == 'returned') bg-success
                                                    @elseif($rent->status == 'active') bg-info
                                                    @elseif($rent->status == 'paid') bg-warning
                                                    @elseif($rent->status == 'pending') bg-secondary
                                                    @elseif($rent->status == 'overdue') bg-danger
                                                    @elseif($rent->status == 'cancelled') bg-dark
                                                    @endif">
                                                    {{ $statusOptions[$rent->status] }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('rent.show', $rent->rent_number) }}" class="btn btn-sm btn-outline-primary">
                                                    Detail
                                                </a>
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
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Belum Ada Penyewaan</h4>
                    <p class="text-muted mb-4">Anda belum memiliki riwayat penyewaan.</p>
                    <a href="{{ route('rent.create') }}" class="btn">Sewa Sekarang</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection