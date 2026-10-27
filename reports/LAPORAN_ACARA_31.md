# LAPORAN PRAKTIKUM
## WORKSHOP SISTEM INFORMASI WEB FRAMEWORK

---

**ACARA 31: LARAVEL IMAGE MANIPULATION**  
**Minggu Praktikum**: Minggu Ke-9 (9/3)  
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
Pada aplikasi web berbasis toko kelontong atau minimarket, representasi visual produk memegang peranan krusial dalam menarik minat pembeli dan membantu kasir mengidentifikasi barang dengan cepat. Namun, berkas gambar yang diunggah pengguna sering kali memiliki dimensi yang sangat besar, orientasi yang keliru, resolusi yang tidak seragam, atau ukuran berkas yang membebani kapasitas penyimpanan server dan memperlambat kecepatan muat halaman (*load time*).

Untuk mengatasi kendala ini, dibutuhkan mekanisme manipulasi gambar secara terprogram (*programmatic image manipulation*). Framework Laravel mendukung integrasi pustaka pemroses gambar pihak ketiga yang sangat andal, yaitu **Intervention Image**. Pustaka ini menyediakan antarmuka pemrograman yang ekspresif, intuitif, dan mendukung dua driver pemrosesan utama pada PHP: **GD Library** dan **Imagick**. Melalui praktikum Acara 31, mahasiswa mempelajari instalasi dan konfigurasi Intervention Image, pembuatan fitur upload gambar dengan validasi ketat, serta penerapan operasi manipulasi esensial mencakup *resize*, *crop*, *rotate*, *watermark*, *format encoding*, dan *direct stream response*.

### 1.2 Tujuan Praktikum
1. Mahasiswa mampu menginstal dan mengonfigurasi library `Intervention Image` pada proyek Laravel.
2. Mahasiswa mampu membangun formulir unggah gambar yang dilengkapi validasi tipe MIME (`jpeg, png, jpg, gif, webp`) dan pembatasan ukuran berkas (maksimal 2MB).
3. Mahasiswa mampu menerapkan teknik otomatisasi pengubahan ukuran (*auto-resize*) ke resolusi standar 300x300 piksel dengan mempertahankan rasio aspek (*aspect ratio*) dan mencegah perbesaran kualitas buruk (*upsize constraint*) saat proses unggah berkas berlangsung (Prosedur 4 BKPM).
4. Mahasiswa mampu menerapkan berbagai teknik manipulasi gambar secara programatik:
   - Pengubahan ukuran gambar (*Resize*) secara dinamis dengan kontrol kualitas kompresi.
   - Pemotongan area spesifik gambar (*Crop*) menggunakan koordinat offset ($x, y$) dan ukuran bidang potong.
   - Penyisipan logo tanda air (*Watermark*) transparan pada berbagai posisi koordinat.
   - Pemutaran sudut gambar (*Rotate*) sesuai derajat yang ditentukan.
   - Konversi format kompresi (*Encoding*) ke WebP, PNG, JPG, atau GIF.
   - Pengaliran respons biner gambar (*Direct Response*) langsung ke peramban tanpa membuat berkas fisik baru di disk server.
5. Mahasiswa mampu menyimpan berkas hasil olahan ke direktori penyimpanan `public/uploads` dan menampilkannya pada antarmuka galeri sistem.

### 1.3 Dasar Teori
1. **Definisi Manipulasi Gambar**:
   *Image manipulation* adalah proses pengubahan data piksel berkas citra digital secara terprogram, seperti mereduksi ukuran dimensi, memotong bidang, memutar sudut orientasi, menyematkan identitas hak cipta (*watermark*), dan menyesuaikan rasio kompresi.
