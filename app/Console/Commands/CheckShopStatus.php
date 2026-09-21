<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckShopStatus extends Command
{
    protected $signature = 'pos:status {jam?}';
    protected $description = 'Mengecek status operasional Toko Kelontong POS';

    public function handle()
    {
        // Menanyakan nama kasir
        $namaKasir = $this->ask('Masukkan nama Anda: ');

        // Mengambil argumen jam, default jam 10 jika tidak diisi
        $jam = $this->argument('jam') ?? 10;

        $this->info("=== SISTEM MONITORING TOKO KELONTONG ===");
        $this->info("Halo $namaKasir, Status Toko pada jam $jam:00 WIB adalah:");

        // Toko buka jam 08:00 s/d 21:00
        if ($jam >= 8 && $jam <= 21) {
            $this->info("✅ BUKA");
            $this->comment("Silakan kasir bersiap di meja transaksi.");
        } else {
            $this->error("❌ TUTUP");
            $this->warn("Akses transaksi kasir dinonaktifkan sementara.");
        }
    }
}
