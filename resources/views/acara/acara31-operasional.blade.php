@extends('layouts.dashmin')

@section('title', 'Edit Foto Produk')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Header Page & Breadcrumb -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Foto Produk</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark"><i class="fa fa-magic text-warning me-2"></i>Laravel Image Manipulation</h4>
            <p class="text-muted small mb-0">Resize otomatis, crop, rotate, watermark, dan konversi format gambar produk.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning text-dark px-3 py-2"><i class="fa fa-image me-1"></i>Intervention Image v2</span>
            <span class="badge bg-primary px-3 py-2"><i class="fa fa-hdd me-1"></i>Driver: GD</span>
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

    @if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <div class="d-flex">
            <i class="fa fa-exclamation-triangle fs-5 me-2 mt-1"></i>
            <div>
                <strong>Periksa kembali form:</strong>
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

    <!-- Upload & Quick Action Row -->
    <div class="row g-4 mb-4">
        <!-- Form Upload Gambar -->
        <div class="col-12 col-lg-5">
            <div class="bg-light rounded p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-cloud-upload-alt text-primary me-2"></i>Upload Gambar</h5>
                    <span class="badge bg-primary text-white">Max 2MB</span>
                </div>
                <form method="POST" action="{{ route('acara31.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="photoInput" class="form-label small fw-semibold text-dark">Pilih Berkas Gambar <span class="text-danger">*</span></label>
                        <input type="file" id="photoInput" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*" required>
                        <small class="text-muted d-block mt-1">Mendukung JPEG, PNG, JPG, GIF, WEBP</small>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="auto_resize" id="autoResize" value="1" checked>
                        <label class="form-check-label small text-dark fw-semibold" for="autoResize">
                            Otomatis Resize ke 300x300 (Prosedur 4 BKPM)
                        </label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Kualitas Simpan (Quality: 10 - 100%)</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="range" class="form-range" name="quality" min="30" max="100" value="90" id="qualityRange" oninput="document.getElementById('qualityVal').textContent = this.value + '%'">
                            <span class="badge bg-secondary" id="qualityVal">90%</span>
                        </div>
                    </div>

                    <!-- Preview Upload Box -->
                    <div id="uploadPreviewBox" class="mb-3 p-2 bg-white rounded border text-center" style="display: none;">
                        <img id="uploadPreviewImg" src="" alt="Preview" class="img-fluid rounded border mb-2" style="max-height: 160px;">
                        <div id="uploadPreviewInfo" class="small text-muted"></div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-upload me-1"></i>Upload & Proses Gambar
                    </button>
                </form>
            </div>
        </div>

        <!-- Panel Info Operasi Intervention Image -->
        <div class="col-12 col-lg-7">
            <div class="bg-light rounded p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-tools text-warning me-2"></i>Operasi yang Didukung</h5>
                    <span class="badge bg-success text-white">Watermark Siap</span>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="p-3 bg-white rounded border h-100">
                            <h6 class="fw-bold text-primary mb-1"><i class="fa fa-expand me-1"></i> 1. Resize Gambar</h6>
                            <code class="small d-block text-break">Image::make($path)->resize(w, h)->save()</code>
                            <small class="text-muted">Mengubah resolusi gambar secara proporsional atau fix.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white rounded border h-100">
                            <h6 class="fw-bold text-success mb-1"><i class="fa fa-crop me-1"></i> 2. Crop Gambar</h6>
                            <code class="small d-block text-break">Image::make($path)->crop(w, h, x, y)->save()</code>
                            <small class="text-muted">Memotong area tertentu dari gambar.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white rounded border h-100">
                            <h6 class="fw-bold text-warning mb-1"><i class="fa fa-stamp me-1"></i> 3. Watermark</h6>
                            <code class="small d-block text-break">Image::make($path)->insert($wm, 'pos')->save()</code>
                            <small class="text-muted">Menyisipkan logo watermark transparan.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white rounded border h-100">
                            <h6 class="fw-bold text-info mb-1"><i class="fa fa-sync-alt me-1"></i> 4. Rotate & Format</h6>
                            <code class="small d-block text-break">Image::make($path)->rotate(45)->encode('png')</code>
                            <small class="text-muted">Memutar sudut derajat & mengubah ekstensi format.</small>
                        </div>
                    </div>
                </div>
                <div class="alert alert-light border small mt-3 mb-0">
                    <i class="fa fa-info-circle text-primary me-1"></i> Pilih salah satu gambar pada galeri di bawah untuk membuka modal manipulasi.
                </div>
            </div>
        </div>
    </div>

    <!-- Galeri & Daftar Gambar Terunggah -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-images text-primary me-2"></i>Galeri Hasil Manipulasi Gambar (public/uploads)</h5>
                    <span class="badge bg-secondary px-3 py-2">{{ count($images) }} gambar</span>
                </div>

                @if(count($images) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th style="width: 90px;">Preview</th>
                                <th>Nama Berkas</th>
                                <th>Dimensi</th>
                                <th>Ukuran</th>
                                <th>Format</th>
                                <th class="text-center" style="width: 320px;">Aksi Manipulasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($images as $idx => $img)
                            <tr>
                                <td class="fw-bold text-muted">{{ $idx + 1 }}</td>
                                <td>
                                    <a href="{{ $img['url'] }}" target="_blank" title="Buka gambar">
                                        <img src="{{ $img['url'] }}" alt="{{ $img['name'] }}" class="rounded border shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark text-break" style="max-width: 260px;">{{ $img['name'] }}</div>
                                    <small class="text-muted">{{ $img['last_modified'] }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $img['width'] }} &times; {{ $img['height'] }} px
                                    </span>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ number_format($img['size'] / 1024, 1) }} KB</span></td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary">.{{ $img['extension'] }}</span></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- Response Langsung di Browser (Prosedur 3f) -->
                                        <a href="{{ route('acara31.response', $img['name']) }}" target="_blank" class="btn btn-outline-info" title="Response Langsung (Image::response)">
                                            <i class="fa fa-eye"></i> Response
                                        </a>

                                        <!-- Resize Modal Trigger -->
                                        <button type="button" class="btn btn-outline-primary btn-resize" data-name="{{ $img['name'] }}" data-w="{{ $img['width'] }}" data-h="{{ $img['height'] }}" title="Resize">
                                            <i class="fa fa-expand"></i>
                                        </button>

                                        <!-- Crop Modal Trigger -->
                                        <button type="button" class="btn btn-outline-success btn-crop" data-name="{{ $img['name'] }}" data-w="{{ $img['width'] }}" data-h="{{ $img['height'] }}" title="Crop">
                                            <i class="fa fa-crop"></i>
                                        </button>

                                        <!-- Watermark Form Trigger -->
                                        <button type="button" class="btn btn-outline-warning text-dark btn-wm" data-name="{{ $img['name'] }}" title="Watermark">
                                            <i class="fa fa-stamp"></i>
                                        </button>

                                        <!-- Rotate Modal Trigger -->
                                        <button type="button" class="btn btn-outline-secondary btn-rotate" data-name="{{ $img['name'] }}" title="Rotate">
                                            <i class="fa fa-redo"></i>
                                        </button>

                                        <!-- Encode Format Modal Trigger -->
                                        <button type="button" class="btn btn-outline-dark btn-encode" data-name="{{ $img['name'] }}" title="Ubah Format">
                                            <i class="fa fa-exchange-alt"></i>
                                        </button>

                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('acara31.delete', $img['name']) }}" class="d-inline" onsubmit="return confirm('Hapus gambar ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fa fa-image text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3 mb-0">Belum ada gambar yang diunggah. Unggah gambar pertama Anda pada form di atas!</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Cheatsheet Sintaks Intervention Image -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center mb-3 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-book-open text-primary me-2"></i>Referensi Sintaksis Intervention Image</h5>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-file-image text-primary me-1"></i> Buka Gambar</h6>
                            <code class="small d-block text-break">$image = Image::make($file);</code>
                            <small class="text-muted mt-1 d-block">Membaca file upload atau path lokal</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-expand text-success me-1"></i> Resize & Kualitas</h6>
                            <code class="small d-block text-break">$image->resize(300, 200)->save($path, 75);</code>
                            <small class="text-muted mt-1 d-block">Ubah resolusi dan atur kualitas kompresi 1-100</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-crop text-info me-1"></i> Crop Area</h6>
                            <code class="small d-block text-break">$image->crop($w, $h, $x, $y);</code>
                            <small class="text-muted mt-1 d-block">Memotong gambar dari koordinat x, y</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-stamp text-warning me-1"></i> Sisipkan Watermark</h6>
                            <code class="small d-block text-break">$image->insert('wm.png', 'bottom-right', 10, 10);</code>
                            <small class="text-muted mt-1 d-block">Watermark pada sudut dengan offset 10px</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-undo text-secondary me-1"></i> Putar (Rotate)</h6>
                            <code class="small d-block text-break">$image->rotate(45);</code>
                            <small class="text-muted mt-1 d-block">Rotasi sudut derajat searah jarum jam</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-desktop text-danger me-1"></i> Response Langsung</h6>
                            <code class="small d-block text-break">return Image::make($path)->response('jpg');</code>
                            <small class="text-muted mt-1 d-block">Kirim byte gambar langsung ke browser</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 1: Resize -->
<div class="modal fade" id="resizeModal" tabindex="-1" aria-labelledby="resizeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('acara31.resize') }}">
                @csrf
                <input type="hidden" name="image_name" id="resizeImgName">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="resizeModalLabel"><i class="fa fa-expand me-2"></i>Resize Gambar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">Berkas: <strong id="resizeImgLabel" class="text-dark"></strong></p>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-dark">Lebar Baru (px) <span class="text-danger">*</span></label>
                            <input type="number" name="width" id="resizeWidth" class="form-control" value="400" min="20" max="3000" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-dark">Tinggi Baru (px) <span class="text-danger">*</span></label>
                            <input type="number" name="height" id="resizeHeight" class="form-control" value="300" min="20" max="3000" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Kualitas Kompresi (Quality: 10 - 100%)</label>
                        <input type="number" name="quality" class="form-control" value="85" min="10" max="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check me-1"></i>Proses Resize</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Crop -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('acara31.crop') }}">
                @csrf
                <input type="hidden" name="image_name" id="cropImgName">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="cropModalLabel"><i class="fa fa-crop me-2"></i>Crop Gambar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">Berkas: <strong id="cropImgLabel" class="text-dark"></strong></p>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-dark">Lebar Crop (px) <span class="text-danger">*</span></label>
                            <input type="number" name="crop_w" id="cropW" class="form-control" value="250" min="20" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-dark">Tinggi Crop (px) <span class="text-danger">*</span></label>
                            <input type="number" name="crop_h" id="cropH" class="form-control" value="250" min="20" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-dark">Titik Offset X</label>
                            <input type="number" name="crop_x" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-dark">Titik Offset Y</label>
                            <input type="number" name="crop_y" class="form-control" value="0" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check me-1"></i>Proses Crop</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 3: Watermark -->
