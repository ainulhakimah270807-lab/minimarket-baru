@extends('layouts.dashmin')

@section('title', 'Layanan Email')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Header Page & Breadcrumb -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Layanan Email</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1 text-dark"><i class="fa fa-envelope text-primary me-2"></i>Kirim Email dengan Laravel</h4>
            <p class="text-muted small mb-0">Kirim email transaksi, notifikasi antrean latar belakang, dan verifikasi akun pengguna.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary px-3 py-2"><i class="fa fa-server me-1"></i>Driver: {{ strtoupper($mailConfig['mailer']) }}</span>
            <span class="badge bg-secondary px-3 py-2"><i class="fa fa-tasks me-1"></i>Queue: {{ strtoupper($mailConfig['queue']) }}</span>
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

    <!-- Konfigurasi Mail Server Card -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-cogs text-primary me-2"></i>Status Konfigurasi Mail Server (.env)</h5>
                    <span class="badge bg-info text-white px-3 py-2"><i class="fa fa-info-circle me-1"></i>Symfony Mailer</span>
                </div>
                <div class="row g-3">
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="border rounded p-3 bg-white text-center h-100">
                            <small class="text-muted d-block mb-1">MAIL_MAILER</small>
                            <span class="fw-bold text-primary">{{ $mailConfig['mailer'] }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="border rounded p-3 bg-white text-center h-100">
                            <small class="text-muted d-block mb-1">MAIL_HOST</small>
                            <span class="fw-bold text-dark text-truncate d-block" title="{{ $mailConfig['host'] }}">{{ $mailConfig['host'] }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="border rounded p-3 bg-white text-center h-100">
                            <small class="text-muted d-block mb-1">MAIL_PORT</small>
                            <span class="fw-bold text-dark">{{ $mailConfig['port'] }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="border rounded p-3 bg-white text-center h-100">
                            <small class="text-muted d-block mb-1">ENCRYPTION</small>
                            <span class="fw-bold text-dark">{{ $mailConfig['encryption'] }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-2">
                        <div class="border rounded p-3 bg-white text-center h-100">
                            <small class="text-muted d-block mb-1">FROM_ADDRESS</small>
                            <span class="fw-bold text-dark text-truncate d-block" title="{{ $mailConfig['from_address'] }}">{{ $mailConfig['from_address'] }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-2">
                        <div class="border rounded p-3 bg-white text-center h-100">
                            <small class="text-muted d-block mb-1">FROM_NAME</small>
                            <span class="fw-bold text-dark text-truncate d-block" title="{{ $mailConfig['from_name'] }}">{{ $mailConfig['from_name'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3 Form Praktik Email -->
    <div class="row g-4 mb-4">
        <!-- Form 1: WelcomeMail (Mailable Class) -->
        <div class="col-12 col-lg-4">
            <div class="bg-light rounded p-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                        <h6 class="mb-0 text-dark fw-bold"><i class="fa fa-paper-plane text-primary me-2"></i>1. Welcome Mail</h6>
                        <span class="badge bg-primary text-white">Mailable</span>
                    </div>
                    <p class="text-muted small">Mengirim email sambutan menggunakan class <code>App\Mail\WelcomeMail</code> berbasis view template HTML.</p>
                    
                    <form method="POST" action="{{ route('acara32.send.welcome') }}" id="formWelcome">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Nama Penerima</label>
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ $currentUser->name ?? 'Ainul Hakimah' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Email Penerima</label>
                            <input type="email" name="email" class="form-control form-control-sm" value="{{ $currentUser->email ?? 'ainulhakimah270807@gmail.com' }}" required>
                        </div>
                        <div class="d-grid gap-2 mb-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-envelope me-1"></i>Kirim Langsung (send)
                            </button>
                            <button type="submit" formaction="{{ route('acara32.queue.welcome') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-clock me-1"></i>Kirim via Antrean (queue)
                            </button>
                        </div>
                    </form>
                </div>
                <div class="mt-3 pt-3 border-top text-center">
                    <a href="{{ route('acara32.preview.welcome') }}" target="_blank" class="text-decoration-none small fw-semibold text-primary">
                        <i class="fa fa-eye me-1"></i>Preview Template HTML di Browser
                    </a>
                </div>
            </div>
        </div>

        <!-- Form 2: Markdown Mail (NotifyUser) -->
        <div class="col-12 col-lg-4">
            <div class="bg-light rounded p-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                        <h6 class="mb-0 text-dark fw-bold"><i class="fa fa-bell text-success me-2"></i>2. Markdown Email</h6>
                        <span class="badge bg-success text-white">Markdown</span>
                    </div>
                    <p class="text-muted small">Template email responsif dengan komponen Blade Markdown <code>@@component('mail::message')</code>.</p>
                    
                    <form method="POST" action="{{ route('acara32.send.markdown') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Nama Penerima</label>
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ $currentUser->name ?? 'Ainul Hakimah' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Email Penerima</label>
                            <input type="email" name="email" class="form-control form-control-sm" value="{{ $currentUser->email ?? 'ainulhakimah270807@gmail.com' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Isi Pesan Notifikasi</label>
                            <textarea name="pesan" rows="2" class="form-control form-control-sm" required>Pembaruan sistem: Stok produk baru telah diperbarui di inventaris minimarket.</textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="fa fa-paper-plane me-1"></i>Kirim Email Markdown
                        </button>
                    </form>
                </div>
                <div class="mt-3 pt-3 border-top text-center">
                    <a href="{{ route('acara32.preview.markdown') }}" target="_blank" class="text-decoration-none small fw-semibold text-success">
                        <i class="fa fa-eye me-1"></i>Preview Markdown di Browser
                    </a>
                </div>
            </div>
        </div>

        <!-- Form 3: Notification (InvoicePaid) -->
        <div class="col-12 col-lg-4">
            <div class="bg-light rounded p-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                        <h6 class="mb-0 text-dark fw-bold"><i class="fa fa-file-invoice-dollar text-warning me-2"></i>3. Notifikasi Invoice</h6>
                        <span class="badge bg-warning text-dark">Notification</span>
                    </div>
                    <p class="text-muted small">Notifikasi email transaksi otomatis menggunakan class <code>App\Notifications\InvoicePaid</code>.</p>
                    
                    <form method="POST" action="{{ route('acara32.send.notification') }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-dark">Email Penerima</label>
                            <input type="email" name="email" class="form-control form-control-sm" value="{{ $currentUser->email ?? 'ainulhakimah270807@gmail.com' }}" required>
                        </div>
                        <input type="hidden" name="name" value="{{ $currentUser->name ?? 'Ainul Hakimah' }}">
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-dark">Nomor Invoice</label>
                            <input type="text" name="invoice_code" class="form-control form-control-sm" value="INV-{{ date('Ymd') }}-001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Total Transaksi (Rp)</label>
                            <input type="number" name="total" class="form-control form-control-sm" value="185000" required>
                        </div>
                        <button type="submit" class="btn btn-warning text-dark btn-sm w-100 fw-semibold">
                            <i class="fa fa-check-circle me-1"></i>Kirim Notifikasi Invoice
                        </button>
                    </form>
                </div>
                <div class="mt-3 pt-3 border-top text-center">
                    <a href="{{ route('acara32.preview.notification') }}" target="_blank" class="text-decoration-none small fw-semibold text-warning">
                        <i class="fa fa-eye me-1"></i>Preview Notifikasi di Browser
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Verifikasi Email Info & Log Card -->
    <div class="row g-4 mb-4">
        <!-- Verifikasi Email Info -->
        <div class="col-12 col-lg-6">
            <div class="bg-light rounded p-4 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-user-check text-info me-2"></i>Verifikasi Email User (MustVerifyEmail)</h5>
                    <span class="badge bg-info text-white">Security</span>
                </div>
                <p class="text-muted small">Implementasi kontrak antarmuka <code>MustVerifyEmail</code> pada model <code>User</code> untuk memverifikasi keaslian akun pendaftar.</p>

                <div class="bg-white p-3 rounded border mb-3">
                    <h6 class="fw-bold text-dark small mb-2"><i class="fa fa-code text-primary me-1"></i> Setup Model User:</h6>
                    <code class="small d-block text-break">
                        use Illuminate\Contracts\Auth\MustVerifyEmail;<br>
                        class User extends Authenticatable implements MustVerifyEmail
                    </code>
                </div>

                <div class="bg-white p-3 rounded border mb-3">
                    <h6 class="fw-bold text-dark small mb-2"><i class="fa fa-shield-alt text-success me-1"></i> Proteksi Rute dengan Middleware:</h6>
                    <code class="small d-block text-break">
                        Route::middleware(['auth', 'verified'])->group(...);
                    </code>
                </div>

                <div class="alert alert-light border small mb-0">
                    <i class="fa fa-info-circle text-info me-1"></i> Saat pengguna baru mendaftar di <code>/register</code>, event <code>Registered</code> akan secara otomatis mengirimkan email verifikasi bersignature hash aman ke alamat pengguna.
                </div>
            </div>
        </div>

        <!-- Log Pengiriman Email (laravel.log) -->
        <div class="col-12 col-lg-6">
            <div class="bg-light rounded p-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                        <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-history text-secondary me-2"></i>Log Pengiriman Email</h5>
                        <form method="POST" action="{{ route('acara32.clear.logs') }}" class="d-inline" onsubmit="return confirm('Kosongkan riwayat log?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Bersihkan log">
                                <i class="fa fa-trash-alt me-1"></i>Hapus Log
                            </button>
                        </form>
                    </div>
                    <p class="text-muted small">Ketika <code>MAIL_MAILER=log</code>, seluruh email tercatat pada <code>storage/logs/laravel.log</code>.</p>

                    @if(count($emailLogs) > 0)
                    <div class="bg-dark text-light p-3 rounded small font-monospace" style="max-height: 220px; overflow-y: auto;">
                        @foreach($emailLogs as $log)
                        <div class="text-break py-1 border-bottom border-secondary">{{ $log }}</div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4 bg-white rounded border">
                        <i class="fa fa-inbox text-muted fs-2"></i>
                        <p class="text-muted small mt-2 mb-0">Belum ada email yang tercatat pada log. Silakan uji coba kirim email di atas!</p>
                    </div>
                    @endif
                </div>
                <small class="text-muted mt-2 d-block text-end">Driver saat ini: <strong>{{ config('mail.default') }}</strong></small>
            </div>
        </div>
    </div>

    <!-- Cheatsheet & Rangkuman Sintaks Acara 32 -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="bg-light rounded p-4 shadow-sm">
                <div class="d-flex align-items-center mb-3 border-bottom pb-3">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fa fa-book-open text-primary me-2"></i>Rangkuman Sintaks Laravel Mail & Notification</h5>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-paper-plane text-primary me-1"></i> Kirim Langsung</h6>
                            <code class="small d-block text-break">Mail::to($email)->send(new WelcomeMail($user));</code>
                            <small class="text-muted mt-1 d-block">Mengirim email seketika secara synchronous</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-clock text-success me-1"></i> Antrean (Queue)</h6>
                            <code class="small d-block text-break">Mail::to($email)->queue(new WelcomeMail($user));</code>
                            <small class="text-muted mt-1 d-block">Mencegah latency respon halaman web</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-code text-info me-1"></i> Template Markdown</h6>
                            <code class="small d-block text-break">$this->markdown('emails.notify');</code>
                            <small class="text-muted mt-1 d-block">Komponen HTML responsif siap pakai</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="border rounded p-3 bg-white h-100">
                            <h6 class="fw-bold text-dark"><i class="fa fa-bell text-warning me-1"></i> Notifikasi Multi-channel</h6>
                            <code class="small d-block text-break">$user->notify(new InvoicePaid($inv));</code>
                            <small class="text-muted mt-1 d-block">Dukungan email, database, SMS, Slack</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
