<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'status',
        'role',
        'age',
        'points',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Catatan: Jika ada setPasswordAttribute mutator, Laravel 10 tidak perlu double hash cast
    ];

    // ==========================================
    // Acara 19: Relasi Antar Model
    // ==========================================

    // a) One to One: User -> Profile
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // b) One to Many: User -> Post
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // c) Many to Many: User -> Role
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // ==========================================
    // Acara 19: Mutator & Accessor
    // ==========================================

    // a) Mutator: Mengubah Password sebelum disimpan (bcrypt)
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // b) Accessor: Menggabungkan first_name dan last_name
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // ==========================================
    // Acara 19: Query Scope
    // ==========================================

    // Local Scope: scopeActive
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
