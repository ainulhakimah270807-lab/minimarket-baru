@extends('layouts.dashmin')

@section('title', 'Status Toko & Operasional - Minimarket POS')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Header Page & Breadcrumb -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Status Toko (POS)</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark"><i class="fa fa-store text-primary me-2"></i>Monitoring Status Toko</h4>
            <p class="text-muted small mb-0">Sistem monitoring jam operasional dan kesiapan meja kasir transaksi minimarket.</p>
        </div>
        <div>
            <a href="{{ url('/admin') }}" class="btn btn-outline-primary shadow-sm">
                <i class="fa fa-tachometer-alt me-1"></i>Ke Dashboard
            </a>
        </div>
    </div>

    <!-- Status Content Row -->
    <div class="row g-4 align-items-stretch">
        <!-- Card 1: Status Operasional Saat Ini -->
        <div class="col-12 col-lg-6">
            <div class="bg-light rounded p-4 shadow-sm h-100 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-clock text-primary me-2"></i>Status Operasional Saat Ini</h5>
                    <span class="badge bg-primary text-white px-2 py-1"><i class="fa fa-satellite-dish me-1"></i>Live Status</span>
                </div>

                <p class="text-muted mb-3">
                    Halo <strong class="text-dark">{{ $namaKasir }}</strong>, status operasional toko pada jam <strong>{{ sprintf('%02d', $jam) }}:00 WIB</strong> adalah:
                </p>

                @if($isBuka)
                <div class="p-4 rounded text-center my-auto border shadow-sm" style="background-color: #d1e7dd; border-color: #badbcc !important;">
                    <div class="text-success mb-2" style="font-size: 3rem;">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-1">TOKO BUKA</h3>
                    <p class="text-success mb-0 small">
                        Pukul <strong>{{ sprintf('%02d', $jam) }}:00 WIB</strong> berada dalam jam operasional minimarket (08:00 – 21:00 WIB). Kasir bersiap di meja kasir dan melayani transaksi.
                    </p>
                </div>
                @else
                <div class="p-4 rounded text-center my-auto border shadow-sm" style="background-color: #f8d7da; border-color: #f5c2c7 !important;">
                    <div class="text-danger mb-2" style="font-size: 3rem;">
                        <i class="fa fa-times-circle"></i>
                    </div>
                    <h3 class="fw-bold text-danger mb-1">TOKO TUTUP</h3>
                    <p class="text-danger mb-0 small">
                        Pukul <strong>{{ sprintf('%02d', $jam) }}:00 WIB</strong> berada di luar jam operasional minimarket (08:00 – 21:00 WIB). Meja kasir ditutup dan tidak melayani transaksi.
                    </p>
                </div>
                @endif

                <div class="mt-4 pt-3 border-top">
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="bg-white p-2 rounded border">
                                <small class="text-muted d-block" style="font-size: 11px;">Jam Operasional</small>
                                <strong class="text-dark small">08:00 - 21:00</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-white p-2 rounded border">
                                <small class="text-muted d-block" style="font-size: 11px;">Petugas Kasir</small>
                                <strong class="text-dark small text-truncate d-block">{{ $namaKasir }}</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-white p-2 rounded border">
                                <small class="text-muted d-block" style="font-size: 11px;">Jam Cek</small>
                                <strong class="text-dark small">{{ sprintf('%02d', $jam) }}:00 WIB</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Simulasi Jam Operasional -->
        <div class="col-12 col-lg-6">
            <div class="bg-light rounded p-4 shadow-sm h-100 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-sliders-h text-primary me-2"></i>Simulasi Jam Operasional</h5>
                    <span class="badge bg-secondary text-white px-2 py-1"><i class="fa fa-user-clock me-1"></i>Uji Shift</span>
                </div>

                <form method="GET" action="{{ url('/pos-status') }}" class="d-flex flex-column h-100">
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold text-dark">Nama Kasir / Petugas</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa fa-user text-muted"></i></span>
                            <input type="text" name="nama" id="nama" class="form-control" value="{{ $namaKasir }}" placeholder="Masukkan nama kasir" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="jam" class="form-label fw-semibold text-dark">Pilih Jam Simulasi (0 - 23 WIB)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa fa-clock text-muted"></i></span>
                            <input type="number" name="jam" id="jam" class="form-control" min="0" max="23" value="{{ $jam }}" placeholder="Contoh: 10" required>
                            <span class="input-group-text bg-white">:00 WIB</span>
                        </div>
                        <div class="form-text mt-1 text-muted">
                            <i class="fa fa-info-circle me-1"></i>Status otomatis <strong>BUKA</strong> pada rentang pukul <strong>08:00 – 21:00 WIB</strong>.
                        </div>
                    </div>

                    <div class="mt-auto pt-3">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 shadow-sm">
                                <i class="fa fa-history me-2"></i>Cek Status Toko
                            </button>
                            <a href="{{ url('/pos-status') }}" class="btn btn-outline-secondary py-2">
                                <i class="fa fa-sync-alt me-2"></i>Reset ke Jam Default
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
