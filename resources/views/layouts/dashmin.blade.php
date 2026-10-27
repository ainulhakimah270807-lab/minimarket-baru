<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Laravel') }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link href="{{ asset('dashmin/img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('dashmin/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashmin/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('dashmin/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('dashmin/css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="{{ url('/admin') }}" class="navbar-brand mx-4 mb-3">
                    <h4 class="text-primary fw-bold mb-0"><i class="fa fa-store me-2"></i>MINIMARKET POS</h4>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle border border-2 border-primary" src="{{ asset('dashmin/img/user.jpg') }}" alt="" style="width: 42px; height: 42px; object-fit: cover;">
                        <div class="bg-success rounded-circle border border-2 border-dark position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3 min-w-0">
                        <h6 class="mb-0 text-white fw-bold text-truncate" style="font-size: 0.95rem;">{{ Auth::user()?->name ?? 'Ainul Hakimah' }}</h6>
                        <span class="badge bg-primary bg-opacity-20 text-info border border-info border-opacity-30 px-2 py-0" style="font-size: 0.72rem;">{{ ucfirst(Auth::user()?->role ?? 'Admin') }}</span>
                    </div>
                </div>

                <div class="navbar-nav w-100 px-2">
                    <div class="px-3 pt-2 pb-1 text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Menu Utama</div>
                    <a href="{{ url('/admin') }}" class="nav-item nav-link {{ request()->is('admin') || request()->is('dashboard') ? 'active' : '' }}">
                        <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('kasir.transaksi') }}" class="nav-item nav-link {{ request()->is('kasir*') ? 'active' : '' }}">
                        <i class="fa fa-cash-register me-2"></i>Kasir POS
                    </a>
                    <a href="{{ url('/pos-status') }}" class="nav-item nav-link {{ request()->is('pos-status*') ? 'active' : '' }}">
                        <i class="fa fa-store me-2"></i>Status Toko
                    </a>

                    <!-- Modul Produk & Inventaris -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle {{ request()->is('acara17*') || request()->is('acara18*') || request()->is('acara19*') || request()->is('acara20*') || request()->is('acara31*') || request()->is('produk-toko*') || request()->is('manajemen-produk*') || request()->is('tambah-produk*') || request()->is('laporan-stok*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                            <i class="fa fa-boxes me-2"></i>Produk & Inventaris
                        </a>
                        <div class="dropdown-menu bg-transparent border-0 ps-3">
                            <a href="{{ route('acara18.index') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('acara18*') || request()->is('manajemen-produk') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-list me-2"></i>Katalog Produk
                            </a>
                            <a href="{{ route('acara20.index') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('acara20*') || request()->is('tambah-produk') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-plus-circle me-2"></i>Tambah Produk
                            </a>
                            <a href="{{ route('acara17.index') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('acara17*') || request()->is('laporan-stok') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-chart-line me-2"></i>Laporan Stok Toko
                            </a>
                            <a href="{{ route('acara19.index') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('acara19*') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-archive me-2"></i>Kategori & Arsip
                            </a>
                            <a href="{{ route('acara31.index') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('acara31*') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-image me-2"></i>Edit Foto Produk
                            </a>
                            <a href="{{ url('/produk-toko') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('produk-toko*') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-shopping-basket me-2"></i>Katalog Belanja
                            </a>
                        </div>
                    </div>

                    <!-- Modul Laporan & Dokumen -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle {{ request()->is('acara29*') || request()->is('acara30*') || request()->is('laporan-pdf*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                            <i class="fa fa-file-alt me-2"></i>Laporan & Dokumen
                        </a>
                        <div class="dropdown-menu bg-transparent border-0 ps-3">
                            <a href="{{ route('acara30.index') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('acara30*') || request()->is('laporan-pdf*') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-file-pdf me-2"></i>Cetak Laporan PDF
                            </a>
                            <a href="{{ route('acara29.index') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('acara29*') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-cloud-upload-alt me-2"></i>Penyimpanan Berkas
                            </a>
                        </div>
                    </div>

                    <!-- Modul Pengaturan & Layanan -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle {{ request()->is('posts*') || request()->is('acara23*') || request()->is('acara24*') || request()->is('acara32*') || request()->is('employee*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                            <i class="fa fa-cogs me-2"></i>Pengaturan & Layanan
                        </a>
                        <div class="dropdown-menu bg-transparent border-0 ps-3">
                            <a href="{{ route('acara23.profile') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('acara23*') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-id-card me-2"></i>Profil Pengguna
                            </a>
                            <a href="{{ route('acara24.index') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('acara24*') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-user-shield me-2"></i>Hak Akses User
                            </a>
                            <a href="{{ url('/employee') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('employee*') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-users me-2"></i>Data Karyawan
                            </a>
                            <a href="{{ route('acara32.index') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('acara32*') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-envelope me-2"></i>Layanan Email
                            </a>
                            <a href="{{ route('posts.index') }}" class="dropdown-item py-2 text-slate-300 {{ request()->is('posts*') ? 'active text-white fw-bold' : '' }}">
                                <i class="fa fa-newspaper me-2"></i>Artikel & Berita
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-white navbar-light sticky-top px-4 py-2 border-bottom shadow-sm">
                <a href="{{ url('/admin') }}" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-store"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0 text-primary me-3">
                    <i class="fa fa-bars fs-5"></i>
                </a>
                <div class="d-none d-md-flex align-items-center position-relative" style="width: 280px;">
                    <i class="fa fa-search position-absolute start-0 ms-3 text-muted"></i>
                    <input class="form-control ps-5 rounded-pill bg-light border-0 small" type="search" placeholder="Cari data atau menu...">
                </div>
                <div class="navbar-nav align-items-center ms-auto">
                    <!-- Notifications Dropdown -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notifikasi</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0 shadow-sm">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Selamat datang di Minimarket</h6>
                                <small class="text-muted">Sistem Aktif</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">Semua Notifikasi</a>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="{{ asset('dashmin/img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">{{ Auth::user()?->name ?? 'Ainul Hakimah' }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0 shadow-sm">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fa fa-user me-2"></i>My Profile</a>
                            <a href="{{ url('/') }}" class="dropdown-item" target="_blank"><i class="fa fa-globe me-2"></i>Lihat Website</a>
                            <div class="dropdown-divider"></div>
                            <!-- Logout Form Breeze -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fa fa-sign-out-alt me-2"></i>Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->

            <!-- Main Content Start -->
            @yield('content')
            <!-- Main Content End -->

            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; {{ date('Y') }} <a href="{{ url('/') }}">{{ config('app.name', 'Minimarket Baru') }}</a>, All Right Reserved.
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            Template by <a href="https://htmlcodex.com">HTML Codex</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('dashmin/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('dashmin/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('dashmin/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('dashmin/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('dashmin/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('dashmin/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('dashmin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('dashmin/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>
