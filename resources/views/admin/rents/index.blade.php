@extends('layouts.app')

@section('title', 'Manajemen Penyewaan')

@section('content')
<div class="container">
    <h1 class="fw-bold text mb-4">Manajemen Penyewaan</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No. Sewa</th>
                            <th>Pelanggan</th>
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
                                <td>
                                    <div>{{ $rent->customer_name }}</div>
                                    <small class="text-muted">{{ $rent->user->email ?? 'Guest' }}</small>
                                </td>
                                <td>
                                    <small>
                                        {{ $rent->start_date->format('d M Y') }}<br>
                                        s/d {{ $rent->end_date->format('d M Y') }}
                                    </small>
                                </td>
                                <td class="fw-semibold text">Rp {{ number_format($rent->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('admin.rents.update-status', $rent) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            @foreach($statusOptions as $value => $label)
                                                <option value="{{ $value }}" {{ $rent->status == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.rents.show', $rent) }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $rents->links() }}
            </div>
        </div>
    </div>
</div>
@endsection