<div class="modal fade" id="wmModal" tabindex="-1" aria-labelledby="wmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('acara31.watermark') }}">
                @csrf
                <input type="hidden" name="image_name" id="wmImgName">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="wmModalLabel"><i class="fa fa-stamp me-2"></i>Sisipkan Watermark</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">Berkas: <strong id="wmImgLabel" class="text-dark"></strong></p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Posisi Watermark</label>
                        <select name="position" class="form-select">
                            <option value="bottom-right" selected>Pojok Kanan Bawah (bottom-right)</option>
                            <option value="bottom-left">Pojok Kiri Bawah (bottom-left)</option>
                            <option value="top-right">Pojok Kanan Atas (top-right)</option>
                            <option value="top-left">Pojok Kiri Atas (top-left)</option>
                            <option value="center">Tengah (center)</option>
                        </select>
                    </div>
                    <div class="alert alert-light border small mb-0">
                        Watermark transparan 'MINIMARKET' otomatis disisipkan menggunakan <code>$image->insert(...)</code>.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold"><i class="fa fa-check me-1"></i>Terapkan Watermark</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 4: Rotate -->
<div class="modal fade" id="rotateModal" tabindex="-1" aria-labelledby="rotateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('acara31.rotate') }}">
                @csrf
                <input type="hidden" name="image_name" id="rotateImgName">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="rotateModalLabel"><i class="fa fa-redo me-2"></i>Rotasi Gambar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">Berkas: <strong id="rotateImgLabel" class="text-dark"></strong></p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Derajat Rotasi</label>
                        <select name="degrees" class="form-select">
                            <option value="45">45 Derajat</option>
                            <option value="90" selected>90 Derajat (Searah Jarum Jam)</option>
                            <option value="180">180 Derajat (Terbalik)</option>
                            <option value="270">270 Derajat</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-secondary"><i class="fa fa-check me-1"></i>Proses Rotasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 5: Encode Format -->
