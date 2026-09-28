@extends('layouts.dashmin')

@section('title', 'Arsip & Kategori - Minimarket POS')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Header Page & Breadcrumb -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Arsip & Kategori</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark"><i class="fa fa-archive text-primary me-2"></i>Arsip & Kategori Produk</h4>
            <p class="text-muted small mb-0">Relasi kategori produk, pemantauan stok menipis, dan pemulihan data produk yang diarsipkan.</p>
        </div>
        <div>
            <a href="{{ route('acara20.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="fa fa-plus me-1"></i>Tambah Produk / Kategori
            </a>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fa fa-check-circle me-2"></i><strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <!-- Card 1: Kategori Produk -->
    <div class="bg-light rounded p-4 shadow-sm mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-tags text-primary me-2"></i>Kategori & Jumlah Produk Aktif</h5>
            <span class="badge bg-primary px-3 py-2 fs-7">{{ $categories->count() }} Kategori</span>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead class="table-white">
                    <tr class="text-dark">
                        <th>Nama Kategori</th>
                        <th>Slug URL</th>
                        <th class="text-end">Jumlah Produk Aktif</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="fw-semibold text-dark">{{ $category->name }}</td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td class="text-end">
                            <span class="badge bg-white text-dark border px-2 py-1">{{ $category->products_count }} produk</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Belum ada kategori. Buat kategori baru melalui menu Tambah Produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card 2: Stok Menipis -->
    <div class="bg-light rounded p-4 shadow-sm mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <div>
                <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-exclamation-triangle text-warning me-2"></i>Peringatan Stok Menipis (≤ 10 unit)</h5>
            </div>
            <a href="{{ route('acara17.index', ['stock' => 'low']) }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-external-link-alt me-1"></i>Lihat di Laporan
            </a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead class="table-white">
                    <tr class="text-dark">
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Sisa Stok</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockProducts as $product)
                    <tr>
                        <td class="fw-semibold text-dark">
                            {{ $product->name }} <small class="text-muted">({{ $product->sku }})</small>
                        </td>
                        <td>{{ $product->category?->name ?? '-' }}</td>
                        <td class="text-end">
                            <span class="badge bg-warning text-dark px-2 py-1 shadow-sm">
                                <i class="fa fa-exclamation-triangle me-1"></i>Stok Menipis ({{ $product->stock }} unit)
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('acara18.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-edit me-1"></i>Kelola
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Semua stok produk dalam kondisi aman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($lowStockProducts->hasPages())
        <div class="mt-4">
            {{ $lowStockProducts->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

    <!-- Card 3: Arsip Produk (Soft Delete) -->
    <div class="bg-light rounded p-4 shadow-sm">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-archive text-primary me-2"></i>Arsip Produk (Dapat Dipulihkan)</h5>
            <span class="badge bg-secondary px-3 py-2 fs-7">{{ $archivedProducts->total() }} Produk Diarsipkan</span>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead class="table-white">
                    <tr class="text-dark">
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Tanggal Diarsipkan</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archivedProducts as $product)
                    <tr>
                        <td class="fw-semibold text-dark">
                            {{ $product->name }} <small class="text-muted">({{ $product->sku }})</small>
                        </td>
                        <td>{{ $product->category?->name ?? '-' }}</td>
                        <td class="small text-muted">{{ $product->deleted_at?->format('d M Y H:i') }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('acara19.products.restore', $product->id) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-success" type="submit">
                                    <i class="fa fa-undo me-1"></i>Pulihkan
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Arsip produk masih kosong. Tidak ada produk yang dihapus.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($archivedProducts->hasPages())
        <div class="mt-4">
            {{ $archivedProducts->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
