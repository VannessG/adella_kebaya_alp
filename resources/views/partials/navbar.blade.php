<nav class="navbar navbar-expand-lg sticky-top py-2 py-md-3" style="background-color: var(--color-white); border-bottom: 1px solid var(--border-color);">
    <div class="container position-relative">
        <div class="d-flex align-items-center">
            <button class="navbar-toggler border-0 p-0 me-3 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: grayscale(100%); width: 24px; height: 24px;"></span>
            </button>

            <a class="navbar-brand d-flex align-items-center me-0" href="{{ url('/') }}" style="font-family: 'Marcellus', serif; letter-spacing: 0.05em; color: var(--color-black);">
                <img src="{{ asset('images/logo.png') }}" alt="Adella" class="me-2 me-md-3" style="height: 35px; width: auto; object-fit: contain; filter: grayscale(100%);">
                <span class="d-none d-sm-inline" style="font-size: 1.25rem;">Adella Kebaya</span>
                <span class="d-inline d-sm-none" style="font-size: 1.1rem;">Adella</span>
            </a>
        </div>

        <div class="collapse navbar-collapse justify-content-center order-3 order-lg-2" id="navbarNav">
            <ul class="navbar-nav mb-2 mb-lg-0 text-uppercase text-center text-lg-center bg-white w-100 w-lg-auto" style="font-size: 0.75rem; letter-spacing: 0.1em; font-weight: 500;">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item px-2">
                            <a class="nav-link text-link-custom {{ request()->routeIs('admin.dashboard') ? 'active border-bottom border-black' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link text-link-custom {{ request()->routeIs('admin.products.*') ? 'active border-bottom border-black' : '' }}" href="{{ route('admin.products.index') }}">Produk</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link text-link-custom {{ request()->routeIs('admin.orders.*') ? 'active border-bottom border-black' : '' }}" href="{{ route('admin.orders.index') }}">Penjualan</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link text-link-custom {{ request()->routeIs('admin.rents.*') ? 'active border-bottom border-black' : '' }}" href="{{ route('admin.rents.index') }}">Penyewaan</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link text-link-custom {{ request()->routeIs('admin.reports.*') ? 'active border-bottom border-black' : '' }}" href="{{ route('admin.reports.index') }}">Laporan</a>
                        </li>
                        <li class="nav-item dropdown px-2">
                            <a class="nav-link dropdown-toggle text-link-custom" href="#" data-bs-toggle="dropdown">Kepegawaian</a>
                            <ul class="dropdown-menu rounded-0 border shadow-sm mt-2 p-0 text-center text-lg-start">
                                <li>
                                    <a class="dropdown-item py-2 small text-uppercase custom-dropdown-item border-bottom" href="{{ route('admin.employees.index') }}">Daftar Pegawai</a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 small text-uppercase custom-dropdown-item" href="{{ route('admin.shifts.index') }}">Kehadiran</a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item px-2"><a class="nav-link text-link-custom {{ request()->is('/') ? 'active border-bottom border-black' : '' }}" href="{{ url('/') }}">Beranda</a></li>
                        <li class="nav-item px-2"><a class="nav-link text-link-custom {{ request()->is('katalog*') ? 'active border-bottom border-black' : '' }}" href="{{ url('/katalog') }}">Katalog</a></li>
                        <li class="nav-item px-2"><a class="nav-link text-link-custom {{ request()->is('pesanan*') ? 'active border-bottom border-black' : '' }}" href="{{ url('/pesanan') }}">Pembelian</a></li>
                        <li class="nav-item px-2"><a class="nav-link text-link-custom {{ request()->is('sewa*') ? 'active border-bottom border-black' : '' }}" href="{{ url('/sewa') }}">Penyewaan</a></li>
                    @endif
                @else
                    <li class="nav-item px-2"><a class="nav-link text-link-custom {{ request()->is('/') ? 'active border-bottom border-black' : '' }}" href="{{ url('/') }}">Beranda</a></li>
                    <li class="nav-item px-2"><a class="nav-link text-link-custom {{ request()->is('katalog*') ? 'active border-bottom border-black' : '' }}" href="{{ url('/katalog') }}">Katalog</a></li>
                @endauth
            </ul>
        </div>

        <div class="d-flex align-items-center gap-3 ms-auto ms-lg-0 order-2 order-lg-3">
            @if(session()->has('selected_branch'))
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none text-black text-uppercase p-0 dropdown-toggle d-none d-lg-block" type="button" data-bs-toggle="dropdown" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                        <i class="bi bi-geo-alt me-1"></i> {{ session('selected_branch')->city }}
                    </button>
                    <button class="btn btn-link text-decoration-none text-black p-0 d-lg-none" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-geo-alt fs-4"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end rounded-0 border shadow-sm mt-2 p-0" style="min-width: 120px;">
                        <li class="d-lg-none px-3 py-2 border-bottom bg-subtle text-center fw-semibold small text-muted text-uppercase">
                            <strong>{{ session('selected_branch')->city }}</strong>
                        </li>
                        @foreach($branches as $branch)
                            @if($branch->id != session('selected_branch')->id)
                                <li>
                                    <form action="{{ route('branch.change') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                        <button type="submit" class="dropdown-item py-2 small text-uppercase w-100 text-center custom-dropdown-item" style="font-size: 0.75rem;">
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
                <a href="{{ route('cart.index') }}" class="position-relative text-black">
                    <i class="bi bi-bag fs-4 fs-lg-5"></i>
                    @if(count(session('cart', [])) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-0 bg-black text-white" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                            {{ count(session('cart', [])) }}
                        </span>
                    @endif
                </a>
                @endif
            @endauth

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none text-black" data-bs-toggle="dropdown"><i class="bi bi-person-circle fs-4 fs-lg-5"></i></a>
                <ul class="dropdown-menu dropdown-menu-end rounded-0 border shadow-sm mt-3 p-0 text-center text-lg-start" style="min-width: 120px;">
                    @guest
                        <li><a class="dropdown-item py-2 small text-uppercase custom-dropdown-item border-bottom" href="{{ route('login') }}">Masuk</a></li>
                        <li><a class="dropdown-item py-2 small text-uppercase custom-dropdown-item" href="{{ route('register') }}">Daftar</a></li>
                    @else
                        <li><a class="dropdown-item py-3 border-bottom small text-uppercase custom-dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear me-2"></i> Edit Profil</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item py-3 small text-uppercase text-danger custom-dropdown-item"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</nav>