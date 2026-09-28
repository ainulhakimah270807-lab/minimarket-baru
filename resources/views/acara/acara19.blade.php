@extends('acara.layout')

@section('title', 'Acara 19: Eloquent ORM (Part 2) - Hasil Praktikum')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-diagram-3 text-info me-2"></i>ACARA 19: Eloquent ORM (Part 2)</h2>
        <p class="text-muted mb-0">Minggu 5/3 &bull; Conditional Clause, Relationships, Mutators, Accessors, Soft Deletes, Scopes</p>
    </div>
    <span class="badge bg-info text-dark px-3 py-2 fs-6">Praktikum Minggu 5</span>
</div>

<!-- Navigasi Cepat -->
<div class="card card-custom p-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
        <a href="#sec1" class="btn btn-sm btn-outline-info">1. Conditional Clause</a>
        <a href="#sec2" class="btn btn-sm btn-outline-info">2. Relasi Antar Model</a>
        <a href="#sec3" class="btn btn-sm btn-outline-info">3. Mutators & Accessors</a>
        <a href="#sec4" class="btn btn-sm btn-outline-info">4. Soft Deletes</a>
        <a href="#sec5" class="btn btn-sm btn-outline-info">5. Mass Assignment</a>
        <a href="#sec6" class="btn btn-sm btn-outline-info">6. Query Scopes</a>
    </div>
</div>

<!-- 1. Conditional Clause -->
<div class="card card-custom" id="sec1">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-funnel text-primary me-2"></i>1) Conditional Clause dalam Query Eloquent</span>
        <span class="badge bg-primary">where, orWhere, whereBetween, whereIn, whereNull, when</span>
    </div>
    <div class="card-body">
        <h6>a) where('status', 'active'):</h6>
        <div class="result-box mb-3">
            <b>Hasil:</b> {{ $whereUsers->pluck('name')->implode(', ') }} (Total: {{ $whereUsers->count() }})
        </div>

        <h6>b) orWhere('role', 'admin'):</h6>
        <div class="code-box">
$users = User::where('status', 'active')->orWhere('role', 'admin')->get();
        </div>
        <div class="result-box mb-3">
            <b>Hasil:</b> {{ $orWhereUsers->pluck('name')->implode(', ') }}
        </div>

        <h6>c) whereBetween('age', [18, 30]):</h6>
        <div class="code-box">
$users = User::whereBetween('age', [18, 30])->get();
        </div>
        <div class="result-box mb-3">
            <b>Pengguna Usia 18 - 30 Tahun:</b>
            <ul>
                @foreach($whereBetweenUsers as $wbu)
                <li>{{ $wbu->name }} (Usia: {{ $wbu->age }} tahun)</li>
                @endforeach
            </ul>
        </div>

        <h6>d) whereIn('role', ['admin', 'editor']):</h6>
        <div class="code-box">
$users = User::whereIn('role', ['admin', 'editor'])->get();
        </div>
        <div class="result-box mb-3">
            <b>Hasil:</b> {{ $whereInUsers->pluck('name')->implode(', ') }}
        </div>

        <h6>e) whereNull() dan whereNotNull():</h6>
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <div class="result-box">
                    <strong>whereNull('deleted_at'):</strong>
                    <br>{{ $whereNullUsers->count() }} pengguna aktif (belum terhapus)
                </div>
            </div>
            <div class="col-md-6">
                <div class="result-box">
                    <strong>whereNotNull('email_verified_at'):</strong>
                    <br>{{ $whereNotNullUsers->count() }} pengguna email terverifikasi ({{ $whereNotNullUsers->pluck('name')->implode(', ') }})
                </div>
            </div>
        </div>

        <h6>f) when() untuk Kondisi Dinamis:</h6>
        <div class="code-box">
$role = 'admin';
$users = User::when($role, function ($query, $role) {
    return $query->where('role', $role);
})->get();
        </div>
        <div class="result-box">
            <b>Hasil Filter Dinamis (role='admin'):</b> {{ $whenUsers->pluck('name')->implode(', ') }}
        </div>
    </div>
</div>

<!-- 2. Relasi Antar Model -->
<div class="card card-custom" id="sec2">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bezier2 text-success me-2"></i>2) Relasi Antar Model (Eloquent Relationships)</span>
        <span class="badge bg-success">hasOne, hasMany, belongsToMany</span>
    </div>
    <div class="card-body">
        <h6>a) One to One ($user->profile):</h6>
        <div class="code-box">
// In Model User:
public function profile() {
    return $this->hasOne(Profile::class);
}
// Penggunaan:
$user = User::with('profile')->find(1);
        </div>
        <div class="result-box mb-3">
            @if($userWithProfile && $userWithProfile->profile)
                <b>User:</b> {{ $userWithProfile->name }} &bull; 
                <b>Bio Profil:</b> {{ $userWithProfile->profile->bio }} &bull; 
                <b>Alamat:</b> {{ $userWithProfile->profile->address }}
            @else
                Belum ada data profil terkait.
            @endif
        </div>

        <h6>b) One to Many ($user->posts):</h6>
        <div class="code-box">
// In Model User:
public function posts() {
    return $this->hasMany(Post::class);
}
// Penggunaan:
$user = User::with('posts')->find(1);
        </div>
        <div class="result-box mb-3">
            <b>Postingan yang dimiliki {{ $userWithPosts->name ?? 'User 1' }}:</b>
            @if($userWithPosts && $userWithPosts->posts->count())
                <ul class="mb-0">
                    @foreach($userWithPosts->posts as $post)
                    <li><b>{{ $post->title }}</b> &mdash; <small class="text-muted">{{ $post->content }}</small></li>
                    @endforeach
                </ul>
            @else
                <p class="mb-0 text-muted">Belum ada postingan.</p>
            @endif
        </div>

        <h6>c) Many to Many ($user->roles):</h6>
        <div class="code-box">
// In Model User:
public function roles() {
    return $this->belongsToMany(Role::class);
}
// Penggunaan:
$user = User::with('roles')->find(1);
        </div>
        <div class="result-box">
            <b>Role yang dimiliki {{ $userWithRoles->name ?? 'User 1' }}:</b>
            @if($userWithRoles && $userWithRoles->roles->count())
                @foreach($userWithRoles->roles as $role)
                    <span class="badge bg-dark">{{ $role->name }}</span>
                @endforeach
            @else
                <span class="text-muted">Tidak ada role terhubung.</span>
            @endif
        </div>
    </div>
</div>

<!-- 3. Mutators & Accessors -->
<div class="card card-custom" id="sec3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-arrow-left-right text-warning me-2"></i>3) Mutators & Accessors</span>
        <span class="badge bg-warning text-dark">Mutator & Accessor</span>
    </div>
    <div class="card-body">
        <h6>a) Mutator (Mengubah Data Sebelum Disimpan):</h6>
        <div class="code-box">
class User extends Model {
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = bcrypt($value);
    }
}
        </div>
        <div class="result-box mb-3">
            <small class="text-muted">Setiap kali nilai <code>$user->password = 'teks'</code> diisi, mutator secara otomatis mengenkripsi (hash bcrypt) string sebelum disimpan ke tabel.</small>
        </div>

        <h6>b) Accessor (Mengubah Data Sebelum Ditampilkan):</h6>
        <div class="code-box">
class User extends Model {
    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }
}
// Penggunaan:
$user = User::find(1);
echo $user->full_name;
        </div>
        <div class="result-box">
            <strong>Demonstrasi Accessor:</strong>
            <br>Nama Depan (first_name): <code>{{ $sampleUser->first_name ?? '-' }}</code>
            <br>Nama Belakang (last_name): <code>{{ $sampleUser->last_name ?? '-' }}</code>
            <br>Output <code>$user->full_name</code>: <b class="text-primary fs-5">{{ $accessorFullName }}</b>
        </div>
    </div>
</div>

<!-- 4. Soft Deletes -->
<div class="card card-custom" id="sec4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-recycle text-danger me-2"></i>4) Soft Deletes (Penghapusan Semu)</span>
        <span class="badge bg-danger">SoftDeletes, withTrashed, onlyTrashed, restore</span>
    </div>
    <div class="card-body">
        <h6>a) Penjelasan & Model Setup:</h6>
        <div class="code-box">
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
        </div>
        <h6>b) Eksekusi Soft Delete, withTrashed(), onlyTrashed(), dan restore():</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 bg-light rounded border text-center">
                    <small class="text-muted">Total Termasuk Terhapus</small>
                    <h5 class="fw-bold text-dark mt-1">User::withTrashed()</h5>
                    <span class="badge bg-info text-dark fs-6">{{ $withTrashedUsers->count() }} Data</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded border text-center">
                    <small class="text-muted">Hanya Data yang Terhapus</small>
                    <h5 class="fw-bold text-danger mt-1">User::onlyTrashed()</h5>
                    <span class="badge bg-danger fs-6">{{ $onlyTrashedUsers->count() }} Data</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded border text-center">
                    <small class="text-muted">Fungsi Restore</small>
                    <h5 class="fw-bold text-success mt-1">$user->restore()</h5>
                    <span class="badge bg-success fs-6"><i class="bi bi-check-lg"></i> Berhasil Dipulihkan</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 5. Mass Assignment Protection -->
<div class="card card-custom" id="sec5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-shield-check text-secondary me-2"></i>5) Mass Assignment Protection</span>
        <span class="badge bg-secondary">$fillable & $guarded</span>
    </div>
    <div class="card-body">
        <h6>a) $fillable (White-list kolom yang diizinkan untuk mass-assign):</h6>
        <div class="code-box">
protected $fillable = ['name', 'first_name', 'last_name', 'email', 'password', 'status', 'role', 'age', 'points', 'active'];
        </div>
        <div class="result-box mb-3">
            <b>Atribut Terdaftar di $fillable User:</b>
            <br><code>{{ json_encode($fillableAttributes) }}</code>
        </div>

        <h6>b) $guarded (Black-list kolom yang tidak diizinkan):</h6>
        <div class="code-box">
protected $guarded = ['id'];
        </div>
        <div class="result-box">
            <b>Atribut Terdaftar di $guarded:</b> <code>{{ json_encode($guardedAttributes) }}</code>
        </div>
    </div>
</div>

<!-- 6. Query Scopes -->
<div class="card card-custom" id="sec6">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-code-slash text-dark me-2"></i>6) Query Scopes (Local Scope)</span>
        <span class="badge bg-dark">scopeActive()</span>
    </div>
    <div class="card-body">
        <h6>a) Deklarasi Local Scope di Model User:</h6>
        <div class="code-box">
class User extends Model {
    public function scopeActive($query) {
        return $query->where('active', 1);
    }
}
        </div>
        <h6>b) Penggunaan di Controller/Aplikasi:</h6>
        <div class="code-box">
$activeUsers = User::active()->get();
        </div>
        <div class="result-box">
            <strong>Daftar Pengguna Aktif (User::active()):</strong>
            <br>{{ $activeUsers->pluck('name')->implode(', ') }}
            <br><span class="badge bg-success mt-2">{{ $activeUsers->count() }} Pengguna Aktif</span>
        </div>
    </div>
</div>
@endsection
