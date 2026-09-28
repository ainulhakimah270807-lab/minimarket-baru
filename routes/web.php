<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Acara17Controller;
use App\Http\Controllers\Acara18Controller;
use App\Http\Controllers\Acara19Controller;
use App\Http\Controllers\Acara20Controller;
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
//ainul jelek
// ardhie ganteng sjsjdsjjhshdsf

// sadd
// as
// dda
Route::get('/', function () {

return view('welcome', [
    'title' => 'Selamat Datang di Aplikasi POS Toko Kelontong',
    'description' => 'Aplikasi ini membantu mengelola transaksi penjualan, stok produk, dan laporan keuangan toko kelontong Anda.'
]);
});

// RUTE DASHBOARD & ADMIN DASHMIN
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

// Group Rute untuk Fitur Admin (Manajemen Data)
Route::prefix('admin')->group(function () {
    Route::get('/produk', function () {
        return 'Halaman Kelola Produk (Hanya Admin)';
    })->name('admin.produk');

    Route::get('/kategori', function () {
        return 'Halaman Kelola Kategori Produk (Hanya Admin)';
    })->name('admin.kategori');
});

// Group Rute untuk Fitur Kasir (Transaksi)
Route::prefix('kasir')->group(function () {
    Route::get('/transaksi', function () {
        return 'Halaman Input Transaksi Penjualan (Kasir)';
    })->name('kasir.transaksi');
});

//kerjaan 2
Route::get('/employee', function () {
    return view('employee');
});

Route::get('/admin', function () {
    return view('admin');
})->name('admin');

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

// Alias Rute Menu Minimarket
Route::get('/laporan-stok', [Acara17Controller::class, 'index'])->name('laporan.stok');
Route::get('/manajemen-produk', [Acara18Controller::class, 'index'])->name('produk.index');
Route::get('/tambah-produk', [Acara20Controller::class, 'index'])->name('produk.create');
Route::get('/arsip-kategori', [Acara19Controller::class, 'index'])->name('kategori.index');

require __DIR__.'/auth.php';

