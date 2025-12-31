<nav class="navbar navbar-expand-lg sticky-top py-3" style="background-color: var(--color-white); border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" style="font-family: 'Marcellus', serif; font-size: 1.5rem; letter-spacing: 0.05em; color: var(--color-black);">
            <img src="{{ asset('images/logo.png') }}" alt="Adella Kebaya" class="me-3" style="height: 40px; width: auto; object-fit: contain; filter: grayscale(100%);">
            Adella Kebaya
        </a>

        @if(session()->has('selected_branch'))
        <div class="d-lg-none ms-auto me-3">
            <span class="badge rounded-0 text-uppercase border border-black text-black bg-white" style="letter-spacing: 0.1em; font-weight: 500;">
                {{ session('selected_branch')->city }}
            </span>
        </div>
        @endif

        <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="filter: grayscale(100%);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.15em; font-weight: 500;">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item px-3"><a class="nav-link text-link-custom {{ request()->routeIs('admin.dashboard') ? 'active fw-bold' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item px-3"><a class="nav-link text-link-custom {{ request()->routeIs('admin.products.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.products.index') }}">Produk</a></li>
                        <li class="nav-item px-3"><a class="nav-link text-link-custom {{ request()->routeIs('admin.orders.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.orders.index') }}">Penjualan</a></li>
                        <li class="nav-item px-3"><a class="nav-link text-link-custom {{ request()->routeIs('admin.rents.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.rents.index') }}">Penyewaan</a></li>
                        <li class="nav-item px-3"><a class="nav-link text-link-custom {{ request()->routeIs('admin.reports.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.reports.index') }}">Laporan</a></li>
                        <li class="nav-item dropdown px-3">
                            <a class="nav-link dropdown-toggle text-link-custom" href="#" data-bs-toggle="dropdown">Kepegawaian</a>
                            <ul class="dropdown-menu rounded-0 border shadow-sm mt-3 p-0" style="border-color: #E0E0E0;">
                                <li>
                                    <a class="dropdown-item py-3 border-bottom text-uppercase custom-dropdown-item" href="{{ route('admin.employees.index') }}" style="font-size: 0.8rem; letter-spacing: 0.05em;">Daftar Pegawai</a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-3 border-bottom text-uppercase custom-dropdown-item" href="{{ route('admin.shifts.index') }}" style="font-size: 0.8rem; letter-spacing: 0.05em;">Kehadiran</a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item px-3"><a class="nav-link text-link-custom {{ request()->is('/') ? 'active border-bottom border-black' : '' }}" href="{{ url('/') }}">Beranda</a></li>
                        <li class="nav-item px-3"><a class="nav-link text-link-custom {{ request()->is('katalog*') ? 'active border-bottom border-black' : '' }}" href="{{ url('/katalog') }}">Katalog</a></li>
                        <li class="nav-item px-3"><a class="nav-link text-link-custom {{ request()->is('pembelian*') ? 'active border-bottom border-black' : '' }}" href="{{ url('/pesanan') }}">Pembelian</a></li>
                        <li class="nav-item px-3"><a class="nav-link text-link-custom {{ request()->is('penyewaan*') ? 'active border-bottom border-black' : '' }}" href="{{ url('/sewa') }}">Penyewaan</a></li>
                    @endif
                @endauth
                
                @guest
                    <li class="nav-item px-3"><a class="nav-link text-link-custom {{ request()->is('/') ? 'active border-bottom border-black' : '' }}" href="{{ url('/') }}">Beranda</a></li>
                    <li class="nav-item px-3"><a class="nav-link text-link-custom {{ request()->is('katalog*') ? 'active border-bottom border-black' : '' }}" href="{{ url('/katalog') }}">Katalog</a></li>
                @endguest
            </ul>

            <div class="d-flex align-items-center gap-4 mt-3 mt-lg-0">
                @if(session()->has('selected_branch'))
                <div class="d-none d-lg-block dropdown">
                    <button class="btn btn-link text-decoration-none text-black text-uppercase p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                        <i class="bi bi-geo-alt me-1"></i> {{ session('selected_branch')->city }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end rounded-0 border shadow-sm mt-3 p-0" style="border-color: #E0E0E0;">
                        @foreach($branches as $branch)
                            @if($branch->id != session('selected_branch')->id)
                                <li>
                                    <form action="{{ route('branch.change') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                        <button type="submit" class="dropdown-item py-3 border-bottom text-uppercase custom-dropdown-item w-100 text-start" style="font-size: 0.75rem; letter-spacing: 0.05em;">
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
                        <i class="bi bi-bag fs-5"></i>
                        @if(count(session('cart', [])) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-0 bg-black text-white" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                                {{ count(session('cart', [])) }}
                            </span>
                        @endif
                    </a>
                    @endif
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="btn btn-link text-black text-decoration-none text-uppercase fw-bold p-0" style="font-size: 0.75rem; letter-spacing: 0.1em;">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary-custom rounded-0 py-2 px-3" style="font-size: 0.7rem;">Register</a>
                @else
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-black text-uppercase" 
                        data-bs-toggle="dropdown" 
                        style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end rounded-0 border shadow-sm mt-3 p-0" style="min-width: 200px;">
                            <li>
                                <a class="dropdown-item py-3 border-bottom text-uppercase custom-dropdown-item" 
                                href="{{ route('profile.edit') }}" 
                                style="font-size: 0.8rem; letter-spacing: 0.05em;">
                                Profil Saya
                                </a>
                            </li>
    
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="dropdown-item py-3 text-danger text-uppercase custom-dropdown-item" 
                                            style="font-size: 0.8rem; letter-spacing: 0.05em;">
                                        Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>