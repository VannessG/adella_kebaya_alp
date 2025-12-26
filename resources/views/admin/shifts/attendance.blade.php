@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-4">
        {{-- Header menampilkan info Cabang dan Waktu yang dipilih --}}
        <div class="card-header bg-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">Presensi Harian: {{ $branch->name }}</h6>
            <small>{{ \Carbon\Carbon::parse($shift_day)->format('d F Y') }} | {{ $start_time }} - {{ $end_time }}</small>
        </div>
        
        <div class="card-body">
            {{-- Form mengirim data ke ShiftController@store --}}
            <form action="{{ route('admin.shifts.store') }}" method="POST">
                @csrf
                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                <input type="hidden" name="shift_day" value="{{ $shift_day }}">
                <input type="hidden" name="start_time" value="{{ $start_time }}">
                <input type="hidden" name="end_time" value="{{ $end_time }}">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Pegawai</th>
                                <th class="text-center">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $emp)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $emp->name }}</div>
                                    <small class="text-muted">{{ $emp->nik }}</small>
                                </td>
                                <td class="text-center">
                                    {{-- Status disimpan dalam array JSON attendance_data --}}
                                    <input type="hidden" 
                                           name="attendance_data[{{ $emp->id }}]" 
                                           id="input-{{ $emp->id }}" 
                                           value="tidak_hadir">
                                    
                                    {{-- Tombol interaktif untuk mengubah status --}}
                                    <button type="button" 
                                            class="btn btn-outline-danger px-4 toggle-btn" 
                                            id="btn-{{ $emp->id }}" 
                                            onclick="toggleAttendance({{ $emp->id }})">
                                        <i class="bi bi-x-circle me-1"></i> Tidak Hadir
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 border-top pt-3 d-flex justify-content-between">
                    <a href="{{ route('admin.shifts.create') }}" class="btn btn-light">Kembali ke Pilih Cabang</a>
                    <button type="submit" class="btn btn-success px-5 fw-bold shadow-sm">Simpan Rekapan Shift</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script untuk mengubah status tombol secara real-time tanpa reload --}}
<script>
function toggleAttendance(id) {
    const btn = document.getElementById('btn-' + id);
    const input = document.getElementById('input-' + id);

    if (input.value === 'tidak_hadir') {
        // Logika saat diubah menjadi HADIR
        input.value = 'hadir';
        btn.classList.remove('btn-outline-danger');
        btn.classList.add('btn-success');
        btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Hadir';
    } else {
        // Logika saat diubah menjadi TIDAK HADIR
        input.value = 'tidak_hadir';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-danger');
        btn.innerHTML = '<i class="bi bi-x-circle me-1"></i> Tidak Hadir';
    }
}
</script>

<style>
    /* Memberikan lebar minimal agar tombol tidak berubah ukuran saat teks berubah */
    .toggle-btn { min-width: 150px; transition: all 0.2s; }
</style>
@endsection