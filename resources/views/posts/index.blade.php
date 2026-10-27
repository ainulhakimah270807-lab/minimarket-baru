@extends('layouts.dashmin')

@section('title', 'Artikel & Pengumuman Toko')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Artikel & Berita</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="fa fa-newspaper text-primary me-2"></i>Artikel & Pengumuman Toko
            </h4>
            <p class="text-muted small mb-0">Publikasi artikel, berita informasi barang, dan pengumuman operasional toko minimarket.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ url('/admin') }}" class="btn btn-outline-primary shadow-sm">
                <i class="fa fa-tachometer-alt me-1"></i>Ke Dashboard
            </a>
        </div>
    </div>

    <!-- Flash Message Notification -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="background-color: #d1e7dd; border-left: 5px solid #198754 !important;">
            <div class="d-flex align-items-center">
                <i class="fa fa-check-circle fa-lg text-success me-3"></i>
                <div>
                    <strong class="d-block text-success">Berhasil!</strong>
                    <span class="text-success">{{ session('success') }}</span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white rounded-3 p-4 shadow-sm border">
        <!-- Card Header Info -->
        <div class="d-flex flex-wrap align-items-center justify-content-between pb-3 mb-4 border-bottom gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                    <i class="fa fa-boxes fa-2x"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Manajemen Data Posts</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold mt-1">
                        Total Data: {{ count($posts) }} Items
                    </span>
                </div>
            </div>
        </div>

        <!-- Table Responsive -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border">
                <thead class="text-white" style="background: linear-gradient(135deg, #009CFF 0%, #0072ff 100%);">
                    <tr>
                        <th scope="col" style="width: 80px;" class="text-center py-3">ID</th>
                        <th scope="col" class="py-3">Nama Barang / Judul</th>
                        <th scope="col" class="py-3">Deskripsi / Konten</th>
                        <th scope="col" style="width: 180px;" class="text-center py-3">Aksi (DELETE)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td class="text-center fw-bold text-secondary">#{{ $post->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 13px;">
                                        <i class="fa fa-box"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">{{ $post->title }}</span>
                                </div>
                            </td>
                            <td class="text-muted">{{ Str::limit($post->content, 90) }}</td>
                            <td class="text-center">
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data [{{ $post->title }}] ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm rounded-2 px-3">
                                        <i class="fa fa-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="fa fa-inbox fa-3x text-secondary opacity-50 mb-3 d-block"></i>
                                <h6>Belum ada data barang (Posts)</h6>
                                <p class="small text-muted mb-0">Data akan muncul di sini setelah ditambahkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
