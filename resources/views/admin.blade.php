@extends('layouts.dashmin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-3 px-md-4">
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-3 p-xl-4 shadow-sm h-100 border">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fa fa-boxes text-primary fs-3"></i>
                </div>
                <div class="text-end text-sm-start flex-grow-1 min-w-0">
                    <p class="mb-1 text-muted small text-truncate">Katalog Produk</p>
                    <h5 class="mb-0 fw-bold stat-card-val text-dark text-truncate">{{ \App\Models\Product::count() }} Item</h5>
                    <small class="text-success"><i class="fa fa-check-circle me-1"></i>Master Data Aktif</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-3 p-xl-4 shadow-sm h-100 border">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fa fa-coins text-success fs-3"></i>
                </div>
                <div class="text-end text-sm-start flex-grow-1 min-w-0">
                    <p class="mb-1 text-muted small text-truncate">Total Valuasi Aset</p>
                    <h5 class="mb-0 fw-bold stat-card-val text-dark text-truncate">Rp {{ number_format(\App\Models\Product::selectRaw('SUM(price * stock) as t')->value('t') ?? 0, 0, ',', '.') }}</h5>
                    <small class="text-primary"><i class="fa fa-warehouse me-1"></i>Inventaris Toko</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-3 p-xl-4 shadow-sm h-100 border">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fa fa-exclamation-triangle text-warning fs-3"></i>
                </div>
                <div class="text-end text-sm-start flex-grow-1 min-w-0">
                    <p class="mb-1 text-muted small text-truncate">Stok Kritis (≤ 10)</p>
                    <h5 class="mb-0 fw-bold stat-card-val text-danger text-truncate">{{ \App\Models\Product::where('stock', '<=', 10)->count() }} Item</h5>
                    <small class="text-danger"><i class="fa fa-bell me-1"></i>Perlu Restock Segera</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-3 p-xl-4 shadow-sm h-100 border">
                <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fa fa-users text-info fs-3"></i>
                </div>
                <div class="text-end text-sm-start flex-grow-1 min-w-0">
                    <p class="mb-1 text-muted small text-truncate">Staf & Karyawan</p>
                    <h5 class="mb-0 fw-bold stat-card-val text-dark text-truncate">{{ \App\Models\Employee::count() }} Petugas</h5>
                    <small class="text-muted"><i class="fa fa-id-badge me-1"></i>Operasional Toko</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Hub: Terhubung Langsung ke Semua Fitur -->
    <div class="bg-white rounded-4 p-4 shadow-sm border mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
            <div>
                <h5 class="fw-bold text-dark mb-1"><i class="fa fa-th text-primary me-2"></i>Pusat Layanan Terpadu Minimarket</h5>
                <p class="text-muted small mb-0">Akses cepat ke seluruh modul operasional toko, manajemen berkas, laporan, dan layanan sistem.</p>
            </div>
            <span class="badge bg-primary px-3 py-2">Sistem Aktif</span>
        </div>
        <div class="row g-3">
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('kasir.transaksi') }}" class="card p-3 border text-center text-decoration-none h-100 rounded-3 shadow-none bg-light text-dark hover-lift">
                    <div class="mx-auto p-3 bg-primary bg-opacity-10 text-primary rounded-circle mb-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-cash-register fs-5"></i>
                    </div>
                    <strong class="d-block small text-dark">Kasir POS</strong>
                    <span class="text-muted" style="font-size: 11px;">Transaksi Penjualan</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('acara18.index') }}" class="card p-3 border text-center text-decoration-none h-100 rounded-3 shadow-none bg-light text-dark hover-lift">
                    <div class="mx-auto p-3 bg-success bg-opacity-10 text-success rounded-circle mb-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-boxes fs-5"></i>
                    </div>
                    <strong class="d-block small text-dark">Katalog Produk</strong>
                    <span class="text-muted" style="font-size: 11px;">Kelola Data Barang</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('acara20.index') }}" class="card p-3 border text-center text-decoration-none h-100 rounded-3 shadow-none bg-light text-dark hover-lift">
                    <div class="mx-auto p-3 bg-info bg-opacity-10 text-info rounded-circle mb-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-plus-circle fs-5"></i>
                    </div>
                    <strong class="d-block small text-dark">Tambah Produk</strong>
                    <span class="text-muted" style="font-size: 11px;">Auto SKU & Kategori</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('acara17.index') }}" class="card p-3 border text-center text-decoration-none h-100 rounded-3 shadow-none bg-light text-dark hover-lift">
                    <div class="mx-auto p-3 bg-warning bg-opacity-10 text-warning rounded-circle mb-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-chart-line fs-5"></i>
                    </div>
                    <strong class="d-block small text-dark">Rekap Stok</strong>
                    <span class="text-muted" style="font-size: 11px;">Valuasi Aset Toko</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('acara30.index') }}" class="card p-3 border text-center text-decoration-none h-100 rounded-3 shadow-none bg-light text-dark hover-lift">
                    <div class="mx-auto p-3 bg-danger bg-opacity-10 text-danger rounded-circle mb-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-file-pdf fs-5"></i>
                    </div>
                    <strong class="d-block small text-dark">Cetak PDF</strong>
                    <span class="text-muted" style="font-size: 11px;">Laporan Resmi Toko</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('acara31.index') }}" class="card p-3 border text-center text-decoration-none h-100 rounded-3 shadow-none bg-light text-dark hover-lift">
                    <div class="mx-auto p-3 bg-primary bg-opacity-10 text-primary rounded-circle mb-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-image fs-5"></i>
                    </div>
                    <strong class="d-block small text-dark">Olah Foto</strong>
                    <span class="text-muted" style="font-size: 11px;">Resize & Watermark</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('acara29.index') }}" class="card p-3 border text-center text-decoration-none h-100 rounded-3 shadow-none bg-light text-dark hover-lift">
                    <div class="mx-auto p-3 bg-secondary bg-opacity-10 text-secondary rounded-circle mb-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-cloud-upload-alt fs-5"></i>
                    </div>
                    <strong class="d-block small text-dark">File Storage</strong>
                    <span class="text-muted" style="font-size: 11px;">Penyimpanan Berkas</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('acara32.index') }}" class="card p-3 border text-center text-decoration-none h-100 rounded-3 shadow-none bg-light text-dark hover-lift">
                    <div class="mx-auto p-3 bg-success bg-opacity-10 text-success rounded-circle mb-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-envelope fs-5"></i>
                    </div>
                    <strong class="d-block small text-dark">Layanan Email</strong>
                    <span class="text-muted" style="font-size: 11px;">Notifikasi & Antrean</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('acara24.index') }}" class="card p-3 border text-center text-decoration-none h-100 rounded-3 shadow-none bg-light text-dark hover-lift">
                    <div class="mx-auto p-3 bg-dark bg-opacity-10 text-dark rounded-circle mb-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-user-shield fs-5"></i>
                    </div>
                    <strong class="d-block small text-dark">Hak Akses</strong>
                    <span class="text-muted" style="font-size: 11px;">Gate & User Policy</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ url('/employee') }}" class="card p-3 border text-center text-decoration-none h-100 rounded-3 shadow-none bg-light text-dark hover-lift">
                    <div class="mx-auto p-3 bg-info bg-opacity-10 text-info rounded-circle mb-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-users fs-5"></i>
                    </div>
                    <strong class="d-block small text-dark">Data Staf</strong>
                    <span class="text-muted" style="font-size: 11px;">Manajemen Karyawan</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('acara19.index') }}" class="card p-3 border text-center text-decoration-none h-100 rounded-3 shadow-none bg-light text-dark hover-lift">
                    <div class="mx-auto p-3 bg-secondary bg-opacity-10 text-secondary rounded-circle mb-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-archive fs-5"></i>
                    </div>
                    <strong class="d-block small text-dark">Arsip & Kategori</strong>
                    <span class="text-muted" style="font-size: 11px;">Restore Data</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('acara23.profile') }}" class="card p-3 border text-center text-decoration-none h-100 rounded-3 shadow-none bg-light text-dark hover-lift">
                    <div class="mx-auto p-3 bg-primary bg-opacity-10 text-primary rounded-circle mb-2" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-id-card fs-5"></i>
                    </div>
                    <strong class="d-block small text-dark">Profil Akun</strong>
                    <span class="text-muted" style="font-size: 11px;">Status & Keamanan</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Sale & Revenue End -->