<div class="modal fade" id="encodeModal" tabindex="-1" aria-labelledby="encodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('acara31.encode') }}">
                @csrf
                <input type="hidden" name="image_name" id="encodeImgName">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="encodeModalLabel"><i class="fa fa-exchange-alt me-2"></i>Ubah Format Berkas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">Berkas: <strong id="encodeImgLabel" class="text-dark"></strong></p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Pilih Format Target</label>
                        <select name="format" class="form-select">
                            <option value="png">PNG (Transparansi)</option>
                            <option value="jpg" selected>JPG / JPEG (Kompresi Efisien)</option>
                            <option value="webp">WEBP (Format Modern Web)</option>
                            <option value="gif">GIF</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark"><i class="fa fa-check me-1"></i>Konversi Format</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Preview gambar upload
    const photoInput = document.getElementById('photoInput');
    const uploadPreviewBox = document.getElementById('uploadPreviewBox');
    const uploadPreviewImg = document.getElementById('uploadPreviewImg');
    const uploadPreviewInfo = document.getElementById('uploadPreviewInfo');

    if (photoInput) {
        photoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const sizeKB = (file.size / 1024).toFixed(1);
                const reader = new FileReader();

                reader.onload = function (e) {
                    uploadPreviewImg.src = e.target.result;
                    uploadPreviewInfo.textContent = file.name + ' (' + sizeKB + ' KB)';
                    uploadPreviewBox.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                uploadPreviewBox.style.display = 'none';
            }
        });
    }

    // Modal triggers setup
    const resizeModal = new bootstrap.Modal(document.getElementById('resizeModal'));
    document.querySelectorAll('.btn-resize').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            const w = this.getAttribute('data-w');
            const h = this.getAttribute('data-h');
            document.getElementById('resizeImgName').value = name;
            document.getElementById('resizeImgLabel').textContent = name;
            document.getElementById('resizeWidth').value = w > 0 ? Math.round(w / 2) : 300;
            document.getElementById('resizeHeight').value = h > 0 ? Math.round(h / 2) : 300;
            resizeModal.show();
        });
    });

    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
    document.querySelectorAll('.btn-crop').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            const w = this.getAttribute('data-w');
            const h = this.getAttribute('data-h');
            document.getElementById('cropImgName').value = name;
            document.getElementById('cropImgLabel').textContent = name;
            document.getElementById('cropW').value = w > 100 ? 200 : w;
            document.getElementById('cropH').value = h > 100 ? 200 : h;
            cropModal.show();
        });
    });

    const wmModal = new bootstrap.Modal(document.getElementById('wmModal'));
    document.querySelectorAll('.btn-wm').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            document.getElementById('wmImgName').value = name;
            document.getElementById('wmImgLabel').textContent = name;
            wmModal.show();
        });
    });

    const rotateModal = new bootstrap.Modal(document.getElementById('rotateModal'));
    document.querySelectorAll('.btn-rotate').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            document.getElementById('rotateImgName').value = name;
            document.getElementById('rotateImgLabel').textContent = name;
            rotateModal.show();
        });
    });

    const encodeModal = new bootstrap.Modal(document.getElementById('encodeModal'));
    document.querySelectorAll('.btn-encode').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            document.getElementById('encodeImgName').value = name;
            document.getElementById('encodeImgLabel').textContent = name;
            encodeModal.show();
        });
    });
});
</script>
@endpush
