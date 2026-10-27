<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class Acara31Test extends TestCase
{
    use RefreshDatabase;

    protected array $createdFiles = [];

    protected function setUp(): void
    {
        parent::setUp();

        $uploadPath = public_path('uploads');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Ensure watermark exists for testing
        $watermarkPath = public_path('images/watermark.png');
        if (!File::exists($watermarkPath)) {
            $img = imagecreatetruecolor(120, 40);
            imagesavealpha($img, true);
            $trans = imagecolorallocatealpha($img, 0, 0, 0, 127);
            imagefill($img, 0, 0, $trans);
            $white = imagecolorallocate($img, 255, 255, 255);
            imagestring($img, 3, 10, 12, 'MINIMARKET', $white);
            imagepng($img, $watermarkPath);
            imagedestroy($img);
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->createdFiles as $file) {
            $path = public_path('uploads/' . $file);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        // Also cleanup any test_* files in public/uploads
        $allFiles = File::files(public_path('uploads'));
        foreach ($allFiles as $file) {
            if (str_starts_with($file->getFilename(), 'test_') || str_contains($file->getFilename(), 'test_product')) {
                File::delete($file->getPathname());
            }
        }

        parent::tearDown();
    }

    public function test_can_view_acara31_index_page()
    {
        $response = $this->get(route('acara31.index'));
        $response->assertStatus(200);
        $response->assertSee('Laravel Image Manipulation');
        $response->assertSee('Upload Gambar');
        $response->assertSee('Galeri Hasil Manipulasi Gambar');
        $response->assertSee('Intervention Image v2');
    }

    public function test_can_upload_image_without_autoresize()
    {
        $file = UploadedFile::fake()->image('test_product.jpg', 640, 480);

        $response = $this->post(route('acara31.upload'), [
            'photo' => $file,
            'auto_resize' => '0',
            'quality' => 90,
        ]);

        $response->assertRedirect(route('acara31.index'));
        $response->assertSessionHas('success');

        // Check file exists in uploads
        $files = File::files(public_path('uploads'));
        $found = false;
        foreach ($files as $f) {
            if (str_contains($f->getFilename(), 'test_product.jpg')) {
                $found = true;
                $this->createdFiles[] = $f->getFilename();
                break;
            }
        }
        $this->assertTrue($found, 'File was uploaded to public/uploads');
    }

    public function test_can_upload_image_with_autoresize_to_300x300()
    {
        $file = UploadedFile::fake()->image('test_product_auto.jpg', 800, 600);

        $response = $this->post(route('acara31.upload'), [
            'photo' => $file,
            'auto_resize' => '1',
            'quality' => 85,
        ]);

        $response->assertRedirect(route('acara31.index'));
        $response->assertSessionHas('success');

        $files = File::files(public_path('uploads'));
        $uploadedFile = null;
        foreach ($files as $f) {
            if (str_contains($f->getFilename(), 'test_product_auto.jpg')) {
                $uploadedFile = $f;
                $this->createdFiles[] = $f->getFilename();
                break;
            }
        }

        $this->assertNotNull($uploadedFile);
        $size = getimagesize($uploadedFile->getPathname());
        $this->assertLessThanOrEqual(300, $size[0]);
        $this->assertLessThanOrEqual(300, $size[1]);
    }

    public function test_upload_fails_if_no_file()
    {
        $response = $this->post(route('acara31.upload'), []);
        $response->assertSessionHasErrors(['photo']);
    }

    public function test_upload_fails_if_file_is_not_an_image()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->post(route('acara31.upload'), [
            'photo' => $file,
        ]);

        $response->assertSessionHasErrors(['photo']);
    }

    public function test_can_resize_image()
    {
        $filename = 'test_resize_base.jpg';
        $path = public_path('uploads/' . $filename);
        $img = imagecreatetruecolor(400, 400);
        imagejpeg($img, $path);
        imagedestroy($img);
        $this->createdFiles[] = $filename;

        $response = $this->post(route('acara31.resize'), [
            'image_name' => $filename,
            'width' => 150,
            'height' => 150,
            'quality' => 80,
        ]);

        $response->assertRedirect(route('acara31.index'));
        $response->assertSessionHas('success');

        $resizedFile = 'test_resize_base_resized_150x150.jpg';
        $this->createdFiles[] = $resizedFile;
        $this->assertTrue(File::exists(public_path('uploads/' . $resizedFile)));

        $dim = getimagesize(public_path('uploads/' . $resizedFile));
        $this->assertEquals(150, $dim[0]);
        $this->assertEquals(150, $dim[1]);
    }

    public function test_can_crop_image()
    {
        $filename = 'test_crop_base.jpg';
        $path = public_path('uploads/' . $filename);
        $img = imagecreatetruecolor(400, 400);
        imagejpeg($img, $path);
        imagedestroy($img);
        $this->createdFiles[] = $filename;

        $response = $this->post(route('acara31.crop'), [
            'image_name' => $filename,
            'crop_w' => 100,
            'crop_h' => 100,
            'crop_x' => 10,
            'crop_y' => 10,
        ]);

        $response->assertRedirect(route('acara31.index'));
        $response->assertSessionHas('success');

        $croppedFile = 'test_crop_base_cropped_100x100.jpg';
        $this->createdFiles[] = $croppedFile;
        $this->assertTrue(File::exists(public_path('uploads/' . $croppedFile)));

        $dim = getimagesize(public_path('uploads/' . $croppedFile));
        $this->assertEquals(100, $dim[0]);
        $this->assertEquals(100, $dim[1]);
    }

    public function test_can_watermark_image()
    {
        $filename = 'test_watermark_base.png';
        $path = public_path('uploads/' . $filename);
        $img = imagecreatetruecolor(300, 300);
        imagepng($img, $path);
        imagedestroy($img);
        $this->createdFiles[] = $filename;

        $response = $this->post(route('acara31.watermark'), [
            'image_name' => $filename,
            'position' => 'bottom-right',
        ]);

        $response->assertRedirect(route('acara31.index'));
        $response->assertSessionHas('success');

        $watermarkedFile = 'test_watermark_base_watermark.png';
        $this->createdFiles[] = $watermarkedFile;
        $this->assertTrue(File::exists(public_path('uploads/' . $watermarkedFile)));
    }

    public function test_can_rotate_image()
    {
        $filename = 'test_rotate_base.jpg';
        $path = public_path('uploads/' . $filename);
        $img = imagecreatetruecolor(200, 100);
        imagejpeg($img, $path);
        imagedestroy($img);
        $this->createdFiles[] = $filename;

        $response = $this->post(route('acara31.rotate'), [
            'image_name' => $filename,
            'degrees' => 90,
        ]);

        $response->assertRedirect(route('acara31.index'));
        $response->assertSessionHas('success');

        $rotatedFile = 'test_rotate_base_rotated_90deg.jpg';
        $this->createdFiles[] = $rotatedFile;
        $this->assertTrue(File::exists(public_path('uploads/' . $rotatedFile)));
    }

    public function test_can_encode_image_to_webp()
    {
        $filename = 'test_encode_base.jpg';
        $path = public_path('uploads/' . $filename);
        $img = imagecreatetruecolor(200, 200);
        imagejpeg($img, $path);
        imagedestroy($img);
        $this->createdFiles[] = $filename;

        $response = $this->post(route('acara31.encode'), [
            'image_name' => $filename,
            'format' => 'webp',
        ]);

        $response->assertRedirect(route('acara31.index'));
        $response->assertSessionHas('success');

        $convertedFile = 'test_encode_base_converted.webp';
        $this->createdFiles[] = $convertedFile;
        $this->assertTrue(File::exists(public_path('uploads/' . $convertedFile)));
    }

    public function test_can_stream_image_response_directly()
    {
        $filename = 'test_response.jpg';
        $path = public_path('uploads/' . $filename);
        $img = imagecreatetruecolor(100, 100);
        imagejpeg($img, $path);
        imagedestroy($img);
        $this->createdFiles[] = $filename;

        $response = $this->get(route('acara31.response', ['filename' => $filename]));
        $response->assertStatus(200);
        $this->assertStringContainsString('image/jpeg', $response->headers->get('content-type'));
    }

    public function test_can_delete_image()
    {
        $filename = 'test_to_delete.jpg';
        $path = public_path('uploads/' . $filename);
        $img = imagecreatetruecolor(100, 100);
        imagejpeg($img, $path);
        imagedestroy($img);

        $this->assertTrue(File::exists($path));

        $response = $this->delete(route('acara31.delete', ['filename' => $filename]));
        $response->assertRedirect(route('acara31.index'));
        $response->assertSessionHas('success');

        $this->assertFalse(File::exists($path));
    }
}
