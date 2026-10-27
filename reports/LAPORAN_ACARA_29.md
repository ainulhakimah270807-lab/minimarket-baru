# LAPORAN PRAKTIKUM
## WORKSHOP SISTEM INFORMASI WEB FRAMEWORK

---

**ACARA 29: LARAVEL FILE STORAGE**  
**Minggu Praktikum**: Minggu Ke-8  
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
Pengelolaan berkas (file management) merupakan komponen fundamental dalam pembangunan aplikasi web modern, khususnya pada sistem Point of Sales (POS) dan e-commerce. Sistem membutuhkan kemampuan untuk mengunggah berkas nota transaksi, dokumen laporan, berkas ekspor data, hingga arsip lampiran produk secara terstruktur dan aman.

Framework Laravel menyediakan abstraksi sistem berkas yang sangat kuat dan fleksibel melalui integrasi paket PHP Flysystem. Abstraksi ini memungkinkan pengembang untuk berinteraksi dengan berbagai driver penyimpanan berkas—mulai dari sistem berkas lokal (`local`, `public`), FTP, hingga layanan penyimpanan awan (cloud storage) seperti Amazon S3—hanya dengan satu API yang seragam (`Storage` facade). Melalui praktikum Acara 29 ini, mahasiswa mempelajari arsitektur penyimpanan berkas Laravel, mekanisme validasi berkas formulir, pembuatan symbolic link publik, serta berbagai operasi manipulasi berkas secara programatik.

### 1.2 Tujuan Praktikum
1. Mahasiswa memahami konsep arsitektur file storage dan konfigurasi disk pada Laravel (`config/filesystems.php`).
2. Mahasiswa mampu mengonfigurasi dan menghubungkan disk `public` ke direktori yang dapat diakses publik melalui perintah `php artisan storage:link`.
3. Mahasiswa mampu membangun formulir unggah berkas berbasis Blade dengan atribut `enctype="multipart/form-data"` dan proteksi `@csrf`.
4. Mahasiswa mampu menerapkan validasi berkas unggahan meliputi format MIME (`jpg, png, pdf, docx, csv, xlsx`) dan batas maksimal ukuran berkas (2MB).
5. Mahasiswa mampu menerapkan berbagai strategi penamaan berkas unik (hash default, nama asli, timestamp, dan UUID) untuk mencegah konflik berkas.
6. Mahasiswa mampu menggunakan ragam fungsi manipulasi berkas pada facade `Storage` (`put`, `get`, `exists`, `delete`, `move`, `copy`, `download`, `url`, dan `path`).

### 1.3 Dasar Teori
1. **Konfigurasi File System (`config/filesystems.php`)**:
   Laravel mendefinisikan beberapa disk penyimpanan bawaan:
   - `local`: Berkas disimpan pada direktori privat `storage/app` yang tidak dapat diakses langsung via URL browser.
   - `public`: Berkas disimpan pada `storage/app/public` yang dapat diakses publik setelah pembuatan symlink ke `public/storage`.
2. **Symbolic Link (`storage:link`)**:
   Untuk mengekspos berkas dari `storage/app/public` ke web, Laravel menyediakan perintah Artisan:
   ```bash
   php artisan storage:link
   ```
   Perintah ini membuat symbolic link dari direktori `public/storage` yang merujuk ke direktori `storage/app/public`.
3. **Validasi File pada Request**:
   Laravel menyediakan aturan validasi berkas:
   - `required`: Field wajib diisi berkas.
   - `file`: Memastikan input adalah berkas yang berhasil diunggah.
   - `mimes:types,...`: Membatasi ekstensi/tipe MIME berkas yang diizinkan.
   - `max:kilobytes`: Membatasi ukuran berkas maksimal dalam satuan kilobita (KB).
4. **Metode Penyimpanan Berkas**:
   - `$file->store('folder', 'disk')`: Menyimpan berkas dengan nama hash acak.
   - `$file->storeAs('folder', 'nama_kustom.ext', 'disk')`: Menyimpan berkas dengan nama yang ditentukan pengembang.
5. **Facade `Storage`**:
   Menyediakan fungsi manipulasi berkas:
   - `Storage::disk('public')->exists($path)`: Memeriksa keberadaan berkas.
   - `Storage::disk('public')->get($path)`: Mengambil isi string/konten biner berkas.
   - `Storage::disk('public')->put($path, $contents)`: Menulis atau memperbarui isi berkas.
   - `Storage::disk('public')->delete($path)`: Menghapus berkas dari disk.
   - `Storage::disk('public')->copy($src, $dest)`: Menduplikasi berkas.
   - `Storage::disk('public')->move($src, $dest)`: Memindahkan atau mengubah nama berkas.
   - `Storage::disk('public')->download($path)`: Mengembalikan HTTP response unduhan berkas.
   - `Storage::disk('public')->url($path)`: Mendapatkan URL publik berkas.