2. **Library Intervention Image**:
   Intervention Image adalah pustaka sumber terbuka (*open-source*) untuk PHP yang membungkus fungsi-fungsi kompleks PHP GD atau ImageMagick ke dalam metode-metode *fluent interface* yang sangat mudah dibaca:
   - `Image::make($source)`: Menginstansiasi objek citra dari berkas fisik, string biner, atau stream data.
   - `->resize($width, $height, $callback)`: Mengubah resolusi gambar. Callback constraint `$constraint->aspectRatio()` memastikan proporsi gambar tidak mengalami distorsi/penyok, sementara `$constraint->upsize()` mencegah gambar kecil dipaksa membesar yang dapat mengakibatkan pecah piksel.
   - `->crop($width, $height, $x, $y)`: Memotong persegi panjang piksel dari gambar mulai titik koordinat $(x, y)$.
   - `->insert($watermark, $position, $x, $y)`: Menimpa gambar lain di atas kanvas gambar utama. Posisi yang didukung: `top-left`, `top-right`, `bottom-left`, `bottom-right`, dan `center`.
   - `->rotate($angle)`: Memutar kanvas gambar searah atau berlawanan arah jarum jam sesuai nilai derajat.
   - `->encode($format, $quality)`: Mengonversi struktur citra ke format lain (misal dari PNG ke format modern WebP) dengan mengatur persentase kompresi (10 - 100%).
   - `->response($format)`: Mengirimkan biner citra sebagai HTTP Response lengkap dengan header MIME type yang sesuai sehingga dapat langsung ditampilkan pada tag `<img>`.
   - `->save($path, $quality)`: Menyimpan hasil olahan gambar ke sistem berkas fisik server.
3. **Driver Pemroses Gambar (GD vs Imagick)**:
   - *GD Library*: Ekstensi bawaan PHP yang ringan, stabil, dan umumnya aktif secara default pada distribusi PHP standar (termasuk Laragon/XAMPP).
   - *Imagick*: Modul PHP yang memanfaatkan API ImageMagick. Memiliki performa lebih tinggi untuk pemrosesan citra skala besar dan mendukung ratusan format berkas grafis.

---

## BAB II: HASIL DAN PEMBAHASAN

### 2.1 Alat dan Bahan
1. **Perangkat Keras**: Laptop RAM 8GB, SSD 512GB, Processor Intel Core i3/i5.
2. **Sistem Operasi**: Windows 10/11 64-bit.
3. **Perangkat Lunak**:
   - Web Server: Laragon / Apache
   - Bahasa Pemrograman: PHP 8.2 (dengan ekstensi `ext-gd` aktif)
   - Framework: Laravel 10.x
   - Library: `intervention/image:^2.7`
   - Editor: Visual Studio Code / Antigravity IDE
   - Browser: Google Chrome / Microsoft Edge

### 2.2 Prosedur dan Langkah Kerja

1. **Instalasi Paket Intervention Image**:
   Menjalankan perintah instalasi pustaka melalui Composer di terminal root proyek:
   ```bash
   composer require intervention/image:^2.7
   ```
   *Catatan*: Versi `2.7` dipilih karena memiliki kompabilitas penuh dengan driver PHP GD pada Laravel 10 dan mendukung sintaksis statis `Image::make()`.

2. **Mendaftarkan Service Provider dan Facade**:
   Membuka berkas konfigurasi `config/app.php` dan menambahkan alias facade agar dapat diakses dengan mudah:
   ```php
   'aliases' => Facade::defaultAliases()->merge([
       'Image' => Intervention\Image\Facades\Image::class,
   ])->toArray(),
   ```

3. **Menyiapkan Aset Watermark dan Direktori Simpan**:
   - Menyiapkan direktori penyimpanan publik di `public/uploads`.
   - Membuat gambar watermark transparan berformat PNG berukuran 120x40 piksel di `public/images/watermark.png` menggunakan skrip PHP GD.

4. **Membangun Controller Pemroses Gambar (`Acara31Controller.php`)**:
   Mendefinisikan fungsi-fungsi manipulasi citra:
   - `index()`: Membaca seluruh file gambar di direktori `public/uploads`, mengekstrak metadata (dimensi lebar $\times$ tinggi, ukuran file dalam KB/MB, waktu unggah, dan URL), serta merender antarmuka galeri.
   - `upload()`: Memvalidasi berkas unggahan, memeriksa opsi centang *auto-resize 300x300*, menerapkan pembatasan rasio aspek, mengatur kualitas kompresi, dan menyimpan berkas.
   - `resize()`: Menerima nama berkas sumber, parameter dimensi lebar & tinggi baru, serta kualitas simpan, lalu menghasilkan berkas baru dengan akhiran `_resized_WxH.ext`.
   - `crop()`: Memotong gambar berdasarkan titik koordinat $(x, y)$ dan dimensi bidang yang diinginkan.
   - `watermark()`: Menyematkan logo watermark transparan dari `public/images/watermark.png` pada posisi sudut yang dipilih pengguna.
   - `rotate()`: Memutar kanvas gambar sesuai sudut rotasi yang diinginkan pengguna.
   - `encode()`: Mengonversi format berkas gambar (misal JPG ke WebP) untuk optimasi kecepatan web.
   - `response()`: Mengalirkan data gambar langsung ke peramban tanpa menulis berkas fisik.
   - `delete()`: Menghapus berkas dari disk server.

