@extends('acara.layout')

@section('title', 'Acara 20: Form & Validation - Hasil Praktikum')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-check2-circle text-warning me-2"></i>ACARA 20: Form and Validation</h2>
        <p class="text-muted mb-0">Minggu 5/4 &bull; Implementasi CSRF, Validasi Controller, Pesan Kustom, Form Request, & Custom Rule</p>
    </div>
    <span class="badge bg-warning text-dark px-3 py-2 fs-6">Praktikum Minggu 5</span>
</div>

<!-- Pesan Sukses Global -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
    <strong>Berhasil!</strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Format Tampilan Pesan Error (Sesuai Modul Hal 21) -->
@if ($errors->any())
<div class="alert alert-danger shadow-sm">
    <h5 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Validasi Gagal! Periksa input berikut:</h5>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row g-4">
    <!-- Form 1: Validasi Standar di Controller -->
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-shield-check me-2"></i>1 & 2. Validasi Standar di Controller</span>
                <span class="badge bg-light text-primary">Controller</span>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    Menggunakan <code>$request->validate()</code> langsung di controller. Aturan: name (3-50 kar.), email valid, password min 6 & confirmed.
                </p>

                <form action="{{ route('acara20.controller') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name1" class="form-label fw-bold">Nama Lengkap:</label>
                        <input type="text" name="name" id="name1" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso">
                    </div>

                    <div class="mb-3">
                        <label for="email1" class="form-label fw-bold">Alamat Email:</label>
                        <input type="email" name="email" id="email1" class="form-control" value="{{ old('email') }}" placeholder="nama@domain.com">
                    </div>

                    <div class="mb-3">
                        <label for="password1" class="form-label fw-bold">Password:</label>
                        <input type="password" name="password" id="password1" class="form-control" placeholder="Minimal 6 karakter">
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation1" class="form-label fw-bold">Konfirmasi Password:</label>
                        <input type="password" name="password_confirmation" id="password_confirmation1" class="form-control" placeholder="Ulangi password di atas">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send me-1"></i>Kirim (Uji Validasi Standar)
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Form 2: Custom Validation Message -->
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-chat-quote me-2"></i>3. Custom Validation Message</span>
                <span class="badge bg-light text-success">Custom Msg</span>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    Mengganti pesan bawaan Laravel dengan array pesan berbahasa Indonesia: <i>"Nama harus diisi!"</i>, <i>"Email tidak boleh kosong!"</i>, <i>"Password tidak cocok!"</i>.
                </p>

                <form action="{{ route('acara20.custom-message') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name2" class="form-label fw-bold">Nama Lengkap:</label>
                        <input type="text" name="name" id="name2" class="form-control" value="{{ old('name') }}" placeholder="Kosongkan untuk menguji pesan kustom">
                    </div>

                    <div class="mb-3">
                        <label for="email2" class="form-label fw-bold">Alamat Email:</label>
                        <input type="text" name="email" id="email2" class="form-control" value="{{ old('email') }}" placeholder="Kosongkan untuk menguji pesan kustom">
                    </div>

                    <div class="mb-3">
                        <label for="password2" class="form-label fw-bold">Password:</label>
                        <input type="password" name="password" id="password2" class="form-control" placeholder="Ketik beda dengan konfirmasi">
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation2" class="form-label fw-bold">Konfirmasi Password:</label>
                        <input type="password" name="password_confirmation" id="password_confirmation2" class="form-control" placeholder="Ketik berbeda">
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-send me-1"></i>Kirim (Uji Pesan Kustom)
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Form 3: Validasi Menggunakan Form Request -->
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header bg-info text-dark d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-earmark-code me-2"></i>4. Form Request (UserRequest)</span>
                <span class="badge bg-dark text-white">php artisan make:request</span>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    Memisahkan logika validasi ke dalam class <code>app/Http/Requests/UserRequest.php</code> dengan rule <code>unique:users,email</code>, <code>min:3</code>, dan pesan kustom.
                </p>

                <form action="{{ route('acara20.form-request') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name3" class="form-label fw-bold">Nama Lengkap:</label>
                        <input type="text" name="name" id="name3" class="form-control" value="{{ old('name') }}" placeholder="Min 3 karakter">
                    </div>

                    <div class="mb-3">
                        <label for="email3" class="form-label fw-bold">Alamat Email (Unik):</label>
                        <input type="email" name="email" id="email3" class="form-control" value="{{ old('email') }}" placeholder="Gunakan johndoe@example.com untuk uji unique">
                    </div>

                    <div class="mb-3">
                        <label for="password3" class="form-label fw-bold">Password:</label>
                        <input type="password" name="password" id="password3" class="form-control" placeholder="Min 6 karakter">
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation3" class="form-label fw-bold">Konfirmasi Password:</label>
                        <input type="password" name="password_confirmation" id="password_confirmation3" class="form-control" placeholder="Harus sama persis">
                    </div>

                    <button type="submit" class="btn btn-info w-100 text-dark fw-bold">
                        <i class="bi bi-send me-1"></i>Kirim (Uji Form Request)
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Form 4: Validasi Kustom (Custom Rule Uppercase) -->
    <div class="col-lg-6">
        <div class="card card-custom h-100">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-type me-2"></i>5. Validasi Kustom (Rule: Uppercase)</span>
                <span class="badge bg-warning text-dark">php artisan make:rule</span>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    Menggunakan custom rule <code>App\Rules\Uppercase</code>. Nilai field input <b>harus seluruhnya huruf kapital</b> (uppercase).
                </p>

                <form action="{{ route('acara20.custom-rule') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name4" class="form-label fw-bold">Nama (Wajib Huruf Kapital):</label>
                        <input type="text" name="name" id="name4" class="form-control" value="{{ old('name') }}" placeholder="Contoh salah: Budi, Contoh benar: BUDI">
                        <small class="text-muted">Coba ketik "budi" untuk melihat error "name harus dalam huruf kapital."</small>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 mt-4">
                        <i class="bi bi-send me-1"></i>Kirim (Uji Custom Rule Uppercase)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
