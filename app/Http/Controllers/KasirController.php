<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
