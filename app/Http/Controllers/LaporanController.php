<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Prosedur Kerja 1: Penggunaan Dasar DomPDF di Laravel
     * Sesuai modul Acara 30 BKPM
     */
    public function generatePDF()
    {
        $data = [
            'title'   => 'Laporan Pegawai',
            'tanggal' => date('d-m-Y'),
            'pegawai' => [
                ['nama' => 'Andi', 'jabatan' => 'Manager', 'gaji' => 5000000],
                ['nama' => 'Budi', 'jabatan' => 'Staff', 'gaji' => 3500000],
            ],
        ];

        $pdf = Pdf::loadView('laporan.pegawai', $data);
        return $pdf->download('laporan-pegawai.pdf');
    }

    /**
     * Stream PDF langsung di browser (preview tanpa download otomatis)
     */
    public function streamPDF()
    {
        $data = [
            'title'   => 'Laporan Pegawai',
            'tanggal' => date('d-m-Y'),
            'pegawai' => [
                ['nama' => 'Andi', 'jabatan' => 'Manager', 'gaji' => 5000000],
                ['nama' => 'Budi', 'jabatan' => 'Staff', 'gaji' => 3500000],
            ],
        ];

        $pdf = Pdf::loadView('laporan.pegawai', $data);
        return $pdf->stream('laporan-pegawai.pdf');
    }
}
