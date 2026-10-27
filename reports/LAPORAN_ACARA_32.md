# LAPORAN PRAKTIKUM
## WORKSHOP SISTEM INFORMASI WEB FRAMEWORK

---

**ACARA 32: LARAVEL EMAIL (MAIL, QUEUES, & NOTIFICATIONS)**  
**Minggu Praktikum**: Minggu Ke-9/10  
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
Komunikasi asinkron antara aplikasi web dan pengguna merupakan bagian integral dalam operasional sistem informasi modern. Pada aplikasi minimarket dan kasir, sistem memerlukan media untuk mengirimkan faktur pembelian (*invoice*), konfirmasi pendaftaran akun baru, pemberitahuan pembaruan status pesanan, serta tautan verifikasi alamat email guna menjamin keaslian data pengguna.

Framework Laravel menyediakan subsistem surat elektronik (*mailing system*) yang sangat matang berbasis pustaka Symfony Mailer. Arsitektur ini mendukung berbagai driver transmisi (SMTP, Mailgun, Postmark, SES, Mailtrap, hingga driver lokal `log`), penyusunan konten email melalui kelas Mailable (baik berbasis HTML biasa maupun komponen Markdown yang elegan), pengiriman di latar belakang (*background queues*) agar waktu respon HTTP tetap instan, pengiriman notifikasi multi-saluran (*multi-channel notifications*), serta proteksi akun menggunakan mekanisme *Email Verification*. Melalui praktikum Acara 32, mahasiswa mempelajari dan mempraktikkan seluruh siklus hidup layanan email di Laravel secara komprehensif.

### 1.2 Tujuan Praktikum
1. Mahasiswa memahami konfigurasi transmisi email pada berkas `.env` dan `config/mail.php` menggunakan berbagai driver transport (SMTP, Mailtrap, dan driver `log` untuk lingkungan pengembangan lokal).
2. Mahasiswa mampu membuat dan menyusun kelas Mailable standar berbasis template view Blade HTML (`App\Mail\WelcomeMail`).
3. Mahasiswa mampu membuat kelas Mailable Markdown (`App\Mail\NotifyUser`) yang memanfaatkan komponen pra-bangun Blade Markdown untuk menghasilkan tata letak email yang responsif dan elegan.
4. Mahasiswa mampu mengimplementasikan pengiriman email melalui sistem antrean (*background queues*) menggunakan metode `Mail::to()->queue()` untuk meningkatkan kecepatan dan pengalaman pengguna aplikasi.
5. Mahasiswa mampu membangun sistem notifikasi transaksi multi-saluran menggunakan kelas Notification Laravel (`App\Notifications\InvoicePaid`).
6. Mahasiswa mampu mengonfigurasi dan menguji fitur verifikasi email pengguna dengan mengimplementasikan antarmuka `MustVerifyEmail` pada model `User` dan mengamankan rute menggunakan middleware `verified`.
7. Mahasiswa mampu melakukan pratinjau (*live preview*) tampilan email langsung di peramban tanpa harus mengirimkan email secara nyata.

### 1.3 Dasar Teori
1. **Konfigurasi Mail Driver (`config/mail.php` & `.env`)**:
   Laravel mendukung berbagai driver:
   - `smtp`: Driver berbasis Simple Mail Transfer Protocol untuk server produksi (misal Google Workspace, Amazon SES, Mailtrap).
   - `log`: Menulis seluruh konten email yang dikirim ke berkas `storage/logs/laravel.log`. Sangat aman dan ideal untuk pengujian lokal tanpa internet.
   - `array`: Menyimpan pesan di memori selama eksekusi skrip (sangat berguna untuk automated testing).
2. **Kelas Mailable**:
   Setiap jenis email direpresentasikan oleh sebuah kelas Mailable (`php artisan make:mail`). Kelas ini bertanggung jawab merakit data dan menentukan tampilan view melalui metode `content()` atau `build()`.
3. **Mailable Markdown**:
   Markdown Mailable memungkinkan penulisan pesan email dengan sintaksis Markdown yang dipadukan dengan komponen Blade khusus, seperti `@component('mail::message')`, `@component('mail::button')`, dan `@component('mail::panel')`. Laravel otomatis mengonversi dokumen ini menjadi email HTML yang responsif dan versi plain-text alternatif.
4. **Email Queues**:
   Pengiriman email via SMTP membutuhkan waktu beberapa detik per permintaan. Jika dilakukan secara sinkron (`send()`), pengguna harus menunggu proses pengiriman selesai sebelum halaman berpindah. Dengan antrean (`queue()`), pesan disimpan ke database atau Redis, dan tugas pengiriman dieksekusi di latar belakang oleh *queue worker* (`php artisan queue:work`), sehingga respons HTTP kembali dalam hitungan milidetik.
5. **Laravel Notifications**:
   Subsistem notifikasi memungkinkan pengiriman pesan yang sama melalui berbagai saluran sekaligus (email, database, SMS via Vonage/Twilio, Slack, atau broadcast).
