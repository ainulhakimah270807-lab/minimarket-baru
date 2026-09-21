<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run()
    {
        DB::table('barang')->insert([
            [
                'nama_barang' => 'Beras 5kg',
                'harga' => 75000,
                'stok' => 50
            ],
            [
                'nama_barang' => 'Minyak Goreng 2L',
                'harga' => 32000,
                'stok' => 30
            ],
            [
                'nama_barang' => 'Gula Pasir 1kg',
                'harga' => 15000,
                'stok' => 45
            ]
        ]);
    }
}
