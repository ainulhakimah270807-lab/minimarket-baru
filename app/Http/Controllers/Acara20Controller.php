<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Rules\Uppercase;
use Illuminate\Http\Request;

class Acara20Controller extends Controller
{
    /**
     * Tampilkan halaman Form dan Validation
     */
    public function index()
    {
        return view('acara.acara20');
    }

    /**
     * 1 & 2. Validasi Standar di Controller
     */
    public function submitController(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:50',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        return redirect()->route('acara20.index')
            ->with('success', 'Form 1 (Validasi Controller): Data berhasil divalidasi!');
    }

    /**
     * 3. Validasi dengan Pesan Kustom (Custom Validation Message)
     */
    public function submitCustomMessage(Request $request)
    {
        $messages = [
            'name.required' => 'Nama harus diisi!',
            'email.required' => 'Email tidak boleh kosong!',
            'password.confirmed' => 'Password tidak cocok!'
        ];

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ], $messages);

        return redirect()->route('acara20.index')
            ->with('success', 'Form 2 (Custom Messages): Data berhasil divalidasi!');
    }

    /**
     * 4. Validasi Menggunakan Form Request (UserRequest)
     */
    public function submitFormRequest(UserRequest $request)
    {
        return redirect()->route('acara20.index')
            ->with('success', 'Form 3 (Form Request): Data berhasil divalidasi!');
    }

    /**
     * 5. Validasi Menggunakan Rule Kustom (Uppercase)
     */
    public function submitCustomRule(Request $request)
    {
        $request->validate([
            'name' => ['required', new Uppercase]
        ]);

        return redirect()->route('acara20.index')
            ->with('success', 'Form 4 (Custom Rule Uppercase): Nama valid dan seluruhnya huruf kapital!');
    }
}
