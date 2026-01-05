<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
        /* ================= VARIABLES & RESET ================= */
        :root {
            --color-black: #000000;
            --color-charcoal: #1A1A1A;
            --color-gray-light: #E0E0E0;
            --color-white: #FFFFFF;
            --color-subtle: #F9F9F9;
            --border-color: #E0E0E0;
            --bg-body: #FFFFFF;
            --text-main: #1A1A1A;
            --radius-sharp: 0px;
        }

        html, body {
            background-color: var(--bg-body) !important;
            color: var(--text-main) !important;
            font-family: 'Jost', sans-serif;
            font-weight: 300;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden; /* Mencegah scroll horizontal body */
        }

        /* ================= TYPOGRAPHY RESPONSIVE ================= */
        h1, h2, h3, h4, h5, h6, .font-serif, .navbar-brand {
            font-family: 'Marcellus', serif;
            color: var(--color-black);
            text-transform: uppercase;
        }

        /* Responsive Font Sizes using Clamp */
        h1, .display-1, .display-2, .display-3 { font-size: clamp(1.8rem, 5vw, 3.5rem) !important; }
        h2, .display-4 { font-size: clamp(1.5rem, 4vw, 2.5rem) !important; }
        h3, .display-5 { font-size: clamp(1.25rem, 3vw, 2rem) !important; }
        h4, .display-6 { font-size: clamp(1.1rem, 2.5vw, 1.5rem) !important; }

        /* ================= COMPONENTS ================= */
        .navbar { background: var(--color-white); border-bottom: 1px solid var(--border-color); }
        .nav-link.text-link-custom { color: #888; transition: 0.3s; }
        .nav-link.text-link-custom:hover, .nav-link.text-link-custom.active { color: var(--color-black); }

        /* Buttons */
        .btn { border-radius: 0; text-transform: uppercase; letter-spacing: 0.1em; padding: 0.8rem 1.5rem; font-size: 0.8rem; }
        .btn-primary-custom, .btn-black { background: var(--color-black); color: #fff; border: 1px solid var(--color-black); }
        .btn-primary-custom:hover { background: #333; border-color: #333; color: #fff; }
        .btn-outline-custom { background: transparent; color: var(--color-black); border: 1px solid var(--color-black); }
        .btn-outline-custom:hover { background: var(--color-black); color: #fff; }
        .btn-filter { border: 1px solid #ddd; color: #999; background: transparent; }
        .btn-filter.active, .btn-filter:hover { background: var(--color-black); color: #fff; border-color: var(--color-black); }

        /* Cards & Images */
        .card { border: 1px solid var(--border-color); border-radius: 0; box-shadow: none; }
        .product-card:hover { transform: translateY(-3px); transition: transform 0.3s; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        
        .product-img {
            width: 100%;
            height: 280px; /* Desktop Default */
            object-fit: cover;
        }

        /* Forms */
        .form-control, .form-select { border-radius: 0; border: 1px solid var(--border-color); padding: 0.75rem 1rem; }
        .form-control:focus, .form-select:focus { box-shadow: none; border-color: var(--color-black); }

        /* Tables Responsive Fix */
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table { min-width: 600px; } /* Mencegah tabel gepeng di HP */
        /* Desktop View (Default) */
        .table-layout-fixed {
            table-layout: fixed;
            width: 100%;
        }
        
        /* Styling Dasar Tabel */
        .table td, .table th {
            vertical-align: middle; /* Agar teks mulai dari atas */
            padding: 12px 8px;
        }

        .dropdown-item:active, 
        .dropdown-item:focus,
        .custom-dropdown-item:active,
        .custom-dropdown-item:focus {
            background-color: #f3f3f3 !important; /* Abu-abu muda, bukan Biru */
            color: var(--color-black) !important;
        }

        /* Jika ingin tombol 'Keluar' merah saat di-klik */
        button.custom-dropdown-item.text-danger:active,
        button.custom-dropdown-item.text-danger:focus {
            background-color: #fff5f5 !important;
            color: var(--color-danger) !important;
        }

        /* ================= MOBILE SPECIFIC (MAX 768px) ================= */
        @media (max-width: 768px) {
            .container { padding-left: 1rem; padding-right: 1rem; }
            .product-img { height: 180px; } /* Tinggi gambar lebih kecil di HP */
            .navbar-brand { font-size: 1.2rem !important; }
            .navbar-brand img { height: 30px !important; }
            .section-title { font-size: 1.5rem; }
            
            /* Form adjustment for touch */
            .btn { padding: 0.6rem 1rem; font-size: 0.75rem; }
            input, select, textarea { font-size: 16px !important; } /* Mencegah zoom di iOS */
            
            /* Hide non-essential elements on very small screens if needed */
            .d-mobile-none { display: none !important; }

            .font-serif.h3 { font-size: 1.25rem; margin-bottom: 1rem !important; } 
        
            /* Lebar Kolom Mobile (Total 100%) */
            /* Kita beri ruang lebih untuk tombol aksi */
            .col-mobile-info { width: 38%; }   /* Kolom No Sewa */
            .col-mobile-price { width: 32%; }  /* Kolom Harga */
            .col-mobile-action { width: 30%; text-align: right; } /* Kolom Tombol */

            /* Styling Teks agar turun ke bawah (Wrap) */
            .rent-number-text {
                word-wrap: break-word;
                white-space: normal;
                font-weight: bold;
                display: block;
                line-height: 1.2;
                margin-bottom: 4px;
            }

            .period-text {
                display: block;
                color: #6c757d;
                font-size: 0.65rem;
                line-height: 1.2;
            }

            /* Container Tombol Mobile */
            .mobile-action-stack {
                display: flex;
                flex-direction: column; /* Stack Vertikal */
                gap: 6px;               /* Jarak antar tombol */
                align-items: flex-end;  /* Rata Kanan */
                width: 100%;
            }
            
            /* Styling Tombol Mobile */
            .btn-mobile-sm {
                padding: 4px 0 !important; /* Padding atas bawah saja */
                font-size: 0.65rem !important;
                width: 100%; /* Tombol memenuhi lebar kolom kolomnya */
                text-align: center;
            }

                /* Styling khusus untuk Star Rating */
    .star-label {
        cursor: pointer;
        font-size: 1.5rem; /* Ukuran bintang */
        color: #ccc;       /* Warna default (kosong) */
        transition: color 0.2s;
    }
    
    /* Sembunyikan radio button asli */
    .rating-radio {
        display: none; 
    }

    /* Helper class untuk menampilkan icon */
    .star-icon-fill { display: none; color: #000; } /* Bintang isi warna hitam */
    .star-icon-empty { display: inline-block; }

    /* State Aktif (dikontrol via JS) */
    .star-label.active .star-icon-fill { display: inline-block; }
    .star-label.active .star-icon-empty { display: none; }
    
    /* Hover effect (Opsional, agar user tahu bisa diklik) */
    .star-label:hover { color: #666; }

        /* Styling Pagination Monokrom */
    .pagination {
        justify-content: center;
        margin-top: 40px;
    }

    .page-item .page-link {
        color: #000;
        background-color: transparent;
        border: 1px solid #ddd;
        border-radius: 0 !important; /* Kotak */
        padding: 8px 16px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .page-item .page-link:hover {
        background-color: #f8f9fa;
        color: #000;
        border-color: #000;
    }

    .page-item.active .page-link {
        background-color: #000;
        border-color: #000;
        color: #fff;
    }

    .page-item.disabled .page-link {
        color: #999;
        background-color: transparent;
        border-color: #eee;
    }
    
    .page-link:focus {
        box-shadow: none;
    }

        /* --- Styling Utama List --- */
    .order-list-container {
        border: 1px solid #e0e0e0;
        background-color: #fff;
    }

    .order-list-header {
        display: flex;
        justify-content: space-between;
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
        background-color: #fff;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
    }

    .order-list-item {
        display: flex;
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        align-items: flex-start;
    }

    .order-list-item:last-child {
        border-bottom: none;
    }

    /* --- Kolom Layout --- */
    .col-thumb {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        margin-right: 20px;
    }

    .col-info {
        flex-grow: 1;
        padding-right: 15px;
        min-width: 0;
    }

    .col-total {
        width: 35%;
        text-align: right;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    /* --- Elemen --- */
    .order-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: 1px solid #eee;
    }

    .order-number {
        font-weight: bold;
        color: #000;
        font-size: 0.9rem;
        margin-bottom: 5px;
        display: block;
        word-break: break-all;
        line-height: 1.3;
    }

    .order-date {
        font-size: 0.75rem;
        color: #888;
        margin-bottom: 15px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .order-price {
        font-weight: bold;
        font-size: 0.95rem;
        color: #000;
        margin-bottom: 8px;
        display: block;
    }

    /* --- Badge Status Kustom --- */
    .badge-status-custom {
        display: inline-block;
        padding: 6px 12px;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-align: center;
        border-radius: 0;
        white-space: nowrap;
    }

    /* --- Tombol --- */
    .btn-action-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-custom-outline {
        border: 1px solid #ccc;
        background: transparent;
        color: #333;
        font-size: 0.65rem;
        padding: 6px 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-custom-outline:hover {
        border-color: #000;
        background: #000;
        color: #fff;
    }

    .btn-custom-black {
        border: 1px solid #000;
        background: #000;
        color: #fff;
        font-size: 0.65rem;
        padding: 6px 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .btn-custom-black:hover {
        background: #333;
        border-color: #333;
    }

    /* --- Responsive Mobile --- */
    @media (max-width: 576px) {
        .order-list-item { padding: 15px; }
        
        .col-thumb { 
            width: 60px; 
            height: 60px; 
            margin-right: 12px;
        }

        .col-info { 
            width: auto; 
            flex-grow: 1; 
            padding-right: 10px;
        }
        .col-total { 
            width: auto; 
            flex-shrink: 0;
        }
        
        .order-number { font-size: 0.8rem; }
        .order-date { font-size: 0.65rem; margin-bottom: 10px; }
        .order-price { font-size: 0.85rem; }
        
        .badge-status-custom {
            padding: 4px 8px;
            font-size: 0.6rem;
        }

        .btn-custom-outline, .btn-custom-black {
            padding: 5px 10px;
            font-size: 0.6rem;
        }
    }


            /* Font size lebih kecil untuk mobile */
            .table { font-size: 0.75rem; } 
            
            /* Header Tabel Mobile: Sembunyikan header yang kolomnya digabung */
            .mobile-hidden-header { display: none; }

            /* Styling Order Number agar bisa 2 baris (break-word) */
            .order-number-text {
                word-wrap: break-word;
                white-space: normal;
                font-weight: bold;
                display: block;
                line-height: 1.2;
                margin-bottom: 4px;
            }

            /* Styling Tanggal di bawah nomor pesanan */
            .date-text {
                display: block;
                color: #6c757d;
                font-size: 0.65rem;
            }

            /* Badge status agar tidak terlalu lebar */
            .badge-status {
                display: inline-block;
                margin-top: 4px;
                font-size: 0.6rem !important;
                padding: 3px 6px !important;
                white-space: normal;
                line-height: 1.1;
            }

            .product-list-item {
                display: flex;
                align-items: flex-start;
                padding-bottom: 1rem;
                margin-bottom: 1rem;
                border-bottom: 1px solid #f0f0f0;
            }
            
            .product-list-item:last-child {
                border-bottom: none;
                margin-bottom: 0;
                padding-bottom: 0;
            }

            .product-thumb {
                width: 70px;
                height: 70px;
                object-fit: cover;
                border: 1px solid #eee;
                flex-shrink: 0;
                margin-right: 15px;
            }

            .product-details {
                flex-grow: 1;
                padding-right: 10px;
            }

            .product-name {
                font-size: 0.85rem;
                font-weight: bold;
                text-transform: uppercase;
                line-height: 1.3;
                margin-bottom: 4px;
                display: block;
            }

            .product-meta {
                font-size: 0.75rem;
                color: #6c757d;
                line-height: 1.4;
            }

            .product-subtotal {
                text-align: right;
                font-weight: bold;
                font-size: 0.85rem;
                white-space: nowrap;
                flex-shrink: 0;
            }
        }

            /* Transisi halus untuk semua properti */
    .btn-shipping-method {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--color-black);
        color: var(--color-black);
        background-color: transparent;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    /* Efek Hover: Naik sedikit + Bayangan + Background abu tipis */
    .btn-shipping-method:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        background-color: #FAFAFA;
        border-color: var(--color-black);
        color: var(--color-black);
        z-index: 2;
    }

    /* Efek Klik (Active): Sedikit mengecil seolah ditekan */
    .btn-shipping-method:active {
        transform: scale(0.98) translateY(0);
        box-shadow: none;
    }

    /* Keadaan Terpilih (Checked): Hitam Solid */
    .btn-check:checked + .btn-shipping-method {
        background-color: var(--color-black);
        color: var(--color-white);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(0);
        z-index: 1;
    }

    /* Ikon di dalam tombol */
    .btn-shipping-method i {
        transition: transform 0.2s ease;
    }
    
    /* Ikon membesar sedikit saat hover */
    .btn-shipping-method:hover i {
        transform: scale(1.1);
    }

        /* Transisi halus untuk semua properti */
    .btn-shipping-method {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--color-black);
        color: var(--color-black);
        background-color: transparent;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    /* Efek Hover: Naik sedikit + Bayangan + Background abu tipis */
    .btn-shipping-method:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        background-color: #FAFAFA;
        border-color: var(--color-black);
        color: var(--color-black);
        z-index: 2;
    }

    /* Efek Klik (Active): Sedikit mengecil seolah ditekan */
    .btn-shipping-method:active {
        transform: scale(0.98) translateY(0);
        box-shadow: none;
    }

    /* Keadaan Terpilih (Checked): Hitam Solid */
    .btn-check:checked + .btn-shipping-method {
        background-color: var(--color-black);
        color: var(--color-white);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(0);
        z-index: 1;
    }

    /* Ikon di dalam tombol */
    .btn-shipping-method i {
        transition: transform 0.2s ease;
    }
    
    /* Ikon membesar sedikit saat hover */
    .btn-shipping-method:hover i {
        transform: scale(1.1);
    }

        /* --- Styling Utama List --- */
    .rent-list-container {
        border: 1px solid #e0e0e0;
        background-color: #fff;
    }

    .rent-list-header {
        display: flex;
        justify-content: space-between;
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
        background-color: #fff;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
    }

    .rent-list-item {
        display: flex;
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        align-items: flex-start;
    }

    .rent-list-item:last-child {
        border-bottom: none;
    }

    /* --- Kolom Layout --- */
    .col-thumb {
        width: 80px; /* Lebar default desktop */
        height: 80px; /* Tinggi fixed agar persegi */
        flex-shrink: 0;
        margin-right: 20px;
    }

    .col-info {
        flex-grow: 1;
        padding-right: 15px;
        min-width: 0; /* Mencegah overflow text */
    }

    .col-total {
        width: 35%;
        text-align: right;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    /* --- Elemen --- */
    .rent-img {
        width: 100%;
        height: 100%; /* Mengisi container */
        object-fit: cover;
        border: 1px solid #eee;
    }

    .rent-number {
        font-weight: bold;
        color: #000;
        font-size: 0.9rem;
        margin-bottom: 5px;
        display: block;
        word-break: break-word;
        line-height: 1.3;
    }

    .rent-date {
        font-size: 0.75rem;
        color: #888;
        margin-bottom: 15px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .rent-price {
        font-weight: bold;
        font-size: 0.95rem;
        color: #000;
        margin-bottom: 8px;
        display: block;
    }

    /* --- Badge Status Kustom --- */
    .badge-status-custom {
        display: inline-block;
        padding: 6px 12px;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-align: center;
        border-radius: 0;
        white-space: nowrap;
    }

    /* --- Tombol --- */
    .btn-action-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-custom-outline {
        border: 1px solid #ccc;
        background: transparent;
        color: #333;
        font-size: 0.65rem;
        padding: 6px 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-custom-outline:hover {
        border-color: #000;
        background: #000;
        color: #fff;
    }

    .btn-custom-black {
        border: 1px solid #000;
        background: #000;
        color: #fff;
        font-size: 0.65rem;
        padding: 6px 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .btn-custom-black:hover {
        background: #333;
        border-color: #333;
    }

    /* --- Responsive Mobile --- */
    @media (max-width: 576px) {
        .rent-list-item { padding: 15px; }
        
        /* Penyesuaian Gambar di Mobile */
        .col-thumb { 
            width: 60px;  /* Lebih kecil */
            height: 60px; 
            margin-right: 12px; /* Margin lebih kecil */
        }

        /* Penyesuaian Lebar Kolom di Mobile */
        .col-info { 
            width: auto; /* Reset width */
            flex-grow: 1; 
            padding-right: 10px;
        }
        .col-total { 
            width: auto; /* Reset width, biarkan konten menentukan lebar */
            flex-shrink: 0;
        }
        
        /* Font Size Mobile */
        .rent-number { font-size: 0.8rem; }
        .rent-date { font-size: 0.65rem; margin-bottom: 10px; }
        .rent-price { font-size: 0.85rem; }
        
        .badge-status-custom {
            padding: 4px 8px;
            font-size: 0.6rem;
        }

        .btn-custom-outline, .btn-custom-black {
            padding: 5px 10px;
            font-size: 0.6rem;
        }
    }

        /* Styling List View (Desktop & Mobile) */
    .category-list-header {
        display: flex;
        justify-content: space-between;
        padding: 15px 20px;
        background-color: #f9f9f9;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }

    .category-list-item {
        display: flex;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .category-list-item:hover {
        background-color: #fafafa;
    }

    .col-name { flex-grow: 1; padding-right: 15px; }
    .col-actions { flex-shrink: 0; }

    /* Tombol Aksi */
    .btn-action {
        font-size: 0.65rem;
        padding: 6px 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        font-weight: 600;
        margin-left: 5px;
    }

    .btn-edit { border: 1px solid #333; color: #333; background: transparent; }
    .btn-edit:hover { background: #333; color: #fff; }

    .btn-delete { border: 1px solid #dc3545; color: #dc3545; background: transparent; }
    .btn-delete:hover { background: #dc3545; color: #fff; }

    /* Mobile Responsive */
    @media (max-width: 576px) {
        .category-list-header { display: none; } /* Sembunyikan header tabel di HP */
        
        .category-list-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 15px;
        }

        .col-name { 
            width: 100%; 
            margin-bottom: 10px; 
            padding-right: 0;
        }

        .col-actions {
            width: 100%;
            display: flex;
            gap: 10px;
        }

        .btn-action {
            flex: 1; /* Tombol rata lebar di HP */
            justify-content: center;
            margin-left: 0;
            padding: 8px;
        }
    }

        /* Styling List View (Desktop & Mobile) */
    .discount-list-header {
        display: flex;
        padding: 15px 20px;
        background-color: #f9f9f9;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }

    .discount-list-item {
        display: flex;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .discount-list-item:hover {
        background-color: #fafafa;
    }

    /* Kolom Desktop */
    .col-name { width: 25%; padding-right: 15px; }
    .col-code { width: 15%; }
    .col-value { width: 15%; }
    .col-period { width: 20%; }
    .col-status { width: 10%; text-align: center; }
    .col-usage { width: 5%; text-align: center; }
    .col-actions { width: 10%; text-align: right; }

    /* Tombol Aksi */
    .btn-action {
        font-size: 0.65rem;
        padding: 6px 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        font-weight: 600;
        margin-left: 5px;
    }

    .btn-edit { border: 1px solid #333; color: #333; background: transparent; }
    .btn-edit:hover { background: #333; color: #fff; }

    .btn-delete { border: 1px solid #dc3545; color: #dc3545; background: transparent; }
    .btn-delete:hover { background: #dc3545; color: #fff; }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .discount-list-header { display: none; } /* Sembunyikan header di tablet/HP */
        
        .discount-list-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            position: relative;
        }

        .col-name, .col-code, .col-value, .col-period, .col-status, .col-usage, .col-actions {
            width: 100%;
            margin-bottom: 8px;
            text-align: left;
            padding-right: 0;
        }

        /* Penyesuaian Tampilan Mobile */
        .col-name { font-size: 1rem; margin-bottom: 5px; }
        .col-code { margin-bottom: 15px; }
        
        .col-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            justify-content: flex-start;
        }

        .btn-action {
            flex: 1;
            justify-content: center;
            margin-left: 0;
            padding: 10px;
        }
    }

        /* --- Styling List View (Desktop & Mobile) --- */
    .report-list-header {
        display: flex;
        padding: 15px 20px;
        background-color: #f9f9f9;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }

    .report-list-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .report-list-item:hover {
        background-color: #fafafa;
    }

    /* Kolom Desktop */
    .col-date { width: 15%; padding-right: 10px; }
    .col-ref { width: 20%; font-family: monospace; }
    .col-type { width: 15%; }
    .col-customer { width: 30%; }
    .col-amount { width: 20%; text-align: right; }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .report-list-header { display: none; }
        
        .report-list-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            position: relative;
        }

        .col-date, .col-ref, .col-type, .col-customer, .col-amount {
            width: 100%;
            margin-bottom: 5px;
            text-align: left;
            padding-right: 0;
        }

        /* Penyesuaian Mobile Layout */
        .col-date { font-size: 0.75rem; color: #888; order: 1; margin-bottom: 2px; }
        .col-ref { font-size: 0.9rem; font-weight: bold; order: 2; margin-bottom: 10px; }
        .col-type { position: absolute; top: 20px; right: 20px; width: auto; text-align: right; order: 3; }
        .col-customer { font-size: 0.85rem; order: 4; margin-bottom: 10px; }
        .col-amount { font-size: 1rem; font-weight: bold; order: 5; text-align: right; border-top: 1px dashed #eee; padding-top: 10px; margin-top: 5px; }
    }

        /* Styling List View (Desktop & Mobile) */
    .shift-list-header {
        display: flex;
        padding: 15px 20px;
        background-color: #f9f9f9;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }

    .shift-list-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .shift-list-item:hover {
        background-color: #fafafa;
    }

    /* Kolom Desktop */
    .col-date { width: 15%; font-weight: bold; }
    .col-branch { width: 25%; }
    .col-time { width: 20%; }
    .col-total { width: 15%; text-align: center; }
    .col-present { width: 15%; text-align: center; }
    .col-actions { width: 10%; text-align: right; }

    /* Tombol Aksi */
    .btn-detail {
        border: 1px solid #333;
        color: #333;
        background: transparent;
        font-size: 0.65rem;
        padding: 6px 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
    }

    .btn-detail:hover {
        background: #333;
        color: #fff;
    }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .shift-list-header { display: none; }
        
        .shift-list-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            position: relative;
        }

        .col-date, .col-branch, .col-time, .col-total, .col-present, .col-actions {
            width: 100%;
            margin-bottom: 8px;
            text-align: left;
            padding-right: 0;
        }

        /* Penyesuaian Mobile Layout */
        .col-date { font-size: 1rem; margin-bottom: 5px; order: 1; }
        .col-branch { order: 2; margin-bottom: 5px; }
        .col-time { font-size: 0.85rem; color: #555; order: 3; margin-bottom: 10px; }
        
        /* Gabungkan Total & Present di satu baris */
        .col-total, .col-present { 
            width: auto; 
            display: inline-block; 
            margin-right: 15px; 
            order: 4; 
            text-align: left;
            margin-bottom: 15px;
        }
        
        .col-actions {
            order: 5;
            display: flex;
            justify-content: flex-start;
        }

        .btn-detail {
            width: 100%;
            text-align: center;
            padding: 10px;
        }
    }

        /* Styling List View (Desktop & Mobile) */
    .shift-detail-header {
        display: flex;
        padding: 15px 20px;
        background-color: #f9f9f9;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }

    .shift-detail-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .shift-detail-item:hover {
        background-color: #fafafa;
    }

    /* Kolom Desktop */
    .col-name { width: 40%; }
    .col-nik { width: 30%; font-family: monospace; color: #555; }
    .col-status { width: 20%; text-align: center; }
    .col-actions { width: 10%; text-align: right; }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .shift-detail-header { display: none; }
        
        .shift-detail-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            position: relative;
        }

        .col-name, .col-nik, .col-status, .col-actions {
            width: 100%;
            margin-bottom: 8px;
            text-align: left;
            padding-right: 0;
        }

        /* Penyesuaian Mobile Layout */
        .col-name { font-size: 1rem; font-weight: bold; margin-bottom: 5px; order: 1; }
        .col-nik { font-size: 0.85rem; margin-bottom: 15px; order: 2; }
        .col-status { margin-bottom: 15px; order: 3; text-align: left; }
        
        .col-actions {
            order: 4;
            display: flex;
            justify-content: flex-start;
        }
    }

        /* Helper class untuk tombol aktif (hitam solid) */
    .btn-black-active {
        background-color: #000;
        color: #fff;
        border: 1px solid #000;
    }
    .btn-black-active:hover {
        background-color: #333;
        color: #fff;
    }

        .btn-toggle-attendance {
        width: 100%;
        max-width: 200px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
        transition: all 0.3s ease;
    }

    /* Styling List View (Desktop & Mobile) */
    .shift-edit-header {
        display: flex;
        padding: 15px 20px;
        background-color: #f9f9f9;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }

    .shift-edit-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .shift-edit-item:hover {
        background-color: #fafafa;
    }

    /* Kolom Desktop */
    .col-name { width: 60%; }
    .col-status { width: 40%; text-align: right; }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .shift-edit-header { display: none; }
        
        .shift-edit-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
        }

        .col-name { 
            width: 100%; 
            margin-bottom: 15px; 
        }

        .col-status {
            width: 100%;
            text-align: left;
        }

        .btn-toggle-attendance {
            max-width: 100%; /* Full width button on mobile */
        }
    }

        /* Helper class untuk tombol aktif (hitam solid) */
    .btn-black-active {
        background-color: #000;
        color: #fff;
        border: 1px solid #000;
    }
    .btn-black-active:hover {
        background-color: #333;
        color: #fff;
    }

        /* Styling Tombol Hadir/Tidak Hadir */
    .btn-toggle-attendance {
        width: 100%;
        max-width: 200px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
        transition: all 0.3s ease;
    }

    /* Styling List View (Desktop & Mobile) */
    .attendance-list-header {
        display: flex;
        padding: 15px 20px;
        background-color: #f9f9f9;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }

    .attendance-list-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .attendance-list-item:hover {
        background-color: #fafafa;
    }

    /* Kolom Desktop */
    .col-name { width: 60%; }
    .col-status { width: 40%; text-align: right; }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .attendance-list-header { display: none; }
        
        .attendance-list-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
        }

        .col-name { 
            width: 100%; 
            margin-bottom: 15px; 
        }

        .col-status {
            width: 100%;
            text-align: left;
        }

        .btn-toggle-attendance {
            max-width: 100%; /* Full width button on mobile */
        }
    }

        /* Styling List View (Desktop & Mobile) */
    .rent-list-header {
        display: flex;
        padding: 15px 20px;
        background-color: #f9f9f9;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }

    .rent-list-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .rent-list-item:hover {
        background-color: #fafafa;
    }

    /* --- PERBAIKAN JARAK KOLOM (DESKTOP) --- */
    .col-rent-no { width: 12%; font-family: monospace; font-weight: bold; }
    .col-customer { width: 18%; padding-right: 10px; }
    
    /* Kurangi lebar period sedikit */
    .col-period { width: 18%; padding-right: 10px; } 
    
    /* Perlebar area Total & beri padding kanan besar agar tidak mepet Status */
    .col-total { 
        width: 22%; 
        padding-right: 40px; /* Jarak aman ke status */
        font-weight: bold; 
    } 
    
    /* Lebar status disesuaikan */
    .col-status { width: 20%; } 
    
    .col-actions { width: 10%; text-align: right; }

    /* Tombol Aksi */
    .btn-detail {
        border: 1px solid #333;
        color: #333;
        background: transparent;
        font-size: 0.65rem;
        padding: 6px 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
    }

    .btn-detail:hover {
        background: #333;
        color: #fff;
    }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .rent-list-header { display: none; }
        
        .rent-list-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            position: relative;
        }

        .col-rent-no, .col-customer, .col-period, .col-total, .col-status, .col-actions {
            width: 100%;
            margin-bottom: 8px;
            text-align: left;
            padding-right: 0;
        }

        /* Penyesuaian Mobile Layout */
        .col-rent-no { font-size: 1rem; margin-bottom: 5px; order: 1; }
        .col-customer { font-size: 0.85rem; color: #555; margin-bottom: 10px; order: 2; }
        .col-period { font-size: 0.8rem; color: #777; margin-bottom: 10px; order: 3; }
        .col-total { font-weight: bold; font-size: 0.95rem; margin-bottom: 15px; order: 4; }
        .col-status { margin-bottom: 15px; order: 5; }
        
        .col-actions {
            order: 6;
            display: flex;
            justify-content: flex-start;
        }

        .btn-detail {
            width: 100%;
            text-align: center;
            padding: 10px;
        }
    }

        /* Styling List View (Desktop & Mobile) */
    .product-list-header {
        display: flex;
        padding: 15px 20px;
        background-color: #f9f9f9;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }

    .product-list-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .product-list-item:hover {
        background-color: #fafafa;
    }

    /* Kolom Desktop */
    .col-thumb {
    width: 80px;        /* Lebar tetap */
    height: 80px;       /* Tinggi tetap */
    flex-shrink: 0;     /* Jangan mengecil */
    margin-right: 20px;
    overflow: hidden;   /* Pastikan gambar tidak keluar */
    border: 1px solid #eee; /* Border di container */
}

.order-img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Crop gambar agar pas kotak */
    border: none;      /* Pindahkan border ke container */
    display: block;    /* Hilangkan gap bawah inline */
}
    .col-name { width: 25%; padding-right: 15px; }
    .col-category { width: 15%; }
    .col-price { width: 15%; }
    .col-stock { width: 10%; text-align: center; }
    .col-status { width: 15%; text-align: center; }
    .col-actions { width: 10%; text-align: right; }

    /* Elemen */
    .product-img {
        width: 60px; height: 60px; 
        object-fit: cover; 
        border: 1px solid #eee;
    }

    /* Tombol Aksi */
    .btn-action {
        font-size: 0.65rem;
        padding: 6px 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        font-weight: 600;
        margin-left: 5px;
    }

    .btn-edit { border: 1px solid #333; color: #333; background: transparent; }
    .btn-edit:hover { background: #333; color: #fff; }

    .btn-delete { border: 1px solid #dc3545; color: #dc3545; background: transparent; }
    .btn-delete:hover { background: #dc3545; color: #fff; }

    /* --- STYLING PAGINATION CUSTOM --- */
    .pagination {
        justify-content: center;
        margin-top: 20px;
    }

    .page-item .page-link {
        color: #000;              /* Teks Hitam */
        background-color: #fff;   /* Background Putih */
        border: 1px solid #e0e0e0;
        border-radius: 0 !important; /* Kotak (No Radius) */
        font-size: 0.8rem;
        padding: 8px 12px;
    }

    .page-item .page-link:hover {
        background-color: #f8f9fa; /* Abu sangat muda saat hover */
        color: #000;
        border-color: #000;
    }

    .page-item.active .page-link {
        background-color: #000;   /* Hitam saat aktif */
        border-color: #000;
        color: #fff;              /* Teks Putih */
    }

    .page-item.disabled .page-link {
        color: #ccc;
        background-color: #fff;
        border-color: #eee;
    }
    
    .page-link:focus {
        box-shadow: none; /* Hilangkan glow biru */
    }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .product-list-header { display: none; }
        
        .product-list-item {
            flex-wrap: wrap;
            padding: 15px;
            align-items: flex-start;
        }

        .col-thumb { margin-right: 15px; margin-bottom: 10px; }
        
        .col-name { 
            width: calc(100% - 95px); /* Sisa lebar setelah gambar */
            margin-bottom: 5px; 
            padding-right: 0;
        }

        .col-category, .col-price, .col-stock, .col-status {
            width: 50%; /* 2 Kolom per baris di HP */
            margin-bottom: 8px;
            text-align: left;
        }

        .col-actions {
            width: 100%;
            display: flex;
            gap: 10px;
            justify-content: flex-start;
            margin-top: 10px;
        }

        .btn-action {
            flex: 1;
            justify-content: center;
            margin-left: 0;
            padding: 8px;
        }
    }

        /* --- Styling List View (Desktop & Mobile) --- */
    .payment-list-container {
        overflow-x: auto; /* Agar bisa scroll samping di layar sempit */
    }

    .payment-list-header {
        display: flex;
        padding: 15px 20px;
        background-color: #f9f9f9;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
        min-width: 1000px; /* Minimal lebar agar kolom tidak mepet */
    }

    .payment-list-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
        min-width: 1000px; /* Samakan dengan header */
    }

    .payment-list-item:hover {
        background-color: #fafafa;
    }

    /* --- Pengaturan Lebar Kolom Desktop (Total 100%) --- */
    .col-payment-no { width: 12%; font-family: monospace; font-weight: bold; }
    .col-type       { width: 6%; }
    .col-ref        { width: 18%; font-family: monospace; padding-right: 15px; word-break: break-all; } /* Lebih lebar & break word */
    .col-method     { width: 16%; padding-right: 10px; } /* Lebih lebar */
    .col-amount     { width: 14%; font-weight: bold; }
    .col-payer      { width: 12%; padding-right: 10px; }
    .col-status     { width: 14%; }
    .col-actions    { width: 8%; text-align: right; }

    /* Tombol Aksi */
    .btn-proof {
        border: 1px solid #333;
        color: #333;
        background: transparent;
        font-size: 0.65rem;
        padding: 6px 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
    }

    .btn-proof:hover {
        background: #333;
        color: #fff;
    }

    /* --- Mobile Responsive (Max 992px) --- */
    @media (max-width: 992px) {
        .payment-list-header { display: none; }
        .payment-list-container { overflow-x: hidden; } /* Matikan scroll horizontal container di HP */
        
        .payment-list-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            position: relative;
            min-width: 0; /* Reset min-width di HP */
        }

        .col-payment-no, .col-type, .col-ref, .col-method, .col-amount, .col-payer, .col-status, .col-actions {
            width: 100%;
            margin-bottom: 8px;
            text-align: left;
            padding-right: 0;
        }

        /* Urutan Tampilan Mobile */
        .col-payment-no { font-size: 1rem; margin-bottom: 5px; order: 1; }
        .col-type       { order: 2; margin-bottom: 5px; }
        .col-ref        { font-size: 0.85rem; color: #555; order: 3; margin-bottom: 10px; }
        .col-amount     { font-size: 1rem; margin-bottom: 5px; order: 4; }
        .col-method     { font-size: 0.8rem; color: #777; margin-bottom: 10px; order: 5; }
        .col-payer      { font-size: 0.85rem; margin-bottom: 15px; order: 6; }
        .col-status     { margin-bottom: 15px; order: 7; }
        
        .col-actions {
            order: 8;
            display: flex;
            justify-content: flex-start;
        }

        .btn-proof {
            width: 100%;
            text-align: center;
            padding: 10px;
        }
    }

        /* Styling List View (Desktop & Mobile) */
    .employee-list-header {
        display: flex;
        padding: 15px 20px;
        background-color: #f9f9f9;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }

    .employee-list-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .employee-list-item:hover {
        background-color: #fafafa;
    }

    /* Kolom Desktop */
    .col-no { width: 5%; color: #888; }
    .col-nik { width: 15%; font-family: monospace; font-weight: bold; }
    .col-name { width: 25%; }
    .col-branch { width: 20%; }
    .col-phone { width: 15%; }
    .col-status { width: 10%; text-align: center; }
    .col-actions { width: 10%; text-align: right; }

    /* Tombol Aksi */
    .btn-action {
        font-size: 0.65rem;
        padding: 6px 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        font-weight: 600;
        margin-left: 5px;
    }

    .btn-edit { border: 1px solid #333; color: #333; background: transparent; }
    .btn-edit:hover { background: #333; color: #fff; }

    .btn-delete { border: 1px solid #dc3545; color: #dc3545; background: transparent; }
    .btn-delete:hover { background: #dc3545; color: #fff; }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .employee-list-header { display: none; }
        .col-no { display: none; } /* Sembunyikan nomor urut di HP */
        
        .employee-list-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            position: relative;
        }

        .col-nik, .col-name, .col-branch, .col-phone, .col-status, .col-actions {
            width: 100%;
            margin-bottom: 8px;
            text-align: left;
            padding-right: 0;
        }

        /* Penyesuaian Mobile Layout */
        .col-name { font-size: 1rem; font-weight: bold; margin-bottom: 5px; order: 1; }
        .col-branch { order: 2; margin-bottom: 10px; }
        .col-nik { font-size: 0.85rem; color: #555; order: 3; }
        .col-phone { font-size: 0.85rem; color: #555; order: 4; margin-bottom: 15px; }
        .col-status { order: 5; margin-bottom: 15px; text-align: left; }
        
        .col-actions {
            order: 6;
            display: flex;
            gap: 10px;
            justify-content: flex-start;
        }

        .btn-action {
            flex: 1;
            justify-content: center;
            margin-left: 0;
            padding: 10px;
        }
    }

    @media (min-width: 992px) {
    .navbar-collapse {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: auto;
    }
}

    /* Styling List View (Desktop & Mobile) */
    .order-list-header {
        display: flex;
        padding: 15px 20px;
        background-color: #f9f9f9;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e0e0e0;
    }

    .order-list-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .order-list-item:hover {
        background-color: #fafafa;
    }

    /* --- PERBAIKAN JARAK KOLOM (DESKTOP KONSISTEN) --- */
    .col-id { width: 12%; font-family: monospace; font-weight: bold; }
    .col-customer { width: 20%; padding-right: 10px; }
    
    /* Date diperkecil */
    .col-date { width: 15%; padding-right: 10px; } 
    
    /* Total diperlebar & padding kanan besar */
    .col-total { 
        width: 18%; 
        padding-right: 35px; /* Jarak aman ke status */
        font-weight: bold;
    } 
    
    .col-status { width: 20%; }
    .col-actions { width: 15%; text-align: right; }

    /* Tombol Aksi */
    .btn-detail {
        border: 1px solid #333;
        color: #333;
        background: transparent;
        font-size: 0.65rem;
        padding: 6px 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
    }

    .btn-detail:hover {
        background: #333;
        color: #fff;
    }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .order-list-header { display: none; }
        
        .order-list-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            position: relative;
        }

        .col-id, .col-customer, .col-date, .col-total, .col-status, .col-actions {
            width: 100%;
            margin-bottom: 8px;
            text-align: left;
            padding-right: 0;
        }

        /* Penyesuaian Mobile Layout */
        .col-id { font-size: 1rem; margin-bottom: 5px; order: 1; }
        .col-date { font-size: 0.75rem; color: #888; margin-bottom: 10px; order: 2; }
        .col-customer { font-size: 0.85rem; color: #000; margin-bottom: 5px; order: 3; font-weight: bold; }
        .col-total { font-weight: normal; font-size: 0.9rem; margin-bottom: 15px; order: 4; }
        .col-status { margin-bottom: 15px; order: 5; }
        
        .col-actions {
            order: 6;
            display: flex;
            justify-content: flex-start;
        }

        .btn-detail {
            width: 100%;
            text-align: center;
            padding: 10px;
        }
    }
    </style>
</head>
<body class="antialiased">

    @unless(request()->routeIs('select.branch') || request()->routeIs('branch.*'))
        @include('partials.navbar')
    @endunless

    <main class="flex-grow-1">
        <div class="container py-4 py-md-5">
            {{-- Global Alerts --}}
            @if(session('success'))
                <div class="alert shadow-sm mb-4 rounded-0 border-start border-5 border-success bg-white" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success fs-5 me-3"></i>
                        <div>
                            <span class="d-block text-uppercase small fw-bold text-success">Berhasil</span>
                            <span class="small text-muted">{{ session('success') }}</span>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert shadow-sm mb-4 rounded-0 border-start border-5 border-danger bg-white" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5 me-3"></i>
                        <div>
                            <span class="d-block text-uppercase small fw-bold text-danger">Perhatian</span>
                            <span class="small text-muted">{{ session('error') }}</span>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
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