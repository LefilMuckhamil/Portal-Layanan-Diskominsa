<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Admin selalu diperbolehkan; user biasa harus berstatus 'aktif'.
        if ($user && $user->role !== 'admin' && $user->status_akun !== 'aktif') {
            $pesan = $user->status_akun === 'ditolak'
                ? 'Pendaftaran akun Anda ditolak. Silakan hubungi Diskominsa untuk klarifikasi.'
                : 'Akun ASN Anda belum diaktivasi. Menunggu verifikasi Administrator Diskominsa.';

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', $pesan);
        }

        return $next($request);
    }
}
