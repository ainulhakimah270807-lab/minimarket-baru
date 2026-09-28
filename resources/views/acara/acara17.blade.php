@extends('acara.layout')

@section('title', 'Acara 17: Query Builder - Hasil Praktikum')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-database text-primary me-2"></i>ACARA 17: Laravel Query Builder</h2>
        <p class="text-muted mb-0">Minggu 5/1 &bull; Implementasi dan Pengujian Operasi Query Builder</p>
    </div>
    <span class="badge bg-primary px-3 py-2 fs-6">Praktikum Minggu 5</span>
</div>

<!-- Navigasi Cepat Section -->
<div class="card card-custom p-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
        <a href="#sec1" class="btn btn-sm btn-outline-primary">1. Insert Data</a>
        <a href="#sec2" class="btn btn-sm btn-outline-primary">2. Mengambil Data (Select)</a>
        <a href="#sec3" class="btn btn-sm btn-outline-primary">3. Memperbarui Data (Update)</a>
        <a href="#sec4" class="btn btn-sm btn-outline-primary">4. Menghapus Data (Delete)</a>
        <a href="#sec5" class="btn btn-sm btn-outline-primary">5. Pluck</a>
        <a href="#sec6" class="btn btn-sm btn-outline-primary">6. Agregat</a>
        <a href="#sec7" class="btn btn-sm btn-outline-primary">7. Join Table</a>
        <a href="#sec8" class="btn btn-sm btn-outline-primary">8. Order, Limit, Offset</a>
        <a href="#sec9" class="btn btn-sm btn-outline-primary">9. Subquery</a>
        <a href="#sec10" class="btn btn-sm btn-outline-primary">10. Raw SQL</a>
    </div>
</div>

<!-- 1. Pengenalan & Pembuatan Data Baru -->
<div class="card card-custom" id="sec1">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-plus-circle text-success me-2"></i>1) Pengenalan & Pembuatan Data Baru (Insert)</span>
        <span class="badge bg-success">insert() & insertGetId()</span>
    </div>
    <div class="card-body">
        <h6>a. Contoh Insert Data Biasa:</h6>
        <div class="code-box">
DB::table('users')->insert([
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
    'password' => bcrypt('password123')
]);
        </div>
        <div class="result-box mb-3">
            <strong>Status Eksekusi Insert:</strong> 
            <span class="badge bg-success"><i class="bi bi-check-lg"></i> Berhasil (Output: {{ $insertResult ? 'true' : 'false' }})</span>
        </div>

        <h6>b. Insert dan Mendapatkan ID yang Dihasilkan:</h6>
        <div class="code-box">
$id = DB::table('users')->insertGetId([
    'name' => 'Jane Doe',
    'email' => 'janedoe@example.com',
    'password' => bcrypt('password123')
]);
        </div>
        <div class="result-box">
            <strong>ID Record Baru yang Dihasilkan:</strong> 
            <span class="badge bg-info text-dark fs-6">{{ $newId }}</span>
        </div>
    </div>
</div>

