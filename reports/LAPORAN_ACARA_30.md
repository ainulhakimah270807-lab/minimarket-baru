# LAPORAN PRAKTIKUM
## WORKSHOP SISTEM INFORMASI WEB FRAMEWORK

---

**ACARA 30: MEMBUAT LAPORAN PDF DENGAN DOMPDF**  
**Minggu Praktikum**: Minggu Ke-8/9  
**Program Studi**: D4 Teknik Informatika  
**Jurusan**: Teknologi Informasi - Politeknik Negeri Jember  

---

### IDENTITAS MAHASISWA
- **Nama Mahasiswa** : Ainul Hakimah
- **NIM**            : E41252793
- **Golongan**       : A
- **Dosen Pengampu** : David Juli Ariyadi, S.Kom., M.Kom
- **Tahun Akademik** : 2026/2027

---

## BAB I: PENDAHULUAN

### 1.1 Latar Belakang
Dalam sistem informasi operasional toko dan kasir (Point of Sales), penyajian data dalam format dokumen digital portabel (Portable Document Format / PDF) merupakan kebutuhan mutlak. Berkas PDF digunakan untuk mencetak laporan penjualan harian, laporan stok barang kritis, slip gaji pegawai, hingga faktur pembelian resmi. Keunggulan utama PDF adalah konsistensi tata letak visual, margin, dan tipografi di semua perangkat dan sistem operasi.

Dalam ekosistem framework Laravel, salah satu pustaka yang paling banyak digunakan untuk menghasilkan berkas PDF adalah **DomPDF** melalui pembungkus (wrapper) `barryvdh/laravel-dompdf`. Library ini merender dokumen PDF langsung dari sintaksis HTML dan CSS yang didefinisikan pada template Blade Laravel. Praktikum Acara 30 berfokus pada instalasi library DomPDF, perancangan template dokumen berbasis Blade, konfigurasi ukuran dan orientasi kertas (A4 Portrait/Landscape), integrasi data dinamis dari basis data via Eloquent ORM, serta penyajian dokumen melalui mekanisme unduh langsung (`download`) maupun pratinjau dalam peramban (`stream`).

### 1.2 Tujuan Praktikum
1. Mahasiswa mampu menginstal dan mengonfigurasi package `barryvdh/laravel-dompdf` pada proyek Laravel.
2. Mahasiswa mampu merancang template Blade dokumen PDF menggunakan kode HTML semantik dan styling CSS yang kompatibel dengan DomPDF.
3. Mahasiswa mampu menghubungkan data dinamis dari basis data menggunakan Eloquent ORM (`Product`, `Category`, `Employee`) ke dalam view laporan PDF.
4. Mahasiswa mampu mengonfigurasi ukuran kertas (`A4`, `Letter`) dan orientasi halaman (`portrait`, `landscape`) menggunakan metode `setPaper()`.
5. Mahasiswa mampu mengimplementasikan metode `download()` untuk mengunduh dokumen secara otomatis dan metode `stream()` untuk menampilkan PDF langsung di browser.
6. Mahasiswa memahami keterbatasan CSS pada DomPDF dan menerapkan teknik penulisan CSS sederhana (border, padding, page-break, text-align) agar dokumen terender tanpa kesalahan.

### 1.3 Dasar Teori
1. **Pustaka DomPDF**:
   DomPDF adalah pustaka perender HTML ke PDF berbasis PHP yang sebagian besar mematuhi spesifikasi CSS 2.1. DomPDF mendukung tabel bersarang, warna latar, batas (borders), serta pemisahan halaman otomatis (*page break*).
2. **Library `barryvdh/laravel-dompdf`**:
   Paket ini menyediakan facade `PDF` (`Barryvdh\DomPDF\Facade\Pdf`) yang terintegrasi dengan ekosistem Laravel sehingga memudahkan perenderan view Blade:
   - `PDF::loadView('view.name', $data)`: Memuat template Blade beserta data array/objek yang akan dirender.
   - `PDF::loadHTML($htmlString)`: Memuat string HTML murni.
   - `->setPaper('a4', 'portrait|landscape')`: Mengatur dimensi dan orientasi media cetak.
   - `->download('filename.pdf')`: Menghasilkan HTTP response dengan header `Content-Disposition: attachment; filename="..."`.
   - `->stream('filename.pdf')`: Menghasilkan HTTP response dengan header `Content-Disposition: inline; filename="..."` agar dokumen terbuka di tab browser.
3. **Tips dan Kendala Umum DomPDF**:
   - *CSS Terbatas*: Properti modern seperti Flexbox atau CSS Grid tidak didukung penuh. Dianjurkan menggunakan layout tabel tradisional (`<table>`, `<tr>`, `<td>`) dan float sederhana.
   - *Jalur Gambar Lokal*: Untuk menyisipkan gambar lokal (seperti logo atau stempel), disarankan menggunakan fungsi `public_path('path/ke/gambar.png')` guna memberikan jalur absolut lokal.
   - *Page Break*: Tag `<div style="page-break-after: always;"></div>` digunakan untuk memaksa pemisahan halaman cetak.

