<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Acara30Controller extends Controller
{
    /**
     * Tampilkan halaman PDF Report & Image Manipulation.
     */
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::withCount('products')->get();
        $employees = Employee::all();

        // Ambil gambar yang sudah di-upload untuk manipulasi
        $uploadedImages = $this->getUploadedImages();

        return view('acara.acara30-operasional', compact('products', 'categories', 'employees', 'uploadedImages'));
    }

    /**
     * 1) Generate PDF Laporan Produk — Daftar semua produk + kategori
     */
    public function generateProductPdf()
    {
        $products = Product::with('category')->orderBy('name')->get();
        $categories = Category::withCount('products')->orderBy('name')->get();

        $data = [
            'title'      => 'Laporan Produk Minimarket',
            'tanggal'    => date('d-m-Y'),
            'products'   => $products,
            'categories' => $categories,
        ];

        $pdf = Pdf::loadView('laporan.produk', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('laporan-produk-' . date('Ymd') . '.pdf');
    }

    /**
     * 2) Generate PDF Laporan Stok — Menampilkan stok rendah
     */
    public function generateStockPdf()
    {
        $allProducts = Product::with('category')->orderBy('stock', 'asc')->get();
        $lowStock = Product::with('category')->lowStock()->get();

        $data = [
            'title'       => 'Laporan Stok Barang',
            'tanggal'     => date('d-m-Y'),
            'products'    => $allProducts,
            'lowStock'    => $lowStock,
            'totalProduk' => $allProducts->count(),
        ];

        $pdf = Pdf::loadView('laporan.stok', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('laporan-stok-' . date('Ymd') . '.pdf');
    }

    /**
     * 3) Generate PDF Laporan Pegawai — Data pegawai
     */
    public function generateEmployeePdf()
    {
        $employees = Employee::orderBy('name')->get();

        if ($employees->isEmpty()) {
            $employees = collect([
                (object) ['name' => 'Andi', 'position' => 'Manager', 'salary' => 5000000],
                (object) ['name' => 'Budi', 'position' => 'Staff', 'salary' => 3500000],
                (object) ['name' => 'Citra', 'position' => 'Kasir', 'salary' => 3000000],
                (object) ['name' => 'Dewi', 'position' => 'Supervisor', 'salary' => 4200000],
            ]);
        }

        $data = [
            'title'     => 'Laporan Pegawai',
            'tanggal'   => date('d-m-Y'),
            'pegawai'   => $employees,
        ];

        $pdf = Pdf::loadView('laporan.pegawai', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('laporan-pegawai-' . date('Ymd') . '.pdf');
    }

    /**
     * 4) Stream PDF di Browser (tidak download)
     */
    public function streamProductPdf()
    {
        $products = Product::with('category')->orderBy('name')->get();
        $categories = Category::withCount('products')->orderBy('name')->get();

        $data = [
            'title'      => 'Laporan Produk Minimarket',
            'tanggal'    => date('d-m-Y'),
            'products'   => $products,
            'categories' => $categories,
        ];

        $pdf = Pdf::loadView('laporan.produk', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-produk-' . date('Ymd') . '.pdf');
    }

    /**
     * Stream PDF Laporan Stok di Browser
     */
    public function streamStockPdf()
    {
        $allProducts = Product::with('category')->orderBy('stock', 'asc')->get();
        $lowStock = Product::with('category')->lowStock()->get();

        $data = [
            'title'       => 'Laporan Stok Barang',
            'tanggal'     => date('d-m-Y'),
            'products'    => $allProducts,
            'lowStock'    => $lowStock,
            'totalProduk' => $allProducts->count(),
        ];

        $pdf = Pdf::loadView('laporan.stok', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-stok-' . date('Ymd') . '.pdf');
    }

    /**
     * Stream PDF Laporan Pegawai di Browser
     */
    public function streamEmployeePdf()
    {
        $employees = Employee::orderBy('name')->get();

        if ($employees->isEmpty()) {
            $employees = collect([
                (object) ['name' => 'Andi', 'position' => 'Manager', 'salary' => 5000000],
                (object) ['name' => 'Budi', 'position' => 'Staff', 'salary' => 3500000],
                (object) ['name' => 'Citra', 'position' => 'Kasir', 'salary' => 3000000],
                (object) ['name' => 'Dewi', 'position' => 'Supervisor', 'salary' => 4200000],
            ]);
        }

        $data = [
            'title'     => 'Laporan Pegawai',
            'tanggal'   => date('d-m-Y'),
            'pegawai'   => $employees,
        ];

        $pdf = Pdf::loadView('laporan.pegawai', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-pegawai-' . date('Ymd') . '.pdf');
    }

    /**
     * 5) Upload gambar untuk manipulasi (tanpa Intervention Image — PHP GD)
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
        ], [
            'photo.required' => 'Gambar wajib diunggah.',
            'photo.image'    => 'File harus berupa gambar.',
            'photo.mimes'    => 'Format gambar: jpeg, jpg, png, gif.',
            'photo.max'      => 'Ukuran maksimal gambar 2MB.',
        ]);

        $file = $request->file('photo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('uploads', $filename, 'public');

        return redirect()->route('acara30.index')->with('success', 'Gambar "' . $filename . '" berhasil diunggah.');
    }

    /**
     * 6) Resize gambar menggunakan PHP GD
     */
    public function resizeImage(Request $request)
    {
        $request->validate([
            'image_name' => 'required|string',
            'width'      => 'required|integer|min:10|max:2000',
            'height'     => 'required|integer|min:10|max:2000',
        ]);

        $sourcePath = storage_path('app/public/uploads/' . $request->image_name);

        if (!file_exists($sourcePath)) {
            return redirect()->route('acara30.index')->with('error', 'Gambar sumber tidak ditemukan.');
        }

        $info = getimagesize($sourcePath);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg': $source = imagecreatefromjpeg($sourcePath); break;
            case 'image/png':  $source = imagecreatefrompng($sourcePath); break;
            case 'image/gif':  $source = imagecreatefromgif($sourcePath); break;
            default:
                return redirect()->route('acara30.index')->with('error', 'Format gambar tidak didukung.');
        }

        $newWidth  = (int) $request->width;
        $newHeight = (int) $request->height;

        $resized = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG
        if ($mime === 'image/png') {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }

        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $info[0], $info[1]);

        $ext = pathinfo($request->image_name, PATHINFO_EXTENSION);
        $baseName = pathinfo($request->image_name, PATHINFO_FILENAME);
        $newFilename = $baseName . '_resized_' . $newWidth . 'x' . $newHeight . '.' . $ext;
        $destPath = storage_path('app/public/uploads/' . $newFilename);

        switch ($mime) {
            case 'image/jpeg': imagejpeg($resized, $destPath, 90); break;
            case 'image/png':  imagepng($resized, $destPath); break;
            case 'image/gif':  imagegif($resized, $destPath); break;
        }

        imagedestroy($source);
        imagedestroy($resized);

        return redirect()->route('acara30.index')->with('success', 'Gambar berhasil di-resize menjadi ' . $newWidth . 'x' . $newHeight . ' → "' . $newFilename . '"');
    }

    /**
     * 7) Rotate gambar menggunakan PHP GD
     */
    public function rotateImage(Request $request)
    {
        $request->validate([
            'image_name' => 'required|string',
            'degrees'    => 'required|integer',
        ]);

        $sourcePath = storage_path('app/public/uploads/' . $request->image_name);

        if (!file_exists($sourcePath)) {
            return redirect()->route('acara30.index')->with('error', 'Gambar sumber tidak ditemukan.');
        }

        $info = getimagesize($sourcePath);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg': $source = imagecreatefromjpeg($sourcePath); break;
            case 'image/png':  $source = imagecreatefrompng($sourcePath); break;
            case 'image/gif':  $source = imagecreatefromgif($sourcePath); break;
            default:
                return redirect()->route('acara30.index')->with('error', 'Format tidak didukung.');
        }

        // imagerotate rotates counter-clockwise, negate for clockwise
        $rotated = imagerotate($source, -((int) $request->degrees), 0);

        if ($mime === 'image/png') {
            imagealphablending($rotated, false);
            imagesavealpha($rotated, true);
        }

        $ext = pathinfo($request->image_name, PATHINFO_EXTENSION);
        $baseName = pathinfo($request->image_name, PATHINFO_FILENAME);
        $newFilename = $baseName . '_rotated_' . $request->degrees . 'deg.' . $ext;
        $destPath = storage_path('app/public/uploads/' . $newFilename);

        switch ($mime) {
            case 'image/jpeg': imagejpeg($rotated, $destPath, 90); break;
            case 'image/png':  imagepng($rotated, $destPath); break;
            case 'image/gif':  imagegif($rotated, $destPath); break;
        }

        imagedestroy($source);
        imagedestroy($rotated);

        return redirect()->route('acara30.index')->with('success', 'Gambar berhasil di-rotate ' . $request->degrees . '° → "' . $newFilename . '"');
    }

    /**
     * 8) Crop gambar menggunakan PHP GD
     */
    public function cropImage(Request $request)
    {
        $request->validate([
            'image_name' => 'required|string',
            'crop_x'     => 'required|integer|min:0',
            'crop_y'     => 'required|integer|min:0',
            'crop_w'     => 'required|integer|min:10',
            'crop_h'     => 'required|integer|min:10',
        ]);

        $sourcePath = storage_path('app/public/uploads/' . $request->image_name);

        if (!file_exists($sourcePath)) {
            return redirect()->route('acara30.index')->with('error', 'Gambar sumber tidak ditemukan.');
        }

        $info = getimagesize($sourcePath);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg': $source = imagecreatefromjpeg($sourcePath); break;
            case 'image/png':  $source = imagecreatefrompng($sourcePath); break;
            case 'image/gif':  $source = imagecreatefromgif($sourcePath); break;
            default:
                return redirect()->route('acara30.index')->with('error', 'Format tidak didukung.');
        }

        $cropped = imagecrop($source, [
            'x'      => (int) $request->crop_x,
            'y'      => (int) $request->crop_y,
            'width'  => (int) $request->crop_w,
            'height' => (int) $request->crop_h,
        ]);

        if ($cropped === false) {
            imagedestroy($source);
            return redirect()->route('acara30.index')->with('error', 'Gagal melakukan crop gambar.');
        }

        if ($mime === 'image/png') {
            imagealphablending($cropped, false);
            imagesavealpha($cropped, true);
        }

        $ext = pathinfo($request->image_name, PATHINFO_EXTENSION);
        $baseName = pathinfo($request->image_name, PATHINFO_FILENAME);
        $newFilename = $baseName . '_cropped.' . $ext;
        $destPath = storage_path('app/public/uploads/' . $newFilename);

        switch ($mime) {
            case 'image/jpeg': imagejpeg($cropped, $destPath, 90); break;
            case 'image/png':  imagepng($cropped, $destPath); break;
            case 'image/gif':  imagegif($cropped, $destPath); break;
        }

        imagedestroy($source);
        imagedestroy($cropped);

        return redirect()->route('acara30.index')->with('success', 'Gambar berhasil di-crop → "' . $newFilename . '"');
    }

    /**
     * 9) Hapus gambar dari uploads
     */
    public function deleteImage($filename)
    {
        $path = 'uploads/' . $filename;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return redirect()->route('acara30.index')->with('success', 'Gambar "' . $filename . '" berhasil dihapus.');
        }

        return redirect()->route('acara30.index')->with('error', 'Gambar tidak ditemukan.');
    }

    /**
     * Helper: Ambil daftar gambar yang sudah di-upload
     */
    private function getUploadedImages()
    {
        $images = [];

        if (Storage::disk('public')->exists('uploads')) {
            $fileList = Storage::disk('public')->files('uploads');

            foreach ($fileList as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $fullPath = storage_path('app/public/' . $file);
                    $dimensions = @getimagesize($fullPath);

                    $images[] = [
                        'name'          => basename($file),
                        'path'          => $file,
                        'size'          => Storage::disk('public')->size($file),
                        'last_modified' => date('d M Y H:i', Storage::disk('public')->lastModified($file)),
                        'url'           => asset('storage/' . $file),
                        'extension'     => $ext,
                        'width'         => $dimensions ? $dimensions[0] : 0,
                        'height'        => $dimensions ? $dimensions[1] : 0,
                    ];
                }
            }
        }

        return $images;
    }
}
