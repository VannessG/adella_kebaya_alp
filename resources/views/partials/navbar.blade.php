<nav class="navbar navbar-expand-lg bg-white sticky-top py-3 shadow-sm">
    <div class="container">
        {{-- LOGO --}}
        <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ url('/') }}" style="color: var(--primary-color); font-size: 1.5rem;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="me-2 rounded-circle" style="height: 45px; width: 45px; object-fit: cover;">
            Adella<span style="color: var(--text-dark)">Kebaya</span>
        </a>

        {{-- Branch Indicator (Mobile) --}}
        @if(session('selected_branch'))
        <div class="d-lg-none ms-auto me-2">
            <span class="badge bg-light text-dark border">
                <i class="bi bi-geo-alt-fill text-danger"></i> {{ session('selected_branch')->city }}
            </span>
        </div>
        @endif

        {{-- Toggler --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item px-2"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'text-primary active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item px-2"><a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'text-primary active' : '' }}" href="{{ route('admin.orders.index') }}">Pesanan</a></li>
                        <li class="nav-item px-2"><a class="nav-link {{ request()->routeIs('admin.rents.*') ? 'text-primary active' : '' }}" href="{{ route('admin.rents.index') }}">Sewa</a></li>
                        <li class="nav-item px-2"><a class="nav-link {{ request()->routeIs('admin.products.*') ? 'text-primary active' : '' }}" href="{{ route('admin.products.index') }}">Produk</a></li>
                        <li class="nav-item px-2">
                            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.reports.index') }}">
                                <i class="bi bi-graph-up-arrow me-1"></i> Rekapan
                            </a>
                        </li>
                        {{-- NAVBAR KEPEGAWAIAN BARU --}}
                        <li class="nav-item dropdown px-2">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.employees.*') || request()->routeIs('admin.shifts.*') ? 'text-primary active' : '' }}" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-people-fill me-1"></i> Kepegawaian
                            </a>
                            <ul class="dropdown-menu border-0 shadow-sm rounded-3">
                                <li><a class="dropdown-item" href="{{ route('admin.employees.index') }}">Daftar Pegawai</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.shifts.index') }}">Presensi & Shift</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item px-2"><a class="nav-link {{ request()->is('/') ? 'text-primary active' : '' }}" href="{{ url('/') }}">Beranda</a></li>
                        <li class="nav-item px-2"><a class="nav-link {{ request()->is('katalog*') ? 'text-primary active' : '' }}" href="{{ url('/katalog') }}">Katalog</a></li>
                        <li class="nav-item px-2"><a class="nav-link {{ request()->is('pesanan*') ? 'text-primary active' : '' }}" href="{{ url('/pesanan') }}">Pesanan</a></li>
                        <li class="nav-item px-2"><a class="nav-link {{ request()->is('sewa*') ? 'text-primary active' : '' }}" href="{{ url('/sewa') }}">Sewa</a></li>
                    @endif
                @endauth
                
                @guest
                    <li class="nav-item px-2"><a class="nav-link" href="{{ url('/') }}">Beranda</a></li>
                    <li class="nav-item px-2"><a class="nav-link" href="{{ url('/katalog') }}">Katalog</a></li>
                    <li class="nav-item px-2"><a class="nav-link" href="{{ url('/about') }}">Tentang</a></li>
                @endguest
            </ul>

            <div class="d-flex align-items-center gap-3">
                {{-- Branch Selector (Desktop) --}}
                @if(session('selected_branch'))
                <div class="d-none d-lg-block dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ session('selected_branch')->city }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2">
                        @foreach(\App\Models\Branch::where('is_active', true)->get() as $branch)
                            @if($branch->id != session('selected_branch')->id)
                                <li>
                                    <form action="{{ route('branch.change') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                        <button type="submit" class="dropdown-item px-3 py-2 text-muted">
                                            {{ $branch->city }}
                                        </button>
                                    </form>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif

                @auth
                    @if(auth()->user()->role === 'user')
                    <a href="{{ route('cart.index') }}" class="position-relative text-dark text-decoration-none me-2">
                        <i class="bi bi-handbag fs-4"></i>
                        @if(count(session('cart', [])) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                {{ count(session('cart', [])) }}
                            </span>
                        @endif
                    </a>
                    @endif
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-custom px-4">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary-custom px-4">Daftar</a>
                @else
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
                            <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold me-2" style="width: 40px; height: 40px;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="d-none d-lg-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2">
                            <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> Profil</a></li>
                            @if(auth()->user()->role === 'user')
                                <li><a class="dropdown-item py-2" href="{{ route('rent.index') }}"><i class="bi bi-clock-history me-2"></i> Riwayat Sewa</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>