@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100 py-5" style="background-color: var(--bg-body); background-image: radial-gradient(var(--border-color) 1px, transparent 1px); background-size: 24px 24px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card border rounded-0 shadow-sm bg-white p-4 p-md-5" style="border-color: var(--border-color);">
                    <div class="card-body p-0">
                        <div class="text-center mb-5">
                            <div class="mb-3">
                                <i class="bi bi-key display-4 text-muted opacity-50"></i>
                            </div>
                            <h2 class="fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.15em; font-size: 1.5rem;">Lupa Password</h2>
                            <p class="text-muted small text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem; line-height: 1.6;">
                                Masukkan email yang terdaftar untuk menerima link reset password.
                            </p>
                        </div>
                        @if (session('status'))
                            <div class="alert rounded-0 border-black bg-white text-black d-flex align-items-center mb-4 p-3" role="alert">
                                <i class="bi bi-check-circle me-3 fs-5"></i>
                                <div class="small text-uppercase" style="letter-spacing: 0.05em;">{{ session('status') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Email Address</label>
                                <input id="email" type="email" class="form-control rounded-0 p-3 bg-subtle border-0" style="font-size: 0.9rem;" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                                @error('email')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary-custom rounded-0 py-3 text-uppercase fw-bold" style="letter-spacing: 0.15em; font-size: 0.8rem;">
                                    Kirim Link Reset
                                </button>
                            </div>
                        </form>
                        <div class="text-center pt-3 border-top" style="border-color: var(--border-color) !important;">
                            <a href="{{ route('login') }}" class="text-decoration-none text-muted small text-uppercase hover-underline" style="letter-spacing: 0.1em; font-size: 0.75rem;">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                            </a>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <p class="text-muted small text-uppercase opacity-50" style="letter-spacing: 0.1em; font-size: 0.65rem;">&copy; {{ date('Y') }} Adella Kebaya</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection