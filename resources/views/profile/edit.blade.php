@extends('layouts.app')

@section('title', $title)

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Edit Profil</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Perbarui informasi akun Anda</p>
        </div>
        <div class="col-md-4 text-center text-md-end mt-3 mt-md-0">
            @if($user->isAdmin())
                <span class="badge rounded-0 px-3 py-2 text-uppercase border border-black bg-black text-white fw-normal" style="letter-spacing: 0.1em;">Admin</span>
            @endif
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status') === 'profile-updated')
                <div class="alert rounded-0 border-black bg-white text-black d-flex align-items-center mb-4 p-3" role="alert">
                    <i class="bi bi-check-circle me-3 fs-5"></i>
                    <div class="small text-uppercase" style="letter-spacing: 0.05em;">Profil berhasil diperbarui!</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border rounded-0 bg-white p-4 p-md-5" style="border-color: var(--border-color);">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    @if(!$user->isAdmin())
                        <h5 class="fw-normal text-uppercase text-black mb-4 pb-2 border-bottom border-black" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em; font-size: 1rem;">Ubah Password</h5>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Password Baru</label>
                            <input id="password" type="password" class="form-control rounded-0 bg-subtle border-0 p-3" name="password" placeholder="••••••••">
                            @error('password')
                                <div class="small text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text small text-muted fst-italic mt-2">Kosongkan jika tidak ingin mengubah password.</div>
                        </div>

                        <div class="mb-5">
                            <label for="password_confirmation" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" type="password" class="form-control rounded-0 bg-subtle border-0 p-3" name="password_confirmation" placeholder="••••••••">
                        </div>
                    @else
                        <div class="alert rounded-0 bg-subtle border-0 text-black mb-5 p-4">
                            <div class="d-flex">
                                <i class="bi bi-info-circle me-3 fs-5"></i>
                                <span class="small" style="line-height: 1.6;">Untuk administrator, perubahan password hanya dapat dilakukan melalui sistem administrator pusat demi keamanan.</span>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex gap-3 pt-2">
                        <button type="submit" class="btn btn-primary-custom rounded-0 px-4 py-3 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.1em;">Simpan Perubahan</button>
                        <a href="{{ url('/') }}" class="btn btn-outline-custom rounded-0 px-4 py-3 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.1em;">Kembali</a>
                    </div>
                </form>

                @if(!$user->isAdmin())
                    <div class="mt-5 pt-5 border-top" style="border-color: #eee !important;">
                        <h5 class="fw-normal text-uppercase text-danger mb-3" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em; font-size: 1rem;">Area Berbahaya</h5>
                        <p class="text-muted small mb-4" style="line-height: 1.6;">Menghapus akun bersifat permanen. Semua data riwayat pesanan dan penyewaan Anda akan dihapus dan tidak dapat dikembalikan.</p>
                        <div class="bg-subtle p-4 border" style="border-color: #eee;">
                            <form method="POST" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('DELETE')
                                
                                <div class="mb-3">
                                    <label for="delete_password" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Konfirmasi Password</label>
                                    <input id="delete_password" type="password" class="form-control rounded-0 bg-white border p-3" style="border-color: #ddd;" name="password" required placeholder="Masukkan password Anda">
                                </div>
                                
                                <button type="submit" class="btn btn-outline-danger rounded-0 px-4 py-2 text-uppercase fw-bold w-100" 
                                        style="font-size: 0.75rem; letter-spacing: 0.1em;"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.')">
                                    <i class="bi bi-trash me-2"></i> Hapus Akun Saya Permanen
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection