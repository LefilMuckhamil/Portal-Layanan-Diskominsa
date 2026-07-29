<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah sudah login DAN role-nya adalah 'admin'
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Jika user biasa mencoba masuk ke link admin, lemparkan ke halaman utama
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman Admin.');
    }
}