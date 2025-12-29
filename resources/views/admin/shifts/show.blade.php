@extends('layouts.app')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Detail Rekapan Absensi</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Laporan detail kehadiran pegawai</p>
        </div>
        <div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
            <a href="{{ route('admin.shifts.edit', $shift->id) }}" class="btn btn-outline-custom rounded-0 py-2 px-4 text-uppercase fw-bold small" style="letter-spacing: 0.1em; font-size: 0.7rem;">
                <i class="bi bi-pencil me-2"></i> Edit Data
            </a>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border rounded-0 bg-white p-4 mb-4" style="border-color: #E0E0E0;">
                <div class="row text-center text-md-start">
                    <div class="col-md-4 mb-3 mb-md-0 border-end-md">
                        <span class="d-block text-muted small text-uppercase mb-1" style="letter-spacing: 0.1em;">Cabang</span>
                        <span class="fw-bold text-black text-uppercase">{{ $shift->branch->name }}</span>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0 border-end-md">
                        <span class="d-block text-muted small text-uppercase mb-1" style="letter-spacing: 0.1em;">Tanggal</span>
                        <span class="fw-bold text-black">{{ date('d F Y', strtotime($shift->shift_day)) }}</span>
                    </div>
                    <div class="col-md-4">
                        <span class="d-block text-muted small text-uppercase mb-1" style="letter-spacing: 0.1em;">Jam Kerja</span>
                        <span class="fw-bold text-black font-monospace">{{ $shift->start_time }} - {{ $shift->end_time }}</span>
                    </div>
                </div>
            </div>

            <div class="card border rounded-0 bg-white p-0" style="border-color: #E0E0E0;">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-subtle">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nama Pegawai</th>
                                <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">NIK</th>
                                <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.7rem;">Status</th>
                                <th class="pe-4 py-3 text-uppercase small text-muted font-weight-bold text-end" style="letter-spacing: 0.1em; font-size: 0.7rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $emp)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="ps-4 py-3">
                                    <span class="fw-bold text-black small text-uppercase" style="letter-spacing: 0.05em;">{{ $emp->name }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="font-monospace small text-muted">{{ $emp->nik }}</span>
                                </td>
                                <td class="text-center py-3">
                                    <span class="badge rounded-0 border px-3 py-2 text-uppercase fw-bold" 
                                          style="font-size: 0.6rem; letter-spacing: 0.05em;
                                          @if($emp->attendance_status_raw == 'hadir') background-color: #000; color: #fff; border-color: #000;
                                          @else background-color: #fff; color: #dc3545; border-color: #dc3545; @endif">
                                        {{ $emp->attendance_label }}
                                    </span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <a href="{{ route('admin.shifts.edit', $shift->id) }}" class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold" 
                                           style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                            <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.shifts.index') }}" class="btn btn-outline-dark rounded-0 px-4 py-2 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection