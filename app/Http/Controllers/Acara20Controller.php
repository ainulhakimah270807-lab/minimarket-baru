<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Acara20Controller extends Controller
{
    /**
     * Tampilkan halaman Form dan Validation
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $generatedSku = Product::generateSku($categories->first()?->id);

        return view('acara.acara20-operasional', compact('categories', 'generatedSku'));
    }

    public function generateSku(Request $request)
    {
        $categoryId = $request->query('category_id');
        $sku = Product::generateSku($categoryId ? (int) $categoryId : null);

        return response()->json(['sku' => $sku]);
    }

    public function store(ProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('acara18.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'category_name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);
        $baseSlug = Str::slug($validated['category_name']);
        $slug = $baseSlug;
        $suffix = 2;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $suffix++;
        }

        Category::create(['name' => $validated['category_name'], 'slug' => $slug]);

        return redirect()->route('acara20.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }
}
