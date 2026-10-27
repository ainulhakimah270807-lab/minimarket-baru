<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Acara29Test extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_can_view_acara29_index_page()
    {
        $response = $this->get(route('acara29.index'));
        $response->assertStatus(200);
        $response->assertSee('File Storage');
        $response->assertSee('Upload File');
        $response->assertSee('Storage::put()');
    }

    public function test_upload_file_validation_fails_for_missing_file()
    {
        $response = $this->post(route('acara29.upload'), []);
        $response->assertSessionHasErrors(['file']);
    }

    public function test_upload_file_validation_fails_for_disallowed_extension()
    {
        $file = UploadedFile::fake()->create('malicious.exe', 100);

        $response = $this->post(route('acara29.upload'), [
            'file' => $file,
        ]);

        $response->assertSessionHasErrors(['file']);
    }

    public function test_can_upload_file_with_original_name()
    {
        $file = UploadedFile::fake()->create('dokumen_rahasia.pdf', 100, 'application/pdf');

        $response = $this->post(route('acara29.upload'), [
            'file' => $file,
            'naming_option' => 'original',
        ]);

        $response->assertRedirect(route('acara29.index'));
        Storage::disk('public')->assertExists('documents/dokumen_rahasia.pdf');
    }

    public function test_can_upload_file_with_hash_name()
    {
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post(route('acara29.upload'), [
            'file' => $file,
            'naming_option' => 'hash',
        ]);

        $response->assertRedirect(route('acara29.index'));
        $files = Storage::disk('public')->files('documents');
        $this->assertNotEmpty($files);
    }

    public function test_can_upload_file_with_timestamp_name()
    {
        $file = UploadedFile::fake()->create('laporan.pdf', 100, 'application/pdf');

        $response = $this->post(route('acara29.upload'), [
            'file' => $file,
            'naming_option' => 'timestamp',
        ]);

        $response->assertRedirect(route('acara29.index'));
        $files = Storage::disk('public')->files('documents');
        $this->assertNotEmpty($files);
        $this->assertStringContainsString('laporan.pdf', $files[0]);
    }

    public function test_can_upload_file_with_uuid_name()
    {
        $file = UploadedFile::fake()->create('data.csv', 50, 'text/csv');

        $response = $this->post(route('acara29.upload'), [
            'file' => $file,
            'naming_option' => 'uuid',
        ]);

        $response->assertRedirect(route('acara29.index'));
        $files = Storage::disk('public')->files('documents');
        $this->assertNotEmpty($files);
        $this->assertStringEndsWith('.csv', $files[0]);
    }

    public function test_can_upload_file_with_custom_name()
    {
        $file = UploadedFile::fake()->create('surat.pdf', 80, 'application/pdf');

        $response = $this->post(route('acara29.upload'), [
            'file' => $file,
            'naming_option' => 'custom',
            'custom_name' => 'surat-resmi-toko',
        ]);

        $response->assertRedirect(route('acara29.index'));
        Storage::disk('public')->assertExists('documents/surat-resmi-toko.pdf');
    }

    public function test_can_save_text_file_using_storage_put()
    {
        $response = $this->post(route('acara29.put'), [
            'put_filename' => 'catatan-kasir',
            'put_content' => 'Ini adalah catatan kasir minimarket untuk testing.',
        ]);

        $response->assertRedirect(route('acara29.index'));
        Storage::disk('public')->assertExists('documents/catatan-kasir.txt');
        $this->assertEquals(
            'Ini adalah catatan kasir minimarket untuk testing.',
            Storage::disk('public')->get('documents/catatan-kasir.txt')
        );
    }

    public function test_can_download_stored_file()
    {
        Storage::disk('public')->put('documents/test_download.txt', 'Isi download');

        $response = $this->get(route('acara29.download', 'test_download.txt'));
        $response->assertStatus(200);
    }

    public function test_can_view_file_details_and_content_json()
    {
        Storage::disk('public')->put('documents/info.txt', 'Informasi Acara 29');

        $response = $this->get(route('acara29.content', 'info.txt'));
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'name' => 'info.txt',
            'is_text' => true,
            'content' => 'Informasi Acara 29',
            'exists' => true,
        ]);
    }

    public function test_can_copy_file()
    {
        Storage::disk('public')->put('documents/asli.txt', 'File asli');

        $response = $this->post(route('acara29.copy'), [
            'source_file' => 'asli.txt',
        ]);

        $response->assertRedirect(route('acara29.index'));
        $files = Storage::disk('public')->files('documents');
        $this->assertCount(2, $files);
    }

    public function test_can_rename_file()
    {
        Storage::disk('public')->put('documents/nama-lama.txt', 'Konten file');

        $response = $this->post(route('acara29.rename'), [
            'old_name' => 'nama-lama.txt',
            'new_name' => 'nama-baru',
        ]);

        $response->assertRedirect(route('acara29.index'));
        Storage::disk('public')->assertMissing('documents/nama-lama.txt');
        Storage::disk('public')->assertExists('documents/nama-baru.txt');
    }

    public function test_can_delete_file()
    {
        Storage::disk('public')->put('documents/hapus_saya.txt', 'Akan dihapus');

        $response = $this->delete(route('acara29.delete', 'hapus_saya.txt'));

        $response->assertRedirect(route('acara29.index'));
        Storage::disk('public')->assertMissing('documents/hapus_saya.txt');
    }
}
