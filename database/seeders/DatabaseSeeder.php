<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Jalankan Seeder Kategori
        $this->call(CategorySeeder::class);

        // 2. Jalankan Seeder Supplier (Tugas Mandiri)
        $this->call(SupplierSeeder::class);

        // 3. Jalankan Factory Produk untuk membuat 50 data dummy produk
        \App\Models\Product::factory(50)->create();
    }
}
