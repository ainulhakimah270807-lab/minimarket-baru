<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Acara17Controller extends Controller
{
    public function index()
    {
        // 1. Pengenalan & Pembuatan Data Baru (Insert)
        // Kita simulasikan insert dan insertGetId
        $insertTestEmail = 'user_test_' . time() . '@example.com';
        $insertResult = DB::table('users')->insert([
            'name' => 'John Doe Test',
            'email' => $insertTestEmail,
            'password' => bcrypt('password123'),
            'status' => 'active',
            'role' => 'user',
            'age' => 24,
            'points' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $insertGetIdEmail = 'user_id_' . time() . '@example.com';
        $newId = DB::table('users')->insertGetId([
            'name' => 'Jane Doe Test',
            'email' => $insertGetIdEmail,
            'password' => bcrypt('password123'),
            'status' => 'active',
            'role' => 'user',
            'age' => 21,
            'points' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Mengambil Data Dari Database (Select & Where)
        // a. Mengambil Semua Data
        $allUsers = DB::table('users')->get();

        // b. Mengambil Data Berdasarkan Kondisi (first)
        $firstUser = DB::table('users')->where('email', 'johndoe@example.com')->first();

        // c. Menentukan Kolom yang Diambil (select)
        $selectedColumns = DB::table('users')->select('id', 'name')->get();

        // d. Where dengan Banyak Kondisi
        $multipleWhere = DB::table('users')
            ->where('status', 'active')
            ->where('role', 'admin')
            ->get();

        // e. Where dengan Operator (>= 18)
        $operatorWhere = DB::table('users')->where('age', '>=', 18)->get();

        // 3. Memperbarui Data (Update, Increment, Decrement)
        // a. Update status user
        DB::table('users')
            ->where('email', 'johndoe@example.com')
            ->update(['status' => 'active']);

        // b. Increment & Decrement points
        DB::table('users')->where('id', 1)->increment('points', 10);
        DB::table('users')->where('id', 1)->decrement('points', 5);
        $updatedUser = DB::table('users')->where('id', 1)->first();

        // 4. Menghapus Data (Delete)
        // Hapus dummy user yang baru di-insert untuk testing delete
        $deletedCount = DB::table('users')->where('email', $insertTestEmail)->delete();

        // 5. Mengambil Daftar Nilai Kolom (Pluck)
        // a. Satu kolom
        $pluckedNames = DB::table('users')->pluck('name');
        // b. Dua kolom (key dan value)
        $pluckedNameEmail = DB::table('users')->pluck('name', 'email');

        // 6. Agregat
        $totalUsers = DB::table('users')->count();
        $totalPoints = DB::table('users')->sum('points');
        $averageAge = round(DB::table('users')->avg('age'), 1);
        $maxSalary = DB::table('employees')->max('salary');
        $minSalary = DB::table('employees')->min('salary');

        // 7. Join
        // a. Inner Join
        $innerJoin = DB::table('users')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.name', 'orders.total_price')
            ->get();

        // b. Left Join
        $leftJoin = DB::table('users')
            ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.name', 'orders.total_price')
            ->get();

        // 8. Pengurutan, Limit, dan Offset
        $orderedUsers = DB::table('users')->orderBy('name', 'asc')->get();
        $limitedUsers = DB::table('users')->limit(3)->get();
        $offsetUsers = DB::table('users')->offset(2)->limit(2)->get();

        // 9. Subquery (Query di dalam Query)
        $subqueryUsers = DB::table('users')
            ->select('name')
            ->selectSub(function ($query) {
                $query->from('orders')->selectRaw('count(*)')
                    ->whereColumn('orders.user_id', 'users.id');
            }, 'order_count')
            ->get();

        // 10. Query Raw (Raw SQL)
        // a. Raw Select
        $rawSelect = DB::table('users')
            ->selectRaw('COUNT(*) as total_users, status')
            ->groupBy('status')
            ->get();

        // b. Raw Where
        $rawWhere = DB::table('users')
            ->whereRaw('age > ? AND status = ?', [18, 'active'])
            ->get();

        return view('acara.acara17', compact(
            'insertResult',
            'newId',
            'allUsers',
            'firstUser',
            'selectedColumns',
            'multipleWhere',
            'operatorWhere',
            'updatedUser',
            'deletedCount',
            'pluckedNames',
            'pluckedNameEmail',
            'totalUsers',
            'totalPoints',
            'averageAge',
            'maxSalary',
            'minSalary',
            'innerJoin',
            'leftJoin',
            'orderedUsers',
            'limitedUsers',
            'offsetUsers',
            'subqueryUsers',
            'rawSelect',
            'rawWhere'
        ));
    }
}
