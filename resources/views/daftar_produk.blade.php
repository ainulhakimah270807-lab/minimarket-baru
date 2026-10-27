@extends('layouts.dashmin')

@section('title', 'Katalog Produk Toko Kelontong')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Katalog Produk</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="fa fa-shopping-basket text-primary me-2"></i>Katalog Produk Toko Kelontong
            </h4>
            <p class="text-muted small mb-0">Daftar Komoditas Utama dan Produk Unggulan Toko Minimarket</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('kasir.transaksi') }}" class="btn btn-success shadow-sm">
                <i class="fa fa-cash-register me-1"></i>Ke Kasir (POS)
            </a>
            <a href="{{ url('/admin') }}" class="btn btn-outline-primary shadow-sm">
                <i class="fa fa-tachometer-alt me-1"></i>Ke Dashboard
            </a>
        </div>
    </div>

    <!-- Product Cards & Table Grid -->
    <div class="bg-white rounded-3 p-4 shadow-sm border">
        <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary">
                    <i class="fa fa-boxes"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0">Daftar Produk Siap Jual</h5>
            </div>
            <span class="badge bg-primary px-3 py-1 rounded-pill">Total: {{ count($produk) }} Barang</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border">
                <thead class="text-white" style="background: linear-gradient(135deg, #009CFF 0%, #0072ff 100%);">
                    <tr>
                        <th scope="col" class="py-3 text-center" style="width: 60px;">No</th>
                        <th scope="col" class="py-3">Nama Produk</th>
                        <th scope="col" class="py-3">Kode / SKU</th>
                        <th scope="col" class="py-3">Harga Jual</th>
                        <th scope="col" class="py-3 text-center">Status Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produk as $index => $item)
                    <tr>
                        <td class="text-center fw-bold text-secondary">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                                    <i class="fa fa-box"></i>
                                </div>
                                <span class="fw-bold text-dark">{{ $item['nama'] }}</span>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border font-monospace">{{ $item['sku'] }}</span></td>
                        <td><strong class="text-primary fs-6">Rp {{ number_format($item['harga'], 0, ',', '.') }}</strong></td>
                        <td class="text-center">
                            @if($item['stok'] > 10)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill">
                                    <i class="fa fa-check-circle me-1"></i>Tersedia ({{ $item['stok'] }} Pcs)
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-1 rounded-pill">
                                    <i class="fa fa-exclamation-triangle me-1"></i>Terbatas ({{ $item['stok'] }} Pcs)
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