---

## BAB II: HASIL DAN PEMBAHASAN

### 2.1 Alat dan Bahan
1. **Perangkat Keras**: Laptop RAM 8GB, SSD 512GB, Processor Intel Core i3/i5.
2. **Sistem Operasi**: Windows 10/11 64-bit.
3. **Perangkat Lunak**:
   - Web Server: Laragon / Apache
   - Bahasa Pemrograman: PHP 8.2+
   - Database: MySQL / SQLite
   - Framework: Laravel 10.x
   - Editor: Visual Studio Code / Antigravity IDE
   - Browser: Google Chrome / Microsoft Edge

### 2.2 Prosedur dan Langkah Kerja

1. **Memeriksa Konfigurasi Disk Penyimpanan**:
   Mengevaluasi file konfigurasi `config/filesystems.php` untuk memastikan disk `local` dan `public` terkonfigurasi dengan benar:
   ```php
   'disks' => [
       'local' => [
           'driver' => 'local',
           'root' => storage_path('app'),
           'throw' => false,
       ],
       'public' => [
           'driver' => 'local',
           'root' => storage_path('app/public'),
           'url' => env('APP_URL').'/storage',
           'visibility' => 'public',
           'throw' => false,
       ],
   ],
   ```

2. **Membuat Symbolic Link Storage**:
   Menjalankan perintah Artisan di terminal proyek:
   ```bash
   php artisan storage:link
   ```
   Hasil: Tautan simbolik `public/storage` berhasil mengarah ke `storage/app/public`.

3. **Membuat Controller Pengelola Berkas (`Acara29Controller.php`)**:
   Menyiapkan controller khusus `app/Http/Controllers/Acara29Controller.php` dengan metode:
   - `index()`: Mengambil daftar semua berkas di direktori `uploads/` disk `public`, menghitung ukuran, tipe MIME, dan URL publik.
   - `upload()`: Memvalidasi berkas, menerapkan strategi penamaan terpilih (Default, Original, Timestamp, UUID, Custom), dan menyimpannya menggunakan `$file->store()` atau `$file->storeAs()`.
   - `download()`: Mengembalikan response download berkas menggunakan `Storage::disk('public')->download()`.
   - `showContent()`: Mengembalikan konten teks berkas (untuk file teks, json, csv, log) dalam format JSON/AJAX.
   - `delete()`: Menghapus berkas menggunakan `Storage::disk('public')->delete()`.
   - `putFile()`: Menulis atau memperbarui isi berkas teks secara langsung.
   - `copy()`: Menduplikasi berkas dengan nama baru menggunakan `Storage::disk('public')->copy()`.
   - `rename()`: Mengubah nama berkas menggunakan `Storage::disk('public')->move()`.

4. **Membuat Tampilan Antarmuka Operasional (`acara29-operasional.blade.php`)**:
   Membuat view Blade yang terintegrasi dengan layout Dashmin di `resources/views/acara/acara29-operasional.blade.php`, mencakup:
   - Panel formulir unggah berkas dengan pilihan strategi penamaan.
   - Tabel interaktif daftar berkas terunggah beserta badge ekstensi, ukuran, waktu modifikasi, dan URL.
   - Tombol aksi: Unduh langsung, Lihat Konten Teks (modal AJAX), Duplikasi (Copy), Ubah Nama (Rename), dan Hapus (Delete).
   - Panel panduan fungsi-fungsi penting facade `Storage`.

5. **Mendaftarkan Rute Web (`routes/web.php`)**:
   Mendaftarkan rute-rute RESTful untuk operasi file storage:
   ```php
   Route::get('/acara29', [Acara29Controller::class, 'index'])->name('acara29.index');
   Route::post('/acara29/upload', [Acara29Controller::class, 'upload'])->name('acara29.upload');
   Route::get('/acara29/download/{filename}', [Acara29Controller::class, 'download'])->name('acara29.download');
   Route::get('/acara29/content/{filename}', [Acara29Controller::class, 'showContent'])->name('acara29.content');
   Route::delete('/acara29/delete/{filename}', [Acara29Controller::class, 'delete'])->name('acara29.delete');
   Route::post('/acara29/put', [Acara29Controller::class, 'putFile'])->name('acara29.put');
   Route::post('/acara29/copy', [Acara29Controller::class, 'copy'])->name('acara29.copy');
   Route::post('/acara29/rename', [Acara29Controller::class, 'rename'])->name('acara29.rename');
   ```

6. **Pengujian Otomatis (`Acara29Test.php`)**:
   Membuat feature test `tests/Feature/Acara29Test.php` untuk memvalidasi:
   - Akses halaman index (`GET /acara29`).
   - Unggah berkas dengan metode penamaan bawaan hash acak.
   - Unggah berkas dengan metode nama asli, timestamp, dan UUID.
   - Validasi kegagalan jika berkas tidak dipilih atau ukuran melebihi batas.
   - Pengunduhan berkas (`download`).
   - Pembacaan konten berkas (`showContent`).
   - Penulisan berkas baru (`putFile`).
   - Duplikasi berkas (`copy`).
   - Pengubahan nama berkas (`rename`).
   - Penghapusan berkas (`delete`).

