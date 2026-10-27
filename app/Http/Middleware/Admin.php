<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Tambahkan namespace Auth ini

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Memeriksa apakah pengguna sudah login
        if (Auth::check()) {
            // Memeriksa apakah peran (role) pengguna adalah 'admin'
            if (Auth::user()->role == 'admin') {
                return $next($request); // Lanjutkan request jika benar
            } else {
                abort(403, 'Akses ditolak. Anda bukan admin.'); // Tolak akses jika bukan admin
            }
        } else {
            return redirect()->route('login'); // Lempar ke halaman login jika belum masuk
        }
    }
}