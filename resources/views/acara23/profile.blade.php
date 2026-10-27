@extends('layouts.dashmin')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Header Page & Breadcrumb -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profil Pengguna</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="fa fa-user-circle text-primary me-2"></i>Profil Pengguna
            </h4>
            <p class="text-muted small mb-0">Kelola informasi identitas akun, status otorisasi peran, dan keamanan sesi login Anda.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ url('/admin') }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="fa fa-tachometer-alt me-1"></i>Ke Dashboard
            </a>
            @if(Auth::check())
            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm px-3">
                <i class="fa fa-user-edit me-1"></i>Edit Profil
            </a>
            @endif
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fa fa-check-circle me-2"></i><strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Profile Main Card -->
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                <!-- Cover Banner Header -->
                <div class="position-relative p-4 p-md-5 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #4f46e5 100%);">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4 position-relative" style="z-index: 2;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative">
                                <img class="rounded-circle border border-3 border-white shadow" src="{{ asset('dashmin/img/user.jpg') }}" alt="Foto Profil" style="width: 80px; height: 80px; object-fit: cover;">
                                @if(Auth::check())
                                <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle p-2" title="Online"></span>
                                @else
                                <span class="position-absolute bottom-0 end-0 bg-warning border border-2 border-white rounded-circle p-2" title="Guest"></span>
                                @endif
                            </div>
                            <div>
                                @if(Auth::check())
                                    <h4 class="fw-bold text-white mb-1">Welcome, {{ Auth::user()?->name }} 👋</h4>
                                    <p class="text-white text-opacity-75 mb-2 small">{{ Auth::user()?->email }}</p>
                                    <span class="badge bg-white text-primary fw-bold px-3 py-1 rounded-pill shadow-sm">
                                        <i class="fa fa-shield-alt me-1"></i>Status: Terautentikasi ({{ ucfirst(Auth::user()?->role ?? 'Admin') }})
                                    </span>
                                @else
                                    <h4 class="fw-bold text-white mb-1">Tamu (Guest)</h4>
                                    <p class="text-white text-opacity-75 mb-2 small">Sesi belum login pada sistem</p>
                                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill shadow-sm">
                                        <i class="fa fa-user-clock me-1"></i>Status: Guest (Belum Login)
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            @if(Auth::check())
                            <form action="{{ route('acara23.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-light btn-sm px-4 py-2 text-danger fw-bold rounded-pill shadow-sm">
                                    <i class="fa fa-sign-out-alt me-1"></i>Keluar Akun
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-light btn-sm px-4 py-2 text-primary fw-bold rounded-pill shadow-sm">
                                <i class="fa fa-sign-in-alt me-1"></i>Login Sekarang
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4 p-md-5">
                    @if(Auth::check())
                        <!-- Authenticated View Details -->
                        <h6 class="fw-bold text-dark text-uppercase mb-3" style="letter-spacing: 0.5px;">
                            <i class="fa fa-id-card text-primary me-2"></i>Rincian Informasi Akun
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <small class="text-muted d-block mb-1">ID Pengguna</small>
                                    <strong class="text-dark fs-5">#{{ $userId ?? Auth::id() }}</strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <small class="text-muted d-block mb-1">Alamat Email</small>
                                    <strong class="text-dark text-truncate d-block">{{ $user?->email ?? Auth::user()?->email }}</strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <small class="text-muted d-block mb-1">Peran Akses</small>
                                    <span class="badge bg-primary px-3 py-1 rounded-pill">{{ ucfirst($user?->role ?? Auth::user()?->role ?? 'User') }}</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <small class="text-muted d-block mb-1">Status Sesi</small>
                                    <span class="badge bg-success px-3 py-1 rounded-pill">Sesi Aktif</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Navigation Tiles -->
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <a href="{{ route('kasir.transaksi') }}" class="card p-3 border text-decoration-none h-100 text-dark bg-white rounded-3 shadow-none border-hover">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle">
                                            <i class="fa fa-cash-register fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Kasir Transaksi</h6>
                                            <small class="text-muted">Buka aplikasi kasir POS</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-12 col-md-4">
                                <a href="{{ route('acara18.index') }}" class="card p-3 border text-decoration-none h-100 text-dark bg-white rounded-3 shadow-none border-hover">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                                            <i class="fa fa-boxes fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Katalog Produk</h6>
                                            <small class="text-muted">Kelola stok dan harga</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-12 col-md-4">
                                <a href="{{ route('acara30.index') }}" class="card p-3 border text-decoration-none h-100 text-dark bg-white rounded-3 shadow-none border-hover">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-3 bg-info bg-opacity-10 text-info rounded-circle">
                                            <i class="fa fa-file-pdf fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Cetak Laporan PDF</h6>
                                            <small class="text-muted">Unduh rekapitulasi data</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                    @else
                        <!-- Guest View: Clean, Well-Structured Guidance -->
                        <div class="row g-4 align-items-center">
                            <div class="col-12 col-lg-7">
                                <div class="pe-lg-3">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill mb-2 fw-semibold">
                                        <i class="fa fa-info-circle me-1"></i>Informasi Akses
                                    </span>
                                    <h4 class="fw-bold text-dark mb-2">Selamat Datang di Sistem Minimarket POS</h4>
                                    <p class="text-muted mb-4" style="line-height: 1.7;">
                                        Anda saat ini sedang menjelajah dalam mode <strong>Tamu (Guest)</strong>. Silakan login terlebih dahulu untuk mengakses fitur penuh sistem seperti kasir pembayaran, manajemen produk, upload berkas, dan laporan keuangan.
                                    </p>

                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <a href="{{ route('login') }}" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
                                            <i class="fa fa-sign-in-alt me-2"></i>Login Sekarang
                                        </a>
                                        <a href="{{ route('register') }}" class="btn btn-outline-primary px-4 py-2 rounded-3">
                                            <i class="fa fa-user-plus me-2"></i>Daftar Akun Baru
                                        </a>
                                    </div>
                                    <small class="text-muted d-block">
                                        <i class="fa fa-shield-alt text-success me-1"></i> Akses dilindungi dengan otentikasi sesi terenkripsi.
                                    </small>
                                </div>
                            </div>

                            <div class="col-12 col-lg-5">
                                <div class="p-4 bg-light rounded-4 border">
                                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                        <h6 class="fw-bold text-dark mb-0">
                                            <i class="fa fa-key text-warning me-2"></i>Akun Uji Coba (Demo)
                                        </h6>
                                        <span class="badge bg-secondary">Tersedia</span>
                                    </div>
                                    <p class="small text-muted mb-3">Gunakan salah satu kredensial berikut untuk menguji sistem langsung:</p>
                                    
                                    <div class="bg-white p-3 rounded-3 border mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge bg-primary">Administrator</span>
                                            <small class="text-muted">Akses Penuh</small>
                                        </div>
                                        <div class="small text-dark font-monospace">admin@minimarket.test</div>
                                        <div class="small text-muted font-monospace">Password: password</div>
                                    </div>

                                    <div class="bg-white p-3 rounded-3 border">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge bg-success">Kasir Toko</span>
                                            <small class="text-muted">POS & Penjualan</small>
                                        </div>
                                        <div class="small text-dark font-monospace">kasir@minimarket.test</div>
                                        <div class="small text-muted font-monospace">Password: password</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
