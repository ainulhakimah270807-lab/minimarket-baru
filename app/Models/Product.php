<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['category_id', 'name', 'sku', 'price', 'stock'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeLowStock($query, int $threshold = 10)
    {
        return $query->where('stock', '<=', $threshold);
    }

    /**
     * Generate kode barang / SKU otomatis unik
     */
    public static function generateSku(?int $categoryId = null): string
    {
        $prefix = 'BRG';
        if ($categoryId) {
            $cat = Category::find($categoryId);
            if ($cat) {
                $words = preg_split('/\s+/', trim($cat->name));
                if (count($words) >= 2) {
                    $prefix = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 2));
                } else {
                    $prefix = strtoupper(substr($cat->name, 0, 3));
                }
            }
        }

        $latest = self::withTrashed()->where('sku', 'like', "{$prefix}-%")->latest('id')->first();
        $number = 1;
        if ($latest && preg_match('/-(\d+)$/', $latest->sku, $matches)) {
            $number = (int) $matches[1] + 1;
        } else {
            $number = (self::withTrashed()->max('id') ?? 0) + 1;
        }

        $sku = sprintf('%s-%03d', $prefix, $number);

        while (self::withTrashed()->where('sku', $sku)->exists()) {
            $number++;
            $sku = sprintf('%s-%03d', $prefix, $number);
        }

        return $sku;
    }

    /**
     * Keterangan status stok: Habis, Menipis, atau Aman (Tidak Menipis)
     */
    public function getStockStatusTextAttribute(): string
    {
        if ($this->stock == 0) {
            return 'Stok Habis';
        }
        if ($this->stock <= 10) {
            return 'Stok Menipis';
        }
        return 'Stok Aman';
    }
}