6. **Email Verification**:
   Fitur keamanan yang memastikan bahwa alamat email yang didaftarkan oleh pengguna benar-benar valid dan dimiliki oleh pengguna tersebut. Mengimplementasikan kontrak `Illuminate\Contracts\Auth\MustVerifyEmail` pada model `User`.

---

## BAB II: HASIL DAN PEMBAHASAN

### 2.1 Alat dan Bahan
1. **Perangkat Keras**: Laptop Core i3/i5, RAM 8GB, SSD 512GB.
2. **Perangkat Lunak**:
   - Web Server: Laragon / Apache
   - Bahasa Pemrograman: PHP 8.2+
   - Framework: Laravel 10.x
   - Editor: Visual Studio Code / Antigravity IDE
   - Driver Pengujian Mail: Driver `log` lokal & driver `smtp` Mailtrap

### 2.2 Prosedur dan Langkah Kerja

1. **Konfigurasi Mail Driver**:
   Mengatur berkas `.env` untuk menggunakan driver `log` (dan konfigurasi Mailtrap untuk pengujian SMTP):
   ```env
   MAIL_MAILER=log
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="toko@minimarket-pos.test"
   MAIL_FROM_NAME="Minimarket POS Official"
   ```

2. **Membuat Kelas Mailable Selamat Datang (`WelcomeMail.php`)**:
   Menjalankan perintah Artisan:
   ```bash
   php artisan make:mail WelcomeMail
   ```
   Kelas ini menginjeksikan data nama penerima, judul pesan, dan isi teks, lalu merender view Blade HTML di `resources/views/emails/welcome.blade.php`.

3. **Membuat Kelas Mailable Markdown (`NotifyUser.php`)**:
   Menjalankan perintah Artisan pembuatan mailable markdown:
   ```bash
   php artisan make:mail NotifyUser --markdown=emails.notify
   ```
   Template `resources/views/emails/notify.blade.php` dirancang menggunakan komponen Markdown Laravel (`mail::message`, `mail::button`, `mail::panel`).

4. **Membuat Kelas Notifikasi Faktur Penjualan (`InvoicePaid.php`)**:
   Menjalankan perintah Artisan:
   ```bash
   php artisan make:notification InvoicePaid
   ```
   Notifikasi ini mengirimkan ringkasan transaksi belanja kasir (nomor invoice, nominal total belanja, tanggal pembayaran, dan tombol unduh struk) ke saluran `mail`.

5. **Mengimplementasikan Verifikasi Email Pengguna**:
   - Membuka model `app/Models/User.php` dan menambahkan kontrak `MustVerifyEmail`:
     ```php
     class User extends Authenticatable implements MustVerifyEmail
     ```
   - Memastikan event `Registered` memicu pengiriman link verifikasi saat registrasi akun baru.

6. **Membangun Controller Pengelola Email (`Acara32Controller.php`)**:
   Menyediakan fungsi terpadu:
   - `index()`: Merender halaman panel manajemen email dan membaca riwayat pengiriman dari `storage/logs/laravel.log`.
   - `sendWelcome()`: Mengirimkan email HTML via `Mail::to()->send()`.
   - `queueWelcome()`: Memasukkan email ke antrean latar belakang via `Mail::to()->queue()`.
   - `sendMarkdown()`: Mengirimkan email notifikasi markdown.
   - `sendNotification()`: Mengirimkan notifikasi invoice via Notification facade.
   - `previewWelcome()`, `previewMarkdown()`, `previewNotification()`: Menampilkan pratinjau visual email di tab peramban.
   - `clearLogs()`: Mengosongkan riwayat log email untuk kenyamanan pengujian.

7. **Mendaftarkan Rute Pengelolaan Email (`routes/web.php`)**:
   ```php
   Route::get('/acara32', [Acara32Controller::class, 'index'])->name('acara32.index');
   Route::post('/acara32/send-welcome', [Acara32Controller::class, 'sendWelcome'])->name('acara32.send.welcome');
   Route::post('/acara32/queue-welcome', [Acara32Controller::class, 'queueWelcome'])->name('acara32.queue.welcome');
   Route::post('/acara32/send-markdown', [Acara32Controller::class, 'sendMarkdown'])->name('acara32.send.markdown');
   Route::post('/acara32/send-notification', [Acara32Controller::class, 'sendNotification'])->name('acara32.send.notification');
   Route::get('/acara32/preview/welcome', [Acara32Controller::class, 'previewWelcome'])->name('acara32.preview.welcome');
   Route::get('/acara32/preview/markdown', [Acara32Controller::class, 'previewMarkdown'])->name('acara32.preview.markdown');
   Route::get('/acara32/preview/notification', [Acara32Controller::class, 'previewNotification'])->name('acara32.preview.notification');
   Route::post('/acara32/clear-logs', [Acara32Controller::class, 'clearLogs'])->name('acara32.clear.logs');
   ```

