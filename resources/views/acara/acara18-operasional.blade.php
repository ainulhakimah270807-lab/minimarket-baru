@extends('layouts.dashmin')

@section('title', 'Manajemen Produk - Minimarket POS')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Header Page & Breadcrumb -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Produk</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark"><i class="fa fa-boxes text-primary me-2"></i>Manajemen & Inventaris Produk</h4>
            <p class="text-muted small mb-0">Kelola daftar produk minimarket, perbarui data harga/stok, atau arsipkan produk.</p>
        </div>
        <div>
            <a href="{{ route('acara20.index') }}" class="btn btn-primary shadow-sm">
                <i class="fa fa-plus me-1"></i>Tambah Produk
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

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-light rounded p-4 shadow-sm">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-list text-primary me-2"></i>Daftar Inventaris Produk</h5>
            <span class="badge bg-primary px-3 py-2 fs-7">{{ $products->total() }} Produk Terdaftar</span>
        </div>

        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead class="table-white">
                    <tr class="text-dark">
                        <th>Produk</th>
                        <th>SKU</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center" style="min-width: 170px;">Status & Stok</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="fw-semibold text-dark">{{ $product->name }}</td>
                        <td><span class="badge bg-white text-dark border">{{ $product->sku }}</span></td>
                        <td>{{ $product->category?->name ?? '-' }}</td>
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
                        <td class="text-center text-nowrap">
                            <details class="d-inline text-start">
                                <summary class="btn btn-sm btn-outline-primary"><i class="fa fa-edit me-1"></i>Ubah</summary>
                                <form class="mt-3 p-3 border rounded bg-white shadow-sm" method="POST" action="{{ route('acara18.products.update', $product) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-2">
                                        <label class="form-label small fw-semibold" for="name-{{ $product->id }}">Nama Produk</label>
                                        <input id="name-{{ $product->id }}" class="form-control form-control-sm" name="name" value="{{ $product->name }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-semibold" for="sku-{{ $product->id }}">SKU</label>
                                        <input id="sku-{{ $product->id }}" class="form-control form-control-sm" name="sku" value="{{ $product->sku }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-semibold" for="category-{{ $product->id }}">Kategori</label>
                                        <select id="category-{{ $product->id }}" class="form-select form-select-sm" name="category_id" required>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @selected($product->category_id === $category->id)>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col">
                                            <label class="form-label small fw-semibold" for="price-{{ $product->id }}">Harga</label>
                                            <input id="price-{{ $product->id }}" class="form-control form-control-sm" type="number" min="0" name="price" value="{{ $product->price }}" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label small fw-semibold" for="stock-{{ $product->id }}">Stok</label>
                                            <input id="stock-{{ $product->id }}" class="form-control form-control-sm" type="number" min="0" name="stock" value="{{ $product->stock }}" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-sm w-100" type="submit"><i class="fa fa-save me-1"></i>Simpan Perubahan</button>
                                </form>
                            </details>
                            <form class="d-inline" method="POST" action="{{ route('acara18.products.destroy', $product) }}" onsubmit="return confirm('Arsipkan produk ini? Produk masih bisa dipulihkan.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger ms-1" type="submit" title="Arsipkan produk">
                                    <i class="fa fa-archive"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada produk. Tambahkan produk untuk mulai mengelola inventaris.</td>
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
