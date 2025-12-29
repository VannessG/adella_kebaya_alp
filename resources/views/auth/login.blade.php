@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100 py-1" style="background-color: var(--bg-body); background-image: radial-gradient(var(--border-color) 1px, transparent 1px); background-size: 24px 24px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-6">
                <div class="card border rounded-0 shadow-sm bg-white p-5 p-md-5" style="border-color: var(--border-color);">
                    <div class="card-body p-0">
                        <div class="text-center mb-5">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px; object-fit: contain; filter: grayscale(100%); opacity: 0.8;" class="mb-3">
                            <h2 class="fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.15em;">LOGIN</h2>
                            <p class="text-muted small text-uppercase" style="letter-spacing: 0.05em; font-size: 0.9rem;">Selamat datang kembali</p>
                        </div>

                        @if (session('status'))
                            <div class="alert rounded-0 border-black bg-white text-black d-flex align-items-center mb-4" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                <div class="small">{{ session('status') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Email</label>
                                <input id="email" type="email" class="form-control rounded-0 p-3 bg-subtle border-0" style="font-size: 0.9rem;" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@gmail.com">
                                @error('email')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="password" class="form-label small text-uppercase fw-bold text-muted mb-0" style="letter-spacing: 0.1em; font-size: 0.7rem;">Password</label>
                                    @if ($canResetPassword ?? false)
                                        <a href="{{ route('password.request') }}" class="text-decoration-none text-muted small hover-underline" style="font-size: 0.75rem;">Lupa password?</a>
                                    @endif
                                </div>
                                <input id="password" type="password" class="form-control rounded-0 p-3 bg-subtle border-0" style="font-size: 0.9rem;"name="password" required placeholder="••••••••">
                                @error('password')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input rounded-0 border-black" id="remember" name="remember">
                                <label class="form-check-label small text-muted" for="remember" style="padding-top: 2px;">Ingat saya di perangkat ini</label>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary-custom rounded-0 py-3 text-uppercase fw-bold" style="letter-spacing: 0.15em; font-size: 0.8rem;">
                                    Login
                                </button>
                            </div>
                        </form>

                        <div class="text-center pt-3 border-top" style="border-color: var(--border-color) !important;">
                            <p class="text-muted small mb-0">Belum punya akun? <a href="{{ route('register') }}" class="text-black fw-bold text-decoration-none text-uppercase hover-underline" style="letter-spacing: 0.05em; font-size: 0.8rem;">Register</a>
                            </p>
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