<!-- Sales Chart Start -->
<div class="container-fluid pt-4 px-3 px-md-4">
    <div class="row g-3 g-md-4">
        <div class="col-12 col-lg-6">
            <div class="bg-light text-center rounded p-3 p-md-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa fa-chart-line text-primary me-2"></i>Grafik Penjualan Mingguan</h6>
                    <a href="#" class="text-primary text-decoration-none small fw-semibold">Lihat Detail</a>
                </div>
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="worldwide-sales"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="bg-light text-center rounded p-3 p-md-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa fa-chart-pie text-success me-2"></i>Trend Omset & Laba</h6>
                    <a href="#" class="text-primary text-decoration-none small fw-semibold">Lihat Detail</a>
                </div>
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="salse-revenue"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Sales Chart End -->

<!-- Recent Sales Start -->
<div class="container-fluid pt-4 px-3 px-md-4">
    <div class="bg-light rounded p-3 p-md-4 shadow-sm">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
            <h6 class="mb-0 fw-bold text-dark"><i class="fa fa-receipt text-primary me-2"></i>Transaksi Terakhir (Minimarket)</h6>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('kasir.transaksi') }}" class="btn btn-sm btn-success shadow-sm"><i class="fa fa-cash-register me-1"></i>Transaksi Kasir Baru</a>
                <a href="{{ route('acara18.index') }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-boxes me-1"></i>Kelola Produk</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-dark">
                        <th scope="col" style="width: 40px;"><input class="form-check-input" type="checkbox"></th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">No. Nota</th>
                        <th scope="col">Pelanggan</th>
                        <th scope="col">Total</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>{{ date('d M Y') }}</td>
                        <td><code class="text-primary fw-bold">POS-{{ rand(1000, 9999) }}</code></td>
                        <td>Budi Santoso</td>
                        <td class="fw-bold">Rp 45.000</td>
                        <td><span class="badge bg-success">Lunas</span></td>
                        <td class="text-center"><a class="btn btn-sm btn-primary py-1 px-3" href="#"><i class="fa fa-eye me-1"></i>Detail</a></td>
                    </tr>
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>{{ date('d M Y') }}</td>
                        <td><code class="text-primary fw-bold">POS-{{ rand(1000, 9999) }}</code></td>
                        <td>Siti Rahma</td>
                        <td class="fw-bold">Rp 120.000</td>
                        <td><span class="badge bg-success">Lunas</span></td>
                        <td class="text-center"><a class="btn btn-sm btn-primary py-1 px-3" href="#"><i class="fa fa-eye me-1"></i>Detail</a></td>
                    </tr>
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>{{ date('d M Y') }}</td>
                        <td><code class="text-primary fw-bold">POS-{{ rand(1000, 9999) }}</code></td>
                        <td>Ahmad Fauzi</td>
                        <td class="fw-bold">Rp 78.500</td>
                        <td><span class="badge bg-success">Lunas</span></td>
                        <td class="text-center"><a class="btn btn-sm btn-primary py-1 px-3" href="#"><i class="fa fa-eye me-1"></i>Detail</a></td>
                    </tr>
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>{{ date('d M Y') }}</td>
                        <td><code class="text-primary fw-bold">POS-{{ rand(1000, 9999) }}</code></td>
                        <td>Dewi Lestari</td>
                        <td class="fw-bold">Rp 215.000</td>
                        <td><span class="badge bg-success">Lunas</span></td>
                        <td class="text-center"><a class="btn btn-sm btn-primary py-1 px-3" href="#"><i class="fa fa-eye me-1"></i>Detail</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Recent Sales End -->

<!-- Widgets Start -->
<div class="container-fluid pt-4 px-3 px-md-4 mb-4">
    <div class="row g-3 g-md-4">
        <!-- Messages -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="h-100 bg-light rounded p-3 p-md-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa fa-bell text-warning me-2"></i>Pemberitahuan Kasir</h6>
                    <a href="#" class="small text-primary fw-semibold">Lihat Semua</a>
                </div>
                <div class="d-flex align-items-center border-bottom py-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 flex-shrink-0 text-center" style="width: 40px; height: 40px;">
                        <i class="fa fa-user text-primary"></i>
                    </div>
                    <div class="w-100 min-w-0">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold text-truncate" style="font-size: 0.9rem;">Kasir 1</h6>
                            <small class="text-muted flex-shrink-0" style="font-size: 0.75rem;">10m lalu</small>
                        </div>
                        <small class="text-muted text-truncate d-block">Stok minyak goreng tersisa 5 pcs.</small>
                    </div>
                </div>
                <div class="d-flex align-items-center pt-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3 flex-shrink-0 text-center" style="width: 40px; height: 40px;">
                        <i class="fa fa-user-check text-success"></i>
                    </div>
                    <div class="w-100 min-w-0">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold text-truncate" style="font-size: 0.9rem;">Kasir 2</h6>
                            <small class="text-muted flex-shrink-0" style="font-size: 0.75rem;">30m lalu</small>
                        </div>
                        <small class="text-muted text-truncate d-block">Tutup shift siang selesai dihitung.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="h-100 bg-light rounded p-3 p-md-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa fa-calendar-alt text-info me-2"></i>Kalender Kerja</h6>
                </div>
                <div id="calender" class="w-100"></div>
            </div>
        </div>

        <!-- To Do List -->
        <div class="col-12 col-md-12 col-lg-4">
            <div class="h-100 bg-light rounded p-3 p-md-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa fa-tasks text-success me-2"></i>Catatan Kasir / Admin</h6>
                </div>
                <div class="d-flex mb-3">
                    <input class="form-control form-control-sm bg-white" type="text" placeholder="Tambah tugas baru...">
                    <button type="button" class="btn btn-sm btn-primary ms-2 flex-shrink-0"><i class="fa fa-plus"></i></button>
                </div>
                <div class="d-flex align-items-center border-bottom py-2">
                    <input class="form-check-input m-0 flex-shrink-0" type="checkbox">
                    <div class="w-100 ms-3 min-w-0">
                        <span class="small text-truncate d-block">Cek stok kadaluarsa produk susu</span>
                    </div>
                </div>
                <div class="d-flex align-items-center border-bottom py-2">
                    <input class="form-check-input m-0 flex-shrink-0" type="checkbox" checked>
                    <div class="w-100 ms-3 min-w-0">
                        <span class="small text-muted text-decoration-line-through text-truncate d-block">Update harga promo mingguan</span>
                    </div>
                </div>
                <div class="d-flex align-items-center pt-2">
                    <input class="form-check-input m-0 flex-shrink-0" type="checkbox">
                    <div class="w-100 ms-3 min-w-0">
                        <span class="small text-truncate d-block">Rekap laporan penjualan harian</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Widgets End -->
@endsection
