<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AcaraPraktikumSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key checks sementara untuk truncate/refresh
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_user')->truncate();
        DB::table('roles')->truncate();
        DB::table('profiles')->truncate();
        DB::table('posts')->truncate();
        DB::table('employees')->truncate();
        DB::table('orders')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Seed Users
        $users = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'johndoe@example.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'role' => 'admin',
                'age' => 25,
                'points' => 150,
                'active' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Jane Doe',
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'janedoe@example.com',
                'password' => Hash::make('password123'),
                'status' => 'inactive',
                'role' => 'editor',
                'age' => 22,
                'points' => 80,
                'active' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Ahmad Santoso',
                'first_name' => 'Ahmad',
                'last_name' => 'Santoso',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'role' => 'admin',
                'age' => 32,
                'points' => 250,
                'active' => 1,
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Siti Nurhaliza',
                'first_name' => 'Siti',
                'last_name' => 'Nurhaliza',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'role' => 'editor',
                'age' => 19,
                'points' => 50,
                'active' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Budi Nugroho',
                'first_name' => 'Budi',
                'last_name' => 'Nugroho',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'status' => 'inactive',
                'role' => 'user',
                'age' => 16,
                'points' => 10,
                'active' => 0,
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('users')->insert($users);

        // 2. Seed Orders (Acara 17 - Join & Subquery)
        DB::table('orders')->insert([
            ['id' => 1, 'user_id' => 1, 'total_price' => 150000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'user_id' => 1, 'total_price' => 320000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'user_id' => 3, 'total_price' => 500000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Seed Employees (Acara 17 - Agregat Max & Min Salary)
        DB::table('employees')->insert([
            ['id' => 1, 'name' => 'Manager Toko', 'salary' => 12000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Staff Gudang', 'salary' => 4500000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Kasir Utama', 'salary' => 5000000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Seed Profile (Acara 19 - One to One)
        DB::table('profiles')->insert([
            ['id' => 1, 'user_id' => 1, 'bio' => 'Web Developer POS Toko Kelontong', 'address' => 'Jl. Kalimantan No. 37, Jember', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 5. Seed Roles & Role User (Acara 19 - Many to Many)
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'editor', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'kasir', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('role_user')->insert([
            ['id' => 1, 'user_id' => 1, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'user_id' => 1, 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'user_id' => 2, 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 6. Seed Posts (Acara 19 - One to Many)
        DB::table('posts')->insert([
            ['id' => 1, 'user_id' => 1, 'title' => 'Update Stok Beras Premium', 'content' => 'Stok beras premium 5kg bertambah 50 karung.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'user_id' => 1, 'title' => 'Promo Diskon Minyak Goreng', 'content' => 'Diskon 10% untuk kemasan 2L akhir pekan.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
