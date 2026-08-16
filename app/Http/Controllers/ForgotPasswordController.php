<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\PhoneNumber;
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
        $request->merge(['phone' => PhoneNumber::normalize($request->phone)]);

        $request->validate([
            'email' => 'required|string',
            'phone' => ['required', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/', 'min:10', 'max:16'],
        ], [
            'email.required' => 'Email atau NIP wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.regex' => 'Format nomor WhatsApp tidak valid.',
        ]);

        $user = User::where('email', $request->email)
            ->orWhere('nip', $request->email)
            ->first();

        if (! $user || PhoneNumber::normalize($user->no_hp) !== $request->phone) {
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

        return back()->with('sukses', 'Permintaan reset sandi berhasil dikirim! Admin akan memverifikasi dan mengirimkan akses via WhatsApp.');
    }

    public static function normalizePhone(?string $phone): string
    {
        return PhoneNumber::normalize($phone);
    }
}
