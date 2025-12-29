<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Adella Kebaya - @yield('title')</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    {{-- Framework CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* =========================================
           1. VARIABLES & RESET
           ========================================= */
        :root {
            color-scheme: light;
            
            /* Palette */
            --color-black: #000000;
            --color-charcoal: #1A1A1A;
            --color-gray-dark: #333333;
            --color-gray-medium: #888888;
            --color-gray-light: #E0E0E0;
            --color-off-white: #FAFAFA;
            --color-subtle: #F9F9F9;
            --color-white: #FFFFFF;
            --color-danger: #dc3545;

            /* Semantics */
            --bg-body: var(--color-white);
            --text-main: var(--color-charcoal);
            --border-color: var(--color-gray-light);
            
            /* Effects */
            --shadow-sm: 0 4px 12px rgba(0,0,0,0.03);
            --shadow-md: 0 8px 24px rgba(0,0,0,0.06);
            --shadow-lg: 0 12px 48px rgba(0,0,0,0.08);
            
            /* Shapes */
            --radius-sharp: 0px;
        }

        html, body {
            background-color: var(--bg-body) !important;
            color: var(--text-main) !important;
            font-family: 'Jost', sans-serif;
            font-weight: 300;
            line-height: 1.8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* =========================================
           2. TYPOGRAPHY
           ========================================= */
        h1, h2, h3, h4, h5, h6, .font-serif, .navbar-brand {
            font-family: 'Marcellus', serif;
            color: var(--color-black);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        
        .display-1, .display-2, .display-3, .display-4, .display-5 {
            font-weight: 400;
        }

        /* =========================================
           3. NAVBAR & NAVIGATION
           ========================================= */
        .navbar {
            background-color: var(--color-white) !important;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 0;
        }
        .navbar-brand img { filter: none; }

        .nav-link.text-link-custom {
            color: var(--color-gray-medium);
            transition: color 0.3s ease;
        }
        .nav-link.text-link-custom:hover,
        .nav-link.text-link-custom.active {
            color: var(--color-black);
        }

        /* =========================================
           4. DROPDOWNS (Bootstrap Override)
           ========================================= */
        .dropdown-menu {
            border-radius: var(--radius-sharp);
            border-color: var(--border-color);
            margin-top: 10px;
        }

        .custom-dropdown-item {
            color: var(--color-black) !important;
            transition: all 0.2s ease;
            background-color: transparent;
        }

        .custom-dropdown-item:hover,
        .custom-dropdown-item:focus,
        .custom-dropdown-item:active,
        .custom-dropdown-item.active {
            background-color: #f8f9fa !important;
            color: var(--color-black) !important;
            font-weight: 600;
            outline: none;
        }

        /* Special case for logout button text color */
        button.custom-dropdown-item.text-danger:hover {
            color: var(--color-danger) !important;
            background-color: #fff5f5 !important;
        }

        /* =========================================
           5. BUTTONS
           ========================================= */
        .btn {
            border-radius: var(--radius-sharp);
            padding: 0.8rem 2.5rem;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.15em;
            transition: all 0.3s ease;
        }

        /* Primary Custom (Black Solid) */
        .btn-primary-custom, .btn-black {
            background-color: var(--color-black);
            border: 1px solid var(--color-black);
            color: var(--color-white);
        }
        .btn-primary-custom:hover, .btn-black:hover {
            background-color: var(--color-gray-dark);
            border-color: var(--color-gray-dark);
            color: var(--color-white);
        }

        /* Outline Custom (Black Border) */
        .btn-outline-custom {
            color: var(--color-black);
            border: 1px solid var(--color-black);
            background: transparent;
        }
        .btn-outline-custom:hover, 
        .btn-check:checked + .btn-outline-custom {
            background-color: var(--color-black);
            color: var(--color-white);
            border-color: var(--color-black);
        }

        /* Filter Button */
        .btn-filter {
            border: 1px solid var(--border-color);
            color: #999;
            background: transparent;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
        }
        .btn-filter:hover, .btn-filter.active {
            border-color: var(--color-black);
            background-color: var(--color-black);
            color: var(--color-white);
        }

        /* Link Button */
        .btn-link-custom {
            color: var(--color-black);
            text-decoration: none;
            border-bottom: 1px solid transparent;
            padding: 0;
            font-weight: 600;
        }
        .btn-link-custom:hover { border-bottom-color: var(--color-black); }

        /* Button Group Fix */
        .btn-group .btn { border: none; }
        .btn-group { border: 1px solid var(--color-black); }

        /* =========================================
           6. CARDS
           ========================================= */
        .card {
            background-color: var(--color-white);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sharp);
            box-shadow: none;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .card:hover, .hover-mono:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: var(--color-black) !important;
        }
        
        .product-card:hover img { transform: scale(1.05); transition: transform 0.5s ease; }

        /* Hover Effects for Specific Components */
        .hover-mono:hover .btn-select-branch {
            background-color: var(--color-black) !important;
            color: var(--color-white) !important;
            border-color: var(--color-black) !important;
        }
        .hover-mono:hover .icon-box {
            background-color: var(--color-white) !important;
            border-color: var(--color-black) !important;
        }

        /* =========================================
           7. FORMS & INPUTS
           ========================================= */
        .form-control, .form-select {
            border-radius: var(--radius-sharp);
            border: 1px solid var(--border-color);
            padding: 0.8rem 1rem;
            font-size: 0.9rem;
            background-color: var(--color-white);
            color: var(--text-main);
        }
        .form-control:focus, .form-select:focus, input[name="search"]:focus {
            border-color: var(--color-black) !important;
            box-shadow: none;
            outline: none;
        }
        .input-group-text {
            border-right: 0;
            background-color: var(--color-subtle);
        }

        /* Clean Dropdown (Select Replacement) */
        .clean-dropdown {
            background-color: var(--color-white);
            border: 1px solid var(--border-color);
            color: var(--color-black);
            padding: 8px 12px;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            cursor: pointer;
            min-width: 160px;
        }
        .clean-dropdown:focus, .clean-dropdown:hover {
            border-color: var(--color-black);
            outline: none;
        }
        .clean-dropdown option { padding: 10px; }

        /* =========================================
           8. TABLES & PAGINATION
           ========================================= */
        .table-hover tbody tr:hover { background-color: var(--color-subtle); }
        
        .pagination .page-link {
            color: var(--color-black);
            border: none;
            margin: 0 5px;
            font-family: 'Jost', sans-serif;
            font-size: 1rem;
        }
        .pagination .active .page-link {
            background-color: var(--color-black);
            border-color: var(--color-black);
            color: var(--color-white);
        }

        /* =========================================
           9. UTILITIES & ALERTS
           ========================================= */
        .alert {
            border-radius: var(--radius-sharp);
            border: 1px solid var(--color-black);
            background-color: var(--color-white);
            color: var(--color-black);
            display: flex;
            align-items: center;
        }
        
        /* Text & Colors */
        .text-black { color: var(--color-black) !important; }
        .bg-subtle { background-color: var(--color-subtle) !important; }
        .border-black { border-color: var(--color-black) !important; }
        
        /* Hover Utilities */
        .hover-text-black:hover { color: var(--color-black) !important; }
        .hover-underline:hover { text-decoration: underline !important; color: var(--color-black) !important; }
        .star-label:hover { transform: scale(1.2); transition: transform 0.2s; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--color-white); }
        ::-webkit-scrollbar-thumb { background: #CCC; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #999; }

        @media (min-width: 768px) {
            .border-end-md { border-right: 1px solid #f0f0f0; }
        }
    </style>
</head>
<body class="antialiased">

    @unless(request()->routeIs('select.branch') || request()->routeIs('branch.*'))
        @include('partials.navbar')
    @endunless

    <main class="flex-grow-1">
        <div class="container py-5">
            {{-- Global Alerts --}}
            @if(session('success'))
                <div class="alert shadow-sm mb-5 p-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 me-3"></i>
                    <div>
                        <span class="d-block text-uppercase small fw-bold" style="letter-spacing: 1px;">Success</span>
                        <span class="fw-light">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert shadow-sm mb-5 p-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>
                    <div>
                        <span class="d-block text-uppercase small fw-bold" style="letter-spacing: 1px;">Notice</span>
                        <span class="fw-light">{{ session('error') }}</span>
                    </div>
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