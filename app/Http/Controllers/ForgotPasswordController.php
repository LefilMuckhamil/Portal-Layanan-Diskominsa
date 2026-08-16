<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function submitRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'phone' => 'required|numeric',
        ], [
            'email.required' => 'Email atau NIP wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.numeric' => 'Nomor WhatsApp harus berupa angka.',
        ]);

        $user = User::where('email', $request->email)
            ->orWhere('nip', $request->email)
            ->first();

        if (! $user || self::normalizePhone($user->no_hp) !== self::normalizePhone($request->phone)) {
            return back()->withErrors([
                'phone' => 'Nomor WhatsApp tidak terdaftar pada akun tersebut. Gunakan nomor HP yang didaftarkan saat membuat akun.',
            ])->withInput();
        }

        DB::table('password_reset_requests')->insert([
            'email_or_nip' => $request->email,
            'phone' => $request->phone,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('status', 'Permohonan reset sandi berhasil dikirim! Admin akan memverifikasi dan mengirimkan akses via WhatsApp.');
    }

    public static function normalizePhone(?string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', (string) $phone);

        return str_starts_with($clean, '0')
            ? '62'.substr($clean, 1)
            : $clean;
    }
}
