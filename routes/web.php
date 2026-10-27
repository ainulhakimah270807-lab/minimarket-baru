<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Acara17Controller;
use App\Http\Controllers\Acara18Controller;
use App\Http\Controllers\Acara19Controller;
use App\Http\Controllers\Acara20Controller;
use App\Http\Controllers\Acara23Controller;
use App\Http\Controllers\Acara24Controller;
use App\Http\Controllers\Acara29Controller;
use App\Http\Controllers\Acara30Controller;
use App\Http\Controllers\Acara31Controller;
use App\Http\Controllers\Acara32Controller;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KasirController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute untuk Halaman Utama / Dashboard POS
Route::get('/', function () {
    return view('welcome', [
        'title' => 'Selamat Datang di Aplikasi POS Toko Kelontong',
        'description' => 'Aplikasi ini membantu mengelola transaksi penjualan, stok produk, dan laporan keuangan toko kelontong Anda.'
    ]);
});

// RUTE DASHBOARD & ADMIN DASHMIN (Umum)
Route::get('/dashboard', function () {
    return view('admin');
})->name('dashboard');

// Rute dengan Parameter Wajib (Melihat detail produk berdasarkan ID)
Route::get('/produk/{id}', function ($id) {
    return 'Menampilkan data produk dengan ID: ' . $id;
});

// Rute dengan Parameter Opsional (Mencari produk berdasarkan nama)
Route::get('/produk/cari/{nama?}', function ($nama = null) {
    if ($nama) {
        return 'Hasil pencarian produk: ' . $nama;
    }
    return 'Silakan masukkan kata kunci pencarian pada URL (contoh: /produk/cari/sabun)';
});

// rute produk toko
Route::get('/produk-toko', function () {
    $produk = [
        [
            'nama' => 'Beras Premium 5kg',
            'sku' => 'BRG-001',
            'harga' => 68000,
            'stok' => 20
        ],
        [
            'nama' => 'Minyak Goreng 2L',
            'sku' => 'BRG-002',
            'harga' => 34000,
            'stok' => 15
        ],
        [
            'nama' => 'Gula Pasir 1kg',
            'sku' => 'BRG-003',
            'harga' => 17500,
            'stok' => 30
        ]
    ];

    return view('daftar_produk', ['produk' => $produk]);
});

// ==========================================
// GROUP RUTE ADMIN (TERPROTEKSI MIDDLEWARE 'auth' & 'admin')
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Halaman Utama Admin (/admin)
    Route::get('/', function () {
        return view('admin');
    })->name('admin');

    // Halaman Kelola Produk Admin (/admin/produk)
    Route::get('/produk', function () {
        return 'Halaman Kelola Produk (Hanya Admin)';
    })->name('admin.produk');

    // Halaman Kelola Kategori Admin (/admin/kategori)
    Route::get('/kategori', function () {
        return 'Halaman Kelola Kategori Produk (Hanya Admin)';
    })->name('admin.kategori');
});

// Group Rute untuk Fitur Kasir (Transaksi - Praktikum Ke-3)
Route::prefix('kasir')->group(function () {
    Route::get('/transaksi', [KasirController::class, 'index'])->name('kasir.transaksi');
});

//kerjaan 2
Route::get('/employee', function () {
    return view('employee');
});

// ✅ RUTE MVC ACARA 6 — /posts (GET & DELETE)
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

// === RUTE MONITORING STATUS TOKO KELONTONG ===
Route::get('/pos-status', function () {
    $jam = (int) request('jam', 10);
    $namaKasir = request('nama', Auth::user()->name ?? 'Ainul Hakimah');
    $isBuka = ($jam >= 8 && $jam <= 21);

    return view('pos-status', compact('jam', 'namaKasir', 'isBuka'));
});

// ✅ RUTE PROFIL PENGGUNA
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// RUTE PRAKTIKUM MINGGU 5 (ACARA 17, 18, 19, 20)
// ==========================================
// Acara 17: Query Builder
Route::get('/acara17', [Acara17Controller::class, 'index'])->name('acara17.index');

