<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // login
    public function authenticate(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            
            $request->session()->regenerate();
            $user = Auth::user();

            return $user->role === 'admin' 
                ? redirect()->intended('/admin/dashboard') 
                : redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Maaf, Email atau Password Anda salah.',
        ])->onlyInput('email');
    }

    // register
    public function registerProcess(Request $request)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'unit_kerja' => ['required', 'string', 'max:255'],
            'jabatan'    => ['required', 'string', 'max:255'],
            'no_hp'      => ['required', 'string', 'max:20'], 
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            
            'nip' => [
                'required', 
                'string', 
                'size:18', 
                'unique:users,nip' 
            ],
            
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users,email', 
                'regex:/@acehbaratkab\.go\.id$/'
            ],
        ], [
            'email.regex'        => 'Pendaftaran wajib menggunakan email resmi @acehbaratkab.go.id.',
            'email.unique'       => 'Email ini sudah terdaftar di sistem.',
            'nip.unique'         => 'NIP ini sudah terdaftar. Silakan gunakan NIP Anda yang sebenarnya.',
            'nip.size'           => 'NIP harus berjumlah tepat 18 digit.',
            'no_hp.required'     => 'Nomor HP wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.'
        ]);

        // sve ke db
        User::create([
            'name'       => $request->name,
            'nip'        => $request->nip,
            'unit_kerja' => $request->unit_kerja,
            'jabatan'    => $request->jabatan,
            'no_hp'      => $request->no_hp,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'user',
        ]);

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