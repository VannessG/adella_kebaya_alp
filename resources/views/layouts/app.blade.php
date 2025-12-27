<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adella Kebaya - @yield('title')</title>
    
    {{-- Fonts: Marcellus (Klasik/Tegas) & Jost (Modern/Bersih) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    {{-- Framework CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            /* --- PALET WARNA MONOKROM SEJATI --- */
            
            /* Solid Colors */
            --color-black: #000000;
            --color-dark-gray: #333333;   /* Untuk teks utama */
            --color-medium-gray: #777777; /* Untuk teks sekunder */
            --color-light-gray: #E5E5E5;  /* Untuk border halus */
            --color-lighter-gray: #F8F8F8;/* Untuk background subtle */
            --color-white: #FFFFFF;

            /* Semantic Mappings */
            --primary-color: var(--color-black);
            --bg-body: var(--color-white);
            --bg-surface: var(--color-white);
            --text-main: var(--color-dark-gray);
            --text-muted: var(--color-medium-gray);
            --border-subtle: var(--color-light-gray);
            
            /* Shadows (Grayscale) */
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
            
            --radius-sm: 4px;
        }

        body {
            font-family: 'Jost', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            line-height: 1.7;
        }

        /* TYPOGRAPHY */
        h1, h2, h3, h4, h5, h6, .navbar-brand, .font-serif {
            font-family: 'Marcellus', serif;
            color: var(--color-black);
            letter-spacing: 0.05em;
        }

        /* NAVBAR (Solid White & Black Border) */
        .navbar {
            background-color: var(--color-white) !important;
            border-bottom: 1px solid var(--color-black);
            padding: 1rem 0;
        }

        /* CARDS (Clean Grayscale) */
        .card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-sm);
            transition: all 0.4s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: var(--color-black); /* Border jadi hitam saat hover */
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        /* BUTTONS (Strictly Black & White) */
        .btn {
            border-radius: 0; /* Sharp/Kotak */
            padding: 0.7rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            transition: all 0.3s ease;
        }

        .btn-primary-custom, .btn-primary {
            background-color: var(--color-black);
            border: 1px solid var(--color-black);
            color: var(--color-white);
        }

        .btn-primary-custom:hover, .btn-primary:hover {
            background-color: var(--color-white);
            color: var(--color-black); /* Invert jadi putih dengan teks hitam */
        }

        .btn-outline-custom {
            color: var(--color-black);
            border: 1px solid var(--color-black);
            background: transparent;
        }

        .btn-outline-custom:hover {
            background-color: var(--color-black);
            color: var(--color-white);
        }

        /* FORMS (Grayscale Focus) */
        .form-control:focus, .form-select:focus {
            border-color: var(--color-black);
            box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
        }

        /* ALERTS (Override warna-warni Bootstrap jadi Monokrom) */
        .alert {
            border-radius: 0;
            border: 1px solid var(--color-black);
            background-color: var(--color-white);
            color: var(--color-black);
        }
        .alert-success .bi { color: var(--color-black); }
        .alert-danger .bi { color: var(--color-black); }

        /* UTILITIES */
        .text-black { color: var(--color-black) !important; }
        .bg-gray-light { background-color: var(--color-lighter-gray) !important; }
        
        /* SCROLLBAR (Grayscale) */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #FFF; }
        ::-webkit-scrollbar-thumb { background: #555; }
    </style>
</head>
<body>

    {{-- Navbar logic --}}
    @unless(request()->routeIs('select.branch') || request()->routeIs('branch.*'))
        @include('partials.navbar')
    @endunless

    <main class="flex-grow-1">
        <div class="container py-5">
            {{-- Alert System (Monochrome) --}}
            @if(session('success'))
                <div class="alert d-flex align-items-center p-3 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 me-3"></i>
                    <div class="fw-medium">{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert d-flex align-items-center p-3 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>
                    <div class="fw-medium">{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @unless(request()->routeIs('select.branch') || request()->routeIs('branch.*'))
        @include('partials.footer')
    @endunless

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>