// Acara 18: Eloquent CRUD produk
Route::get('/acara18', [Acara18Controller::class, 'index'])->name('acara18.index');
Route::put('/acara18/products/{product}', [Acara18Controller::class, 'update'])->name('acara18.products.update');
Route::delete('/acara18/products/{product}', [Acara18Controller::class, 'destroy'])->name('acara18.products.destroy');

// Acara 19: Relasi kategori, scope stok rendah, dan pemulihan produk
Route::get('/acara19', [Acara19Controller::class, 'index'])->name('acara19.index');
Route::post('/acara19/products/{id}/restore', [Acara19Controller::class, 'restore'])->name('acara19.products.restore');

// Acara 20: Form dan validasi produk/kategori
Route::get('/acara20', [Acara20Controller::class, 'index'])->name('acara20.index');
Route::get('/acara20/generate-sku', [Acara20Controller::class, 'generateSku'])->name('acara20.generate-sku');
Route::post('/acara20/products', [Acara20Controller::class, 'store'])->name('acara20.products.store');
Route::post('/acara20/categories', [Acara20Controller::class, 'storeCategory'])->name('acara20.categories.store');

// Alias Rute Menu Minimarket Terpadu
Route::get('/laporan-stok', [Acara17Controller::class, 'index'])->name('laporan.stok');
Route::get('/manajemen-produk', [Acara18Controller::class, 'index'])->name('produk.index');
Route::get('/tambah-produk', [Acara20Controller::class, 'index'])->name('produk.create');
Route::get('/kategori-produk', [Acara19Controller::class, 'index'])->name('kategori.index');
Route::get('/profil-pengguna', [Acara23Controller::class, 'showProfile'])->name('profil.pengguna');
Route::get('/hak-akses', [Acara24Controller::class, 'index'])->name('hak.akses');
Route::get('/manajemen-berkas', [Acara29Controller::class, 'index'])->name('berkas.index');
Route::get('/cetak-laporan', [Acara30Controller::class, 'index'])->name('cetak.laporan');
Route::get('/olah-foto', [Acara31Controller::class, 'index'])->name('olah.foto');
Route::get('/layanan-email', [Acara32Controller::class, 'index'])->name('layanan.email');

// Acara 23: Authentication (Part 2)
Route::get('/acara23/profile', [Acara23Controller::class, 'showProfile'])->name('acara23.profile');
Route::post('/acara23/logout', [Acara23Controller::class, 'logout'])->name('acara23.logout');

// Acara 24: Authorization (Gates & Policies)
Route::get('/acara24', [Acara24Controller::class, 'index'])->name('acara24.index');
Route::put('/acara24/posts/{post}/gate', [Acara24Controller::class, 'updateWithGate'])->name('acara24.update-gate');
Route::put('/acara24/posts/{post}/policy', [Acara24Controller::class, 'updateWithPolicy'])->name('acara24.update-policy');
Route::get('/acara24/posts/{post}/edit', [Acara24Controller::class, 'editWithMiddleware'])->middleware('can:update,post')->name('acara24.edit-middleware');

// Acara 29: File Storage
Route::get('/acara29', [Acara29Controller::class, 'index'])->name('acara29.index');
Route::post('/acara29/upload', [Acara29Controller::class, 'upload'])->name('acara29.upload');
Route::get('/acara29/download/{filename}', [Acara29Controller::class, 'download'])->name('acara29.download');
Route::get('/acara29/content/{filename}', [Acara29Controller::class, 'showContent'])->name('acara29.content');
Route::delete('/acara29/delete/{filename}', [Acara29Controller::class, 'delete'])->name('acara29.delete');
Route::post('/acara29/put', [Acara29Controller::class, 'putFile'])->name('acara29.put');
Route::post('/acara29/copy', [Acara29Controller::class, 'copy'])->name('acara29.copy');
Route::post('/acara29/rename', [Acara29Controller::class, 'rename'])->name('acara29.rename');

