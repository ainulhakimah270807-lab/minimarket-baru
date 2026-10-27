<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Praktikum Laravel - Minimarket POS')</title>
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --dark-bg: #0f172a;
            --card-border: rgba(226, 232, 240, 0.8);
        }

        body {
            background-color: #f1f5f9;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 14px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .navbar-custom .navbar-brand {
            font-size: 1.25rem;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #818cf8 0%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .navbar-custom .nav-link {
            color: #94a3b8;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .navbar-custom .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .navbar-custom .nav-link.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
        }

        .card-custom {
            background-color: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.05);
            margin-bottom: 24px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 35px -12px rgba(79, 70, 229, 0.12);
        }

        .card-custom .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 700;
            padding: 18px 24px;
            color: #0f172a;
        }

        .code-box {
            background-color: #0f172a;
            color: #38bdf8;
            padding: 16px 20px;
            border-radius: 12px;
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 13.5px;
            overflow-x: auto;
            margin-bottom: 14px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .result-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 14.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            border: none;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.5);
            transform: translateY(-1px);
        }

        footer {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fa-solid fa-cart-shopping me-2"></i>Minimarket POS
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2 align-items-center mt-3 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/admin') }}">
                            <i class="fa-solid fa-gauge-high me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('acara18*') || request()->is('manajemen-produk') ? 'active' : '' }}" href="{{ route('acara18.index') }}">
                            <i class="fa-solid fa-boxes-stacked me-1"></i>Manajemen Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('acara20*') || request()->is('tambah-produk') ? 'active' : '' }}" href="{{ route('acara20.index') }}">
                            <i class="fa-solid fa-circle-plus me-1"></i>Tambah Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('acara17*') || request()->is('laporan-stok') ? 'active' : '' }}" href="{{ route('acara17.index') }}">
                            <i class="fa-solid fa-chart-line me-1"></i>Laporan Stok
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('acara19*') ? 'active' : '' }}" href="{{ route('acara19.index') }}">
                            <i class="fa-solid fa-box-archive me-1"></i>Arsip & Kategori
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content Container -->
    <div class="container py-4">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="text-center py-4 text-muted border-top mt-5">
        <div class="container">
            <small class="fw-medium">&copy; {{ date('Y') }} Minimarket Baru &bull; Sistem Informasi Kasir POS Modern</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
