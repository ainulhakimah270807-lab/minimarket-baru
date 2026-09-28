<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_pages_show_product_workflows(): void
    {
        $category = $this->createCategory();
        Product::create([
            'category_id' => $category->id,
            'name' => 'Beras Premium',
            'sku' => 'BRG-001',
            'price' => 68000,
            'stock' => 8,
        ]);

        $this->get(route('acara17.index'))->assertOk()->assertSee('Beras Premium');
        $this->get(route('acara18.index'))->assertOk()->assertSee('Beras Premium');
        $this->get(route('acara19.index'))->assertOk()->assertSee('Beras Premium');
        $this->get(route('acara20.index'))->assertOk()->assertSee('Tambah Produk');
    }

    public function test_product_can_be_created_and_invalid_data_is_rejected(): void
    {
        $category = $this->createCategory();

        $this->post(route('acara20.products.store'), [
            'category_id' => $category->id,
            'name' => 'Minyak Goreng',
            'sku' => 'MNY-001',
            'price' => 34000,
            'stock' => 12,
        ])->assertRedirect(route('acara18.index'));

        $this->assertDatabaseHas('products', ['sku' => 'MNY-001', 'stock' => 12]);

        $this->from(route('acara20.index'))
            ->post(route('acara20.products.store'), [
                'category_id' => $category->id,
                'name' => 'Produk tidak valid',
                'sku' => 'MNY-001',
                'price' => -1,
                'stock' => -3,
            ])
            ->assertRedirect(route('acara20.index'))
            ->assertSessionHasErrors(['sku', 'price', 'stock']);
    }

    public function test_product_can_be_updated_archived_and_restored(): void
    {
        $category = $this->createCategory();
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Gula Pasir',
            'sku' => 'GUL-001',
            'price' => 17500,
            'stock' => 30,
        ]);

        $this->put(route('acara18.products.update', $product), [
            'category_id' => $category->id,
            'name' => 'Gula Pasir 1kg',
            'sku' => 'GUL-001',
            'price' => 18000,
            'stock' => 24,
        ])->assertRedirect(route('acara18.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Gula Pasir 1kg', 'price' => 18000]);

        $this->delete(route('acara18.products.destroy', $product))->assertRedirect(route('acara19.index'));
        $this->assertSoftDeleted('products', ['id' => $product->id]);

        $this->post(route('acara19.products.restore', $product->id))->assertRedirect(route('acara19.index'));
        $this->assertNotSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_category_can_be_created_with_a_unique_slug(): void
    {
        $this->createCategory('Makanan Ringan', 'makanan-ringan');

        $this->post(route('acara20.categories.store'), ['category_name' => 'Makanan Ringan!'])
            ->assertRedirect(route('acara20.index'));

        $this->assertDatabaseHas('categories', ['name' => 'Makanan Ringan!', 'slug' => 'makanan-ringan-2']);
    }

    private function createCategory(string $name = 'Makanan', string $slug = 'makanan'): Category
    {
        return Category::create(['name' => $name, 'slug' => $slug]);
    }
}
