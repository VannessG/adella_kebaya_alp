@extends('layouts.app')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Presensi Harian</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">
                <span class="fw-bold text-black">{{ $branch->name }}</span> | 
                {{ date('d F Y', strtotime($shift_day)) }} | 
                <span class="font-monospace small">{{ $start_time }} - {{ $end_time }}</span>
            </p>
        </div>
    </div>
    <div class="d-md-none" style="width: 60px; height: 1px; background-color: #000; margin: 15px auto;"></div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="card border rounded-0 bg-white p-4" style="border-color: var(--border-color);">
                
                {{-- Form mengirim data ke ShiftController@store --}}
                <form action="{{ route('admin.shifts.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                    <input type="hidden" name="shift_day" value="{{ $shift_day }}">
                    <input type="hidden" name="start_time" value="{{ $start_time }}">
                    <input type="hidden" name="end_time" value="{{ $end_time }}">

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-subtle">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">Pegawai</th>
                                    <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.9rem;">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $emp)
                                <tr style="border-bottom: 1px solid #f0f0f0;">
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-black text-uppercase" style="letter-spacing: 0.05em;">{{ $emp->name }}</div>
                                        <small class="font-monospace" style="font-size: 0.75rem;">{{ $emp->nik }}</small>
                                    </td>
                                    <td class="text-center py-3">
                                        {{-- Status disimpan dalam array JSON attendance_data --}}
                                        <input type="hidden" 
                                               name="attendance_data[{{ $emp->id }}]" 
                                               id="input-{{ $emp->id }}" 
                                               value="tidak_hadir">
                                        
                                        {{-- Tombol interaktif untuk mengubah status --}}
                                        <button type="button" 
                                                class="btn btn-outline-custom rounded-0 small fw-bold text-uppercase toggle-btn" 
                                                id="btn-{{ $emp->id }}" 
                                                onclick="toggleAttendance({{ $emp->id }})"
                                                style="font-size: 0.8rem; letter-spacing: 0.05em; padding: 0.5rem 1.5rem;">
                                            <i class="bi bi-x me-1"></i> Tidak Hadir
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top" style="border-color: #f0f0f0 !important;">
                        <a href="{{ route('admin.shifts.create') }}" class="btn btn-outline-custom rounded-0 py-2 px-4 text-uppercase fw-bold small" style="font-size: 0.7rem; letter-spacing: 0.1em;">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary-custom rounded-0 py-3 px-5 text-uppercase fw-bold small" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            Simpan Rekapan
                        </button>
                    </div>
                </form>

            </div>
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
        btn.classList.remove('btn-outline-custom'); // Hapus outline
        btn.classList.add('btn-black-active'); // Tambah hitam solid
        btn.innerHTML = '<i class="bi bi-check me-1"></i> Hadir';
    } else {
        // Logika saat diubah menjadi TIDAK HADIR
        input.value = 'tidak_hadir';
        btn.classList.remove('btn-black-active'); // Hapus hitam solid
        btn.classList.add('btn-outline-custom'); // Tambah outline
        btn.innerHTML = '<i class="bi bi-x me-1"></i> Tidak Hadir';
    }
}
</script>
@endsection