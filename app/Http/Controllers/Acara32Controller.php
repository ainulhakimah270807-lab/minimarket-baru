<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\NotifyUser;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Notifications\InvoicePaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class Acara32Controller extends Controller
{
    /**
     * Halaman Utama Acara 32: Kirim Email dengan Laravel
     */
    public function index()
    {
        // 1. Ambil status konfigurasi email dari config / .env
        $mailConfig = [
            'mailer'       => config('mail.default'),
            'host'         => config('mail.mailers.smtp.host', '-'),
            'port'         => config('mail.mailers.smtp.port', '-'),
            'encryption'   => config('mail.mailers.smtp.encryption', 'null'),
            'from_address' => config('mail.from.address'),
            'from_name'    => config('mail.from.name'),
            'queue'        => config('queue.default'),
        ];

        // 2. Ambil user terdaftar untuk pilihan target (atau fallback)
        $users = User::take(10)->get();
        $currentUser = Auth::user() ?? (object) [
            'name'  => 'Ainul Hakimah',
            'email' => 'ainulhakimah270807@gmail.com',
        ];

        // 3. Baca riwayat log email terbaru dari storage/logs/laravel.log
        $emailLogs = $this->getRecentEmailLogs();

        return view('acara.acara32-operasional', compact('mailConfig', 'users', 'currentUser', 'emailLogs'));
    }

    /**
     * Kirim Welcome Email Biasa (Mailable View) via Mail::to()->send()
     */
    public function sendWelcome(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name'  => 'required|string|max:100',
        ], [
            'email.required' => 'Email penerima wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'name.required'  => 'Nama penerima wajib diisi.',
        ]);

        $recipient = (object) [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        Mail::to($recipient->email)->send(new WelcomeMail($recipient));

        $mailer = config('mail.default');
        $msg = "Email Sambutan (WelcomeMail) berhasil dikirim ke {$recipient->email} via driver [{$mailer}].";
        if ($mailer === 'log') {
            $msg .= ' Karena driver MAIL_MAILER=log, isi email dicatat pada storage/logs/laravel.log.';
        }

        return redirect()->route('acara32.index')->with('success', $msg);
    }

    /**
     * Kirim Email Menggunakan Queue via Mail::to()->queue()
     */
    public function queueWelcome(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name'  => 'required|string|max:100',
        ]);

        $recipient = (object) [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        Mail::to($recipient->email)->queue(new WelcomeMail($recipient));

        $queueDriver = config('queue.default');
        return redirect()->route('acara32.index')->with('success', "Email berhasil dimasukkan ke antrean (Queue) via driver [{$queueDriver}].");
    }

    /**
     * Kirim Email Template Markdown via NotifyUser
     */
    public function sendMarkdown(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name'  => 'required|string|max:100',
            'pesan' => 'required|string|max:500',
        ]);

        $recipient = (object) [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        Mail::to($recipient->email)->send(new NotifyUser($recipient, $request->pesan));

        return redirect()->route('acara32.index')->with('success', "Email Markdown berhasil dikirim ke {$recipient->email}.");
    }

    /**
     * Kirim Email Notifikasi via Notification::route() / $user->notify()
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'email'        => 'required|email',
            'name'         => 'required|string|max:100',
            'invoice_code' => 'required|string|max:50',
            'total'        => 'required|numeric|min:1000',
        ]);

        $invoiceData = [
            'code'  => $request->invoice_code,
            'total' => $request->total,
        ];

        $recipient = (object) [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // Gunakan Notification on-demand
        Notification::route('mail', $recipient->email)->notify(new InvoicePaid($invoiceData));

        return redirect()->route('acara32.index')->with('success', "Notifikasi Pembayaran Invoice [{$request->invoice_code}] berhasil dikirim ke {$recipient->email}.");
    }

    /**
     * Preview Email WelcomeMail di Browser
     */
    public function previewWelcome()
    {
        $dummyUser = (object) [
            'name'  => 'Ainul Hakimah',
            'email' => 'ainulhakimah270807@gmail.com',
        ];

        return new WelcomeMail($dummyUser);
    }

    /**
     * Preview Email Markdown NotifyUser di Browser
     */
    public function previewMarkdown()
    {
        $dummyUser = (object) [
            'name'  => 'Ainul Hakimah',
            'email' => 'ainulhakimah270807@gmail.com',
        ];

        return new NotifyUser(
            $dummyUser,
            'Pembaruan berkala sistem Point of Sales: Kami baru saja memperbarui katalog produk sembako dan modul laporan keuangan toko.',
            url('/dashboard')
        );
    }

    /**
     * Preview Email Notifikasi InvoicePaid di Browser
     */
    public function previewNotification()
    {
        $dummyUser = (object) [
            'name'  => 'Ainul Hakimah',
            'email' => 'ainulhakimah270807@gmail.com',
        ];

        $notification = new InvoicePaid([
            'code'  => 'INV-202610-0089',
            'total' => 285000,
        ]);

        return $notification->toMail($dummyUser)->render();
    }

    /**
     * Helper: Ambil cuplikan log email terbaru dari laravel.log
     */
    private function getRecentEmailLogs()
    {
        $logPath = storage_path('logs/laravel.log');

        if (!File::exists($logPath)) {
            return [];
        }

        $content = File::get($logPath);
        $lines = explode("\n", $content);

        // Ambil baris-baris yang memuat kata 'From:', 'To:', atau 'Subject:'
        $mailLogs = [];
        $currentLog = [];
        $capture = false;

        // Ambil 300 baris terakhir
        $recentLines = array_slice($lines, -300);

        foreach ($recentLines as $line) {
            if (str_contains($line, 'From:') || str_contains($line, 'To:') || str_contains($line, 'Subject:')) {
                $mailLogs[] = trim($line);
            }
        }

        return array_reverse(array_slice($mailLogs, -15));
    }

    /**
     * Bersihkan log laravel.log
     */
    public function clearLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }

        return redirect()->route('acara32.index')->with('success', 'Riwayat log email berhasil dibersihkan.');
    }
}
