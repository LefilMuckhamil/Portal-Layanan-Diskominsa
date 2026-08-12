<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordAdminController extends Controller
{
    public function index()
    {
        $requests = DB::table('password_reset_requests')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reset-password.index', compact('requests'));
    }

    public function process($id)
    {
        $requestData = DB::table('password_reset_requests')->where('id', $id)->first();

        if (!$requestData) {
            return back()->with('error', 'Permohonan tidak ditemukan.');
        }

        // Cari user berdasarkan Email atau NIP
        $user = User::where('email', $requestData->email_or_nip)
                    ->orWhere('nip', $requestData->email_or_nip)
                    ->first();

        if (!$user) {
            return back()->with('error', 'Akun ASN dengan Email/NIP tersebut tidak ditemukan di database.');
        }

        // Generate password baru
        $newPassword = 'Pass' . rand(100000, 999999) . '!';
        $user->password = Hash::make($newPassword);
        $user->save();

        // Tandai status permohonan selesai
        DB::table('password_reset_requests')->where('id', $id)->update([
            'status'     => 'processed',
            'updated_at' => now(),
        ]);

        // Format nomor HP ke 628xx
        $phone = preg_replace('/^0/', '62', $requestData->phone);

        // Buat pesan draf WhatsApp
        $pesan = "Halo {$user->name}, permohonan reset kata sandi Anda pada Portal Layanan Diskominsa telah disetujui.%0A%0A"
               . "Kata Sandi Baru: *{$newPassword}*%0A%0A"
               . "Silakan masuk dan segera ubah kata sandi Anda pada menu profil demi keamanan.";

        return redirect()->away("https://wa.me/{$phone}?text={$pesan}");
    }
}