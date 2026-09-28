<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MinimarketDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Kategori Minimarket
        $categoriesData = [
            'Makanan Ringan' => [
                ['name' => 'Indomie Goreng Spesial 85g', 'sku' => 'MKN-001', 'price' => 3500, 'stock' => 150],
                ['name' => 'Indomie Kuah Ayam Bawang 75g', 'sku' => 'MKN-002', 'price' => 3500, 'stock' => 120],
                ['name' => 'Chitato Sapi Panggang 68g', 'sku' => 'MKN-003', 'price' => 11500, 'stock' => 45],
                ['name' => 'SilverQueen Milk Chocolate 58g', 'sku' => 'MKN-004', 'price' => 16500, 'stock' => 8], // Stok Menipis
                ['name' => 'Oreo Vanilla Roll 119.6g', 'sku' => 'MKN-005', 'price' => 9000, 'stock' => 35],
                ['name' => 'Taro Net Seaweed 65g', 'sku' => 'MKN-006', 'price' => 8500, 'stock' => 25],
                ['name' => 'Roma Biskuit Kelapa 300g', 'sku' => 'MKN-007', 'price' => 12000, 'stock' => 4], // Stok Menipis
            ],
            'Minuman' => [
                ['name' => 'Aqua Air Mineral 600ml', 'sku' => 'MNM-001', 'price' => 3500, 'stock' => 180],
                ['name' => 'Le Minerale 600ml', 'sku' => 'MNM-002', 'price' => 3500, 'stock' => 100],
                ['name' => 'Teh Botol Sosro Kotak 250ml', 'sku' => 'MNM-003', 'price' => 4000, 'stock' => 60],
                ['name' => 'Ultra Milk Cokelat 250ml', 'sku' => 'MNM-004', 'price' => 6500, 'stock' => 50],
                ['name' => 'Pocari Sweat Can 330ml', 'sku' => 'MNM-005', 'price' => 7500, 'stock' => 5], // Stok Menipis
                ['name' => 'Coca Cola Botol 390ml', 'sku' => 'MNM-006', 'price' => 5500, 'stock' => 0], // Stok Habis
                ['name' => 'Good Day Cappuccino 250ml', 'sku' => 'MNM-007', 'price' => 7000, 'stock' => 40],
            ],
            'Kebutuhan Pokok' => [
                ['name' => 'Beras Premium Ramos 5kg', 'sku' => 'KBP-001', 'price' => 72000, 'stock' => 30],
                ['name' => 'Minyak Goreng Sania 2L', 'sku' => 'KBP-002', 'price' => 34500, 'stock' => 40],
                ['name' => 'Minyak Goreng Bimoli 2L', 'sku' => 'KBP-003', 'price' => 36000, 'stock' => 6], // Stok Menipis
                ['name' => 'Gula Pasir Gulaku 1kg', 'sku' => 'KBP-004', 'price' => 17500, 'stock' => 55],
                ['name' => 'Tepung Terigu Segitiga Biru 1kg', 'sku' => 'KBP-005', 'price' => 13000, 'stock' => 35],
                ['name' => 'Telur Ayam Negeri 1kg', 'sku' => 'KBP-006', 'price' => 28000, 'stock' => 20],
            ],
            'Kebutuhan Rumah Tangga' => [
                ['name' => 'Sunlight Jeruk Nipis 750ml', 'sku' => 'KRT-001', 'price' => 15500, 'stock' => 45],
                ['name' => 'Rinso Molto Deterjen Bubuk 770g', 'sku' => 'KRT-002', 'price' => 21000, 'stock' => 30],
                ['name' => 'So Klin Pembersih Lantai 780ml', 'sku' => 'KRT-003', 'price' => 11000, 'stock' => 25],
                ['name' => 'Baygon Aerosol Tea Blossom 600ml', 'sku' => 'KRT-004', 'price' => 38000, 'stock' => 3], // Stok Menipis
                ['name' => 'Tissue Paseo Smart Facial 250s', 'sku' => 'KRT-005', 'price' => 14500, 'stock' => 50],
            ],
            'Perawatan Diri' => [
                ['name' => 'Sabun Mandi Lifebuoy Total 10 85g', 'sku' => 'PWD-001', 'price' => 4500, 'stock' => 65],
                ['name' => 'Shampoo Pantene Anti Dandruff 160ml', 'sku' => 'PWD-002', 'price' => 26500, 'stock' => 20],
                ['name' => 'Pasta Gigi Pepsodent 190g', 'sku' => 'PWD-003', 'price' => 13500, 'stock' => 40],
                ['name' => 'Rexona Men Deodorant Roll On 50ml', 'sku' => 'PWD-004', 'price' => 19000, 'stock' => 7], // Stok Menipis
            ],
            'Obat-obatan & Kesehatan' => [
                ['name' => 'Tolak Angin Cair Box', 'sku' => 'OBT-001', 'price' => 22000, 'stock' => 30],
                ['name' => 'Paracetamol 500mg Strip', 'sku' => 'OBT-002', 'price' => 5000, 'stock' => 50],
                ['name' => 'Hansaplast Plester Luka Reguler', 'sku' => 'OBT-003', 'price' => 6000, 'stock' => 40],
                ['name' => 'Minyak Kayu Putih Cap Lang 60ml', 'sku' => 'OBT-004', 'price' => 24500, 'stock' => 2], // Stok Menipis
            ],
            'Bumbu & Dapur' => [
                ['name' => 'Kecap Manis Bango 520ml', 'sku' => 'BMB-001', 'price' => 23000, 'stock' => 35],
                ['name' => 'Saus Sambal ABC Extra Pedas 335ml', 'sku' => 'BMB-002', 'price' => 14000, 'stock' => 40],
                ['name' => 'Masako Rasa Sapi 100g', 'sku' => 'BMB-003', 'price' => 5500, 'stock' => 80],
                ['name' => 'Garam Beryodium Cap Kapal 250g', 'sku' => 'BMB-004', 'price' => 3000, 'stock' => 60],
            ],
        ];

        foreach ($categoriesData as $categoryName => $products) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName]
            );

            foreach ($products as $prod) {
                Product::updateOrCreate(
                    ['sku' => $prod['sku']],
                    [
                        'category_id' => $category->id,
                        'name' => $prod['name'],
                        'price' => $prod['price'],
                        'stock' => $prod['stock'],
                    ]
                );
            }
        }

        // 2. Data Produk untuk Arsip (Soft Deleted) untuk menguji fitur restore Acara 19
        $archivedCategory = Category::where('slug', 'makanan-ringan')->first() ?? Category::first();
        if ($archivedCategory) {
            $archived1 = Product::updateOrCreate(
                ['sku' => 'ARS-001'],
                [
                    'category_id' => $archivedCategory->id,
                    'name' => 'Sari Roti Sobek Cokelat 120g (Kadaluarsa)',
                    'price' => 16000,
                    'stock' => 0,
                ]
            );
            if (!$archived1->trashed()) {
                $archived1->delete();
            }

            $archived2 = Product::updateOrCreate(
                ['sku' => 'ARS-002'],
                [
                    'category_id' => $archivedCategory->id,
                    'name' => 'Susu Kedelai Fresh 200ml (Kemasan Rusak)',
                    'price' => 8000,
                    'stock' => 0,
                ]
            );
            if (!$archived2->trashed()) {
                $archived2->delete();
            }
        }
    }
}
