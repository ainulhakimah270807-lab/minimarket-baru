@extends('layouts.dashmin')

@section('title', 'Cetak Laporan PDF')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Header Page & Breadcrumb -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cetak Laporan PDF</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark"><i class="fa fa-file-pdf text-danger me-2"></i>Cetak Laporan PDF</h4>
            <p class="text-muted small mb-0">Cetak dan unduh laporan stok, katalog produk, dan data kepegawaian toko dalam format PDF.</p>
        </div>
        <div>
            <span class="badge bg-danger px-3 py-2"><i class="fa fa-file-pdf me-1"></i>DomPDF</span>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fa fa-check-circle me-2"></i><strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fa fa-exclamation-triangle me-2"></i><strong>Gagal!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <div class="d-flex">
            <i class="fa fa-exclamation-triangle fs-5 me-2 mt-1"></i>
            <div>
                <strong>Periksa kembali data:</strong>
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

    <!-- ============================================ -->
    <!-- BAGIAN 1: GENERATE PDF REPORT -->
    <!-- ============================================ -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-file-pdf text-danger me-2"></i>Generate Laporan PDF (DomPDF)</h5>
                    <span class="badge bg-danger text-white px-2 py-1"><i class="fa fa-download me-1"></i>Download / Stream</span>
                </div>

                <div class="row g-3">
                    <!-- Laporan Produk -->
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-4 bg-white h-100 shadow-sm">
                            <div class="d-flex align-items-center mb-3">
                                <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle" style="width:50px; height:50px; min-width:50px;">
                                    <i class="fa fa-boxes text-primary fs-4"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-bold text-dark mb-0">Laporan Produk</h6>
                                    <small class="text-muted">{{ $products->count() }} produk tersedia</small>
                                </div>
                            </div>
                            <p class="text-muted small">Laporan lengkap daftar semua produk beserta kategori, harga, dan status stok.</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('acara30.pdf.produk') }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-download me-1"></i>Download
                                </a>
                                <a href="{{ route('acara30.pdf.produk.stream') }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="fa fa-eye me-1"></i>Lihat di Browser
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Laporan Stok -->
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-4 bg-white h-100 shadow-sm">
                            <div class="d-flex align-items-center mb-3">
                                <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle" style="width:50px; height:50px; min-width:50px;">
                                    <i class="fa fa-chart-bar text-success fs-4"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-bold text-dark mb-0">Laporan Stok</h6>
                                    <small class="text-muted">Format Landscape A4</small>
                                </div>
                            </div>
                            <p class="text-muted small">Laporan stok barang dengan peringatan produk stok rendah (≤ 10 unit). Layout landscape.</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('acara30.pdf.stok') }}" class="btn btn-success btn-sm">
                                    <i class="fa fa-download me-1"></i>Download
                                </a>
                                <a href="{{ route('acara30.pdf.stok.stream') }}" class="btn btn-outline-success btn-sm" target="_blank">
                                    <i class="fa fa-eye me-1"></i>Lihat di Browser
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Laporan Pegawai -->
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-4 bg-white h-100 shadow-sm">
                            <div class="d-flex align-items-center mb-3">
                                <div class="d-flex align-items-center justify-content-center bg-purple bg-opacity-10 rounded-circle" style="width:50px; height:50px; min-width:50px; background: rgba(111,66,193,0.1);">
                                    <i class="fa fa-users" style="color: #6f42c1; font-size: 1.3rem;"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-bold text-dark mb-0">Laporan Pegawai</h6>
                                    <small class="text-muted">{{ $employees->count() }} pegawai</small>
                                </div>
                            </div>
                            <p class="text-muted small">Laporan data pegawai lengkap dengan informasi gaji dan total pengeluaran.</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('acara30.pdf.pegawai') }}" class="btn btn-sm" style="background: #6f42c1; color: white;">
                                    <i class="fa fa-download me-1"></i>Download
                                </a>
                                <a href="{{ route('acara30.pdf.pegawai.stream') }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                                    <i class="fa fa-eye me-1"></i>Lihat di Browser
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- BAGIAN 2: UPLOAD & MANIPULASI GAMBAR -->
    <!-- ============================================ -->
    <div class="row g-4 mb-4">
        <!-- Upload Gambar -->
        <div class="col-12 col-lg-5">
            <div class="bg-light rounded p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-image text-warning me-2"></i>Upload Gambar</h5>
                    <span class="badge bg-warning text-dark px-2 py-1"><i class="fa fa-cloud-upload-alt me-1"></i>Max 2MB</span>
                </div>
                <form method="POST" action="{{ route('acara30.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="photo" class="form-label fw-semibold text-dark">Pilih Gambar <span class="text-danger">*</span></label>
                        <input type="file" id="photo" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*" required>
                        @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-1">Format: jpeg, jpg, png, gif — Maks. 2MB</small>
                    </div>

                    <!-- Preview Gambar -->
                    <div class="mb-3" id="imgPreviewBox" style="display: none;">
                        <div class="border rounded p-2 bg-white text-center">
                            <img id="imgPreview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                            <p class="small text-muted mt-2 mb-0" id="imgPreviewInfo"></p>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning text-dark px-4 py-2">
                        <i class="fa fa-upload me-2"></i>Upload Gambar
                    </button>
                </form>
            </div>
        </div>

        <!-- Manipulasi Gambar -->
        <div class="col-12 col-lg-7">
            <div class="bg-light rounded p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-magic text-info me-2"></i>Manipulasi Gambar</h5>
                    <span class="badge bg-info text-white px-2 py-1"><i class="fa fa-sliders-h me-1"></i>PHP GD</span>
                </div>

                @if(count($uploadedImages) > 0)
                <!-- Tabs untuk Resize / Rotate / Crop -->
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-resize" type="button"><i class="fa fa-expand-arrows-alt me-1"></i>Resize</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-rotate" type="button"><i class="fa fa-undo me-1"></i>Rotate</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-crop" type="button"><i class="fa fa-crop me-1"></i>Crop</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Tab Resize -->
                    <div class="tab-pane fade show active" id="tab-resize" role="tabpanel">
                        <form method="POST" action="{{ route('acara30.resize') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Pilih Gambar</label>
                                <select name="image_name" class="form-select" required>
                                    <option value="">-- Pilih Gambar --</option>
                                    @foreach($uploadedImages as $img)
                                    <option value="{{ $img['name'] }}">{{ $img['name'] }} ({{ $img['width'] }}x{{ $img['height'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold text-dark">Lebar (px)</label>
                                    <input type="number" name="width" class="form-control" value="300" min="10" max="2000" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold text-dark">Tinggi (px)</label>
                                    <input type="number" name="height" class="form-control" value="300" min="10" max="2000" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info text-white"><i class="fa fa-expand-arrows-alt me-1"></i>Resize</button>
                        </form>
                    </div>

                    <!-- Tab Rotate -->
                    <div class="tab-pane fade" id="tab-rotate" role="tabpanel">
                        <form method="POST" action="{{ route('acara30.rotate') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Pilih Gambar</label>
                                <select name="image_name" class="form-select" required>
                                    <option value="">-- Pilih Gambar --</option>
                                    @foreach($uploadedImages as $img)
                                    <option value="{{ $img['name'] }}">{{ $img['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Derajat Rotasi</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="degrees" value="45" id="rot45">
                                        <label class="form-check-label" for="rot45">45°</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="degrees" value="90" id="rot90" checked>
                                        <label class="form-check-label" for="rot90">90°</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="degrees" value="180" id="rot180">
                                        <label class="form-check-label" for="rot180">180°</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="degrees" value="270" id="rot270">
                                        <label class="form-check-label" for="rot270">270°</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info text-white"><i class="fa fa-undo me-1"></i>Rotate</button>
                        </form>
                    </div>

                    <!-- Tab Crop -->
                    <div class="tab-pane fade" id="tab-crop" role="tabpanel">
                        <form method="POST" action="{{ route('acara30.crop') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Pilih Gambar</label>
                                <select name="image_name" class="form-select" required>
                                    <option value="">-- Pilih Gambar --</option>
                                    @foreach($uploadedImages as $img)
                                    <option value="{{ $img['name'] }}">{{ $img['name'] }} ({{ $img['width'] }}x{{ $img['height'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-3">
                                    <label class="form-label fw-semibold text-dark small">X</label>
                                    <input type="number" name="crop_x" class="form-control" value="0" min="0" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label fw-semibold text-dark small">Y</label>
                                    <input type="number" name="crop_y" class="form-control" value="0" min="0" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label fw-semibold text-dark small">Lebar</label>
                                    <input type="number" name="crop_w" class="form-control" value="200" min="10" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label fw-semibold text-dark small">Tinggi</label>
                                    <input type="number" name="crop_h" class="form-control" value="200" min="10" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info text-white"><i class="fa fa-crop me-1"></i>Crop</button>
                        </form>
                    </div>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fa fa-image text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3 mb-0">Upload gambar terlebih dahulu untuk melakukan manipulasi.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- BAGIAN 3: GALERI GAMBAR -->
    <!-- ============================================ -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-images text-success me-2"></i>Galeri Gambar</h5>
                    <span class="badge bg-secondary text-white px-3 py-2">{{ count($uploadedImages) }} gambar</span>
                </div>

                @if(count($uploadedImages) > 0)
                <div class="row g-3">
                    @foreach($uploadedImages as $img)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="border rounded bg-white shadow-sm overflow-hidden h-100">
                            <div class="position-relative" style="height: 180px; overflow: hidden;">
                                <img src="{{ $img['url'] }}" alt="{{ $img['name'] }}" class="w-100 h-100" style="object-fit: cover;">
                                <span class="badge bg-dark bg-opacity-75 text-white position-absolute bottom-0 end-0 m-1" style="font-size: 10px;">
                                    {{ $img['width'] }}x{{ $img['height'] }}
                                </span>
                            </div>
                            <div class="p-2">
                                <p class="fw-semibold small text-dark mb-1 text-truncate" title="{{ $img['name'] }}">{{ $img['name'] }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ number_format($img['size'] / 1024, 1) }} KB</small>
                                    <form method="POST" action="{{ route('acara30.image.delete', $img['name']) }}" class="d-inline" onsubmit="return confirm('Hapus gambar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1" title="Hapus">
                                            <i class="fa fa-trash-alt" style="font-size: 11px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fa fa-image text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3 mb-0">Belum ada gambar yang diunggah.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- BAGIAN 4: REFERENSI CODE SNIPPET -->
    <!-- ============================================ -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center mb-3 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-code text-primary me-2"></i>Referensi Code Snippet</h5>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-file-pdf text-danger me-1"></i> Generate PDF</h6>
                            <code class="small d-block text-break">$pdf = PDF::loadView('view', $data);</code>
                            <code class="small d-block text-break mt-1">return $pdf->download('file.pdf');</code>
                            <small class="text-muted mt-1 d-block">Generate & download PDF</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-eye text-primary me-1"></i> Stream PDF</h6>
                            <code class="small d-block text-break">return $pdf->stream('file.pdf');</code>
                            <small class="text-muted mt-1 d-block">Tampilkan PDF di browser</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-cog text-success me-1"></i> Paper Setting</h6>
                            <code class="small d-block text-break">$pdf->setPaper('A4', 'landscape');</code>
                            <small class="text-muted mt-1 d-block">Atur ukuran & orientasi kertas</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-expand-arrows-alt text-info me-1"></i> Resize Gambar</h6>
                            <code class="small d-block text-break">imagecopyresampled($dst, $src, ...);</code>
                            <small class="text-muted mt-1 d-block">Resize gambar dengan PHP GD</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-undo text-warning me-1"></i> Rotate Gambar</h6>
                            <code class="small d-block text-break">$rotated = imagerotate($src, 45, 0);</code>
                            <small class="text-muted mt-1 d-block">Rotasi gambar dengan PHP GD</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-crop text-danger me-1"></i> Crop Gambar</h6>
                            <code class="small d-block text-break">$cropped = imagecrop($src, $rect);</code>
                            <small class="text-muted mt-1 d-block">Crop area tertentu dari gambar</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // === Preview Gambar Upload ===
    const photoInput = document.getElementById('photo');
    const imgPreviewBox = document.getElementById('imgPreviewBox');
    const imgPreview = document.getElementById('imgPreview');
    const imgPreviewInfo = document.getElementById('imgPreviewInfo');

    if (photoInput) {
        photoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const sizeKB = (file.size / 1024).toFixed(1);

                const reader = new FileReader();
                reader.onload = function (e) {
                    imgPreview.src = e.target.result;
                    imgPreviewInfo.textContent = file.name + ' (' + sizeKB + ' KB)';
                    imgPreviewBox.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imgPreviewBox.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
