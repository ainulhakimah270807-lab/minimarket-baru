<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class GenerateLaporanPdfCommand extends Command
{
    protected $signature = 'laporan:generate {acara? : Nomor acara (29, 30, 31, 32 atau all)} {--gol=A : Golongan mahasiswa}';
    protected $description = 'Generate PDF Laporan Praktikum BKPM Acara 29, 30, 31, dan 32';

    public function handle()
    {
        $target = $this->argument('acara') ?? 'all';
        $gol = strtoupper($this->option('gol') ?? 'A');
        $nama = 'AINUL HAKIMAH';
        $nim = 'E41252793';
        $dosen = 'David Juli Ariyadi, S.Kom., M.Kom';

        $outputDir = public_path('laporan_praktikum');
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        $allData = $this->getData($nama, $nim, $gol, $dosen);

        $selected = ($target === 'all') ? array_keys($allData) : [intval($target)];

        foreach ($selected as $no) {
            if (!isset($allData[$no])) {
                $this->error("Data untuk Acara {$no} tidak ditemukan.");
                continue;
            }

            $data = $allData[$no];
            $filename = "ACARA-{$no}_{$gol}_{$nim}_AINUL-HAKIMAH.pdf";
            $destPath = $outputDir . '/' . $filename;

            $this->info("Sedang menghasilkan Laporan Acara {$no} -> {$filename}...");

            $pdf = Pdf::loadView('laporan.template_acara', $data)
                      ->setPaper('a4', 'portrait');

            $pdf->save($destPath);

            $fileSizeKb = round(filesize($destPath) / 1024, 1);
            $this->line("<info>✔ Berhasil:</info> {$destPath} ({$fileSizeKb} KB)");
        }

        $this->newLine();
        $this->info("Semua laporan PDF selesai dibuat di direktori: public/laporan_praktikum/");
        return 0;
    }

    private function getData($nama, $nim, $gol, $dosen): array
    {
        return [
            29 => [
                'title' => 'Laporan Praktikum Acara 29 - Laravel File Storage',
                'acara_no' => 'ACARA 29',
                'acara_judul' => 'LARAVEL FILE STORAGE',
                'minggu' => 'Minggu Ke-8',
                'nama' => $nama,
                'nim' => $nim,
                'golongan' => $gol,
                'dosen' => $dosen,
                'latar_belakang' => [
                    'Pengelolaan berkas (file management) merupakan komponen penting dalam aplikasi sistem kasir dan inventaris modern. Berkas seperti faktur belanja, lampiran nota supplier, hingga gambar bukti transaksi perlu dikelola secara terstruktur, aman, dan efisien.',
                    'Framework Laravel menyediakan abstraksi file system yang tangguh melalui paket Flysystem. Dengan abstraksi ini, pengembang dapat berinteraksi dengan disk lokal maupun penyimpanan awan menggunakan satu API seragam pada facade Storage. Mahasiswa mempelajari konfigurasi disk, pembuatan symbolic link, validasi unggahan, strategi penamaan unik, dan manipulasi berkas programatik.',
                ],
                'tujuan' => [
                    'Memahami konsep arsitektur file storage dan konfigurasi disk pada Laravel (config/filesystems.php).',
                    'Mengonfigurasi dan menghubungkan disk public via perintah php artisan storage:link.',
                    'Membangun form upload berkas berbasis Blade dengan multipart/form-data dan proteksi CSRF.',
                    'Menerapkan validasi berkas meliputi MIME types (jpg, png, pdf, docx, csv, xlsx) dan batasan ukuran berkas (2MB).',
                    'Menerapkan strategi penamaan berkas unik (hash, original, timestamp, UUID, dan custom name).',
                    'Mengimplementasikan operasi berkas pada Storage facade (put, get, exists, delete, move, copy, download, url).',
                ],
                'dasar_teori' => [
                    [
                        'sub' => '1. Konfigurasi Disk Penyimpanan (config/filesystems.php)',
                        'desc' => 'Laravel mendefinisikan disk lokal privat pada storage/app dan disk publik pada storage/app/public yang dapat diakses publik setelah pembuatan tautan simbolik.',
                    ],
                    [
                        'sub' => '2. Tautan Simbolik (storage:link)',
                        'desc' => 'Perintah php artisan storage:link membuat tautan simbolik dari public/storage yang merujuk ke storage/app/public agar berkas dapat diakses secara publik via web browser.',
                        'code' => 'php artisan storage:link',
                    ],
                    [
                        'sub' => '3. Operasi Manipulasi Berkas pada Facade Storage',
                        'desc' => 'Facade Storage menyediakan metode-metode programatik untuk mengontrol siklus hidup berkas.',
                        'code' => "Storage::disk('public')->put('uploads/file.txt', \$content);\n\$exists = Storage::disk('public')->exists('uploads/file.txt');\nStorage::disk('public')->download('uploads/file.txt');\nStorage::disk('public')->delete('uploads/file.txt');",
                    ],
                ],
                'alat_bahan' => [
                    'Hardware: Laptop Intel Core i3/i5, RAM 8GB, SSD 512GB.',
                    'Software: Laragon, PHP 8.2, Laravel 10.x, MySQL/SQLite.',
                    'Browser: Google Chrome / Microsoft Edge.',
                    'Code Editor: Visual Studio Code / Antigravity IDE.',
                ],
                'langkah_kerja' => [
                    [
                        'judul' => '1. Verifikasi Konfigurasi Disk Public',
                        'deskripsi' => 'Memeriksa konfigurasi disk public pada config/filesystems.php dengan driver local dan visibility public.',
                    ],
                    [
                        'judul' => '2. Pembuatan Symbolic Link',
                        'deskripsi' => 'Mengeksekusi perintah Artisan php artisan storage:link untuk menghubungkan storage/app/public ke public/storage.',
                    ],
                    [
                        'judul' => '3. Pembuatan Controller Acara29Controller',
                        'deskripsi' => 'Membangun logika pengunggahan, pemilihan strategi penamaan berkas (Default, Original, Timestamp, UUID, Custom), download, preview teks AJAX, copy, rename, dan delete.',
                    ],
                    [
                        'judul' => '4. Perancangan View Antarmuka Operasional',
                        'deskripsi' => 'Merancang Blade view pada resources/views/acara/acara29-operasional.blade.php dengan layout Dashmin.',
                    ],
                ],
                'kode_program' => [
                    [
                        'file' => 'app/Http/Controllers/Acara29Controller.php',
                        'penjelasan' => 'Logika validasi upload dan variasi penamaan berkas:',
                        'code' => "public function upload(Request \$request)\n{\n    \$request->validate([\n        'document' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,csv,xlsx|max:2048',\n        'naming_strategy' => 'required|in:default,original,timestamp,uuid,custom',\n    ]);\n    // Penanganan strategi penamaan berkas & penyimpanan ke disk public\n    \$path = \$file->storeAs('uploads', \$filename, 'public');\n    return redirect()->route('acara29.index')->with('success', 'Berkas berhasil disimpan.');\n}",
                    ],
                ],
                'evaluasi_text' => 'Pengujian dilakukan secara otomatis menggunakan PHPUnit Feature Test pada tests/Feature/Acara29Test.php. Sebanyak 14 skenario pengujian berhasil lulus 100% tanpa kendala:',
                'test_cases' => [
                    ['case' => 'Akses Halaman Utama File Storage (GET /acara29)', 'status' => 'PASS (200 OK)'],
                    ['case' => 'Unggah berkas dengan metode default hash acak', 'status' => 'PASS (Valid)'],
                    ['case' => 'Unggah berkas dengan metode nama asli (Original Name)', 'status' => 'PASS (Valid)'],
                    ['case' => 'Unggah berkas dengan metode timestamp prefix', 'status' => 'PASS (Valid)'],
                    ['case' => 'Unggah berkas dengan metode UUID acak unik', 'status' => 'PASS (Valid)'],
                    ['case' => 'Unggah berkas dengan metode Custom Slug Name', 'status' => 'PASS (Valid)'],
                    ['case' => 'Validasi penolakan berkas melebihi batas 2MB', 'status' => 'PASS (422 Rejected)'],
                    ['case' => 'Validasi penolakan tipe MIME tidak diizinkan', 'status' => 'PASS (422 Rejected)'],
                    ['case' => 'Pengunduhan berkas via Storage::download()', 'status' => 'PASS (200 Download)'],
                    ['case' => 'Pembacaan konten teks berkas via Storage::get()', 'status' => 'PASS (JSON OK)'],
                    ['case' => 'Pembuatan berkas teks baru via Storage::put()', 'status' => 'PASS (Created)'],
                    ['case' => 'Duplikasi berkas via Storage::copy()', 'status' => 'PASS (Copied)'],
                    ['case' => 'Pengubahan nama berkas via Storage::move()', 'status' => 'PASS (Renamed)'],
                    ['case' => 'Penghapusan berkas via Storage::delete()', 'status' => 'PASS (Deleted)'],
                ],
                'kesimpulan' => [
                    'Laravel menyediakan API sistem berkas yang seragam, elegan, dan aman melalui integrasi paket Flysystem pada facade Storage.',
                    'Pemisahan direktori penyimpanan privat dan publik via symbolic link public/storage melindungi integritas sistem aplikasi.',
                    'Penerapan validasi berkas yang ketat dan strategi penamaan unik (UUID/Timestamp) mencegah insiden tabrakan berkas (overwrite) dan celah keamanan.',
                    'Operasi manipulasi berkas (download, copy, rename, delete) dapat dijalankan secara efisien dengan sintaksis ekspresif.',
                ],
                'daftar_pustaka' => [
                    'Laravel LLC. (2026). File Storage Documentation. https://laravel.com/docs/10.x/filesystem',
                    'The PHP League. (2026). Flysystem: Filesystem abstraction for PHP. https://flysystem.thephpleague.com/',
                    'Ariyadi, D. J. (2026). Buku Kerja Praktik Mahasiswa (BKPM): Workshop Sistem Informasi Web Framework. JTI Politeknik Negeri Jember.',
                ],
            ],

            30 => [
                'title' => 'Laporan Praktikum Acara 30 - Membuat Laporan PDF dengan DomPDF',
                'acara_no' => 'ACARA 30',
                'acara_judul' => 'MEMBUAT LAPORAN PDF DENGAN DOMPDF',
                'minggu' => 'Minggu Ke-8/9',
                'nama' => $nama,
                'nim' => $nim,
                'golongan' => $gol,
                'dosen' => $dosen,
                'latar_belakang' => [
                    'Dalam sistem Point of Sales minimarket, penyajian laporan penjualan harian, slip pegawai, dan rekapitulasi stok barang dalam format PDF merupakan kebutuhan mendasar untuk pencetakan dokumen fisik resmi yang konsisten di semua sistem perangkat.',
                    'Library barryvdh/laravel-dompdf memungkinkan perenderan berkas PDF langsung dari template Blade HTML dan CSS. Praktikum ini berfokus pada integrasi data dinamis database via Eloquent ORM, pengaturan layout kertas (A4 Portrait dan Landscape), serta penyediaan mode download langsung dan streaming di peramban.',
                ],
                'tujuan' => [
                    'Menginstal dan mengonfigurasi pustaka barryvdh/laravel-dompdf pada proyek Laravel.',
                    'Merancang template laporan Blade HTML dan CSS yang kompatibel dengan perender DomPDF.',
                    'Menghubungkan data dinamis dari database via Eloquent ORM (Product, Category, Employee).',
                    'Mengatur ukuran kertas (A4) dan orientasi (portrait / landscape) dengan metode setPaper().',
                    'Menerapkan fungsi download() untuk unduhan otomatis dan stream() untuk pratinjau inline.',
                ],
                'dasar_teori' => [
                    [
                        'sub' => '1. Pustaka DomPDF & barryvdh/laravel-dompdf',
                        'desc' => 'DomPDF mengonversi struktur HTML dan CSS 2.1 menjadi dokumen PDF secara cepat pada bahasa pemrograman PHP.',
                    ],
                    [
                        'sub' => '2. Fungsi-Fungsi Utama DomPDF',
                        'desc' => 'Facade Pdf menyediakan metode chaining yang sangat ringkas:',
                        'code' => "\$pdf = Pdf::loadView('laporan.pegawai', compact('pegawai'))\n          ->setPaper('a4', 'portrait');\nreturn \$pdf->download('laporan.pdf'); // atau ->stream('laporan.pdf')",
                    ],
                ],
                'alat_bahan' => [
                    'Hardware: Laptop Intel Core i3/i5, RAM 8GB, SSD 512GB.',
                    'Software: Laragon, PHP 8.2, Laravel 10.x, barryvdh/laravel-dompdf.',
                    'Code Editor: Visual Studio Code / Antigravity IDE.',
                ],
                'langkah_kerja' => [
                    [
                        'judul' => '1. Instalasi Paket barryvdh/laravel-dompdf',
                        'deskripsi' => 'Menginstal dependensi via composer require barryvdh/laravel-dompdf.',
                    ],
                    [
                        'judul' => '2. Pembuatan LaporanController (/laporan-pdf)',
                        'deskripsi' => 'Mengimplementasikan LaporanController sesuai panduan BKPM untuk mengunduh laporan pegawai.',
                    ],
                    [
                        'judul' => '3. Pembuatan Template Blade Laporan',
                        'deskripsi' => 'Merancang template laporan/pegawai.blade.php, laporan/produk.blade.php, dan laporan/stok.blade.php.',
                    ],
                    [
                        'judul' => '4. Integrasi Acara30Controller dan Rute Web',
                        'deskripsi' => 'Menghubungkan data model Product, Employee, dan Category ke tampilan PDF berorientasi Portrait dan Landscape.',
                    ],
                ],
                'kode_program' => [
                    [
                        'file' => 'app/Http/Controllers/LaporanController.php',
                        'penjelasan' => 'Implementasi fungsi download dan stream PDF:',
                        'code' => "public function generatePDF()\n{\n    \$pegawai = [['nama' => 'Budi Santoso', 'jabatan' => 'Manager Toko'], ...];\n    \$pdf = Pdf::loadView('laporan.pegawai', compact('pegawai'))->setPaper('a4', 'portrait');\n    return \$pdf->download('laporan-pegawai.pdf');\n}",
                    ],
                ],
                'evaluasi_text' => 'Pengujian otomatis pada tests/Feature/Acara30Test.php berhasil meloloskan 10 skenario pengujian dengan response header application/pdf yang valid:',
                'test_cases' => [
                    ['case' => 'Akses Halaman Dasbor PDF Report (GET /acara30)', 'status' => 'PASS (200 OK)'],
                    ['case' => 'Download Laporan Pegawai BKPM (GET /laporan-pdf)', 'status' => 'PASS (Attachment PDF)'],
                    ['case' => 'Stream Laporan Pegawai BKPM (GET /laporan-pdf/stream)', 'status' => 'PASS (Inline PDF)'],
                    ['case' => 'Download Katalog Produk Toko (GET /acara30/pdf/produk)', 'status' => 'PASS (Attachment PDF)'],
                    ['case' => 'Stream Katalog Produk Toko (GET /acara30/pdf/produk/stream)', 'status' => 'PASS (Inline PDF)'],
                    ['case' => 'Download Laporan Audit Stok Lanskap (GET /acara30/pdf/stok)', 'status' => 'PASS (Attachment PDF)'],
                    ['case' => 'Stream Laporan Audit Stok Lanskap (GET /acara30/pdf/stok/stream)', 'status' => 'PASS (Inline PDF)'],
                    ['case' => 'Download Laporan Karyawan Database (GET /acara30/pdf/pegawai)', 'status' => 'PASS (Attachment PDF)'],
                    ['case' => 'Stream Laporan Karyawan Database (GET /acara30/pdf/pegawai/stream)', 'status' => 'PASS (Inline PDF)'],
                    ['case' => 'Fitur Unggah Gambar untuk Manipulasi Tambahan', 'status' => 'PASS (Valid)'],
                ],
                'kesimpulan' => [
                    'Paket barryvdh/laravel-dompdf menyederhanakan konversi template Blade HTML/CSS menjadi dokumen PDF resmi.',
                    'Dukungan metode setPaper() memungkinkan pemilihan dimensi dan orientasi halaman yang presisi.',
                    'Dua mode output (download dan stream) memenuhi kebutuhan pencetakan dan pratinjau langsung di aplikasi.',
                ],
                'daftar_pustaka' => [
                    'Barryvdh. (2026). Laravel DOMPDF Wrapper. https://github.com/barryvdh/laravel-dompdf',
                    'DomPDF. (2026). DomPDF HTML to PDF Converter. https://github.com/dompdf/dompdf',
                    'Ariyadi, D. J. (2026). BKPM Workshop Sistem Informasi Web Framework. JTI Polije.',
                ],
            ],

            31 => [
                'title' => 'Laporan Praktikum Acara 31 - Laravel Image Manipulation',
                'acara_no' => 'ACARA 31',
                'acara_judul' => 'LARAVEL IMAGE MANIPULATION',
                'minggu' => 'Minggu Ke-9 (9/3)',
                'nama' => $nama,
                'nim' => $nim,
                'golongan' => $gol,
                'dosen' => $dosen,
                'latar_belakang' => [
                    'Pada aplikasi e-commerce dan kasir toko, gambar produk yang diunggah sering kali memiliki resolusi tidak seragam, dimensi yang terlalu besar, atau orientasi yang keliru sehingga membebani penyimpanan dan memperlambat waktu pemuatan situs.',
                    'Intervention Image merupakan library pemrosesan gambar terpopuler di PHP yang mendukung driver GD dan Imagick. Pada praktikum Acara 31, mahasiswa mempelajari teknik unggah gambar dengan auto-resize 300x300 piksel, serta manipulasi programatik seperti resize dinamis, crop area, watermark logo, rotate sudut, format encoding (WebP), dan direct streaming response.',
                ],
                'tujuan' => [
                    'Menginstal dan mengonfigurasi library Intervention Image v2 pada Laravel.',
                    'Membangun form upload gambar dengan validasi format dan ukuran file.',
                    'Menerapkan auto-resize otomatis ke 300x300 dengan menjaga aspect ratio (Prosedur 4 BKPM).',
                    'Menerapkan berbagai teknik manipulasi: resize dinamis, crop, watermark, rotate, encode, dan response.',
                    'Menyimpan dan menampilkan hasil olahan gambar pada antarmuka galeri interaktif.',
                ],
                'dasar_teori' => [
                    [
                        'sub' => '1. Library Intervention Image v2',
                        'desc' => 'Menyediakan abstraksi tingkat tinggi di atas GD Library untuk pengolahan citra digital via Image::make().',
                        'code' => "Image::make(\$file)->resize(300, 300, function(\$c){\n    \$c->aspectRatio();\n    \$c->upsize();\n})->save(\$destPath, 90);",
                    ],
                    [
                        'sub' => '2. Operasi Manipulasi Lanjutan',
                        'desc' => 'Operasi crop memotong area spesifik, insert menyematkan logo watermark, rotate memutar kanvas, dan encode mengubah format berkas.',
                    ],
                ],
                'alat_bahan' => [
                    'Hardware: Laptop Intel Core i3/i5, RAM 8GB, SSD 512GB.',
                    'Software: Laragon, PHP 8.2 (ext-gd aktif), Laravel 10.x, intervention/image:^2.7.',
                    'Code Editor: Visual Studio Code / Antigravity IDE.',
                ],
                'langkah_kerja' => [
                    [
                        'judul' => '1. Instalasi Library Intervention Image',
                        'deskripsi' => 'Menjalankan composer require intervention/image:^2.7 dan mendaftarkan alias Image pada config/app.php.',
                    ],
                    [
                        'judul' => '2. Penyiapan Aset Watermark dan Folder Unggah',
                        'deskripsi' => 'Menyiapkan direktori public/uploads dan berkas watermark transparan pada public/images/watermark.png.',
                    ],
                    [
                        'judul' => '3. Pembuatan Controller Acara31Controller',
                        'deskripsi' => 'Membangun logika upload dengan auto-resize 300x300, serta metode resize, crop, watermark, rotate, encode, dan response.',
                    ],
                    [
                        'judul' => '4. Perancangan View Operasional Blade',
                        'deskripsi' => 'Mendesain galeri gambar dan modal aksi manipulasi cepat pada resources/views/acara/acara31-operasional.blade.php.',
                    ],
                ],
                'kode_program' => [
                    [
                        'file' => 'app/Http/Controllers/Acara31Controller.php',
                        'penjelasan' => 'Implementasi Prosedur 4: Upload dan Auto-Resize ke 300x300:',
                        'code' => "public function upload(Request \$request)\n{\n    \$file = \$request->file('photo');\n    Image::make(\$file)->resize(300, 300, function (\$c) {\n        \$c->aspectRatio();\n        \$c->upsize();\n    })->save(\$destPath, 90);\n    return redirect()->route('acara31.index')->with('success', 'Gambar berhasil diproses.');\n}",
                    ],
                ],
                'evaluasi_text' => 'Pengujian otomatis dieksekusi melalui tests/Feature/Acara31Test.php dengan hasil 12 pengujian lulus 100%:',
                'test_cases' => [
                    ['case' => 'Akses Halaman Utama Acara 31 (GET /acara31)', 'status' => 'PASS (200 OK)'],
                    ['case' => 'Unggah gambar standar tanpa auto-resize', 'status' => 'PASS (Valid)'],
                    ['case' => 'Unggah gambar dengan auto-resize 300x300', 'status' => 'PASS (<= 300x300)'],
                    ['case' => 'Validasi penolakan tanpa berkas gambar', 'status' => 'PASS (422 Error)'],
                    ['case' => 'Validasi penolakan berkas non-gambar (PDF)', 'status' => 'PASS (422 Error)'],
                    ['case' => 'Operasi Resize resolusi kustom', 'status' => 'PASS (Dimensi Pas)'],
                    ['case' => 'Operasi Crop bidang tertentu', 'status' => 'PASS (Crop Sukses)'],
                    ['case' => 'Operasi Watermark sisip logo', 'status' => 'PASS (Watermarked)'],
                    ['case' => 'Operasi Rotate pemutaran sudut derajat', 'status' => 'PASS (Rotated)'],
                    ['case' => 'Operasi Encode konversi format ke WebP', 'status' => 'PASS (Converted)'],
                    ['case' => 'Operasi Response stream biner langsung ke browser', 'status' => 'PASS (image/*)'],
                    ['case' => 'Penghapusan berkas gambar dari disk', 'status' => 'PASS (Deleted)'],
                ],
                'kesimpulan' => [
                    'Intervention Image memudahkan manipulasi citra digital di Laravel dengan antarmuka yang ekspresif.',
                    'Auto-resize 300x300 piksel saat proses unggah efektif menghemat ruang penyimpanan server tanpa merusak aspect ratio gambar.',
                    'Dukungan fitur watermark, crop, dan rotate mempermudah personalisasi serta perlindungan hak cipta aset toko.',
                    'Fitur encoding format ke WebP mereduksi ukuran berkas dengan efisiensi tinggi.',
                ],
                'daftar_pustaka' => [
                    'Intervention Image. (2026). Intervention Image v2 Documentation. https://image.intervention.io/v2',
                    'Ariyadi, D. J. (2026). BKPM Workshop Sistem Informasi Web Framework. JTI Polije.',
                    'PHP Manual. (2026). Image Processing and GD. https://www.php.net/manual/en/book.image.php',
                ],
            ],

            32 => [
                'title' => 'Laporan Praktikum Acara 32 - Laravel Email',
                'acara_no' => 'ACARA 32',
                'acara_judul' => 'LARAVEL EMAIL (MAIL, QUEUES, & NOTIFICATIONS)',
                'minggu' => 'Minggu Ke-9/10',
                'nama' => $nama,
                'nim' => $nim,
                'golongan' => $gol,
                'dosen' => $dosen,
                'latar_belakang' => [
                    'Komunikasi asinkron via surat elektronik merupakan kebutuhan wajib aplikasi transaksi untuk mengirimkan faktur pembelian, pemberitahuan pembaruan data, hingga verifikasi email demi keamanan akun pengguna.',
                    'Framework Laravel menyediakan sistem pengiriman email terpadu berbasis Symfony Mailer yang mendukung konfigurasi berbagai driver transport, kelas Mailable HTML & Markdown, pengiriman di latar belakang melalui antrean (queues), sistem notifikasi multi-saluran, serta verifikasi email pengguna.',
                ],
                'tujuan' => [
                    'Memahami konfigurasi mail transport pada .env dan config/mail.php.',
                    'Membuat Mailable standar HTML (WelcomeMail) dan Mailable Markdown (NotifyUser).',
                    'Menerapkan pengiriman email via antrean background (Mail::to()->queue()).',
                    'Membangun notifikasi faktur multi-channel menggunakan Notification (InvoicePaid).',
                    'Mengonfigurasi verifikasi email pengguna dengan kontrak MustVerifyEmail pada model User.',
                    'Menampilkan live preview email langsung di peramban web.',
                ],
                'dasar_teori' => [
                    [
                        'sub' => '1. Mailable Class & Markdown',
                        'desc' => 'Mailable memisahkan logika penyusunan data dari tampilan email. Template Markdown mempermudah pembuatan email responsif.',
                    ],
                    [
                        'sub' => '2. Background Email Queues',
                        'desc' => 'Metode Mail::to()->queue() mengalihkan proses pengiriman ke queue worker sehingga respon HTTP tidak mengalami hambatan (latency).',
                    ],
                    [
                        'sub' => '3. Email Verification',
                        'desc' => 'Mengimplementasikan MustVerifyEmail pada model User dan mengamankan rute dengan middleware verified.',
                    ],
                ],
                'alat_bahan' => [
                    'Hardware: Laptop Intel Core i3/i5, RAM 8GB, SSD 512GB.',
                    'Software: Laragon, PHP 8.2, Laravel 10.x, Mailtrap / driver log.',
                    'Code Editor: Visual Studio Code / Antigravity IDE.',
                ],
                'langkah_kerja' => [
                    [
                        'judul' => '1. Konfigurasi Mail Driver',
                        'deskripsi' => 'Mengatur driver log dan SMTP Mailtrap pada berkas .env.',
                    ],
                    [
                        'judul' => '2. Pembuatan Mailable Class WelcomeMail dan NotifyUser',
                        'deskripsi' => 'Membuat Mailable HTML WelcomeMail dan Mailable Markdown NotifyUser dengan artisan make:mail.',
                    ],
                    [
                        'judul' => '3. Pembuatan Notification InvoicePaid',
                        'deskripsi' => 'Membuat notifikasi faktur transaksi penjualan via php artisan make:notification InvoicePaid.',
                    ],
                    [
                        'judul' => '4. Implementasi MustVerifyEmail pada Model User',
                        'deskripsi' => 'Menambahkan kontrak MustVerifyEmail pada app/Models/User.php.',
                    ],
                ],
                'kode_program' => [
                    [
                        'file' => 'app/Http/Controllers/Acara32Controller.php',
                        'penjelasan' => 'Logika pengiriman email sinkron, antrean (queues), dan notifikasi:',
                        'code' => "public function queueWelcome(Request \$request)\n{\n    // Mengirim email via antrean background\n    Mail::to(\$request->email)->queue(new WelcomeMail(\$request->name));\n    return redirect()->route('acara32.index')->with('success', 'Email berhasil dimasukkan ke antrean.');\n}",
                    ],
                ],
                'evaluasi_text' => 'Pengujian otomatis pada tests/Feature/Acara32Test.php berhasil meloloskan 11 skenario pengujian dengan 30 asersi valid:',
                'test_cases' => [
                    ['case' => 'Akses Halaman Utama Modul Email (GET /acara32)', 'status' => 'PASS (200 OK)'],
                    ['case' => 'Pengiriman Welcome Mail sinkron via Mail facade', 'status' => 'PASS (Sent)'],
                    ['case' => 'Validasi form pengiriman email', 'status' => 'PASS (422 Error)'],
                    ['case' => 'Pengiriman Welcome Mail via antrean (Queue)', 'status' => 'PASS (Queued)'],
                    ['case' => 'Pengiriman Markdown NotifyUser Email', 'status' => 'PASS (Sent)'],
                    ['case' => 'Pengiriman Notifikasi Faktur InvoicePaid', 'status' => 'PASS (Notified)'],
                    ['case' => 'Pratinjau langsung Welcome Mail di browser', 'status' => 'PASS (HTML Rendered)'],
                    ['case' => 'Pratinjau langsung Markdown Mail di browser', 'status' => 'PASS (Markdown Rendered)'],
                    ['case' => 'Pratinjau langsung Notifikasi di browser', 'status' => 'PASS (Rendered)'],
                    ['case' => 'Model User mengimplementasikan MustVerifyEmail', 'status' => 'PASS (Contract Met)'],
                    ['case' => 'Registrasi memicu event verifikasi email', 'status' => 'PASS (Event Fired)'],
                ],
                'kesimpulan' => [
                    'Sistem mailing Laravel menyediakan antarmuka terpadu dan fleksibel di atas pustaka Symfony Mailer.',
                    'Pemisahan kelas Mailable dan template Markdown menghasilkan email yang elegan, responsif, dan mudah diuji.',
                    'Penggunaan email antrean (queues) secara dramatis meningkatkan konkurensi dan kecepatan respon aplikasi.',
                    'Sistem notifikasi multi-saluran dan verifikasi email menjamin integritas data serta keamanan akun pengguna.',
                ],
                'daftar_pustaka' => [
                    'Laravel LLC. (2026). Mail Documentation. https://laravel.com/docs/10.x/mail',
                    'Laravel LLC. (2026). Notifications Documentation. https://laravel.com/docs/10.x/notifications',
                    'Ariyadi, D. J. (2026). BKPM Workshop Sistem Informasi Web Framework. JTI Polije.',
                ],
            ],
        ];
    }
}
