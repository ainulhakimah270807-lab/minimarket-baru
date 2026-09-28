<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Praktikum Laravel - Minggu 5')</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .card-custom .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #edf2f7;
            font-weight: 600;
            padding: 16px 20px;
        }
        .code-box {
            background-color: #1e1e1e;
            color: #d4d4d4;
            padding: 14px 18px;
            border-radius: 8px;
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 13.5px;
            overflow-x: auto;
            margin-bottom: 12px;
        }
        .result-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 18px;
            font-size: 14px;
        }
        .badge-method {
            font-size: 11px;
            padding: 5px 9px;
            border-radius: 6px;
        }
        .nav-pills .nav-link.active {
            background-color: #1e3c72;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-shop me-2"></i>Minimarket POS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-1">
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ url('/admin') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('acara18*') ? 'active fw-bold text-white' : '' }}" href="{{ route('acara18.index') }}">
                            <i class="bi bi-box-seam me-1"></i>Manajemen Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('acara20*') ? 'active fw-bold text-white' : '' }}" href="{{ route('acara20.index') }}">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('acara17*') ? 'active fw-bold text-white' : '' }}" href="{{ route('acara17.index') }}">
                            <i class="bi bi-bar-chart me-1"></i>Laporan Stok
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('acara19*') ? 'active fw-bold text-white' : '' }}" href="{{ route('acara19.index') }}">
                            <i class="bi bi-archive me-1"></i>Arsip & Kategori
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
    <footer class="text-center py-4 text-muted border-top bg-white mt-5">
        <div class="container">
            <small>Workshop Sistem Informasi Web Framework &bull; D4 Teknik Informatika &bull; Politeknik Negeri Jember</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
