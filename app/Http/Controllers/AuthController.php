<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'email'      => ['required', 'email'],
            'password'   => ['required'],
            'tipe_login' => ['required'], 
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if ($user->role !== 'admin' && $user->role !== $request->tipe_login) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akses ditolak. Anda terdaftar sebagai ' . ucfirst($user->role) . '.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return $user->role === 'admin' 
                ? redirect()->intended('/admin/dashboard') 
                : redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Maaf, Email atau Password Anda salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}