8. **Pengujian Fungsional Fitur (`Acara32Test.php`)**:
   Menguji seluruh skenario pengiriman email sinkron, antrean (queues), notifikasi, pratinjau browser, dan verifikasi email.

### 2.3 Implementasi Kode Program

#### A. Kelas Mailable Standar HTML (`WelcomeMail.php`)
```php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipientName,
        public string $customMessage = ''
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang di Minimarket POS!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }
}
```

#### B. Template Email Markdown (`emails/notify.blade.php`)
```markdown
@component('mail::message')
# Halo, {{ $userName }}!

{{ $introText }}

@component('mail::panel')
**Pemberitahuan Penting:**
Transaksi kasir dan sinkronisasi laporan inventaris toko Anda telah berhasil diperbarui ke dalam sistem cloud Minimarket POS.
@endcomponent

@component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
Lihat Dasbor Toko
@endcomponent

Terima kasih atas dedikasi dan kerja sama Anda,<br>
**Tim Manajemen {{ config('app.name') }}**
@endcomponent
```

#### C. Kelas Notifikasi Transaksi Pembayaran (`InvoicePaid.php`)
```php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    use Queueable;

    public function __construct(
        public string $invoiceNumber,
        public float $amount,
        public string $customerName
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Faktur Pembayaran Lunas - #' . $this->invoiceNumber)
            ->greeting('Yth. ' . $this->customerName . ',')
            ->line('Terima kasih telah berbelanja di Minimarket POS. Pembayaran transaksi Anda telah kami terima.')
            ->line('Nomor Transaksi: **#' . $this->invoiceNumber . '**')
            ->line('Total Pembayaran: **Rp ' . number_format($this->amount, 0, ',', '.') . '**')
            ->action('Lihat Detail Transaksi', url('/acara32'))
            ->line('Simpan email ini sebagai bukti sah pembayaran Anda.');
    }
}
```

### 2.4 Hasil Pengujian dan Evaluasi

Pengujian otomatis dieksekusi dengan perintah Artisan:
```bash
php artisan test --filter=Acara32Test
```
**Hasil Pengujian**:
```text
   PASS  Tests\Feature\Acara32Test
  ✓ can view acara32 index page
  ✓ can send welcome mail via mail facade
  ✓ send welcome mail validates inputs
  ✓ can queue welcome mail
  ✓ can send markdown notify user email
  ✓ can send invoice paid notification
  ✓ can preview welcome email in browser
  ✓ can preview markdown email in browser
  ✓ can preview notification in browser
  ✓ user implements must verify email contract
  ✓ registration triggers registered event for email verification

  Tests:    11 passed (30 assertions)
  Duration: 0.92s
```
Semua 11 skenario pengujian berhasil lulus 100%, memverifikasi bahwa pengiriman email sinkron, antrean latar belakang, Mailable Markdown, Multi-Channel Notification, serta verifikasi email bekerja dengan sempurna.

---

## BAB III: KESIMPULAN

Berdasarkan praktikum Acara 32 yang telah diselesaikan, dapat diambil kesimpulan bahwa:
1. Laravel menyediakan abstraksi layanan email yang sangat terstruktur dan fleksibel melalui integrasi Symfony Mailer yang mendukung pergantian driver transport (`smtp`, `log`, `mailgun`) tanpa perlu merombak kode program.
2. Penggunaan kelas Mailable memisahkan logika penyusunan data dari tampilan presentasi, menghasilkan kode program yang rapi, modular, dan mudah diuji (*testable*).
3. Mailable berbasis Markdown mempermudah pembuatan email bertampilan modern, responsif, dan konsisten di berbagai aplikasi email klien (*desktop*, web, maupun *mobile*).
4. Penerapan antrean pesan (*queues*) menggunakan metode `queue()` berhasil mengeliminasi waktu tunggu pengguna pada siklus permintaan HTTP, meningkatkan konkurensi dan responsivitas aplikasi secara signifikan.
5. Subsistem Notifikasi Laravel menyederhanakan penyebaran informasi ke berbagai kanal sekaligus melalui satu pemanggilan fungsi `$user->notify()`.
6. Implementasi kontrak `MustVerifyEmail` meningkatkan integritas data pengguna dan mengamankan aplikasi dari pendaftaran akun palsu.

---

## DAFTAR PUSTAKA

1. Laravel LLC. (2026). *Mail Documentation*. https://laravel.com/docs/10.x/mail
2. Laravel LLC. (2026). *Notifications Documentation*. https://laravel.com/docs/10.x/notifications
3. Laravel LLC. (2026). *Email Verification Documentation*. https://laravel.com/docs/10.x/verification
4. Ariyadi, D. J. (2026). *Buku Kerja Praktik Mahasiswa (BKPM): Workshop Sistem Informasi Web Framework*. Jurusan Teknologi Informasi, Politeknik Negeri Jember.
