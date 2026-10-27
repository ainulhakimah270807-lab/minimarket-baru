<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Acara23Controller extends Controller
{
    /**
     * Acara 23: Retrieving The Authenticated User
     */
    public function showProfile()
    {
        // Mengambil Data Pengguna yang sedang login
        $user = Auth::user();
        $userId = Auth::id();

        return view('acara23.profile', compact('user', 'userId'));
    }

    /**
     * Acara 23: Recreating Logout Feature
     */
    public function logout(Request $request)
    {
        // Logout user
        Auth::logout();

        // Hapus sesi & regenerasi CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman utama
        return redirect('/')->with('message', 'Anda telah berhasil logout!');
    }
}
