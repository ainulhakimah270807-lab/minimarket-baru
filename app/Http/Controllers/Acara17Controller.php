<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class Acara17Controller extends Controller
{
    public function index()
    {
        $search = request('search');
        $categoryId = request('category_id');
        $stockFilter = request('stock');

        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereNull('products.deleted_at')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('products.name', 'like', "%{$search}%")
                        ->orWhere('products.sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, fn ($query) => $query->where('products.category_id', $categoryId))
            ->when($stockFilter === 'low', fn ($query) => $query->where('products.stock', '<=', 10)->where('products.stock', '>', 0))
            ->when($stockFilter === 'safe', fn ($query) => $query->where('products.stock', '>', 10))
            ->when($stockFilter === 'empty', fn ($query) => $query->where('products.stock', 0))
            ->select('products.id', 'products.name', 'products.sku', 'products.price', 'products.stock', 'categories.name as category_name')
            ->orderBy('products.name')
            ->paginate(10)
            ->appends(request()->query());

        $summary = DB::table('products')
            ->whereNull('deleted_at')
            ->selectRaw('COUNT(*) as product_count, COALESCE(SUM(stock), 0) as total_stock, COALESCE(SUM(price * stock), 0) as stock_value')
            ->first();

        $lowStockCount = DB::table('products')->whereNull('deleted_at')->where('stock', '<=', 10)->count();
        $categories = DB::table('categories')->orderBy('name')->get(['id', 'name']);

        return view('acara.acara17-operasional', compact(
            'products', 'summary', 'lowStockCount', 'categories', 'search', 'categoryId', 'stockFilter'
        ));
    }
}
