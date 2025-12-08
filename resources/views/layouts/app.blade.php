<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adella Kebaya - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .branch-card {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 15px;
            height: 100%;
        }
        .branch-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(113, 63, 9, 0.2);
            border-color: #713f09;
        }
        .btn-branch {
            background-color: #713f09;
            color: white;
            border: 2px solid #713f09;
        }
        .btn-branch:hover {
            background-color: #5a3207;
            border-color: #5a3207;
            color: white;
        }
        .dashboard-stat-card {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s;
        }
        .dashboard-stat-card:hover {
            transform: translateY(-5px);
        }
        .dashboard-action-card {
            height: 220px;
            display: flex;
            flex-direction: column;
        }
        .dashboard-action-card .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .list-group-item:hover {
            background-color: #f8f9fa;
        }
        .bg { background-color: #3a2611 !important; }
        .text { color: #3a2611 !important; }
        .btn { 
            background-color: #713f09; 
            border-color: #713f09;
            color: white;
        }
        .btn:hover {
            background-color: #3a2611;
            border-color: #3a2611;
            color: white;
        }
        .border { border-color: #713f09 !important; }
        .min-h-screen { min-height: 100vh; }
        
        .pagination .page-link {
            color: #3a2611;
            border-color: #713f09;
        }
        .pagination .page-link:hover {
            background-color: #3a2611;
            color: white;
            border-color: #3a2611;
        }
        .pagination .page-item.active .page-link {
            background-color: #713f09;
            border-color: #713f09;
            color: white;
        }
        
        /* Branch indicator */
        .branch-indicator {
            background: linear-gradient(135deg, #713f09, #3a2611);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .branch-indicator:hover {
            background: linear-gradient(135deg, #5a3207, #2d1e0d);
        }

        /* Star Rating */
        .rating-input {
            display: flex;
            gap: 10px;
            direction: rtl;
        }
        .star-label {
            cursor: pointer;
            position: relative;
            color: #ddd;
            transition: color 0.3s;
        }
        .star-label:hover,
        .star-label:hover ~ .star-label {
            color: #ffc107;
        }
        .rating-input input:checked ~ .star-label {
            color: #ffc107;
        }
        .star-label i:last-child {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
        .rating-input input:checked ~ .star-label i:last-child,
        .star-label:hover i:last-child,
        .star-label:hover ~ .star-label i:last-child {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-h-screen">

    @include('partials.navbar')

    <main class="flex-grow-1 container my-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>