---

## BAB II: HASIL DAN PEMBAHASAN

### 2.1 Alat dan Bahan
1. **Perangkat Keras**: Laptop Core i3/i5, RAM 8GB, SSD 512GB.
2. **Perangkat Lunak**:
   - Web Server: Laragon (PHP 8.2+, Apache/Nginx, MySQL)
   - Framework: Laravel 10.x
   - Library Tambahan: `barryvdh/laravel-dompdf`
   - Editor: Visual Studio Code / Antigravity IDE

### 2.2 Prosedur dan Langkah Kerja

1. **Instalasi Package DomPDF**:
   Menginstal pustaka `barryvdh/laravel-dompdf` menggunakan Composer:
   ```bash
   composer require barryvdh/laravel-dompdf
   ```

2. **Mendaftarkan Service Provider dan Facade (Bila Diperlukan)**:
   Meskipun Laravel mendukung *Package Auto-Discovery*, alias facade didaftarkan pada `config/app.php`:
   ```php
   'aliases' => Facade::defaultAliases()->merge([
       'PDF' => Barryvdh\DomPDF\Facade\Pdf::class,
   ])->toArray(),
   ```

3. **Membuat Controller Modul Dasar (`LaporanController.php`)**:
   Sesuai instruksi dasar pada modul BKPM Acara 30, dibuat `LaporanController` yang memuat data contoh pegawai dan menghasilkan file PDF:
   ```php
   namespace App\Http\Controllers;
   use Barryvdh\DomPDF\Facade\Pdf;

   class LaporanController extends Controller
   {
       public function generatePDF()
       {
           $pegawai = [
               ['nama' => 'Budi Santoso', 'jabatan' => 'Manager Toko'],
               ['nama' => 'Siti Aminah', 'jabatan' => 'Kasir Utama'],
               ['nama' => 'Rian Hidayat', 'jabatan' => 'Staf Gudang & Logistik'],
               ['nama' => 'Dewi Lestari', 'jabatan' => 'Pramuniaga'],
           ];

           $pdf = Pdf::loadView('laporan.pegawai', compact('pegawai'))
                     ->setPaper('a4', 'portrait');

           return $pdf->download('laporan-pegawai.pdf');
       }

       public function streamPDF()
       {
           $pegawai = [
               ['nama' => 'Budi Santoso', 'jabatan' => 'Manager Toko'],
               ['nama' => 'Siti Aminah', 'jabatan' => 'Kasir Utama'],
           ];

           $pdf = Pdf::loadView('laporan.pegawai', compact('pegawai'))
                     ->setPaper('a4', 'portrait');

           return $pdf->stream('laporan-pegawai.pdf');
       }
   }
   ```

4. **Membuat Template Blade PDF (`resources/views/laporan/`)**:
   Dibuat beberapa template dokumen cetak yang bersih dan profesional:
   - `resources/views/laporan/pegawai.blade.php`: Laporan daftar pegawai (A4 Portrait).
   - `resources/views/laporan/produk.blade.php`: Laporan katalog master produk toko lengkap dengan kategori, harga, dan status stok (A4 Portrait).
   - `resources/views/laporan/stok.blade.php`: Laporan inventaris audit stok barang minimarket lengkap dengan nilai aset (A4 Landscape).

5. **Membuat Controller Terpadu Minimarket (`Acara30Controller.php`)**:
   Menghubungkan model Eloquent ORM ke dalam laporan cetak:
   - Mengambil data produk dengan Eager Loading: `Product::with('category')->get()`.
   - Mengambil data stok kritis: `Product::with('category')->where('stock', '<=', 10)->get()`.
   - Menghitung rekapitulasi total item, total kuantitas stok, dan total valuasi nominal aset toko.

6. **Mendaftarkan Rute Laporan (`routes/web.php`)**:
   ```php
   // Rute Modul BKPM Acara 30
   Route::get('/laporan-pdf', [LaporanController::class, 'generatePDF'])->name('laporan.pdf');
   Route::get('/laporan-pdf/stream', [LaporanController::class, 'streamPDF'])->name('laporan.pdf.stream');

   // Rute Interaktif Acara 30 POS
   Route::get('/acara30', [Acara30Controller::class, 'index'])->name('acara30.index');
   Route::get('/acara30/pdf/produk', [Acara30Controller::class, 'generateProductPdf'])->name('acara30.pdf.produk');
   Route::get('/acara30/pdf/produk/stream', [Acara30Controller::class, 'streamProductPdf'])->name('acara30.pdf.produk.stream');
   Route::get('/acara30/pdf/stok', [Acara30Controller::class, 'generateStockPdf'])->name('acara30.pdf.stok');
   Route::get('/acara30/pdf/stok/stream', [Acara30Controller::class, 'streamStockPdf'])->name('acara30.pdf.stok.stream');
   Route::get('/acara30/pdf/pegawai', [Acara30Controller::class, 'generateEmployeePdf'])->name('acara30.pdf.pegawai');
   Route::get('/acara30/pdf/pegawai/stream', [Acara30Controller::class, 'streamEmployeePdf'])->name('acara30.pdf.pegawai.stream');
   ```

