<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class Acara31Controller extends Controller
{
    /**
     * Tampilkan halaman utama Acara 31: Manipulasi Gambar dengan Intervention Image
     */
    public function index()
    {
        $images = $this->getUploadedImages();
        $watermarkExists = File::exists(public_path('images/watermark.png'));

        return view('acara.acara31-operasional', compact('images', 'watermarkExists'));
    }

    /**
     * Prosedur 4: Upload gambar dan resize otomatis
     */
    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'photo.required' => 'File gambar wajib dipilih.',
            'photo.image'    => 'File harus berformat gambar.',
            'photo.mimes'    => 'Format gambar yang diizinkan: jpeg, png, jpg, gif, webp.',
            'photo.max'      => 'Ukuran gambar maksimal 2MB (2048 KB).',
        ]);

        $file = $request->file('photo');
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $uploadPath = public_path('uploads');

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $destPath = $uploadPath . '/' . $filename;

        // Pilihan: simpan asli atau resize langsung (sesuai modul BKPM 300x300)
        $autoResize = $request->boolean('auto_resize', false);
        $quality = (int) $request->input('quality', 90);

        if ($autoResize) {
            Image::make($file)
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($destPath, $quality);
            $msg = "Gambar '{$filename}' berhasil diunggah dan di-resize ke 300x300 (Kualitas: {$quality}%).";
        } else {
            Image::make($file)->save($destPath, $quality);
            $msg = "Gambar '{$filename}' berhasil diunggah (Kualitas: {$quality}%).";
        }

        return redirect()->route('acara31.index')->with('success', $msg);
    }

    /**
     * Prosedur 3a: Resize Gambar
     */
    public function resize(Request $request)
    {
        $request->validate([
            'image_name' => 'required|string',
            'width'      => 'required|integer|min:20|max:3000',
            'height'     => 'required|integer|min:20|max:3000',
            'quality'    => 'nullable|integer|min:10|max:100',
        ]);

        $sourcePath = public_path('uploads/' . $request->image_name);

        if (!File::exists($sourcePath)) {
            return redirect()->route('acara31.index')->with('error', 'Gambar sumber tidak ditemukan.');
        }

        $width = (int) $request->width;
        $height = (int) $request->height;
        $quality = (int) ($request->quality ?? 85);

        $ext = pathinfo($request->image_name, PATHINFO_EXTENSION);
        $baseName = pathinfo($request->image_name, PATHINFO_FILENAME);
        $newFilename = $baseName . '_resized_' . $width . 'x' . $height . '.' . $ext;
        $destPath = public_path('uploads/' . $newFilename);

        Image::make($sourcePath)
            ->resize($width, $height)
            ->save($destPath, $quality);

        return redirect()->route('acara31.index')->with('success', "Gambar berhasil di-resize menjadi {$width}x{$height} piksel → '{$newFilename}'.");
    }

    /**
     * Prosedur 3b: Crop Gambar
     */
    public function crop(Request $request)
    {
        $request->validate([
            'image_name' => 'required|string',
            'crop_w'     => 'required|integer|min:20|max:2000',
            'crop_h'     => 'required|integer|min:20|max:2000',
            'crop_x'     => 'nullable|integer|min:0',
            'crop_y'     => 'nullable|integer|min:0',
        ]);

        $sourcePath = public_path('uploads/' . $request->image_name);

        if (!File::exists($sourcePath)) {
            return redirect()->route('acara31.index')->with('error', 'Gambar sumber tidak ditemukan.');
        }

        $w = (int) $request->crop_w;
        $h = (int) $request->crop_h;
        $x = (int) ($request->crop_x ?? 0);
        $y = (int) ($request->crop_y ?? 0);

        $ext = pathinfo($request->image_name, PATHINFO_EXTENSION);
        $baseName = pathinfo($request->image_name, PATHINFO_FILENAME);
        $newFilename = $baseName . '_cropped_' . $w . 'x' . $h . '.' . $ext;
        $destPath = public_path('uploads/' . $newFilename);

        Image::make($sourcePath)
            ->crop($w, $h, $x, $y)
            ->save($destPath);

        return redirect()->route('acara31.index')->with('success', "Gambar berhasil di-crop ukuran {$w}x{$h} (offset: {$x},{$y}) → '{$newFilename}'.");
    }

    /**
     * Prosedur 3c: Menambahkan Watermark
     */
    public function watermark(Request $request)
    {
        $request->validate([
            'image_name' => 'required|string',
            'position'   => 'nullable|string|in:top-left,top-right,bottom-left,bottom-right,center',
        ]);

        $sourcePath = public_path('uploads/' . $request->image_name);
        $watermarkPath = public_path('images/watermark.png');

        if (!File::exists($sourcePath)) {
            return redirect()->route('acara31.index')->with('error', 'Gambar sumber tidak ditemukan.');
        }

        if (!File::exists($watermarkPath)) {
            return redirect()->route('acara31.index')->with('error', 'File watermark tidak ditemukan.');
        }

        $position = $request->position ?? 'bottom-right';
        $ext = pathinfo($request->image_name, PATHINFO_EXTENSION);
        $baseName = pathinfo($request->image_name, PATHINFO_FILENAME);
        $newFilename = $baseName . '_watermark.' . $ext;
        $destPath = public_path('uploads/' . $newFilename);

        Image::make($sourcePath)
            ->insert($watermarkPath, $position, 15, 15)
            ->save($destPath);

        return redirect()->route('acara31.index')->with('success', "Watermark berhasil ditambahkan pada posisi [{$position}] → '{$newFilename}'.");
    }

    /**
     * Prosedur 3d: Rotate Gambar
     */
    public function rotate(Request $request)
    {
        $request->validate([
            'image_name' => 'required|string',
            'degrees'    => 'required|integer',
        ]);

        $sourcePath = public_path('uploads/' . $request->image_name);

        if (!File::exists($sourcePath)) {
            return redirect()->route('acara31.index')->with('error', 'Gambar sumber tidak ditemukan.');
        }

        $degrees = (int) $request->degrees;
        $ext = pathinfo($request->image_name, PATHINFO_EXTENSION);
        $baseName = pathinfo($request->image_name, PATHINFO_FILENAME);
        $newFilename = $baseName . '_rotated_' . $degrees . 'deg.' . $ext;
        $destPath = public_path('uploads/' . $newFilename);

        Image::make($sourcePath)
            ->rotate(-$degrees) // negative for clockwise
            ->save($destPath);

        return redirect()->route('acara31.index')->with('success', "Gambar berhasil dirotasi {$degrees}° → '{$newFilename}'.");
    }

    /**
     * Prosedur 3e: Ubah Format Gambar (Encoding)
     */
    public function encode(Request $request)
    {
        $request->validate([
            'image_name' => 'required|string',
            'format'     => 'required|in:png,jpg,webp,gif',
        ]);

        $sourcePath = public_path('uploads/' . $request->image_name);

        if (!File::exists($sourcePath)) {
            return redirect()->route('acara31.index')->with('error', 'Gambar sumber tidak ditemukan.');
        }

        $format = strtolower($request->format);
        $baseName = pathinfo($request->image_name, PATHINFO_FILENAME);
        $newFilename = $baseName . '_converted.' . $format;
        $destPath = public_path('uploads/' . $newFilename);

        Image::make($sourcePath)
            ->encode($format, 85)
            ->save($destPath);

        return redirect()->route('acara31.index')->with('success', "Format gambar berhasil diubah ke {$format} → '{$newFilename}'.");
    }

    /**
     * Prosedur 3f: Menampilkan Langsung di Browser (tanpa simpan berkas baru)
     */
    public function response($filename)
    {
        $path = public_path('uploads/' . $filename);

        if (!File::exists($path)) {
            abort(404, 'Gambar tidak ditemukan.');
        }

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $format = in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp']) ? $ext : 'jpg';

        return Image::make($path)->response($format);
    }

    /**
     * Hapus gambar dari folder uploads
     */
    public function delete($filename)
    {
        $path = public_path('uploads/' . $filename);

        if (File::exists($path)) {
            File::delete($path);
            return redirect()->route('acara31.index')->with('success', "Gambar '{$filename}' berhasil dihapus.");
        }

        return redirect()->route('acara31.index')->with('error', 'Gambar tidak ditemukan.');
    }

    /**
     * Helper: Ambil daftar file gambar dari folder public/uploads
     */
    private function getUploadedImages()
    {
        $images = [];
        $dir = public_path('uploads');

        if (File::exists($dir)) {
            $files = File::files($dir);

            foreach ($files as $file) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $dimensions = @getimagesize($file->getPathname());

                    $images[] = [
                        'name'          => $file->getFilename(),
                        'size'          => $file->getSize(),
                        'last_modified' => date('d M Y H:i:s', $file->getMTime()),
                        'url'           => asset('uploads/' . $file->getFilename()),
                        'extension'     => $ext,
                        'width'         => $dimensions ? $dimensions[0] : 0,
                        'height'        => $dimensions ? $dimensions[1] : 0,
                    ];
                }
            }
        }

        return array_reverse($images);
    }
}
