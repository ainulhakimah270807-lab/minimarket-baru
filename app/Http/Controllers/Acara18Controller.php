<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Acara18Controller extends Controller
{
    public function index()
    {
        // 1. Menambahkan Data ke Database (Create)
        // a) Menggunakan create()
        $createEmail = 'eloquent_create_' . time() . '@example.com';
        $userCreated = User::create([
            'name' => 'John Doe Eloquent',
            'email' => $createEmail,
            'password' => 'password123',
            'status' => 'active',
            'role' => 'user',
            'age' => 26,
            'points' => 20,
            'active' => 1
        ]);

        // b) Menggunakan save()
        $saveEmail = 'eloquent_save_' . time() . '@example.com';
        $userSaved = new User;
        $userSaved->name = 'Jane Doe Eloquent';
        $userSaved->email = $saveEmail;
        $userSaved->password = 'password123';
        $userSaved->status = 'active';
        $userSaved->role = 'user';
        $userSaved->age = 23;
        $userSaved->points = 25;
        $userSaved->active = 1;
        $userSaved->save();

        // 2. Mengambil Data dari Database (Retrieve)
        // a) Mengambil Semua Data
        $allUsers = User::all();

        // b) Mengambil Data Berdasarkan ID
        $userFind = User::find(1);

        // c) Menggunakan Query Builder di Eloquent
        $userWhere = User::where('email', 'johndoe@example.com')->get();

        // d) Menggunakan firstOrFail()
        $userFirstOrFail = User::where('email', 'johndoe@example.com')->firstOrFail();

        // 3. Memperbarui Data (Update)
        // a) Menggunakan update()
        User::where('email', $createEmail)->update(['name' => 'John Updated by update()']);
        $userAfterUpdate = User::where('email', $createEmail)->first();

        // b) Menggunakan save()
        $userToSave = User::find($userSaved->id);
        if ($userToSave) {
            $userToSave->name = 'Jane Updated by save()';
            $userToSave->save();
        }

        // 4. Menghapus Data (Delete)
        // a) Menggunakan delete()
        $userToDelete = User::find($userCreated->id);
        $deletedViaDelete = false;
        if ($userToDelete) {
            $userToDelete->delete();
            $deletedViaDelete = true;
        }

        // b) Menggunakan destroy()
        $destroyedCount = User::destroy($userSaved->id);

        return view('acara.acara18', compact(
            'userCreated',
            'userSaved',
            'allUsers',
            'userFind',
            'userWhere',
            'userFirstOrFail',
            'userAfterUpdate',
            'userToSave',
            'deletedViaDelete',
            'destroyedCount'
        ));
    }
}
