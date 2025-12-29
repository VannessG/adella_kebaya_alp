@extends('layouts.app')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Edit Data Pegawai</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Perbarui informasi karyawan</p>
        </div>
    </div>
    <div class="d-md-none" style="width: 60px; height: 1px; background-color: #000; margin: 15px auto;"></div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border rounded-0 bg-white p-4 p-md-5" style="border-color: var(--border-color);">
                
                <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.9rem;">NIK (Nomor Induk Karyawan)</label>
                            <input type="text" name="nik" class="form-control rounded-0 bg-subtle border-0 p-3 @error('nik') is-invalid @enderror" 
                                   value="{{ old('nik', $employee->nik) }}" required placeholder="Contoh: 12345678">
                            @error('nik') <div class="invalid-feedback small">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.9rem;">Cabang Bekerja</label>
                            <select name="branch_id" class="form-select rounded-0 bg-subtle border-0 p-3" required>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $employee->branch_id == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="mb-4">
                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ old('name', $employee->name) }}" required placeholder="Masukkan nama lengkap">
                    </div>

                    {{-- Telepon --}}
                    <div class="mb-4">
                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nomor Telepon/WhatsApp</label>
                        <input type="text" name="phone" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ old('phone', $employee->phone) }}" required placeholder="Contoh: 08123456789">
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-4">
                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Alamat Tinggal</label>
                        <textarea name="address" class="form-control rounded-0 bg-subtle border-0 p-3" rows="3" required placeholder="Masukkan alamat lengkap">{{ old('address', $employee->address) }}</textarea>
                    </div>

                    {{-- Status Pegawai --}}
                    <div class="mb-5">
                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Status Pegawai</label>
                        <select name="is_active" class="form-select rounded-0 bg-subtle border-0 p-3">
                            <option value="1" {{ $employee->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$employee->is_active ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        <div class="form-text small text-muted fst-italic mt-1" style="font-size: 0.65rem;">Pegawai non-aktif tidak dapat login ke sistem.</div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-3 pt-2">
                        <button type="submit" class="btn btn-primary-custom rounded-0 px-4 py-3 text-uppercase fw-bold flex-grow-1 flex-md-grow-0" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            Perbarui Data
                        </button>
                        <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-custom rounded-0 px-4 py-3 text-uppercase fw-bold flex-grow-1 flex-md-grow-0" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection