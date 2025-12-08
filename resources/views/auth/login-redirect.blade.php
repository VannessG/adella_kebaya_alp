@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-person-circle display-1 text"></i>
                    </div>
                    <h2 class="fw-bold text mb-3">Selamat Datang</h2>
                    <p class="text-muted mb-4">Silakan login untuk melanjutkan atau buat akun baru jika belum memiliki akun.</p>
                    
                    <div class="d-grid gap-3">
                        <a href="{{ route('login') }}" class="btn btn-lg">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg">Daftar Akun Baru</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection