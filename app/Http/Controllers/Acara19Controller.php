<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Post;
use App\Models\Role;
use Illuminate\Http\Request;

class Acara19Controller extends Controller
{
    public function index()
    {
        // 1. Menggunakan Conditional Clause dalam Query
        // a) where()
        $whereUsers = User::where('status', 'active')->get();

        // b) orWhere()
        $orWhereUsers = User::where('status', 'active')->orWhere('role', 'admin')->get();

        // c) whereBetween()
        $whereBetweenUsers = User::whereBetween('age', [18, 30])->get();

        // d) whereIn()
        $whereInUsers = User::whereIn('role', ['admin', 'editor'])->get();

        // e) whereNull() dan whereNotNull()
        $whereNullUsers = User::whereNull('deleted_at')->get();
        $whereNotNullUsers = User::whereNotNull('email_verified_at')->get();

        // f) when() untuk Kondisi Dinamis
        $roleParam = 'admin';
        $whenUsers = User::when($roleParam, function ($query, $role) {
            return $query->where('role', $role);
        })->get();

        // 2. Relasi Antar Model (Eloquent Relationships)
        // a) One to One (User -> Profile)
        $userWithProfile = User::with('profile')->find(1);

        // b) One to Many (User -> Posts)
        $userWithPosts = User::with('posts')->find(1);

        // c) Many to Many (User -> Roles)
        $userWithRoles = User::with('roles')->find(1);

        // 3. Mutators & Accessors
        // Test user with first_name and last_name for Accessor
        $sampleUser = User::find(1);
        $accessorFullName = $sampleUser ? $sampleUser->full_name : 'Belum diatur';

        // 4. Soft Deletes
        // Buat satu record sementara untuk demo soft delete dan restore
        $softDeleteUser = User::firstOrCreate(
            ['email' => 'softdelete_test@example.com'],
            [
                'name' => 'Soft Delete Tester',
                'first_name' => 'Soft',
                'last_name' => 'Tester',
                'password' => 'password123',
                'status' => 'active',
                'role' => 'user',
                'age' => 20,
                'active' => 1
            ]
        );

        // Lakukan soft delete
        $softDeleteUser->delete();

        // Mengambil semua termasuk yang dihapus
        $withTrashedUsers = User::withTrashed()->get();

        // Mengambil data yang hanya sudah dihapus
        $onlyTrashedUsers = User::onlyTrashed()->get();

        // Restore data yang dihapus
        $restoredUser = User::onlyTrashed()->where('email', 'softdelete_test@example.com')->first();
        if ($restoredUser) {
            $restoredUser->restore();
        }

        // 5. Mass Assignment Protection
        // fillable & guarded attributes di User model
        $userModelInstance = new User();
        $fillableAttributes = $userModelInstance->getFillable();
        $guardedAttributes = $userModelInstance->getGuarded();

        // 6. Query Scopes (Local Scope scopeActive)
        $activeUsers = User::active()->get();

        return view('acara.acara19', compact(
            'whereUsers',
            'orWhereUsers',
            'whereBetweenUsers',
            'whereInUsers',
            'whereNullUsers',
            'whereNotNullUsers',
            'whenUsers',
            'userWithProfile',
            'userWithPosts',
            'userWithRoles',
            'sampleUser',
            'accessorFullName',
            'withTrashedUsers',
            'onlyTrashedUsers',
            'fillableAttributes',
            'guardedAttributes',
            'activeUsers'
        ));
    }
}
