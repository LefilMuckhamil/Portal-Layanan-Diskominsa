<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Fungsi untuk memproses data login
    public function authenticate(Request $request)
    {
        // 1. Validasi inputan form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek apakah email dan password cocok di database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 3. Jika cocok, cek role-nya. Admin ke dashboard, User ke halaman utama/layanan
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/'); 
        }

        // 4. Jika salah password/email, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Maaf, Email atau Password Anda salah.',
        ])->onlyInput('email');
    }

    // Fungsi untuk logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}