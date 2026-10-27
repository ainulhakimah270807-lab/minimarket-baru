<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct($invoice = null)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $invoiceCode = $this->invoice['code'] ?? 'INV-' . strtoupper(substr(md5((string) time()), 0, 8));
        $total = number_format($this->invoice['total'] ?? 150000, 0, ',', '.');

        return (new MailMessage)
            ->subject('Pembayaran Invoice Berhasil - ' . $invoiceCode)
            ->greeting('Halo, ' . ($notifiable->name ?? 'Pelanggan') . '!')
            ->line('Transaksi pembayaran Anda telah berhasil kami verifikasi.')
            ->line('Nomor Invoice: ' . $invoiceCode)
            ->line('Total Pembayaran: Rp ' . $total)
            ->action('Lihat Bukti Transaksi', url('/kasir/transaksi'))
            ->line('Terima kasih telah mempercayakan kebutuhan belanja Anda kepada Minimarket Baru!');
    }
}