5. **Membangun Antarmuka Pengguna (`acara31-operasional.blade.php`)**:
   Merancang halaman antarmuka operasional yang selaras dengan tema Dashmin:
   - Formulir unggahan gambar dilengkapi kotak *Live Preview* JavaScript saat berkas dipilih.
   - Slider pengatur persentase kualitas penyimpanan (30% - 100%).
   - Kartu panduan sintaksis untuk tiap-tiap operasi pemrosesan Intervention Image.
   - Tabel galeri gambar interaktif yang menampilkan thumbnail pratinjau, nama berkas, resolusi piksel, ukuran kilobita, dan tombol dropdown modal untuk aksi manipulasi cepat.
   - Modal Bootstrap interaktif: Modal Resize, Modal Crop, Modal Watermark, Modal Rotate, dan Modal Encode.

6. **Mendaftarkan Rute Manipulasi Gambar (`routes/web.php`)**:
   ```php
   Route::get('/acara31', [Acara31Controller::class, 'index'])->name('acara31.index');
   Route::post('/acara31/upload', [Acara31Controller::class, 'upload'])->name('acara31.upload');
   Route::post('/acara31/resize', [Acara31Controller::class, 'resize'])->name('acara31.resize');
   Route::post('/acara31/crop', [Acara31Controller::class, 'crop'])->name('acara31.crop');
   Route::post('/acara31/watermark', [Acara31Controller::class, 'watermark'])->name('acara31.watermark');
   Route::post('/acara31/rotate', [Acara31Controller::class, 'rotate'])->name('acara31.rotate');
   Route::post('/acara31/encode', [Acara31Controller::class, 'encode'])->name('acara31.encode');
   Route::get('/acara31/response/{filename}', [Acara31Controller::class, 'response'])->name('acara31.response');
   Route::delete('/acara31/delete/{filename}', [Acara31Controller::class, 'delete'])->name('acara31.delete');
   ```

7. **Pengujian Fungsional Fitur (`Acara31Test.php`)**:
   Menulis pengujian otomatis untuk menguji seluruh rute dan operasi manipulasi gambar secara komprehensif.

### 2.3 Implementasi Kode Program

#### A. Prosedur 4: Upload & Auto-Resize 300x300 Piksel
```php
public function upload(Request $request)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ], [
        'photo.required' => 'File gambar wajib dipilih.',
        'photo.image'    => 'File harus berformat gambar.',
        'photo.mimes'    => 'Format gambar yang diizinkan: jpeg, png, jpg, gif, webp.',
        'photo.max'      => 'Ukuran gambar maksimal 2MB (2048 KB).',
    ]);

    $file = $request->file('photo');
    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
    $uploadPath = public_path('uploads');

    if (!File::exists($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true);
    }

    $destPath = $uploadPath . '/' . $filename;
    $autoResize = $request->boolean('auto_resize', false);
    $quality = (int) $request->input('quality', 90);

    if ($autoResize) {
        // Menerapkan Prosedur 4 BKPM: Resize 300x300 dengan aspect ratio & upsize
        Image::make($file)
            ->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($destPath, $quality);

        $msg = "Gambar '{$filename}' berhasil diunggah dan di-resize ke 300x300 (Kualitas: {$quality}%).";
    } else {
        Image::make($file)->save($destPath, $quality);
        $msg = "Gambar '{$filename}' berhasil diunggah (Kualitas: {$quality}%).";
    }

    return redirect()->route('acara31.index')->with('success', $msg);
}
```

#### B. Prosedur 3: Operasi Watermark dan Response Stream
```php
public function watermark(Request $request)
{
    $request->validate([
        'image_name' => 'required|string',
        'position'   => 'nullable|string|in:top-left,top-right,bottom-left,bottom-right,center',
    ]);

    $sourcePath = public_path('uploads/' . $request->image_name);
    $watermarkPath = public_path('images/watermark.png');

    $position = $request->position ?? 'bottom-right';
    $ext = pathinfo($request->image_name, PATHINFO_EXTENSION);
    $baseName = pathinfo($request->image_name, PATHINFO_FILENAME);
    $newFilename = $baseName . '_watermark.' . $ext;
    $destPath = public_path('uploads/' . $newFilename);

    Image::make($sourcePath)
        ->insert($watermarkPath, $position, 15, 15)
        ->save($destPath);

    return redirect()->route('acara31.index')
        ->with('success', "Watermark berhasil disisipkan pada posisi [{$position}] → '{$newFilename}'.");
}

public function response($filename)
{
    $path = public_path('uploads/' . $filename);
    if (!File::exists($path)) {
        abort(404, 'Gambar tidak ditemukan.');
    }

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $format = in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp']) ? $ext : 'jpg';

    // Mengembalikan response citra biner langsung ke peramban
    return Image::make($path)->response($format);
}
```

### 2.4 Hasil Pengujian dan Evaluasi

Pengujian otomatis dieksekusi dengan perintah Artisan:
```bash
php artisan test --filter=Acara31Test
```
**Hasil Eksekusi**:
```text
   PASS  Tests\Feature\Acara31Test
  ✓ can view acara31 index page
  ✓ can upload image without autoresize
  ✓ can upload image with autoresize to 300x300
  ✓ upload fails if no file
  ✓ upload fails if file is not an image
  ✓ can resize image
  ✓ can crop image
  ✓ can watermark image
  ✓ can rotate image
  ✓ can encode image to webp
  ✓ can stream image response directly
  ✓ can delete image

  Tests:    12 passed (52 assertions)
  Duration: 1.10s
```
Sebanyak 12 skenario pengujian unit dan fitur berhasil dijalankan dengan 52 asersi yang valid, membuktikan bahwa fungsi pengolahan citra berjalan stabil dan aman.

---

## BAB III: KESIMPULAN

Berdasarkan praktikum Acara 31 yang telah diselesaikan, dapat disimpulkan bahwa:
1. Pustaka `Intervention Image` menyederhanakan manipulasi grafis pada Laravel dengan menyediakan antarmuka API yang bersih, intuitif, dan bertenaga.
2. Fitur *auto-resize* pada saat pengunggahan gambar ke resolusi standar 300x300 dengan *constraint aspect ratio* sangat efektif dalam menghemat ruang disk dan menjaga konsistensi visual katalog produk minimarket tanpa merusak proporsi gambar asli.
3. Fitur *crop*, *rotate*, dan *watermark* dapat diterapkan dengan mudah untuk memotong bagian gambar yang tidak perlu, memperbaiki orientasi foto, dan melindungi hak cipta aset visual toko.
4. Fitur konversi format (*encoding*) ke WebP terbukti mampu mereduksi ukuran berkas secara signifikan dengan tetap mempertahankan kejernihan visual (*lossy compression* efisien).
5. Penggunaan metode `response()` memungkinkan penyajian gambar terproses secara *on-the-fly* langsung ke browser tanpa membebani disk penyimpanan dengan berkas-berkas sementara (*temporary cache files*).

---

## DAFTAR PUSTAKA

1. Intervention Image. (2026). *Intervention Image Documentation (v2)*. https://image.intervention.io/v2
2. Ariyadi, D. J. (2026). *Buku Kerja Praktik Mahasiswa (BKPM): Workshop Sistem Informasi Web Framework*. Jurusan Teknologi Informasi, Politeknik Negeri Jember.
3. PHP Manual. (2026). *Image Processing and GD*. The PHP Group: https://www.php.net/manual/en/book.image.php
4. Laravel LLC. (2026). *File Storage and Uploads in Laravel*. https://laravel.com/docs/10.x/filesystem
