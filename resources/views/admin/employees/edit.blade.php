@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Data Pegawai</h6>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">NIK (Nomor Induk Karyawan)</label>
                                <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                                       value="{{ old('nik', $employee->nik) }}" required>
                                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Cabang Bekerja</label>
                                <select name="branch_id" class="form-select" required>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $employee->branch_id == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nomor Telepon/WhatsApp</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Alamat Tinggal</label>
                            <textarea name="address" class="form-control" rows="3" required>{{ old('address', $employee->address) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Status Pegawai</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ $employee->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$employee->is_active ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>

                        <hr class="my-4">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning py-2 fw-bold text-dark">Perbarui Data Pegawai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection