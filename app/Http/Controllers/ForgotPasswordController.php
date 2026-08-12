<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
            'phone.numeric'  => 'Nomor WhatsApp harus berupa angka.',
        ]);

        DB::table('password_reset_requests')->insert([
            'email_or_nip' => $request->email,
            'phone'        => $request->phone,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return back()->with('status', 'Permohonan reset sandi berhasil dikirim! Admin akan memverifikasi dan mengirimkan akses via WhatsApp.');
    }
}