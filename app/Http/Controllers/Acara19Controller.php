<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class Acara19Controller extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        $lowStockProducts = Product::with('category')->lowStock()->orderBy('stock')->paginate(10);
        $archivedProducts = Product::onlyTrashed()->with('category')->latest('deleted_at')->paginate(10);

        return view('acara.acara19-operasional', compact('categories', 'lowStockProducts', 'archivedProducts'));
    }

    public function restore(int $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('acara19.index')->with('success', 'Produk berhasil dipulihkan ke daftar aktif.');
    }
}
