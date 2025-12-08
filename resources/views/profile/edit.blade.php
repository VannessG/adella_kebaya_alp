@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h4 class="mb-0 fw-bold text">Edit Profil</h4>
                    <div class="mt-2">
                        @if($user->isAdmin())
                            <span class="badge bg-danger">Administrator</span>
                        @else
                            <span class="badge bg-secondary">Customer</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Profil berhasil diperbarui!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        {{-- HANYA TAMPILKAN FORM PASSWORD UNTUK NON-ADMIN --}}
                        @if(!$user->isAdmin())
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Untuk administrator, perubahan password hanya dapat dilakukan melalui sistem administrator.
                        </div>
                        @endif

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn">
                                <i class="bi bi-check-circle"></i> Perbarui Profil
                            </button>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>

                    {{-- HANYA TAMPILKAN DELETE ACCOUNT UNTUK NON-ADMIN --}}
                    @if(!$user->isAdmin())
                    <div class="mt-5 pt-4 border-top">
                        <h5 class="text-danger mb-3">Hapus Akun</h5>
                        <p class="text-muted mb-3">
                            Setelah akun Anda dihapus, semua data dan resource Anda akan dihapus secara permanen. 
                        </p>
                        
                        <form method="POST" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <div class="mb-3">
                                <label for="delete_password" class="form-label">Konfirmasi Password</label>
                                <input id="delete_password" type="password" class="form-control" name="password" required placeholder="Masukkan password Anda untuk konfirmasi">
                            </div>
                            <button type="submit" class="btn btn-outline-danger" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.')">
                                <i class="bi bi-trash"></i> Hapus Akun Saya
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection