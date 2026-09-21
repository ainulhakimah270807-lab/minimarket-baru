<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT. Indofood Sukses Makmur',
                'phone' => '02157958822',
                'address' => 'Jl. Jend. Sudirman Plaza Indofood, Jakarta',
            ],
            [
                'name' => 'PT. Unilever Indonesia',
                'phone' => '02180827000',
                'address' => 'BSD Green Office Park, Tangerang',
            ],
            [
                'name' => 'PT. Mayora Indah Tbk',
                'phone' => '02180637000',
                'address' => 'Jl. Tomang Raya No. 21-23, Jakarta Barat',
            ],
        ];

        foreach ($suppliers as $data) {
            Supplier::create($data);
        }
    }
}
