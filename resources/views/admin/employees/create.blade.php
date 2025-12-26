@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Pegawai Baru</h6>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.employees.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">NIK (Nomor Induk Karyawan)</label>
                                <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" required>
                                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Pilih Cabang Tempat Bekerja</label>
                                <select name="branch_id" class="form-select" required>
                                    <option value="">-- Pilih Cabang --</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama tanpa gelar" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nomor Telepon/WhatsApp</label>
                            <input type="text" name="phone" class="form-control" placeholder="Contoh: 08123456789" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Alamat Tinggal</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Alamat lengkap domisili" required></textarea>
                        </div>

                        <hr class="my-4">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">Simpan Data Pegawai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection