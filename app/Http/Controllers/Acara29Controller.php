<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Acara29Controller extends Controller
{
    /**
     * Tampilkan halaman File Storage dengan daftar file yang sudah diunggah.
     */
    public function index()
    {
        $files = $this->getUploadedFiles();

        return view('acara.acara29-operasional', compact('files'));
    }

    /**
     * Upload file via form dengan validasi.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,csv,xlsx|max:2048',
        ], [
            'file.required' => 'File wajib diunggah.',
            'file.file' => 'Upload harus berupa file yang valid.',
            'file.mimes' => 'Format file yang diizinkan: jpg, jpeg, png, gif, pdf, doc, docx, txt, csv, xlsx.',
            'file.max' => 'Ukuran file maksimal 2MB (2048 KB).',
        ]);

        $file = $request->file('file');

        // Pilihan penamaan file
        $namingOption = $request->input('naming_option', 'original');

        switch ($namingOption) {
            case 'hash':
                // Gunakan store() bawaan Laravel (random hash name)
                $path = $file->store('documents', 'public');
                break;

            case 'timestamp':
                // Gunakan timestamp + nama asli
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents', $filename, 'public');
                break;

            case 'uuid':
                // Gunakan UUID + ekstensi asli
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('documents', $filename, 'public');
                break;

            case 'custom':
                // Gunakan nama kustom dari user
                $customName = $request->input('custom_name', 'file');
                $customName = Str::slug($customName);
                $filename = $customName . '.' . $file->getClientOriginalExtension();

                // Cek jika file sudah ada, tambahkan angka
                $counter = 1;
                $originalFilename = $filename;
                while (Storage::disk('public')->exists('documents/' . $filename)) {
                    $filename = pathinfo($originalFilename, PATHINFO_FILENAME) . '_' . $counter . '.' . $file->getClientOriginalExtension();
                    $counter++;
                }
                $path = $file->storeAs('documents', $filename, 'public');
                break;

            default:
                // Nama asli
                $filename = $file->getClientOriginalName();
                $counter = 1;
                $originalFilename = $filename;
                while (Storage::disk('public')->exists('documents/' . $filename)) {
                    $filename = pathinfo($originalFilename, PATHINFO_FILENAME) . '_' . $counter . '.' . pathinfo($originalFilename, PATHINFO_EXTENSION);
                    $counter++;
                }
                $path = $file->storeAs('documents', $filename, 'public');
                break;
        }

        return redirect()->route('acara29.index')->with('success', 'File berhasil diunggah! Tersimpan di: ' . $path);
    }

    /**
     * Ambil detail file dan isi teks menggunakan Storage::get(), Storage::path(), Storage::exists().
     */
    public function showContent($filename)
    {
        $path = 'documents/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan di storage.'
            ], 404);
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $isText = in_array($extension, ['txt', 'csv', 'json', 'log', 'md', 'html', 'xml']);
        $content = null;

        if ($isText) {
            $content = Storage::disk('public')->get($path);
        }

        return response()->json([
            'success' => true,
            'name' => $filename,
            'path' => $path,
            'absolute_path' => Storage::disk('public')->path($path),
            'url' => Storage::disk('public')->url($path),
            'size' => Storage::disk('public')->size($path),
            'formatted_size' => number_format(Storage::disk('public')->size($path) / 1024, 2) . ' KB',
            'last_modified' => date('d M Y H:i:s', Storage::disk('public')->lastModified($path)),
            'exists' => Storage::disk('public')->exists($path),
            'is_text' => $isText,
            'content' => $content,
        ]);
    }

    /**
     * Download file.
     */
    public function download($filename)
    {
        $path = 'documents/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            return redirect()->route('acara29.index')->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($path);
    }

    /**
     * Hapus file dari storage.
     */
    public function delete($filename)
    {
        $path = 'documents/' . $filename;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return redirect()->route('acara29.index')->with('success', 'File "' . $filename . '" berhasil dihapus.');
        }

        return redirect()->route('acara29.index')->with('error', 'File tidak ditemukan.');
    }

    /**
     * Simpan file teks via Storage::put.
     */
    public function putFile(Request $request)
    {
        $request->validate([
            'put_filename' => 'required|string|max:100',
            'put_content' => 'required|string|max:5000',
        ], [
            'put_filename.required' => 'Nama file wajib diisi.',
            'put_content.required' => 'Isi konten file wajib diisi.',
        ]);

        $filename = Str::slug($request->input('put_filename')) . '.txt';
        Storage::disk('public')->put('documents/' . $filename, $request->input('put_content'));

        return redirect()->route('acara29.index')->with('success', 'File "' . $filename . '" berhasil disimpan dengan Storage::put().');
    }

    /**
     * Salin (copy) file.
     */
    public function copy(Request $request)
    {
        $request->validate([
            'source_file' => 'required|string',
        ]);

        $source = 'documents/' . $request->input('source_file');
        $newName = 'copy_' . time() . '_' . $request->input('source_file');
        $destination = 'documents/' . $newName;

        if (!Storage::disk('public')->exists($source)) {
            return redirect()->route('acara29.index')->with('error', 'File sumber tidak ditemukan.');
        }

        Storage::disk('public')->copy($source, $destination);

        return redirect()->route('acara29.index')->with('success', 'File berhasil disalin menjadi "' . $newName . '".');
    }

    /**
     * Ganti nama (move) file.
     */
    public function rename(Request $request)
    {
        $request->validate([
            'old_name' => 'required|string',
            'new_name' => 'required|string|max:100',
        ]);

        $oldPath = 'documents/' . $request->input('old_name');
        $ext = pathinfo($request->input('old_name'), PATHINFO_EXTENSION);
        $newFilename = Str::slug($request->input('new_name')) . '.' . $ext;
        $newPath = 'documents/' . $newFilename;

        if (!Storage::disk('public')->exists($oldPath)) {
            return redirect()->route('acara29.index')->with('error', 'File asal tidak ditemukan.');
        }

        if (Storage::disk('public')->exists($newPath)) {
            return redirect()->route('acara29.index')->with('error', 'File dengan nama "' . $newFilename . '" sudah ada.');
        }

        Storage::disk('public')->move($oldPath, $newPath);

        return redirect()->route('acara29.index')->with('success', 'File berhasil diganti nama menjadi "' . $newFilename . '".');
    }

    /**
     * Ambil daftar file yang sudah diunggah.
     */
    private function getUploadedFiles()
    {
        $files = [];

        if (Storage::disk('public')->exists('documents')) {
            $fileList = Storage::disk('public')->files('documents');

            foreach ($fileList as $file) {
                $files[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => Storage::disk('public')->size($file),
                    'last_modified' => date('d M Y H:i:s', Storage::disk('public')->lastModified($file)),
                    'url' => Storage::url($file),
                    'extension' => pathinfo($file, PATHINFO_EXTENSION),
                    'is_image' => in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']),
                ];
            }
        }

        return $files;
    }
}
