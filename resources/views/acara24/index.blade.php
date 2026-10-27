@extends('layouts.dashmin')

@section('title', 'Hak Akses & Otorisasi Pengguna')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hak Akses User</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="fa fa-user-shield text-primary me-2"></i>Hak Akses & Otorisasi Pengguna
            </h4>
            <p class="text-muted small mb-0">Kontrol izin akses data pos dan aturan privasi berdasarkan peran pengguna serta kepemilikan data.</p>
        </div>
        <div>
            <a href="{{ url('/admin') }}" class="btn btn-outline-primary shadow-sm">
                <i class="fa fa-tachometer-alt me-1"></i>Ke Dashboard
            </a>
        </div>
    </div>

    <!-- Alert Message -->
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="background-color: #d1e7dd; border-left: 5px solid #198754 !important;">
            <div class="d-flex align-items-center">
                <i class="fa fa-check-circle fa-lg text-success me-3"></i>
                <div class="text-success">
                    <strong>Informasi Otorisasi:</strong> {{ session('message') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Info Status User Card -->
    <div class="bg-white rounded-3 p-4 shadow-sm border mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                    <i class="fa fa-user-check fa-2x"></i>
                </div>
                <div>
                    @auth
                        <h6 class="fw-bold text-dark mb-1">Status Pengguna Aktif</h6>
                        <span class="badge bg-primary px-3 py-1 rounded-pill">
                            <i class="fa fa-user me-1"></i>{{ Auth::user()->name }} (ID: #{{ Auth::id() }})
                        </span>
                    @else
                        <h6 class="fw-bold text-danger mb-1">Status Pengguna: Belum Login</h6>
                        <span class="badge bg-danger px-3 py-1 rounded-pill">Guest</span>
                    @endauth
                </div>
            </div>
            <div class="text-muted small max-w-md">
                <i class="fa fa-info-circle text-primary me-1"></i>
                Fitur ini membatasi aksi edit postingan hanya untuk pengguna yang memiliki kewenangan (Penulis / Admin).
            </div>
        </div>
    </div>

    <!-- Main Posts Grid -->
    <div class="row g-4">
        @forelse($posts as $post)
            <div class="col-12 col-lg-6">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-light border-bottom p-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">
                            <i class="fa fa-file-alt me-2"></i>Post #{{ $post->id }}
                        </span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1 small">
                            Penulis: {{ $post->user->name ?? 'User #'.$post->user_id }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-2">{{ $post->title }}</h5>
                        <p class="text-muted mb-4">{{ $post->content }}</p>

                        <!-- Action Buttons Gate & Policy -->
                        <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                            {{-- 1. Gate Check --}}
                            @can('edit-post', $post)
                                <form action="{{ route('acara24.update-gate', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="title" value="{{ $post->title }} (Edit via Gate)">
                                    <button type="submit" class="btn btn-sm btn-primary shadow-sm">
                                        <i class="fa fa-key me-1"></i>Edit via Gate (Disetujui)
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-light border text-muted" disabled>
                                    <i class="fa fa-lock me-1"></i>Gate Ditolak
                                </button>
                            @endcan

                            {{-- 2. Policy Check --}}
                            @can('update', $post)
                                <form action="{{ route('acara24.update-policy', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="title" value="{{ $post->title }} (Edit via Policy)">
                                    <button type="submit" class="btn btn-sm btn-success shadow-sm">
                                        <i class="fa fa-user-shield me-1"></i>Edit via Policy (Disetujui)
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-light border text-muted" disabled>
                                    <i class="fa fa-ban me-1"></i>Policy Unauthorized
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="bg-white rounded-3 p-5 text-center shadow-sm border">
                    <i class="fa fa-folder-open fa-3x text-secondary opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark">Belum Ada Postingan</h5>
                    <p class="text-muted mb-0">Silakan tambahkan data postingan terlebih dahulu.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
