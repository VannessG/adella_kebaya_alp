@extends('layouts.app')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">Detail Rekapan Absensi</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Laporan detail kehadiran pegawai</p>
        </div>
        <div class="col-12 col-md-4 text-center text-md-end">
            <a href="{{ route('admin.shifts.edit', $shift->id) }}" class="btn btn-outline-custom rounded-0 py-2 px-4 text-uppercase fw-bold small w-100 w-md-auto" style="letter-spacing: 0.1em;">
                <i class="bi bi-pencil me-2"></i> Edit Data
            </a>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5 px-0 px-md-3">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card border rounded-0 bg-white p-4 mb-4 shadow-sm" style="border-color: #E0E0E0;">
                <div class="row g-3 text-center text-md-start">
                    <div class="col-12 col-md-4 border-end-md">
                        <span class="d-block text-muted small text-uppercase mb-1" style="letter-spacing: 0.1em; font-size: 0.7rem;">Cabang</span>
                        <span class="fw-bold text-black text-uppercase">{{ $shift->branch->name }}</span>
                    </div>
                    <div class="col-12 col-md-4 border-end-md">
                        <span class="d-block text-muted small text-uppercase mb-1" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tanggal</span>
                        <span class="fw-bold text-black">{{ date('d F Y', strtotime($shift->shift_day)) }}</span>
                    </div>
                    <div class="col-12 col-md-4">
                        <span class="d-block text-muted small text-uppercase mb-1" style="letter-spacing: 0.1em; font-size: 0.7rem;">Jam Kerja</span>
                        <span class="fw-bold text-black font-monospace">{{ $shift->start_time }} - {{ $shift->end_time }}</span>
                    </div>
                </div>
            </div>

            <div class="card border rounded-0 bg-white shadow-sm mx-0 mx-md-0" style="border-color: #E0E0E0;">
                
                <div class="shift-detail-header d-none d-md-flex">
                    <div class="col-name">Nama Pegawai</div>
                    <div class="col-nik">NIK</div>
                    <div class="col-status">Status</div>
                </div>

                <div class="shift-detail-body">
                    @foreach($employees as $emp)
                        <div class="shift-detail-item">
                            <div class="col-name">
                                <span class="d-md-none text-muted small text-uppercase d-block mb-1">Nama Pegawai</span>
                                <span class="fw-bold text-black text-uppercase small" style="letter-spacing: 0.05em;">{{ $emp->name }}</span>
                            </div>

                            <div class="col-nik">
                                <span class="d-md-none text-muted small text-uppercase d-block mb-1">NIK</span>
                                {{ $emp->nik }}
                            </div>

                            <div class="col-status">
                                <span class="d-md-none text-muted small text-uppercase d-block mb-1">Status</span>
                                <span class="badge rounded-0 border px-3 py-2 text-uppercase fw-bold" style="font-size: 0.6rem; letter-spacing: 0.05em;
                                        @if($emp->attendance_status_raw == 'hadir') background-color: #000; color: #fff; border-color: #000;
                                        @else background-color: #fff; color: #dc3545; border-color: #dc3545; @endif">
                                    {{ $emp->attendance_label }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.shifts.index') }}" class="btn btn-outline-dark rounded-0 px-4 py-2 text-uppercase fw-bold small w-100 w-md-auto" style="letter-spacing: 0.1em;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection