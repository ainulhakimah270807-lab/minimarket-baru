@extends('layouts.dashmin')

@section('title', 'Tambah Produk & Validasi - Minimarket POS')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Header Page & Breadcrumb -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('acara18.index') }}">Manajemen Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Produk</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark"><i class="fa fa-plus-circle text-primary me-2"></i>Tambah Produk & Validasi</h4>
            <p class="text-muted small mb-0">Simpan produk baru dengan Form Request dan pesan validasi berbahasa Indonesia.</p>
        </div>
        <div>
            <a href="{{ route('acara18.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="fa fa-boxes me-1"></i>Daftar Produk
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
        <div class="d-flex">
            <i class="fa fa-exclamation-triangle fs-5 me-2 mt-1"></i>
            <div>
                <strong>Periksa kembali data yang diisi:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    <!-- Content Row -->
    <div class="row g-4 align-items-start">
        <!-- Form Tambah Produk -->
        <div class="col-12 col-lg-7">
            <div class="bg-light rounded p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-box-open text-primary me-2"></i>Tambah Produk</h5>
                    <span class="badge bg-primary text-white px-2 py-1"><i class="fa fa-shield-alt me-1"></i>Form Request</span>
                </div>
                <form method="POST" action="{{ route('acara20.products.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-semibold text-dark">Kategori Produk <span class="text-danger">*</span></label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($categories->isEmpty())
                        <small class="text-danger d-block mt-1">Tambahkan kategori terlebih dahulu melalui form di samping.</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-dark">Nama Produk <span class="text-danger">*</span></label>
                        <input id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" maxlength="255" placeholder="Contoh: Beras Premium 5kg" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label for="sku" class="form-label fw-semibold text-dark mb-0">
                                SKU / Kode Barang <span class="text-danger">*</span>
                            </label>
                            <span class="badge bg-primary text-white" style="font-size: 11px;">
                                <i class="fa fa-magic me-1"></i>Otomatis
                            </span>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa fa-barcode text-muted"></i></span>
                            <input id="sku" name="sku" value="{{ old('sku', $generatedSku ?? '') }}" class="form-control @error('sku') is-invalid @enderror" maxlength="255" placeholder="Contoh: BRG-001" required>
                            <button type="button" class="btn btn-outline-primary" id="btn-generate-sku" title="Buat Kode Otomatis Baru">
                                <i class="fa fa-sync-alt me-1"></i>Generate Kode
                            </button>
                        </div>
                        @error('sku')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-1">Kode barang dibuat otomatis secara unik. Bisa diubah jika ada format khusus.</small>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label for="price" class="form-label fw-semibold text-dark">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">Rp</span>
                                <input id="price" name="price" type="number" min="0" step="1" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror" placeholder="68000" required>
                            </div>
                            @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label for="stock" class="form-label fw-semibold text-dark mb-0">Stok Awal <span class="text-danger">*</span></label>
                                <span id="stock-indicator" class="badge bg-secondary text-white" style="font-size: 11px;">Status Stok</span>
                            </div>
                            <input id="stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', 20) }}" class="form-control @error('stock') is-invalid @enderror" placeholder="20" required>
                            @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">
                                Ketentuan: ≤ 10 unit = <strong class="text-warning">Stok Menipis</strong>, > 10 unit = <strong class="text-success">Stok Aman</strong>
                            </small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary px-4 py-2" @disabled($categories->isEmpty())>
                        <i class="fa fa-check me-2"></i>Simpan Produk
                    </button>
                </form>
            </div>
        </div>

        <!-- Form Tambah Kategori & Daftar Kategori -->
        <div class="col-12 col-lg-5">
            <div class="bg-light rounded p-4 shadow-sm mb-4">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold text-nowrap"><i class="fa fa-tag text-primary me-2"></i>Tambah Kategori</h5>
                    <span class="badge bg-secondary text-white px-2 py-1"><i class="fa fa-folder-plus me-1"></i>Kategori</span>
                </div>
                <form method="POST" action="{{ route('acara20.categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="category_name" class="form-label fw-semibold text-dark">Nama Kategori <span class="text-danger">*</span></label>
                        <input id="category_name" name="category_name" value="{{ old('category_name') }}" class="form-control @error('category_name') is-invalid @enderror" maxlength="255" placeholder="Contoh: Makanan Ringan" required>
                        @error('category_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fa fa-plus me-1"></i>Simpan Kategori
                    </button>
                </form>
            </div>

            <div class="bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa fa-tags text-primary me-2"></i>Kategori Tersedia</h6>
                    <span class="badge bg-light text-dark border">{{ $categories->count() }} kategori</span>
                </div>
                <div class="d-flex flex-wrap gap-2 pt-2">
                    @forelse($categories as $category)
                    <span class="badge bg-white text-dark border px-3 py-2 fw-normal shadow-sm">
                        <i class="fa fa-check-circle text-success me-1"></i>{{ $category->name }}
                    </span>
                    @empty
                    <p class="text-muted small mb-0">Belum ada kategori terdaftar.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('category_id');
    const skuInput = document.getElementById('sku');
    const btnGenerate = document.getElementById('btn-generate-sku');
    const stockInput = document.getElementById('stock');
    const stockIndicator = document.getElementById('stock-indicator');

    function updateStockIndicator() {
        if (!stockInput || !stockIndicator) return;
        const val = parseInt(stockInput.value, 10);
        if (isNaN(val) || val <= 0) {
            stockIndicator.className = 'badge bg-danger text-white';
            stockIndicator.innerHTML = '<i class="fa fa-times-circle me-1"></i>Stok Habis (0)';
        } else if (val <= 10) {
            stockIndicator.className = 'badge bg-warning text-dark';
            stockIndicator.innerHTML = '<i class="fa fa-exclamation-triangle me-1"></i>Stok Menipis (' + val + ')';
        } else {
            stockIndicator.className = 'badge bg-success text-white';
            stockIndicator.innerHTML = '<i class="fa fa-check-circle me-1"></i>Stok Aman (' + val + ')';
        }
    }

    if (stockInput) {
        stockInput.addEventListener('input', updateStockIndicator);
        updateStockIndicator();
    }

    function fetchNewSku() {
        if (!btnGenerate || !skuInput) return;
        const categoryId = categorySelect ? categorySelect.value : '';
        btnGenerate.disabled = true;
        btnGenerate.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>...';

        fetch(`{{ route('acara20.generate-sku') }}?category_id=${categoryId}`)
            .then(res => res.json())
            .then(data => {
                if (data.sku) {
                    skuInput.value = data.sku;
                }
            })
            .catch(err => console.error('Error generate SKU:', err))
            .finally(() => {
                btnGenerate.disabled = false;
                btnGenerate.innerHTML = '<i class="fa fa-sync-alt me-1"></i>Generate Kode';
            });
    }

    if (btnGenerate) {
        btnGenerate.addEventListener('click', fetchNewSku);
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', function () {
            fetchNewSku();
        });
    }
});
</script>
@endpush
