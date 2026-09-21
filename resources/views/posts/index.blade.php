@extends('layouts.dashmin')

@section('title', 'Daftar Data Barang (Posts)')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Notifikasi Flash Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="bg-light text-center rounded p-4 shadow-sm">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="text-start">
                <h5 class="mb-1 text-primary"><i class="fa fa-boxes me-2"></i>Daftar Postingan Barang Minimarket</h5>
                <small class="text-muted">Praktikum Implementasi HTTP Route Method GET & DELETE</small>
            </div>
            <a href="{{ url('/admin') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-tachometer-alt me-1"></i>Ke Dashboard
            </a>
        </div>

        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-dark table-secondary">
                        <th scope="col" style="width: 70px;" class="text-center">ID</th>
                        <th scope="col">Nama Barang / Judul</th>
                        <th scope="col">Deskripsi / Konten</th>
                        <th scope="col" style="width: 170px;" class="text-center">Aksi (Route DELETE)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td class="text-center fw-bold">{{ $post->id }}</td>
                            <td class="fw-semibold text-primary">{{ $post->title }}</td>
                            <td>{{ $post->content }}</td>
                            <td class="text-center">
                                <!-- Form Route DELETE Method Spoofing -->
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data [{{ $post->title }}] ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm">
                                        <i class="fa fa-trash me-1"></i>Hapus Data
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fa fa-info-circle me-1"></i>Belum ada data barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