### 2.3 Implementasi Kode Program

#### A. Kode Controller (`Acara29Controller.php`)
```php
public function upload(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,csv,xlsx|max:2048',
        'naming_strategy' => 'required|in:default,original,timestamp,uuid,custom',
        'custom_name' => 'nullable|string|max:100',
    ]);

    $file = $request->file('document');
    $strategy = $request->input('naming_strategy', 'default');
    $extension = $file->getClientOriginalExtension();

    switch ($strategy) {
        case 'original':
            $filename = $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');
            break;
        case 'timestamp':
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');
            break;
        case 'uuid':
            $filename = Str::uuid() . '.' . $extension;
            $path = $file->storeAs('uploads', $filename, 'public');
            break;
        case 'custom':
            $customName = Str::slug($request->input('custom_name', 'file')) . '.' . $extension;
            $path = $file->storeAs('uploads', $customName, 'public');
            break;
        default:
            $path = $file->store('uploads', 'public');
            break;
    }

    return redirect()->route('acara29.index')
        ->with('success', "Berkas berhasil diunggah ke path: storage/{$path}");
}
```

#### B. Kode Operasi Manipulasi Berkas
```php
public function download($filename)
{
    $path = 'uploads/' . $filename;
    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'Berkas tidak ditemukan.');
    }
    return Storage::disk('public')->download($path);
}

public function delete($filename)
{
    $path = 'uploads/' . $filename;
    if (Storage::disk('public')->exists($path)) {
        Storage::disk('public')->delete($path);
        return redirect()->route('acara29.index')->with('success', "Berkas '{$filename}' berhasil dihapus.");
    }
    return redirect()->route('acara29.index')->with('error', 'Berkas tidak ditemukan.');
}
```

### 2.4 Hasil Pengujian dan Evaluasi

Pengujian otomatis dijalankan menggunakan PHPUnit via Artisan:
```bash
php artisan test --filter=Acara29Test
```
**Output Pengujian**:
```text
   PASS  Tests\Feature\Acara29Test
  ✓ can view acara29 file storage index page
  ✓ can upload file with default hashed name
  ✓ can upload file with original name
  ✓ can upload file with timestamp name
  ✓ can upload file with uuid name
  ✓ can upload file with custom name
  ✓ upload fails if file exceeds max size
  ✓ upload fails if invalid mime type
  ✓ can download uploaded file
  ✓ can view text file content via json
  ✓ can put or create new text file directly
  ✓ can copy existing file
  ✓ can rename existing file
  ✓ can delete file from storage

  Tests:    14 passed (38 assertions)
  Duration: 0.85s
```
Seluruh 14 skenario pengujian berhasil lulus 100% tanpa kendala, membuktikan bahwa seluruh fungsi penyimpanan dan manipulasi file storage berfungsi stabil.

---

## BAB III: KESIMPULAN

Berdasarkan praktikum Acara 29 yang telah diselesaikan, dapat disimpulkan bahwa:
1. Laravel menyediakan sistem manajemen berkas yang sangat terstruktur dan seragam melalui integrasi Flysystem dan facade `Storage`.
2. Pemisahan antara direktori `storage/app/public` dan tautan simbolik `public/storage` menjaga keamanan struktur internal aplikasi sekaligus memungkinkan berkas publik diakses dengan aman oleh peramban pengguna.
3. Penerapan validasi MIME type dan batasan ukuran berkas pada `FormRequest` secara efektif mencegah pengunggahan berkas ilegal (malicious scripts) dan kelebihan kapasitas server.
4. Fleksibilitas metode penamaan berkas (seperti UUID dan timestamp) mencegah penimpaan berkas (file overwrite) saat beberapa pengguna mengunggah berkas dengan nama yang sama secara bersamaan.
5. Operasi berkas seperti `download()`, `copy()`, `move()`, dan `delete()` dapat diimplementasikan dengan sintaksis ekspresif dan efisien.

---

## DAFTAR PUSTAKA

1. Laravel LLC. (2026). *File Storage Documentation*. https://laravel.com/docs/10.x/filesystem
2. The PHP League. (2026). *Flysystem: Filesystem abstraction for PHP*. https://flysystem.thephpleague.com/
3. Ariyadi, D. J. (2026). *Buku Kerja Praktik Mahasiswa (BKPM): Workshop Sistem Informasi Web Framework*. Jurusan Teknologi Informasi, Politeknik Negeri Jember.
4. Otwell, T. (2023). *Laravel: Up & Running: A Framework for Building Modern PHP Apps* (3rd ed.). O'Reilly Media.
