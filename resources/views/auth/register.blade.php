@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')

<div class="d-flex align-items-center justify-content-center min-vh-100 py-5" style="background-color: var(--bg-body); background-image: radial-gradient(var(--border-color) 1px, transparent 1px); background-size: 24px 24px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-6">
                <div class="card border rounded-0 shadow-sm bg-white p-4 p-md-5" style="border-color: var(--border-color);">
                    <div class="card-body p-0">
                        <div class="text-center mb-5">
                            <i class="bi bi-person-plus display-4 text-black mb-3"></i>
                            <h2 class="fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.15em;">Register</h2>
                            <p class="text-muted small text-uppercase" style="letter-spacing: 0.05em; font-size: 0.9rem;">Bergabunglah dengan Adella Kebaya</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nama Lengkap</label>
                                <input id="name" type="text" class="form-control rounded-0 p-3 bg-subtle border-0" style="font-size: 0.9rem;" name="name" value="{{ old('name') }}" required autofocus placeholder="Nama Anda">
                                @error('name')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Alamat Email</label>
                                <input id="email" type="email" class="form-control rounded-0 p-3 bg-subtle border-0" style="font-size: 0.9rem;" name="email" value="{{ old('email') }}" required placeholder="nama@gmail.com">
                                @error('email')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nomor Telepon</label>
                                <input id="phone" type="text" class="form-control rounded-0 p-3 bg-subtle border-0" style="font-size: 0.9rem;" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="address" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Alamat Lengkap</label>
                                <textarea id="address" class="form-control rounded-0 p-3 bg-subtle border-0" style="font-size: 0.9rem;"name="address" rows="3" placeholder="Jalan ...">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Password</label>
                                <input id="password" type="password" class="form-control rounded-0 p-3 bg-subtle border-0" style="font-size: 0.9rem;" name="password" required placeholder="••••••••">
                                @error('password')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-5">
                                <label for="password_confirmation" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Konfirmasi Password</label>
                                <input id="password_confirmation" type="password" class="form-control rounded-0 p-3 bg-subtle border-0" style="font-size: 0.9rem;" name="password_confirmation" required placeholder="••••••••">
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary-custom rounded-0 py-3 text-uppercase fw-bold" style="letter-spacing: 0.15em; font-size: 0.8rem;">Buat Akun Baru</button>
                            </div>
                        </form>

                        <div class="text-center pt-3 border-top" style="border-color: var(--border-color) !important;">
                            <p class="text-muted small mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-black fw-bold text-decoration-none text-uppercase hover-underline" style="letter-spacing: 0.05em; font-size: 0.8rem;">Masuk di Sini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection