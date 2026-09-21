<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        // Ambil semua data dari tabel posts
        $posts = Post::all();

        // Kirim data ke View
        return view('posts.index', compact('posts'));
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect('/posts')->with('success', 'Data berhasil dihapus dengan method DELETE!');
    }
}
