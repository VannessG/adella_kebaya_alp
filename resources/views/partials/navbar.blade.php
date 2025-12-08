<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        {{-- LOGO --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 55px; width: auto;">
        </a>

        {{-- Branch Indicator (jika sudah pilih cabang) --}}
        @if(session('selected_branch'))
        <div class="me-3 d-none d-lg-block">
            <span class="branch-indicator">
                <i class="bi bi-shop me-1"></i> {{ session('selected_branch')->city }}
            </span>
        </div>
        @endif

        {{-- Mobile Cart + Toggler --}}
        <div class="d-lg-none d-flex align-items-center gap-2">
            @auth
                @if(auth()->user()->role === 'user')
                    <a href="{{ url('/cart') }}" class="btn btn-outline-secondary position-relative p-2">
                        <i class="bi bi-cart3 fs-5"></i>
                        @if(count(session('cart', [])) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ count(session('cart', [])) }}
                            </span>
                        @endif
                    </a>
                @endif
            @endauth

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        {{-- Navbar Menu --}}
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav mx-auto text-center" style="gap: 1rem;">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('admin.products.index') }}">Produk</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('admin.categories.index') }}">Kategori</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('admin.orders.index') }}">Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('admin.rents.index') }}">Sewa</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link fw-semibold" href="{{ url('/') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="{{ url('/katalog') }}">Katalog</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="{{ url('/kategori') }}">Kategori</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="{{ url('/pesanan') }}">Transaksi</a></li>
                        <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('rent.index') }}">Sewa</a></li>
                    @endif
                @endauth

                @guest
                    <li class="nav-item"><a class="nav-link fw-semibold" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="{{ url('/katalog') }}">Katalog</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="{{ url('/kategori') }}">Kategori</a></li>
                @endguest

                <li class="nav-item"><a class="nav-link fw-semibold" href="{{ url('/about') }}">Tentang Kami</a></li>
            </ul>

            {{-- Bagian kanan desktop --}}
            <div class="d-none d-lg-flex align-items-center gap-3">
                {{-- Branch Selector for Users --}}
                @auth
                    @if(auth()->user()->role === 'user' || auth()->user()->role === 'admin')
                        <div class="dropdown d-none d-lg-block">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                    id="branchDropdown" data-bs-toggle="dropdown">
                                <i class="bi bi-shop"></i> {{ session('selected_branch')->city }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @foreach(\App\Models\Branch::where('is_active', true)->get() as $branch)
                                    @if($branch->id != session('selected_branch')->id)
                                        <li>
                                            <form action="{{ route('branch.change') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                                <button type="submit" class="dropdown-item">
                                                    {{ $branch->city }}
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endauth
                
                @auth
                    @if(auth()->user()->role === 'user')
                        <a href="{{ url('/cart') }}" class="btn btn-outline-secondary position-relative p-2">
                            <i class="bi bi-cart3 fs-5"></i>
                            @if(count(session('cart', [])) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ count(session('cart', [])) }}
                                </span>
                            @endif
                        </a>
                    @endif

                    {{-- User dropdown --}}
                    <div class="dropdown">
                        <button class="btn custom-dropdown dropdown-toggle" type="button" id="userDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu custom-dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                            @if(auth()->user()->role === 'user')
                                <li><a class="dropdown-item" href="{{ route('reviews.index') }}">Review Saya</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @endguest
            </div>

            {{-- Mobile view --}}
            <div class="d-lg-none mt-3 text-center">
                @if(session('selected_branch'))
                    <div class="mb-3">
                        <span class="branch-indicator d-inline-block mb-2">
                            <i class="bi bi-shop me-1"></i> {{ session('selected_branch')->city }}
                        </span>
                    </div>
                @endif
                
                @auth
                    <div class="dropdown mb-2">
                        <button class="btn custom-dropdown dropdown-toggle w-75" type="button" id="userDropdownMobile"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu custom-dropdown-menu w-75 mx-auto mt-2 text-center">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                            @if(auth()->user()->role === 'user')
                                <li><a class="dropdown-item" href="{{ route('reviews.index') }}">Review Saya</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth

                @guest
                    <div class="d-flex flex-column align-items-center gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-75">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary w-75">Register</a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>