<!-- 2. Mengambil Data Dari Database -->
<div class="card card-custom" id="sec2">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-search text-primary me-2"></i>2) Mengambil Data Dari Database (Retrieve)</span>
        <span class="badge bg-primary">get(), first(), select(), where()</span>
    </div>
    <div class="card-body">
        <h6>a. Mengambil Semua Data ($users = DB::table('users')->get();):</h6>
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-sm table-hover align-middle">
                <thead class="table-light">
                    <tr><th>ID</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Usia</th><th>Poin</th></tr>
                </thead>
                <tbody>
                    @foreach($allUsers->take(5) as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge bg-secondary">{{ $u->role ?? '-' }}</span></td>
                        <td><span class="badge bg-{{ $u->status == 'active' ? 'success' : 'warning' }}">{{ $u->status }}</span></td>
                        <td>{{ $u->age ?? '-' }}</td>
                        <td>{{ $u->points }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h6>b. Mengambil Data Berdasarkan Kondisi (where()->first()):</h6>
        <div class="code-box">
$user = DB::table('users')->where('email', 'johndoe@example.com')->first();
        </div>
        <div class="result-box mb-3">
            <strong>Hasil:</strong> {{ $firstUser ? $firstUser->name . ' (' . $firstUser->email . ')' : 'Tidak ditemukan' }}
        </div>

        <h6>c. Menentukan Kolom yang Diambil (select('id', 'name')->get()):</h6>
        <div class="code-box">
$users = DB::table('users')->select('id', 'name')->get();
        </div>
        <div class="result-box mb-3">
            <strong>Data yang diambil (hanya ID dan Nama):</strong>
            <code>{{ $selectedColumns->take(3)->toJson() }}</code>
        </div>

        <h6>d. Where dengan Banyak Kondisi (where('status', 'active')->where('role', 'admin')):</h6>
        <div class="result-box mb-3">
            <strong>Pengguna dengan status=active dan role=admin:</strong>
            <ul>
                @foreach($multipleWhere as $mw)
                <li>{{ $mw->name }} ({{ $mw->email }}) - Role: {{ $mw->role }}</li>
                @endforeach
            </ul>
        </div>

        <h6>e. Where dengan Operator (where('age', '>=', 18)):</h6>
        <div class="result-box">
            <strong>Jumlah Pengguna Berusia >= 18 tahun:</strong> 
            <span class="badge bg-primary">{{ $operatorWhere->count() }} orang</span>
            <small class="text-muted ms-2">({{ $operatorWhere->pluck('name')->implode(', ') }})</small>
        </div>
    </div>
</div>

<!-- 3. Memperbarui Data -->
<div class="card card-custom" id="sec3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-pencil-square text-warning me-2"></i>3) Memperbarui Data (Update, Increment & Decrement)</span>
        <span class="badge bg-warning text-dark">update(), increment(), decrement()</span>
    </div>
    <div class="card-body">
        <h6>a. Update Status:</h6>
        <div class="code-box">
DB::table('users')->where('email', 'johndoe@example.com')->update(['status' => 'active']);
        </div>
        <h6>b. Increment & Decrement:</h6>
        <div class="code-box">
DB::table('users')->where('id', 1)->increment('points', 10);
DB::table('users')->where('id', 1)->decrement('points', 5);
        </div>
        <div class="result-box">
            <strong>Data User ID 1 setelah diperbarui:</strong>
            <br>Nama: <b>{{ $updatedUser->name }}</b> | Status: <span class="badge bg-success">{{ $updatedUser->status }}</span> | Total Poin Sekarang: <span class="badge bg-primary fs-6">{{ $updatedUser->points }}</span> (bertambah netto +5)
        </div>
    </div>
</div>

<!-- 4. Menghapus Data -->
<div class="card card-custom" id="sec4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-trash text-danger me-2"></i>4) Menghapus Data (Delete & Truncate)</span>
        <span class="badge bg-danger">delete(), truncate()</span>
    </div>
    <div class="card-body">
        <h6>a. Menghapus Data Tertentu:</h6>
        <div class="code-box">
DB::table('users')->where('email', 'temp_test@example.com')->delete();
        </div>
        <div class="result-box mb-3">
            <strong>Jumlah baris yang dihapus:</strong> <span class="badge bg-danger">{{ $deletedCount }} baris</span>
        </div>
        <h6>b. Menghapus Semua Data di Tabel (Truncate):</h6>
        <div class="code-box">
DB::table('users')->truncate(); // Mereset ID auto-increment dan menghapus semua baris
        </div>
    </div>
</div>

<!-- 5. Pluck -->
<div class="card card-custom" id="sec5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list-check text-info me-2"></i>5) Mengambil Daftar Nilai Kolom (Pluck)</span>
        <span class="badge bg-info text-dark">pluck()</span>
    </div>
    <div class="card-body">
        <h6>a. Mengambil Satu Kolom ($names = DB::table('users')->pluck('name');):</h6>
        <div class="result-box mb-3">
            <code>{{ json_encode($pluckedNames) }}</code>
        </div>
        <h6>b. Mengambil Dua Kolom Sebagai Key dan Value ($users = DB::table('users')->pluck('name', 'email');):</h6>
        <div class="result-box">
            <code>{{ json_encode($pluckedNameEmail, JSON_PRETTY_PRINT) }}</code>
        </div>
    </div>
</div>

