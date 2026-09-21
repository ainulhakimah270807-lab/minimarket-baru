@extends('layouts.dashmin')

@section('title', 'Status Operasional Toko - Warkop Cak Kebo')
@section('page_title', 'Status Toko & Operasional')

@section('content')
<div class="warkop-dashboard-header">
    <div>
        <h1 class="warkop-page-title">Monitoring Status Toko</h1>
        <p class="warkop-page-subtitle">Sistem cek jam operasional dan kesiapan meja transaksi</p>
    </div>
    <div>
        <a href="{{ url('/admin') }}" class="warkop-btn-period">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Ke Dashboard</span>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="warkop-card">
            <h2 class="fs-6 fw-bold mb-3 text-dark">Status Operasional Saat Ini</h2>
            
            <p class="text-muted mb-3">
                Halo <strong class="text-dark">{{ $namaKasir }}</strong>, status toko pada jam <strong>{{ sprintf('%02d', $jam) }}:00 WIB</strong> adalah:
            </p>

            @if($isBuka)
                <div class="p-4 rounded-3 text-center mb-3" style="background-color: var(--pill-green-bg); border: 1px solid rgba(30, 130, 76, 0.2);">
                    <div class="fs-1 mb-2 text-success"><i class="fa-solid fa-circle-check"></i></div>
                    <h3 class="fw-bold mb-1" style="color: var(--pill-green-text);">TOKO BUKA</h3>
                    <p class="mb-0 small text-muted">Silakan kasir bersiap di meja transaksi dan melayani pesanan.</p>
                </div>
            @else
                <div class="p-4 rounded-3 text-center mb-3" style="background-color: var(--pill-red-bg); border: 1px solid rgba(214, 48, 49, 0.2);">
                    <div class="fs-1 mb-2 text-danger"><i class="fa-solid fa-circle-xmark"></i></div>
                    <h3 class="fw-bold mb-1" style="color: var(--pill-red-text);">TOKO TUTUP</h3>
                    <p class="mb-0 small text-muted">Di luar jam operasional (08:00 – 21:00 WIB).</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="warkop-card">
            <h2 class="fs-6 fw-bold mb-3 text-dark">Simulasi Jam Operasional</h2>
            <form method="GET" action="{{ url('/pos-status') }}">
                <div class="warkop-input-group">
                    <label class="warkop-input-label">Nama Kasir / Petugas</label>
                    <input type="text" name="nama" class="warkop-input" value="{{ $namaKasir }}" placeholder="Masukkan nama kasir" required>
                </div>

                <div class="warkop-input-group mb-4">
                    <label class="warkop-input-label">Pilih Jam (0 - 23 WIB)</label>
                    <input type="number" name="jam" class="warkop-input" min="0" max="23" value="{{ $jam }}" placeholder="Contoh: 10">
                </div>

                <button type="submit" class="warkop-btn-primary">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i>
                    <span>Cek Status Toko</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
