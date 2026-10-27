@extends('layouts.dashmin')

@section('title', 'Penyimpanan Berkas')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Header Page & Breadcrumb -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Penyimpanan Berkas</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark"><i class="fa fa-cloud-upload-alt text-primary me-2"></i>Penyimpanan & Manajemen Berkas</h4>
            <p class="text-muted small mb-0">Unggah, manipulasi, duplikasi, dan kelola dokumen sistem secara terpusat dan aman.</p>
        </div>
        <div>
            <span class="badge bg-primary px-3 py-2"><i class="fa fa-hdd me-1"></i>Disk: Public</span>
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
    <div class="row g-4">
        <!-- Upload File Form -->
        <div class="col-12 col-lg-6">
            <div class="bg-light rounded p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-upload text-primary me-2"></i>Upload File</h5>
                    <span class="badge bg-primary text-white px-2 py-1"><i class="fa fa-file me-1"></i>Max 2MB</span>
                </div>
                <form method="POST" action="{{ route('acara29.upload') }}" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <!-- File Input -->
                    <div class="mb-3">
                        <label for="file" class="form-label fw-semibold text-dark">Pilih File <span class="text-danger">*</span></label>
                        <input type="file" id="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                        @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-1">Format: jpg, jpeg, png, gif, pdf, doc, docx, txt, csv, xlsx</small>
                    </div>

                    <!-- Naming Option -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Opsi Penamaan File</label>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="naming_option" id="naming_original" value="original" checked>
                                <label class="form-check-label" for="naming_original">Nama Asli</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="naming_option" id="naming_hash" value="hash">
                                <label class="form-check-label" for="naming_hash">Hash Acak (store)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="naming_option" id="naming_timestamp" value="timestamp">
                                <label class="form-check-label" for="naming_timestamp">Timestamp (storeAs)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="naming_option" id="naming_uuid" value="uuid">
                                <label class="form-check-label" for="naming_uuid">UUID (storeAs)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="naming_option" id="naming_custom" value="custom">
                                <label class="form-check-label" for="naming_custom">Kustom (storeAs)</label>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Name Field (hidden by default) -->
                    <div class="mb-3" id="customNameGroup" style="display: none;">
                        <label for="custom_name" class="form-label fw-semibold text-dark">Nama File Kustom</label>
                        <input type="text" id="custom_name" name="custom_name" class="form-control" placeholder="masukkan-nama-file">
                        <small class="text-muted">Ekstensi file otomatis ditambahkan.</small>
                    </div>

                    <!-- File Preview -->
                    <div class="mb-3" id="filePreview" style="display: none;">
                        <div class="border rounded p-3 bg-white">
                            <div class="d-flex align-items-center gap-3">
                                <div id="previewIcon" class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded" style="width: 50px; height: 50px; min-width: 50px;">
                                    <i class="fa fa-file text-primary fs-4"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="mb-0 fw-semibold text-dark text-truncate" id="previewName">-</p>
                                    <small class="text-muted" id="previewSize">-</small>
                                </div>
                            </div>
                            <div id="imagePreviewContainer" class="mt-3" style="display: none;">
                                <img id="imagePreview" src="" alt="Preview" class="img-fluid rounded border" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="fa fa-cloud-upload-alt me-2"></i>Upload File
                    </button>
                </form>
            </div>
        </div>

        <!-- Storage::put Form -->
        <div class="col-12 col-lg-6">
            <div class="bg-light rounded p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-file-alt text-primary me-2"></i>Simpan Teks</h5>
                    <span class="badge bg-info text-white px-2 py-1"><i class="fa fa-code me-1"></i>Storage::put()</span>
                </div>
                <form method="POST" action="{{ route('acara29.put') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="put_filename" class="form-label fw-semibold text-dark">Nama File <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="put_filename" name="put_filename" value="{{ old('put_filename') }}" class="form-control @error('put_filename') is-invalid @enderror" placeholder="contoh-catatan" required>
                            <span class="input-group-text">.txt</span>
                        </div>
                        @error('put_filename')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="put_content" class="form-label fw-semibold text-dark">Isi Konten <span class="text-danger">*</span></label>
                        <textarea id="put_content" name="put_content" class="form-control @error('put_content') is-invalid @enderror" rows="5" placeholder="Tulis isi konten file teks di sini..." required>{{ old('put_content') }}</textarea>
                        @error('put_content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-info text-white px-4 py-2">
                        <i class="fa fa-save me-2"></i>Simpan File Teks
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Daftar File yang Terunggah -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-folder-open text-primary me-2"></i>Daftar File Tersimpan</h5>
                    <span class="badge bg-secondary text-white px-3 py-2">{{ count($files) }} file</span>
                </div>

                @if(count($files) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="rounded-start" style="width: 40px;">#</th>
                                <th>Preview</th>
                                <th>Nama File</th>
                                <th>Ukuran</th>
                                <th>Tipe</th>
                                <th>Terakhir Diubah</th>
                                <th class="rounded-end text-center" style="width: 280px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $index => $file)
                            <tr>
                                <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                <td>
                                    @if($file['is_image'])
                                    <img src="{{ asset('storage/' . $file['path']) }}" alt="{{ $file['name'] }}" class="rounded border" style="width: 48px; height: 48px; object-fit: cover;">
                                    @else
                                    <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded" style="width: 48px; height: 48px;">
                                        @switch($file['extension'])
                                            @case('pdf')
                                                <i class="fa fa-file-pdf text-danger fs-4"></i>
                                                @break
                                            @case('doc')
                                            @case('docx')
                                                <i class="fa fa-file-word text-primary fs-4"></i>
                                                @break
                                            @case('xlsx')
                                            @case('csv')
                                                <i class="fa fa-file-excel text-success fs-4"></i>
                                                @break
                                            @case('txt')
                                                <i class="fa fa-file-alt text-info fs-4"></i>
                                                @break
                                            @default
                                                <i class="fa fa-file text-secondary fs-4"></i>
                                        @endswitch
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark text-break" style="max-width: 250px;">{{ $file['name'] }}</div>
                                    <small class="text-muted">{{ $file['path'] }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ number_format($file['size'] / 1024, 1) }} KB</span></td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary">.{{ $file['extension'] }}</span></td>
                                <td><small class="text-muted">{{ $file['last_modified'] }}</small></td>
                                <td class="text-center">
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        <!-- Detail / Read Content (Storage::get & Storage::path) -->
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-detail" data-filename="{{ $file['name'] }}" title="Detail & Baca Isi (Storage::get)">
                                            <i class="fa fa-eye"></i>
                                        </button>

                                        <!-- Download -->
                                        <a href="{{ route('acara29.download', $file['name']) }}" class="btn btn-sm btn-outline-success" title="Download">
                                            <i class="fa fa-download"></i>
                                        </a>

                                        <!-- Copy -->
                                        <form method="POST" action="{{ route('acara29.copy') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="source_file" value="{{ $file['name'] }}">
                                            <button type="submit" class="btn btn-sm btn-outline-info" title="Salin File (Storage::copy)">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                        </form>

                                        <!-- Rename -->
                                        <button type="button" class="btn btn-sm btn-outline-warning btn-rename" data-filename="{{ $file['name'] }}" title="Ganti Nama (Storage::move)">
                                            <i class="fa fa-pen"></i>
                                        </button>

                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('acara29.delete', $file['name']) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus file ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus (Storage::delete)">
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
                    <i class="fa fa-inbox text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3 mb-0">Belum ada file yang diunggah. Mulai upload file pertama Anda!</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Info Ringkasan File Operations -->
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center mb-3 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-info-circle text-primary me-2"></i>Referensi Operasi File Storage</h5>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-save text-primary me-1"></i> Simpan File</h6>
                            <code class="small d-block text-break">Storage::put('file.txt', 'Isi');</code>
                            <small class="text-muted mt-1 d-block">Menyimpan konten ke file baru</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-search text-success me-1"></i> Cek File</h6>
                            <code class="small d-block text-break">Storage::exists('file.txt');</code>
                            <small class="text-muted mt-1 d-block">Periksa keberadaan file</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-trash text-danger me-1"></i> Hapus File</h6>
                            <code class="small d-block text-break">Storage::delete('file.txt');</code>
                            <small class="text-muted mt-1 d-block">Menghapus file dari storage</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-exchange-alt text-warning me-1"></i> Ganti Nama</h6>
                            <code class="small d-block text-break">Storage::move('old', 'new');</code>
                            <small class="text-muted mt-1 d-block">Memindahkan / rename file</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-copy text-info me-1"></i> Salin File</h6>
                            <code class="small d-block text-break">Storage::copy('src', 'dest');</code>
                            <small class="text-muted mt-1 d-block">Menduplikasi file</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-file-download text-success me-1"></i> Ambil Isi</h6>
                            <code class="small d-block text-break">Storage::get('file.txt');</code>
                            <small class="text-muted mt-1 d-block">Membaca isi konten file</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-link text-primary me-1"></i> Ambil URL</h6>
                            <code class="small d-block text-break">Storage::url('file.txt');</code>
                            <small class="text-muted mt-1 d-block">URL publik untuk disk public/S3</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-map-marker-alt text-danger me-1"></i> Path Absolut</h6>
                            <code class="small d-block text-break">Storage::path('file.txt');</code>
                            <small class="text-muted mt-1 d-block">Path lengkap file di server</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rename -->
<div class="modal fade" id="renameModal" tabindex="-1" aria-labelledby="renameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('acara29.rename') }}">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="renameModalLabel"><i class="fa fa-pen me-2"></i>Ganti Nama File</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="old_name" id="renameOldName">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Nama File Saat Ini</label>
                        <input type="text" class="form-control bg-light" id="renameCurrentName" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="new_name" class="form-label fw-semibold text-dark">Nama File Baru <span class="text-danger">*</span></label>
                        <input type="text" id="new_name" name="new_name" class="form-control" placeholder="nama-file-baru" required>
                        <small class="text-muted">Ekstensi file akan dipertahankan secara otomatis.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check me-1"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail & Konten File (Storage::get, Storage::path, Storage::exists) -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="detailModalLabel"><i class="fa fa-info-circle text-primary me-2"></i>Detail File & Konten Storage</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detailLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Memuat...</span>
                    </div>
                    <p class="text-muted mt-2 mb-0">Mengambil informasi dari API Storage...</p>
                </div>
                <div id="detailBody" style="display: none;">
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-sm mb-0">
                            <tbody>
                                <tr>
                                    <th class="bg-light" style="width: 220px;">Nama File</th>
                                    <td id="detailName" class="fw-bold text-dark"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Status Storage::exists()</th>
                                    <td><span class="badge bg-success" id="detailExists"><i class="fa fa-check me-1"></i>File Terverifikasi Ada</span></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Ukuran (Storage::size)</th>
                                    <td id="detailSize"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Terakhir Diubah</th>
                                    <td id="detailModified"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Storage::url() Publik</th>
                                    <td>
                                        <a href="#" id="detailUrlLink" target="_blank" class="text-break text-decoration-none">
                                            <span id="detailUrl"></span> <i class="fa fa-external-link-alt ms-1 small"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Storage::path() Absolut</th>
                                    <td><code class="small text-break" id="detailAbsPath"></code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Text content container (Storage::get) -->
                    <div id="detailContentContainer" style="display: none;">
                        <h6 class="fw-bold text-dark mb-2"><i class="fa fa-file-alt text-info me-1"></i> Isi Konten File (via <code>Storage::get()</code>):</h6>
                        <pre class="bg-dark text-light p-3 rounded small" style="max-height: 250px; overflow-y: auto; white-space: pre-wrap;" id="detailTextContent"></pre>
                    </div>

                    <!-- Image preview container -->
                    <div id="detailImageContainer" class="text-center" style="display: none;">
                        <h6 class="fw-bold text-dark text-start mb-2"><i class="fa fa-image text-warning me-1"></i> Preview Gambar:</h6>
                        <img id="detailImage" src="" alt="File Preview" class="img-fluid rounded border" style="max-height: 260px;">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // === Toggle Custom Name Field ===
    const namingOptions = document.querySelectorAll('input[name="naming_option"]');
    const customNameGroup = document.getElementById('customNameGroup');

    namingOptions.forEach(function (radio) {
        radio.addEventListener('change', function () {
            customNameGroup.style.display = this.value === 'custom' ? 'block' : 'none';
        });
    });

    // === File Preview ===
    const fileInput = document.getElementById('file');
    const filePreview = document.getElementById('filePreview');
    const previewName = document.getElementById('previewName');
    const previewSize = document.getElementById('previewSize');
    const previewIcon = document.getElementById('previewIcon');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const sizeKB = (file.size / 1024).toFixed(1);
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                const ext = file.name.split('.').pop().toLowerCase();

                previewName.textContent = file.name;
                previewSize.textContent = file.size > 1048576 ? sizeMB + ' MB' : sizeKB + ' KB';
                filePreview.style.display = 'block';

                // Icon berdasarkan tipe
                const iconMap = {
                    'pdf': 'fa-file-pdf text-danger',
                    'doc': 'fa-file-word text-primary',
                    'docx': 'fa-file-word text-primary',
                    'xlsx': 'fa-file-excel text-success',
                    'csv': 'fa-file-excel text-success',
                    'txt': 'fa-file-alt text-info',
                    'jpg': 'fa-file-image text-warning',
                    'jpeg': 'fa-file-image text-warning',
                    'png': 'fa-file-image text-warning',
                    'gif': 'fa-file-image text-warning',
                };
                const iconClass = iconMap[ext] || 'fa-file text-secondary';
                previewIcon.innerHTML = '<i class="fa ' + iconClass + ' fs-4"></i>';

                // Image preview
                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        imagePreviewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreviewContainer.style.display = 'none';
                }
            } else {
                filePreview.style.display = 'none';
            }
        });
    }

    // === Rename Modal ===
    const renameButtons = document.querySelectorAll('.btn-rename');
    const renameModal = new bootstrap.Modal(document.getElementById('renameModal'));

    renameButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const filename = this.getAttribute('data-filename');
            document.getElementById('renameOldName').value = filename;
            document.getElementById('renameCurrentName').value = filename;
            document.getElementById('new_name').value = filename.replace(/\.[^.]+$/, '');
            renameModal.show();
        });
    });

    // === Detail & Read Content Modal (Storage::get & path) ===
    const detailButtons = document.querySelectorAll('.btn-detail');
    const detailModalEl = document.getElementById('detailModal');
    const detailModal = new bootstrap.Modal(detailModalEl);

    detailButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const filename = this.getAttribute('data-filename');
            const loading = document.getElementById('detailLoading');
            const body = document.getElementById('detailBody');
            const contentContainer = document.getElementById('detailContentContainer');
            const imageContainer = document.getElementById('detailImageContainer');

            loading.style.display = 'block';
            body.style.display = 'none';
            contentContainer.style.display = 'none';
            imageContainer.style.display = 'none';

            detailModal.show();

            fetch('{{ url("/acara29/content") }}/' + encodeURIComponent(filename))
                .then(response => response.json())
                .then(data => {
                    loading.style.display = 'none';
                    if (data.success) {
                        body.style.display = 'block';
                        document.getElementById('detailName').textContent = data.name;
                        document.getElementById('detailSize').textContent = data.formatted_size + ' (' + data.size + ' bytes)';
                        document.getElementById('detailModified').textContent = data.last_modified;
                        document.getElementById('detailUrl').textContent = data.url;
                        document.getElementById('detailUrlLink').href = data.url;
                        document.getElementById('detailAbsPath').textContent = data.absolute_path;

                        if (data.is_text && data.content !== null) {
                            document.getElementById('detailTextContent').textContent = data.content;
                            contentContainer.style.display = 'block';
                        }

                        const ext = data.name.split('.').pop().toLowerCase();
                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                            document.getElementById('detailImage').src = data.url;
                            imageContainer.style.display = 'block';
                        }
                    } else {
                        alert(data.message || 'Gagal memuat detail file.');
                        detailModal.hide();
                    }
                })
                .catch(err => {
                    loading.style.display = 'none';
                    alert('Terjadi kesalahan saat memuat detail file.');
                    detailModal.hide();
                });
        });
    });
});
</script>
@endpush