<!-- 6. Agregat -->
<div class="card card-custom" id="sec6">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calculator text-dark me-2"></i>6) Fungsi Agregat</span>
        <span class="badge bg-dark">count(), sum(), avg(), max(), min()</span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 bg-light rounded text-center border">
                    <small class="text-muted">Total Pengguna (count)</small>
                    <h4 class="fw-bold text-primary mt-1">{{ $totalUsers }} Pengguna</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded text-center border">
                    <small class="text-muted">Total Poin Keseluruhan (sum)</small>
                    <h4 class="fw-bold text-success mt-1">{{ number_format($totalPoints) }} Poin</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded text-center border">
                    <small class="text-muted">Rata-rata Usia Pengguna (avg)</small>
                    <h4 class="fw-bold text-info mt-1">{{ $averageAge }} Tahun</h4>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded text-center border">
                    <small class="text-muted">Gaji Tertinggi Pegawai (max)</small>
                    <h4 class="fw-bold text-danger mt-1">Rp {{ number_format($maxSalary, 0, ',', '.') }}</h4>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded text-center border">
                    <small class="text-muted">Gaji Terendah Pegawai (min)</small>
                    <h4 class="fw-bold text-secondary mt-1">Rp {{ number_format($minSalary, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 7. Join Table -->
<div class="card card-custom" id="sec7">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-link-45deg text-primary me-2"></i>7) Menggabungkan Tabel (Join)</span>
        <span class="badge bg-primary">join() & leftJoin()</span>
    </div>
    <div class="card-body">
        <h6>a. Inner Join (Hanya menampilkan pengguna yang memiliki pesanan di tabel orders):</h6>
        <div class="code-box">
$users = DB::table('users')
    ->join('orders', 'users.id', '=', 'orders.user_id')
    ->select('users.name', 'orders.total_price')
    ->get();
        </div>
        <div class="table-responsive mb-4">
            <table class="table table-sm table-bordered">
                <thead class="table-light"><tr><th>Nama User</th><th>Total Pesanan (Rp)</th></tr></thead>
                <tbody>
                    @foreach($innerJoin as $ij)
                    <tr><td>{{ $ij->name }}</td><td>Rp {{ number_format($ij->total_price, 0, ',', '.') }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h6>b. Left Join (Menampilkan semua pengguna beserta data pesanan bila ada):</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light"><tr><th>Nama User</th><th>Total Pesanan</th></tr></thead>
                <tbody>
                    @foreach($leftJoin->take(6) as $lj)
                    <tr>
                        <td>{{ $lj->name }}</td>
                        <td>{{ $lj->total_price ? 'Rp ' . number_format($lj->total_price, 0, ',', '.') : '<Belum ada pesanan>' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 8. Pengurutan, Limit, dan Offset -->
<div class="card card-custom" id="sec8">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-sort-alpha-down text-secondary me-2"></i>8) Pengurutan, Limit, dan Offset</span>
        <span class="badge bg-secondary">orderBy(), limit(), offset()</span>
    </div>
    <div class="card-body">
        <h6>a. Mengurutkan Nama (orderBy('name', 'asc')):</h6>
        <div class="result-box mb-3">
            <b>Urutan:</b> {{ $orderedUsers->pluck('name')->implode(' &rarr; ') }}
        </div>
        <h6>b. Batasi 3 Data (limit(3)):</h6>
        <div class="result-box mb-3">
            <b>3 User Pertama:</b> {{ $limitedUsers->pluck('name')->implode(', ') }}
        </div>
        <h6>c. Offset untuk Pagination (offset(2)->limit(2)):</h6>
        <div class="result-box">
            <b>2 Data setelah melewati 2 data pertama:</b> {{ $offsetUsers->pluck('name')->implode(', ') }}
        </div>
    </div>
</div>

<!-- 9. Subquery -->
<div class="card card-custom" id="sec9">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-layers text-success me-2"></i>9) Subquery (Query di dalam Query)</span>
        <span class="badge bg-success">selectSub()</span>
    </div>
    <div class="card-body">
        <div class="code-box">
$users = DB::table('users')
    ->select('name')
    ->selectSub(function ($query) {
        $query->from('orders')->selectRaw('count(*)')
              ->whereColumn('orders.user_id', 'users.id');
    }, 'order_count')
    ->get();
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light"><tr><th>Nama Pengguna</th><th>Jumlah Pesanan (order_count)</th></tr></thead>
                <tbody>
                    @foreach($subqueryUsers as $sq)
                    <tr><td>{{ $sq->name }}</td><td><span class="badge bg-primary">{{ $sq->order_count }} pesanan</span></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 10. Query Raw -->
<div class="card card-custom" id="sec10">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-terminal text-dark me-2"></i>10) Query Raw (Raw SQL)</span>
        <span class="badge bg-dark">selectRaw() & whereRaw()</span>
    </div>
    <div class="card-body">
        <h6>a. Raw Select dengan Group By:</h6>
        <div class="code-box">
$users = DB::table('users')
    ->selectRaw('COUNT(*) as total_users, status')
    ->groupBy('status')
    ->get();
        </div>
        <div class="result-box mb-3">
            @foreach($rawSelect as $rs)
            <div>Status: <b>{{ $rs->status }}</b> &rarr; Jumlah Pengguna: <b>{{ $rs->total_users }}</b></div>
            @endforeach
        </div>

        <h6>b. Raw Where:</h6>
        <div class="code-box">
$users = DB::table('users')->whereRaw('age > ? AND status = ?', [18, 'active'])->get();
        </div>
        <div class="result-box">
            <b>Hasil Pengguna (age > 18 DAN status = 'active'):</b>
            {{ $rawWhere->pluck('name')->implode(', ') }}
        </div>
    </div>
</div>
@endsection
