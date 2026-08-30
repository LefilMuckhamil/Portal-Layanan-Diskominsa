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
            'email' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
        ], [
            'email.required' => 'Email atau NIP wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
        ]);

        $user = User::where('email', $request->email)
            ->orWhere('nip', $request->email)
            ->first();

        // Akun tidak ditemukan → respons sukses semu (cegah account enumeration).
        if (! $user) {
            return back()->with('sukses', 'Permintaan reset sandi berhasil dikirim! Admin akan memverifikasi dan mengirimkan akses via WhatsApp.');
        }

        // Nomor WhatsApp tidak cocok dengan akun → error.
        if (PhoneNumber::normalize($user->no_hp) !== $request->phone) {
            return back()->withErrors([
                'phone' => 'Nomor WhatsApp tidak terdaftar pada akun tersebut. Gunakan nomor HP yang didaftarkan saat membuat akun.',
            ])->withInput();
        }

        // Cegah duplikasi: tolak jika ada permohonan pending untuk email/NIP + phone yang sama.
        $adaPending = DB::table('password_reset_requests')
            ->where('email_or_nip', $request->email)
            ->where('phone', $request->phone)
            ->where('status', 'pending')
            ->exists();

        if ($adaPending) {
            return back()->with('warning', 'Permintaan reset sandi untuk akun ini masih dalam antrean verifikasi Admin. Harap tunggu proses selesai.');
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
