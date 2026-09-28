<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Jalankan Seeder Kategori & Produk Minimarket
        $this->call(MinimarketDataSeeder::class);

        // 2. Jalankan Seeder Supplier (Tugas Mandiri)
        $this->call(SupplierSeeder::class);

        // 3. Jalankan Seeder Acara Praktikum
        $this->call(AcaraPraktikumSeeder::class);
    }
}

