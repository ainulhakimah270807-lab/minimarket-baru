<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (empty($this->sku)) {
            $this->merge([
                'sku' => \App\Models\Product::generateSku($this->category_id ? (int) $this->category_id : null),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku')->ignore($this->route('product')),
            ],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak tersedia.',
            'name.required' => 'Nama produk wajib diisi.',
            'sku.required' => 'SKU produk wajib diisi.',
            'sku.unique' => 'SKU tersebut sudah digunakan produk lain.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.integer' => 'Harga harus berupa angka bulat.',
            'price.min' => 'Harga tidak boleh kurang dari nol.',
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'stock.min' => 'Stok tidak boleh kurang dari nol.',
        ];
    }
}
