@extends('layouts.dashmin')

@section('title', 'Daftar Karyawan Minimarket')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daftar Karyawan</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="fa fa-users text-primary me-2"></i>Daftar Karyawan Minimarket
            </h4>
            <p class="text-muted small mb-0">Pengelolaan Data Tim, Petugas Kasir, dan Staf Operasional Toko</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                <i class="fa fa-plus-circle me-1"></i>Tambah Karyawan
            </button>
            <a href="{{ url('/admin') }}" class="btn btn-outline-primary shadow-sm">
                <i class="fa fa-tachometer-alt me-1"></i>Ke Dashboard
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="bg-white rounded-3 p-3 shadow-sm border d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block">Total Karyawan</span>
                    <h4 class="fw-bold text-primary mb-0">12 Orang</h4>
                </div>
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                    <i class="fa fa-users fa-lg"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="bg-white rounded-3 p-3 shadow-sm border d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block">Petugas Kasir</span>
                    <h4 class="fw-bold text-success mb-0">5 Orang</h4>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                    <i class="fa fa-cash-register fa-lg"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="bg-white rounded-3 p-3 shadow-sm border d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block">Staf Gudang & Stok</span>
                    <h4 class="fw-bold text-warning mb-0">4 Orang</h4>
                </div>
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                    <i class="fa fa-boxes fa-lg"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="bg-white rounded-3 p-3 shadow-sm border d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block">Shift Aktif Hari Ini</span>
                    <h4 class="fw-bold text-info mb-0">8 Shift</h4>
                </div>
                <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info">
                    <i class="fa fa-clock fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card & Table -->
    <div class="bg-white rounded-3 p-4 shadow-sm border">
        <!-- Filter & Search Bar -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom">
            <h5 class="fw-bold text-dark mb-0">
                <i class="fa fa-address-book text-primary me-2"></i>Direktori Staf Toko
            </h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="Cari nama / NIK..." style="max-width: 220px;">
                <select class="form-select form-select-sm" style="max-width: 160px;">
                    <option value="">Semua Posisi</option>
                    <option value="kasir">Kasir</option>
                    <option value="gudang">Staf Gudang</option>
                    <option value="manager">Manager Toko</option>
                </select>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border">
                <thead class="text-white" style="background: linear-gradient(135deg, #009CFF 0%, #0072ff 100%);">
                    <tr>
                        <th scope="col" class="py-3 ps-3">Karyawan</th>
                        <th scope="col" class="py-3">Jabatan / Posisi</th>
                        <th scope="col" class="py-3">No. Kontak</th>
                        <th scope="col" class="py-3 text-center">Status Shift</th>
                        <th scope="col" class="py-3 text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-3">
                                <img class="rounded-circle border" src="{{ asset('dashmin/img/user.jpg') }}" alt="Avatar" style="width: 40px; height: 40px; object-fit: cover;">
                                <div>
                                    <strong class="d-block text-dark">Ainul Hakimah</strong>
                                    <small class="text-muted">NIK: EMP-001</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">Admin Utama</span></td>
                        <td class="text-muted"><i class="fa fa-phone me-1 text-primary"></i>0812-3456-7890</td>
                        <td class="text-center"><span class="badge bg-success px-3 py-1 rounded-pill">Aktif (Pagi)</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                    DJ
                                </div>
                                <div>
                                    <strong class="d-block text-dark">David Juli Ariyadi</strong>
                                    <small class="text-muted">NIK: EMP-002</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-info bg-opacity-10 text-info px-3 py-1 rounded-pill">Supervisor Kasir</span></td>
                        <td class="text-muted"><i class="fa fa-phone me-1 text-primary"></i>0857-9876-5432</td>
                        <td class="text-center"><span class="badge bg-success px-3 py-1 rounded-pill">Aktif (Siang)</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                    B
                                </div>
                                <div>
                                    <strong class="d-block text-dark">Budi Santoso</strong>
                                    <small class="text-muted">NIK: EMP-003</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-warning bg-opacity-10 text-dark px-3 py-1 rounded-pill">Staf Gudang</span></td>
                        <td class="text-muted"><i class="fa fa-phone me-1 text-primary"></i>0813-1122-3344</td>
                        <td class="text-center"><span class="badge bg-secondary px-3 py-1 rounded-pill">Off Shift</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
