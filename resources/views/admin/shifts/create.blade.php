@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Buat Shift Harian Baru</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shifts.attendance') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Pilih Cabang</label>
                            <select name="branch_id" class="form-select" required>
                                <option value="">-- Pilih Cabang --</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tanggal Shift</label>
                            <input type="date" name="shift_day" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Jam Mulai</label>
                                <input type="time" name="start_time" class="form-control" value="08:00" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Jam Selesai</label>
                                <input type="time" name="end_time" class="form-control" value="16:00" required>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold">Lanjut ke Absensi <i class="bi bi-arrow-right"></i></button>
                            <a href="{{ route('admin.shifts.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection