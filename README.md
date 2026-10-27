# Minimarket POS - Sistem Kasir & Manajemen Inventaris Toko Terpadu

Aplikasi **Point of Sales (POS)** dan **Enterprise Resource Planning (ERP)** Minimarket modern berbasis framework **Laravel 10** dengan integrasi tema responsif **Dashmin**. Seluruh modul fungsional saling terhubung (*seamless integration*) dalam satu ekosistem operasional toko yang utuh, mulai dari pencatatan transaksi kasir, manajemen katalog barang, optimasi foto produk, penyimpanan berkas digital, cetak laporan resmi PDF, hingga layanan notifikasi email otomatis.

---

### 👤 Identitas Mahasiswa & Pengembang
- **Nama Mahasiswa**: Ainul Hakimah
- **NIM**: E41252793
- **Program Studi**: D4 Teknik Informatika
- **Jurusan**: Teknologi Informasi, Politeknik Negeri Jember
- **Dosen Pengampu**: David Juli Ariyadi, S.Kom., M.Kom
- **Tahun Akademik**: 2026/2027

---

### 🌟 Modul & Arsitektur Fitur Terpadu

#### 1. 🛒 Modul Kasir & Transaksi Penjualan (POS)
- Terminal kasir interaktif dengan pencarian produk cepat, penghitungan subtotal & kembalian otomatis, serta pencetakan struk pembayaran.
- Pemantauan status toko (*buka/tutup/istirahat*) secara real-time via `/pos-status`.

#### 2. 📦 Modul Produk & Inventaris Toko
- **Katalog Master Produk**: Pemantauan data barang dengan proteksi Eloquent `$fillable`, relasi `belongsTo(Category::class)`, dan eager loading optimasi kueri database.
- **Pendaftaran Produk & Auto-SKU**: Otomatisasi pembuatan kode barang unik (`Product::generateSku()`) melalui Form Request dengan pesan validasi berbahasa Indonesia.
- **Rekapitulasi Stok & Valuasi Aset**: Agregasi SQL Query Builder (`selectRaw('COUNT, SUM(stock), SUM(price*stock)')`) untuk pemantauan nilai aset toko secara real-time.
- **Kategori & Pemulihan Arsip**: Relasi One-to-Many `Category` &rarr; `Product`, local query scope `Product::lowStock()`, dan mekanisme **Soft Deletes** (`deleted_at`) dengan fitur pemulihan (*restore*) produk.
- **Pengolahan Foto Produk (Intervention Image v2)**: Otomatisasi resize ke `300x300` piksel saat upload, resize resolusi kustom, crop offset, penyisipan watermark logo toko, rotasi sudut kanvas, konversi format WebP, dan direct stream response.

#### 3. 📄 Modul Dokumen & Cetak Laporan (DomPDF & Storage)
- **Cetak Laporan PDF Resmi (DomPDF)**: Pembuatan dokumen laporan berorientasi A4 Portrait & Landscape untuk Laporan Produk Toko, Laporan Stok Kritis, dan Laporan Kepegawaian, dengan opsi unduh langsung (`download`) atau pratinjau browser (`stream`).
- **Penyimpanan Berkas Digital (Laravel File Storage)**: Abstraksi penyimpanan disk publik dengan symbolic link `storage:link`, validasi berkas multi-format, ragam strategi penamaan unik (UUID, Timestamp, Hash), pembacaan konten teks AJAX, dan manipulasi berkas (`put`, `get`, `copy`, `rename`, `delete`).

#### 4. ⚙️ Modul Pengaturan, Keamanan & Layanan Sistem
- **Autentikasi & Profil Pengguna**: Manajemen profil akun, informasi sesi login, dan antarmuka ramah mode Tamu (*Guest*) dengan kredensial demo cepat.
- **Hak Akses & Otorisasi Pengguna**: Proteksi data berbasis **Gate** dan **User Policy** (`can:update,post`) untuk membedakan hak akses Administrator dan staf biasa.
- **Manajemen Staf & Karyawan**: Pendataan karyawan toko, pembagian jabatan/posisi kerja, dan riwayat tugas.
- **Layanan Email & Notifikasi (Symfony Mailer)**: Pengiriman email selamat datang via Mailable HTML, pengiriman email notifikasi via Mailable Markdown, pengalihan pengiriman ke antrean latar belakang (*Background Queues*), notifikasi faktur multi-channel (`InvoicePaid`), dan implementasi kontrak verifikasi email `MustVerifyEmail`.
- **Artikel & Pengumuman Toko**: Publikasi informasi barang dan pengumuman operasional minimarket berbasis arsitektur MVC.

---

### 🗺️ Peta Navigasi & Rute Terpadu Sistem

| Kategori Menu | Rute Bersih (Clean URL) | Rute Alternatif / Rute Uji | Deskripsi Fitur |
|---|---|---|---|
| **Dashboard** | `/admin` / `/dashboard` | `/` | Dasbor eksekutif dan pusat layanan terpadu toko |
| **Kasir POS** | `/kasir` | `/kasir` | Aplikasi terminal kasir penjualan |
| **Status Toko** | `/pos-status` | `/pos-status` | Pemantauan jam operasional toko minimarket |
| **Katalog Produk** | `/manajemen-produk` | `/acara18` | Kelola stok, harga, dan master data barang |
| **Tambah Produk** | `/tambah-produk` | `/acara20` | Formulir tambah barang dengan auto kode SKU |
| **Laporan Stok** | `/laporan-stok` | `/acara17` | Analisis stok dan perhitungan nilai aset toko |
| **Kategori & Arsip** | `/kategori-produk` | `/acara19` | Kelola kategori barang dan pemulihan arsip |
| **Olah Foto Produk** | `/olah-foto` | `/acara31` | Resize, watermark, crop, dan kompresi WebP |
| **Cetak Laporan PDF** | `/cetak-laporan` | `/acara30`, `/laporan-pdf` | Unduh dan streaming laporan PDF resmi |
| **Penyimpanan Berkas** | `/manajemen-berkas` | `/acara29` | Penyimpanan cloud berkas & dokumen digital |
| **Profil Pengguna** | `/profil-pengguna` | `/acara23/profile` | Informasi akun, status otentikasi, dan sesi login |
| **Hak Akses User** | `/hak-akses` | `/acara24` | Kontrol hak akses berbasis Gate & Policy |
| **Data Karyawan** | `/employee` | `/employee` | Pengelolaan data tim kasir dan staf minimarket |
| **Layanan Email** | `/layanan-email` | `/acara32` | Notifikasi faktur dan pengiriman email antrean |
| **Artikel & Berita** | `/posts` | `/posts` | Pengumuman dan postingan informasi produk |

---

### 🧪 Pengujian Otomatis (Automated Testing)

Aplikasi memiliki rangkaian pengujian fitur (*feature test suite*) yang sangat komprehensif untuk memastikan seluruh alur transaksi, otentikasi, dan pengolahan data berfungsi stabil:

```bash
php artisan test
```

**Hasil Pengujian Terakhir**:
```text
  Tests:    90 passed (287 assertions)
  Duration: 6.5s
```

---

### 📑 Laporan Praktikum Resmi (BKPM)

Dokumen laporan praktikum resmi format A4 PDF telah digenerate secara otomatis menggunakan perintah:
```bash
php artisan laporan:generate all --gol=A
```

Berkas PDF laporan resmi siap upload ke E-Learning JTI Polije tersimpan pada direktori `public/laporan_praktikum/`:
1. `ACARA-29_A_E41252793_AINUL-HAKIMAH.pdf` (Penyimpanan Berkas / File Storage)
2. `ACARA-30_A_E41252793_AINUL-HAKIMAH.pdf` (Cetak Laporan PDF / DomPDF)
3. `ACARA-31_A_E41252793_AINUL-HAKIMAH.pdf` (Manipulasi Foto Produk / Intervention Image)
4. `ACARA-32_A_E41252793_AINUL-HAKIMAH.pdf` (Layanan Email & Notifikasi Transaksi)

Dokumen sumber laporan format Markdown tersedia di direktori `reports/`.
