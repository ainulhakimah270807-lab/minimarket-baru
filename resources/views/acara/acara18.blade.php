@extends('acara.layout')

@section('title', 'Acara 18: Eloquent ORM (Part 1) - Hasil Praktikum')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-box-seam text-success me-2"></i>ACARA 18: Eloquent ORM (Part 1)</h2>
        <p class="text-muted mb-0">Minggu 5/2 &bull; Operasi Dasar CRUD (Create, Read, Update, Delete) dengan Eloquent Model</p>
    </div>
    <span class="badge bg-success px-3 py-2 fs-6">Praktikum Minggu 5</span>
</div>

<!-- Navigasi Cepat -->
<div class="card card-custom p-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
        <a href="#sec1" class="btn btn-sm btn-outline-success">1. Menambahkan Data (Create)</a>
        <a href="#sec2" class="btn btn-sm btn-outline-success">2. Mengambil Data (Retrieve)</a>
        <a href="#sec3" class="btn btn-sm btn-outline-success">3. Memperbarui Data (Update)</a>
        <a href="#sec4" class="btn btn-sm btn-outline-success">4. Menghapus Data (Delete)</a>
    </div>
</div>

<!-- 1. Menambahkan Data (Create) -->
<div class="card card-custom" id="sec1">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-plus-circle text-success me-2"></i>1) Menambahkan Data ke Database (Create)</span>
        <span class="badge bg-success">create() & save()</span>
    </div>
    <div class="card-body">
        <h6>a) Menggunakan create():</h6>
        <div class="code-box">
use App\Models\User;

$user = User::create([
    'name' => 'John Doe Eloquent',
    'email' => 'johndoe@example.com',
    'password' => bcrypt('password')
]);
        </div>
        <div class="result-box mb-3">
            <strong>Catatan Model:</strong> Telah didefinisikan <code>protected $fillable = ['name', 'email', 'password', ...]</code> di <code>app/Models/User.php</code> agar aman dari Mass Assignment Exception.
            <br><strong>Data Terbuat:</strong> ID: <code>{{ $userCreated->id }}</code> | Nama: <b>{{ $userCreated->name }}</b> | Email: <code>{{ $userCreated->email }}</code>
        </div>

        <h6>b) Menggunakan save():</h6>
        <div class="code-box">
$user = new User;
$user->name = 'Jane Doe Eloquent';
$user->email = 'janedoe@example.com';
$user->password = bcrypt('password');
$user->save();
        </div>
        <div class="result-box">
            <strong>Data Tersimpan:</strong> ID: <code>{{ $userSaved->id }}</code> | Nama: <b>{{ $userSaved->name }}</b> | Email: <code>{{ $userSaved->email }}</code>
        </div>
    </div>
</div>

<!-- 2. Mengambil Data (Retrieve) -->
<div class="card card-custom" id="sec2">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-search text-primary me-2"></i>2) Mengambil Data dari Database (Retrieve)</span>
        <span class="badge bg-primary">all(), find(), where(), firstOrFail()</span>
    </div>
    <div class="card-body">
        <h6>a) Mengambil Semua Data (User::all()):</h6>
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-sm table-hover align-middle">
                <thead class="table-light">
                    <tr><th>ID</th><th>Nama</th><th>Email</th><th>Created At</th></tr>
                </thead>
                <tbody>
                    @foreach($allUsers->take(5) as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h6>b) Mengambil Data Berdasarkan ID ($user = User::find(1);):</h6>
        <div class="result-box mb-3">
            <strong>Hasil find(1):</strong> 
            {{ $userFind ? "Nama: {$userFind->name} | Email: {$userFind->email}" : "User ID 1 tidak ditemukan" }}
        </div>

        <h6>c) Menggunakan Query Builder di Eloquent (User::where('email', 'johndoe@example.com')->get()):</h6>
        <div class="result-box mb-3">
            <strong>Jumlah Cocok:</strong> {{ $userWhere->count() }} data
            @if($userWhere->first())
                (Nama: {{ $userWhere->first()->name }})
            @endif
        </div>

        <h6>d) Menggunakan firstOrFail():</h6>
        <div class="code-box">
$user = User::where('email', 'johndoe@example.com')->firstOrFail();
        </div>
        <div class="result-box">
            <strong>Hasil firstOrFail():</strong> Berhasil ditemukan data <b>{{ $userFirstOrFail->name }}</b>.
            <br><small class="text-muted">*Jika data tidak ditemukan, metode ini akan secara otomatis melempar <code>ModelNotFoundException</code> yang menghasilkan respons HTTP 404 Not Found.</small>
        </div>
    </div>
</div>

<!-- 3. Memperbarui Data (Update) -->
<div class="card card-custom" id="sec3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-pencil-square text-warning me-2"></i>3) Memperbarui Data (Update)</span>
        <span class="badge bg-warning text-dark">update() & save()</span>
    </div>
    <div class="card-body">
        <h6>a) Menggunakan update():</h6>
        <div class="code-box">
User::where('email', 'user@example.com')->update(['name' => 'John Updated']);
        </div>
        <div class="result-box mb-3">
            <strong>Hasil Data setelah update():</strong> 
            {{ $userAfterUpdate ? $userAfterUpdate->name : 'John Updated' }}
        </div>

        <h6>b) Menggunakan save():</h6>
        <div class="code-box">
$user = User::find(1);
$user->name = 'Jane Updated by save()';
$user->save();
        </div>
        <div class="result-box">
            <strong>Hasil Data setelah save():</strong> 
            {{ $userToSave ? $userToSave->name : 'Jane Updated by save()' }}
        </div>
    </div>
</div>

<!-- 4. Menghapus Data (Delete) -->
<div class="card card-custom" id="sec4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-trash text-danger me-2"></i>4) Menghapus Data (Delete)</span>
        <span class="badge bg-danger">delete() & destroy()</span>
    </div>
    <div class="card-body">
        <h6>a) Menggunakan delete():</h6>
        <div class="code-box">
$user = User::find(1);
$user->delete();
        </div>
        <div class="result-box mb-3">
            <strong>Status Eksekusi delete():</strong> 
            <span class="badge bg-{{ $deletedViaDelete ? 'success' : 'secondary' }}">
                {{ $deletedViaDelete ? 'Berhasil dihapus' : 'Record sudah dihapus sebelumnya' }}
            </span>
        </div>

        <h6>b) Menggunakan destroy():</h6>
        <div class="code-box">
User::destroy(1); // Menerima ID atau array ID
        </div>
        <div class="result-box">
            <strong>Hasil destroy():</strong> {{ $destroyedCount }} record berhasil dihapus.
        </div>
    </div>
</div>
@endsection
