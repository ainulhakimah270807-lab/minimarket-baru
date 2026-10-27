<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class Acara24Controller extends Controller
{
    /**
     * Demonstrasi Acara 24: Halaman Utama Otorisasi
     */
    public function index()
    {
        $posts = Post::with('user')->latest()->get();
        return view('acara24.index', compact('posts'));
    }

    /**
     * Demonstrasi 1: Menggunakan Gate dalam Controller
     */
    public function updateWithGate(Request $request, Post $post)
    {
        if (Gate::denies('edit-post', $post)) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit postingan ini.');
        }

        $request->validate(['title' => 'required|string|max:255']);
        $post->update(['title' => $request->title]);

        return back()->with('message', 'Postingan berhasil diperbarui menggunakan Gate!');
    }

    /**
     * Demonstrasi 2: Menggunakan Policy dalam Controller ($this->authorize)
     */
    public function updateWithPolicy(Request $request, Post $post)
    {
        // Memanggil Policy (PostPolicy->update)
        $this->authorize('update', $post);

        $request->validate(['title' => 'required|string|max:255']);
        $post->update(['title' => $request->title]);

        return back()->with('message', 'Postingan berhasil diperbarui menggunakan Policy!');
    }

    /**
     * Demonstrasi 3: Rute yang dilindungi Middleware 'can:update,post'
     */
    public function editWithMiddleware(Post $post)
    {
        return view('acara24.edit', compact('post'));
    }
}