7. **Pengujian Fitur Otomatis (`Acara30Test.php`)**:
   Menguji perenderan halaman, response header unduhan PDF (`application/pdf`, `attachment`), dan response stream inline (`inline`).

### 2.3 Implementasi Kode Program

#### A. Template Blade PDF Laporan Pegawai (`laporan/pegawai.blade.php`)
```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Pegawai</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .kop { text-align: center; border-bottom: 2px solid #2b3a4a; padding-bottom: 10px; margin-bottom: 20px; }
        .kop h2 { margin: 0; font-size: 18px; color: #1e3a8a; text-transform: uppercase; }
        .kop p { margin: 2px 0; font-size: 11px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 10px; }
        th { background-color: #f1f5f9; color: #1e293b; font-weight: bold; text-align: left; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .footer { margin-top: 30px; font-size: 10px; text-align: right; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="kop">
        <h2>MINIMARKET POS MANAGEMENT</h2>
        <p>Laporan Resmi Kepegawaian & Sumber Daya Manusia</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th>Nama Lengkap</th>
                <th>Posisi / Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawai as $index => $p)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $p['nama'] }}</td>
                <td>{{ $p['jabatan'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis pada: {{ date('d F Y, H:i:s') }} WIB
    </div>
</body>
</html>
```

#### B. Controller Perender PDF Lanskap (`Acara30Controller.php`)
```php
public function generateStockPdf()
{
    $products = Product::with('category')->orderBy('stock', 'asc')->get();
    $totalStock = $products->sum('stock');
    $totalAsset = $products->sum(fn($p) => $p->price * $p->stock);

    $pdf = Pdf::loadView('laporan.stok', [
        'products' => $products,
        'totalStock' => $totalStock,
        'totalAsset' => $totalAsset,
        'printedAt' => now()->translatedFormat('d F Y H:i:s'),
    ])->setPaper('a4', 'landscape');

    return $pdf->download('laporan-stok-minimarket-' . date('Ymd_His') . '.pdf');
}
```

### 2.4 Hasil Pengujian dan Evaluasi

Pengujian otomatis dieksekusi dengan perintah:
```bash
php artisan test --filter=Acara30Test
```
**Hasil Eksekusi**:
```text
   PASS  Tests\Feature\Acara30Test
  ✓ can view acara30 index page
  ✓ can generate laporan pdf download from laporan controller
  ✓ can stream laporan pdf from laporan controller
  ✓ can generate product pdf download
  ✓ can stream product pdf
  ✓ can generate stock pdf download
  ✓ can stream stock pdf
  ✓ can generate employee pdf download
  ✓ can stream employee pdf
  ✓ can upload image for manipulation

  Tests:    10 passed (24 assertions)
  Duration: 1.25s
```
Semua 10 pengujian berhasil lulus. Header HTTP response mengembalikan `content-type: application/pdf` dengan atribut `Content-Disposition` yang akurat baik untuk mode download maupun mode stream.

---

## BAB III: KESIMPULAN

Dari serangkaian praktikum Acara 30 yang telah dilakukan, dapat diambil kesimpulan sebagai berikut:
1. Pustaka `barryvdh/laravel-dompdf` mempermudah pembuatan dokumen laporan profesional dalam Laravel dengan memanfaatkan kekuatan templating Blade.
2. Pengembang dapat mengatur tata letak cetak secara presisi menggunakan metode `setPaper()` untuk memilih dimensi ukuran kertas (`A4`) dan orientasi (`portrait` atau `landscape`).
3. Dukungan terhadap dua mode penyajian—`download()` untuk pengunduhan berkas fisik dan `stream()` untuk pratinjau instan di peramban—memberikan fleksibilitas tinggi bagi kenyamanan pengguna.
4. Desain dokumen PDF yang stabil dicapai dengan menggunakan sintaksis CSS dasar (`border-collapse`, `padding`, `width`, `background-color`, dan `sans-serif`) serta menghindari pemakaian properti CSS tingkat lanjut yang belum didukung oleh engine DomPDF.
5. Integrasi dengan data Eloquent ORM berjalan mulus dan efisien melalui pemanfaatan relasi eager loading.

---

## DAFTAR PUSTAKA

1. Barryvdh. (2026). *Laravel DOMPDF Wrapper Repository*. GitHub: https://github.com/barryvdh/laravel-dompdf
2. DomPDF. (2026). *DomPDF: HTML to PDF converter for PHP*. GitHub: https://github.com/dompdf/dompdf
3. Ariyadi, D. J. (2026). *Buku Kerja Praktik Mahasiswa (BKPM): Workshop Sistem Informasi Web Framework*. Jurusan Teknologi Informasi, Politeknik Negeri Jember.
4. Laravel LLC. (2026). *Blade Templates Documentation*. https://laravel.com/docs/10.x/blade
