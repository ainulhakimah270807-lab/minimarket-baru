<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;

class Acara18Controller extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('name')->paginate(10);
        $categories = Category::orderBy('name')->get();

        return view('acara.acara18-operasional', compact('products', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('acara18.index')->with('success', 'Data produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('acara19.index')->with('success', 'Produk dipindahkan ke arsip dan masih bisa dipulihkan.');
    }
}
