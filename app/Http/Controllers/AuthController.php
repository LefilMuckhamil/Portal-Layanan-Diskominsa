<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // login
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $key = 'login_attempt_'.strtolower($request->email);
        if (Cache::get($key, 0) >= 5) {
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan login. Coba lagi dalam 15 menit.',
            ])->onlyInput('email');
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $request->session()->regenerate();
            $user = Auth::user();
            Cache::forget($key);

            return $user->role === 'admin'
                ? redirect()->intended('/admin/dashboard')
                : redirect()->intended('/');
        }

        Cache::put($key, Cache::get($key, 0) + 1, now()->addMinutes(15));

        return back()->withErrors([
            'email' => 'Maaf, Email atau Password Anda salah.',
        ])->onlyInput('email');
    }

    // register
    public function registerProcess(Request $request)
    {
        $request->merge(['no_hp' => PhoneNumber::normalize($request->no_hp)]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'unit_kerja' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/', 'min:10', 'max:16', 'unique:users,no_hp'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'nip' => [
                'required',
                'string',
                'size:18',
                'unique:users,nip',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[^@]+@acehbaratkab\.go\.id$/i',
            ],
        ], [
            'email.regex' => 'Pendaftaran wajib menggunakan email resmi @acehbaratkab.go.id.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'nip.unique' => 'NIP ini sudah terdaftar. Silakan gunakan NIP Anda yang sebenarnya.',
            'nip.size' => 'NIP harus berjumlah tepat 18 digit.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Format nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau 62xxxxxxxxxx.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // normalisasi email ke huruf kecil sebelum disimpan
        $request->merge(['email' => strtolower(trim($request->email))]);

        // sve ke db
        $user = User::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'unit_kerja' => $request->unit_kerja,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // role tidak boleh di-mass-assignment; tetapkan eksplisit setelah create
        $user->forceFill(['role' => 'user'])->save();

        return redirect()->route('login')->with('sukses', 'Akun berhasil didaftarkan! Silakan masuk menggunakan email dan kata sandi Anda.');
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
