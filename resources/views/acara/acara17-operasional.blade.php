@extends('layouts.dashmin')

@section('title', 'Laporan Stok - Minimarket POS')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Header Page & Breadcrumb -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Laporan Stok</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark"><i class="fa fa-chart-bar text-primary me-2"></i>Laporan Stok & Inventaris</h4>
            <p class="text-muted small mb-0">Pencarian produk, filter kategori, dan ringkasan nilai aset stok barang.</p>
        </div>
        <div>
            <a href="{{ route('acara18.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="fa fa-boxes me-1"></i>Manajemen Produk
            </a>
        </div>
    </div>

    <!-- Summary Metrics Cards (Dashmin Style) -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fa fa-box fa-3x text-primary"></i>
                <div class="ms-3 text-end">
                    <p class="mb-1 text-muted small">Produk Aktif</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($summary->product_count) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fa fa-cubes fa-3x text-primary"></i>
                <div class="ms-3 text-end">
                    <p class="mb-1 text-muted small">Total Unit Stok</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($summary->total_stock) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fa fa-money-bill-wave fa-3x text-primary"></i>
                <div class="ms-3 text-end">
                    <p class="mb-1 text-muted small">Nilai Aset Stok</p>
                    <h5 class="mb-0 fw-bold text-success">Rp {{ number_format($summary->stock_value, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 shadow-sm">
                <i class="fa fa-exclamation-triangle fa-3x text-warning"></i>
                <div class="ms-3 text-end">
                    <p class="mb-1 text-muted small">Stok Menipis (≤ 10)</p>
                    <h4 class="mb-0 fw-bold text-warning">{{ number_format($lowStockCount) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-light rounded p-4 shadow-sm mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
            <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-search text-primary me-2"></i>Filter & Penelusuran Produk</h5>
        </div>
        <form method="GET" action="{{ route('acara17.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="search" class="form-label small fw-semibold text-dark">Cari Nama atau SKU</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                    <input id="search" name="search" value="{{ $search }}" class="form-control" placeholder="Contoh: Beras atau BRG-001">
                </div>
            </div>
            <div class="col-md-3">
                <label for="category_id" class="form-label small fw-semibold text-dark">Kategori</label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="stock" class="form-label small fw-semibold text-dark">Status Stok</label>
                <select id="stock" name="stock" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="low" @selected($stockFilter === 'low')>Stok Menipis (≤ 10)</option>
                    <option value="safe" @selected($stockFilter === 'safe')>Stok Aman / Tidak Menipis (> 10)</option>
                    <option value="empty" @selected($stockFilter === 'empty')>Stok Habis (0)</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit" title="Terapkan Filter">
                    <i class="fa fa-filter me-1"></i>Filter
                </button>
                <a class="btn btn-outline-secondary" href="{{ route('acara17.index') }}" title="Reset Filter">
                    <i class="fa fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-light rounded p-4 shadow-sm">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-table text-primary me-2"></i>Hasil Laporan Stok</h5>
            <span class="badge bg-primary px-3 py-2 fs-7">{{ $products->total() }} Produk Ditemukan</span>
        </div>

        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead class="table-white">
                    <tr class="text-dark">
                        <th>Produk</th>
                        <th>SKU</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center" style="min-width: 170px;">Status & Jumlah Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="fw-semibold text-dark">{{ $product->name }}</td>
                        <td><span class="badge bg-white text-dark border">{{ $product->sku }}</span></td>
                        <td>{{ $product->category_name }}</td>
                        <td class="text-end fw-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($product->stock == 0)
                                <span class="badge bg-danger px-2 py-1 shadow-sm">
                                    <i class="fa fa-times-circle me-1"></i>Stok Habis (0 unit)
                                </span>
                            @elseif($product->stock <= 10)
                                <span class="badge bg-warning text-dark px-2 py-1 shadow-sm">
                                    <i class="fa fa-exclamation-triangle me-1"></i>Stok Menipis ({{ $product->stock }} unit)
                                </span>
                            @else
                                <span class="badge bg-success px-2 py-1 shadow-sm">
                                    <i class="fa fa-check-circle me-1"></i>Stok Aman ({{ $product->stock }} unit)
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data produk yang cocok dengan kriteria filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