// Acara 30: PDF Report (DomPDF) & Image Manipulation
Route::get('/acara30', [Acara30Controller::class, 'index'])->name('acara30.index');
Route::get('/acara30/pdf/produk', [Acara30Controller::class, 'generateProductPdf'])->name('acara30.pdf.produk');
Route::get('/acara30/pdf/produk/stream', [Acara30Controller::class, 'streamProductPdf'])->name('acara30.pdf.produk.stream');
Route::get('/acara30/pdf/stok', [Acara30Controller::class, 'generateStockPdf'])->name('acara30.pdf.stok');
Route::get('/acara30/pdf/stok/stream', [Acara30Controller::class, 'streamStockPdf'])->name('acara30.pdf.stok.stream');
Route::get('/acara30/pdf/pegawai', [Acara30Controller::class, 'generateEmployeePdf'])->name('acara30.pdf.pegawai');
Route::get('/acara30/pdf/pegawai/stream', [Acara30Controller::class, 'streamEmployeePdf'])->name('acara30.pdf.pegawai.stream');

// Alias Rute Modul BKPM Acara 30 (/laporan-pdf)
Route::get('/laporan-pdf', [LaporanController::class, 'generatePDF'])->name('laporan.pdf');
Route::get('/laporan-pdf/stream', [LaporanController::class, 'streamPDF'])->name('laporan.pdf.stream');

Route::post('/acara30/upload', [Acara30Controller::class, 'uploadImage'])->name('acara30.upload');
Route::post('/acara30/resize', [Acara30Controller::class, 'resizeImage'])->name('acara30.resize');
Route::post('/acara30/rotate', [Acara30Controller::class, 'rotateImage'])->name('acara30.rotate');
Route::post('/acara30/crop', [Acara30Controller::class, 'cropImage'])->name('acara30.crop');
// Acara 31: Laravel Image Manipulation (Intervention Image)
Route::get('/acara31', [Acara31Controller::class, 'index'])->name('acara31.index');
Route::post('/acara31/upload', [Acara31Controller::class, 'upload'])->name('acara31.upload');
Route::post('/acara31/resize', [Acara31Controller::class, 'resize'])->name('acara31.resize');
Route::post('/acara31/crop', [Acara31Controller::class, 'crop'])->name('acara31.crop');
Route::post('/acara31/watermark', [Acara31Controller::class, 'watermark'])->name('acara31.watermark');
Route::post('/acara31/rotate', [Acara31Controller::class, 'rotate'])->name('acara31.rotate');
Route::post('/acara31/encode', [Acara31Controller::class, 'encode'])->name('acara31.encode');
Route::get('/acara31/response/{filename}', [Acara31Controller::class, 'response'])->name('acara31.response');
Route::delete('/acara31/delete/{filename}', [Acara31Controller::class, 'delete'])->name('acara31.delete');

// Acara 32: Laravel Email
Route::get('/acara32', [Acara32Controller::class, 'index'])->name('acara32.index');
Route::post('/acara32/send-welcome', [Acara32Controller::class, 'sendWelcome'])->name('acara32.send.welcome');
Route::post('/acara32/queue-welcome', [Acara32Controller::class, 'queueWelcome'])->name('acara32.queue.welcome');
Route::post('/acara32/send-markdown', [Acara32Controller::class, 'sendMarkdown'])->name('acara32.send.markdown');
Route::post('/acara32/send-notification', [Acara32Controller::class, 'sendNotification'])->name('acara32.send.notification');
Route::get('/acara32/preview/welcome', [Acara32Controller::class, 'previewWelcome'])->name('acara32.preview.welcome');
Route::get('/acara32/preview/markdown', [Acara32Controller::class, 'previewMarkdown'])->name('acara32.preview.markdown');
Route::get('/acara32/preview/notification', [Acara32Controller::class, 'previewNotification'])->name('acara32.preview.notification');
Route::post('/acara32/clear-logs', [Acara32Controller::class, 'clearLogs'])->name('acara32.clear.logs');

require __DIR__.'/auth.php';