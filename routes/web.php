<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
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
Route::get('/', function () {

return view('welcome', [
    'title' => 'Selamat Datang di Aplikasi POS Toko Kelontong',
    'description' => 'Aplikasi ini membantu mengelola transaksi penjualan, stok produk, dan laporan keuangan toko kelontong Anda.'
]);
});

// RUTE DASHBOARD & ADMIN DASHMIN (DILINDUNGI AUTH BREEZE)
Route::get('/dashboard', function () {
    return view('admin');
})->middleware(['auth', 'verified'])->name('dashboard');

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
})->middleware(['auth', 'verified'])->name('admin');

// ✅ RUTE MVC ACARA 6 — /posts (GET & DELETE)
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

// === RUTE MONITORING STATUS TOKO KELONTONG ===
Route::get('/pos-status', function () {
    $jam = request('jam', 10);
    $namaKasir = request('nama', 'Pengguna');
    $pesan = "<h2>=== SISTEM MONITORING TOKO KELONTONG ===</h2>";
    $pesan .= "<p>Halo <strong>$namaKasir</strong>, Status Toko pada jam $jam:00 WIB adalah:</p>";

    if ($jam >= 8 && $jam <= 21) {
        $pesan .= "<h3 style='color:green;'>✅ BUKA</h3>";
        $pesan .= "<p>Silakan kasir bersiap di meja transaksi.</p>";
    } else {
        $pesan .= "<h3 style='color:red;'>🔴 TUTUP</h3>";
        $pesan .= "<p>Di luar jam operasional (08:00–21:00).</p>";
    }

    $pesan .= "
    <hr>
    <form method='get'>
        Nama Kasir: <input type='text' name='nama' placeholder='Masukkan nama' required>
        Jam: <input type='number' name='jam' min='0' max='23' placeholder='contoh: 10'>
        <button type='submit'>Cek Status</button>
    </form>
    ";

    return $pesan;
});

// ✅ RUTE PROFIL PENGGUNA
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
