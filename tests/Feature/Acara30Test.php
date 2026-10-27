<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Acara30Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_can_view_acara30_index_page()
    {
        $category = Category::create(['name' => 'Sembako', 'slug' => 'sembako']);
        Product::create([
            'name' => 'Beras Pandan Wangi 5kg',
            'sku' => 'BRG-001',
            'price' => 75000,
            'stock' => 20,
            'category_id' => $category->id,
        ]);
        Employee::create(['name' => 'Siti Nurhaliza', 'salary' => 4500000]);

        $response = $this->get(route('acara30.index'));
        $response->assertStatus(200);
        $response->assertSee('Generate Laporan PDF (DomPDF)');
        $response->assertSee('Laporan Produk');
        $response->assertSee('Laporan Stok');
        $response->assertSee('Laporan Pegawai');
    }

    public function test_can_generate_laporan_pdf_download_from_laporan_controller()
    {
        $response = $this->get(route('laporan.pdf'));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment;', $response->headers->get('content-disposition'));
    }

    public function test_can_stream_laporan_pdf_from_laporan_controller()
    {
        $response = $this->get(route('laporan.pdf.stream'));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('inline;', $response->headers->get('content-disposition'));
    }

    public function test_can_generate_product_pdf_download()
    {
        $category = Category::create(['name' => 'Minuman', 'slug' => 'minuman']);
        Product::create([
            'name' => 'Susu Kotak 1L',
            'sku' => 'BRG-002',
            'price' => 18000,
            'stock' => 15,
            'category_id' => $category->id,
        ]);

        $response = $this->get(route('acara30.pdf.produk'));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment;', $response->headers->get('content-disposition'));
    }

    public function test_can_stream_product_pdf()
    {
        $category = Category::create(['name' => 'Snack', 'slug' => 'snack']);
        Product::create([
            'name' => 'Keripik Kentang',
            'sku' => 'BRG-003',
            'price' => 9500,
            'stock' => 50,
            'category_id' => $category->id,
        ]);

        $response = $this->get(route('acara30.pdf.produk.stream'));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('inline;', $response->headers->get('content-disposition'));
    }

    public function test_can_generate_stock_pdf_download()
    {
        $category = Category::create(['name' => 'Bumbu', 'slug' => 'bumbu']);
        Product::create([
            'name' => 'Garam Dapur 250g',
            'sku' => 'BRG-004',
            'price' => 3000,
            'stock' => 5, // low stock
            'category_id' => $category->id,
        ]);

        $response = $this->get(route('acara30.pdf.stok'));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment;', $response->headers->get('content-disposition'));
    }

    public function test_can_stream_stock_pdf()
    {
        $response = $this->get(route('acara30.pdf.stok.stream'));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('inline;', $response->headers->get('content-disposition'));
    }

    public function test_can_generate_employee_pdf_download()
    {
        Employee::create(['name' => 'Ahmad Kasir', 'salary' => 3200000]);

        $response = $this->get(route('acara30.pdf.pegawai'));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment;', $response->headers->get('content-disposition'));
    }

    public function test_can_stream_employee_pdf()
    {
        $response = $this->get(route('acara30.pdf.pegawai.stream'));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('inline;', $response->headers->get('content-disposition'));
    }

    public function test_can_upload_image_for_manipulation()
    {
        $file = UploadedFile::fake()->image('produk_test.jpg', 600, 400);

        $response = $this->post(route('acara30.upload'), [
            'photo' => $file,
        ]);

        $response->assertRedirect(route('acara30.index'));
        $files = Storage::disk('public')->files('uploads');
        $this->assertNotEmpty($files);
    }
}
