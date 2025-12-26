@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Detail Rekap Absensi</h4>
        <a href="{{ route('admin.shifts.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><strong>Cabang:</strong><p>{{ $shift->branch->name }}</p></div>
                <div class="col-md-3"><strong>Tanggal:</strong><p>{{ $shift->shift_day->format('d F Y') }}</p></div>
                <div class="col-md-3"><strong>Jam Kerja:</strong><p>{{ $shift->start_time }} - {{ $shift->end_time }}</p></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="table-responsive">
            <table class="table align-middle m-0">
                <thead class="bg-light">
                    <tr><th>Nama Pegawai</th><th>NIK</th><th class="text-center">Status</th></tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                    <tr>
                        <td>{{ $emp->name }}</td>
                        <td>{{ $emp->nik }}</td>
                        <td class="text-center">
                            @php $status = $shift->attendance_data[$emp->id] ?? 'tidak_hadir'; @endphp
                            <span class="badge {{ $status == 'hadir' ? 'bg-success' : 'bg-danger' }}">
                                {{ strtoupper($status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection