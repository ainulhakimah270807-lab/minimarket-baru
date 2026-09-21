@extends('layouts.dashmin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fa fa-chart-line fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-muted">Penjualan Hari Ini</p>
                    <h5 class="mb-0">Rp 1.250.000</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fa fa-chart-bar fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-muted">Total Penjualan</p>
                    <h5 class="mb-0">Rp 24.800.000</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fa fa-chart-area fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-muted">Pendapatan Hari Ini</p>
                    <h5 class="mb-0">Rp 850.000</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fa fa-boxes fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-muted">Total Transaksi</p>
                    <h5 class="mb-0">142 Pesanan</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Sale & Revenue End -->

<!-- Sales Chart Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light text-center rounded p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Grafik Penjualan Mingguan</h6>
                    <a href="#" class="text-primary text-decoration-none small">Lihat Detail</a>
                </div>
                <canvas id="worldwide-sales"></canvas>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light text-center rounded p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Trend Omset & Laba</h6>
                    <a href="#" class="text-primary text-decoration-none small">Lihat Detail</a>
                </div>
                <canvas id="salse-revenue"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- Sales Chart End -->

<!-- Recent Sales Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4 shadow-sm">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Transaksi Terakhir (Minimarket)</h6>
            <a href="{{ url('/posts') }}" class="btn btn-sm btn-outline-primary">Lihat Produk</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-dark table-secondary">
                        <th scope="col"><input class="form-check-input" type="checkbox"></th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">No. Nota</th>
                        <th scope="col">Pelanggan</th>
                        <th scope="col">Total</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>{{ date('d M Y') }}</td>
                        <td>POS-{{ rand(1000, 9999) }}</td>
                        <td>Budi Santoso</td>
                        <td>Rp 45.000</td>
                        <td><span class="badge bg-success">Lunas</span></td>
                        <td><a class="btn btn-sm btn-primary" href="#">Detail</a></td>
                    </tr>
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>{{ date('d M Y') }}</td>
                        <td>POS-{{ rand(1000, 9999) }}</td>
                        <td>Siti Rahma</td>
                        <td>Rp 120.000</td>
                        <td><span class="badge bg-success">Lunas</span></td>
                        <td><a class="btn btn-sm btn-primary" href="#">Detail</a></td>
                    </tr>
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>{{ date('d M Y') }}</td>
                        <td>POS-{{ rand(1000, 9999) }}</td>
                        <td>Ahmad Fauzi</td>
                        <td>Rp 78.500</td>
                        <td><span class="badge bg-success">Lunas</span></td>
                        <td><a class="btn btn-sm btn-primary" href="#">Detail</a></td>
                    </tr>
                    <tr>
                        <td><input class="form-check-input" type="checkbox"></td>
                        <td>{{ date('d M Y') }}</td>
                        <td>POS-{{ rand(1000, 9999) }}</td>
                        <td>Dewi Lestari</td>
                        <td>Rp 215.000</td>
                        <td><span class="badge bg-success">Lunas</span></td>
                        <td><a class="btn btn-sm btn-primary" href="#">Detail</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Recent Sales End -->

<!-- Widgets Start -->
<div class="container-fluid pt-4 px-4 mb-4">
    <div class="row g-4">
        <!-- Messages -->
        <div class="col-sm-12 col-md-6 col-xl-4">
            <div class="h-100 bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="mb-0">Pemberitahuan Kasir</h6>
                    <a href="#" class="small text-primary">Lihat Semua</a>
                </div>
                <div class="d-flex align-items-center border-bottom py-3">
                    <img class="rounded-circle flex-shrink-0" src="{{ asset('dashmin/img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0">Kasir 1</h6>
                            <small class="text-muted">10 menit lalu</small>
                        </div>
                        <small class="text-muted">Stok minyak goreng tersisa 5 pcs.</small>
                    </div>
                </div>
                <div class="d-flex align-items-center pt-3">
                    <img class="rounded-circle flex-shrink-0" src="{{ asset('dashmin/img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0">Kasir 2</h6>
                            <small class="text-muted">30 menit lalu</small>
                        </div>
                        <small class="text-muted">Tutup shift siang selesai dihitung.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar -->
        <div class="col-sm-12 col-md-6 col-xl-4">
            <div class="h-100 bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Kalender Kerja</h6>
                </div>
                <div id="calender"></div>
            </div>
        </div>

        <!-- To Do List -->
        <div class="col-sm-12 col-md-6 col-xl-4">
            <div class="h-100 bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Catatan Kasir / Admin</h6>
                </div>
                <div class="d-flex mb-2">
                    <input class="form-control bg-white" type="text" placeholder="Tambah tugas baru...">
                    <button type="button" class="btn btn-primary ms-2">Tambah</button>
                </div>
                <div class="d-flex align-items-center border-bottom py-2">
                    <input class="form-check-input m-0" type="checkbox">
                    <div class="w-100 ms-3">
                        <span>Cek stok kadaluarsa produk susu</span>
                    </div>
                </div>
                <div class="d-flex align-items-center border-bottom py-2">
                    <input class="form-check-input m-0" type="checkbox" checked>
                    <div class="w-100 ms-3">
                        <span><del>Update harga promo mingguan</del></span>
                    </div>
                </div>
                <div class="d-flex align-items-center pt-2">
                    <input class="form-check-input m-0" type="checkbox">
                    <div class="w-100 ms-3">
                        <span>Rekap laporan penjualan harian</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Widgets End -->
@endsection
