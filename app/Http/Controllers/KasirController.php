<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\OrderPlaced; // 1. Tambahkan import Event ini di atas
use App\Models\User;        // (Opsional) Import Model User[cite: 11, 13]

class KasirController extends Controller
{
    /**
     * Tampilkan Halaman Transaksi Pembayaran Kasir POS.
     * Praktikum Ke-3
     */
    public function index()
    {
        // Generate auto transaction number based on current date
        $transactionNumber = 'TRX-' . date('Ymd') . '-001';

        return view('kasir.transaksi', compact('transactionNumber'));
    }

    /**
     * Simpan Transaksi Baru dan Trigger Event Broadcast Reverb (Acara 32)
     */
    public function store(Request $request)
    {
        // 1. Lakukan validasi atau simpan data transaksi ke database sesuai project kamu
        // Contoh membuat objek order/transaksi (sesuaikan dengan tabel database kamu):
        // $order = Order::create([
        //     'user_id' => auth()->id(),
        //     'total' => $request->total,
        //     'transaction_number' => $request->transaction_number,
        // ]);

        // *Catatan untuk latihan/praktikum jika belum ada tabel order asli, 
        // kamu bisa buat data dummy objek seperti ini agar event bisa terkirim:*
        $order = (object) [
            'id' => rand(100, 999),
            'total' => $request->input('total', 50000),
        ];

        // 2. Panggil event broadcast setelah order berhasil diproses (Acara 32 - Langkah 2)
        event(new OrderPlaced($order, auth()->user()));

        return redirect()->back()->with('success', 'Transaksi berhasil disimpan dan broadcast dikirim!');
    }
}