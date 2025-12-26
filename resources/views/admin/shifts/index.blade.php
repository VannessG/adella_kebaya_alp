@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rekap Presensi Shift</h1>
        <a href="{{ route('admin.shifts.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Create Today Shift
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    {{-- Filter Rentang Waktu --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.shifts.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100">Filter</button>
                        <a href="{{ route('admin.shifts.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Cabang</th>
                            <th>Jam Kerja</th>
                            <th class="text-center">Total Staff</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                            @php
                                $attendance = $shift->attendance_data ?? [];
                                $presentCount = collect($attendance)->filter(fn($val) => $val === 'hadir')->count();
                            @endphp
                            <tr>
                                <td class="ps-4 fw-medium">{{ $shift->shift_day->format('d/m/Y') }}</td>
                                <td><span class="badge bg-info text-dark">{{ $shift->branch->name }}</span></td>
                                <td>{{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }}</td>
                                <td class="text-center">{{ count($attendance) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success rounded-pill px-3">{{ $presentCount }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.shifts.show', $shift->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Belum ada rekapan shift dalam rentang waktu ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection