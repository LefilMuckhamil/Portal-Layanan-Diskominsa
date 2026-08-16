<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        if (! $requestData) {
            return back()->with('error', 'Permohonan tidak ditemukan.');
        }

        $user = User::where('email', $requestData->email_or_nip)
            ->orWhere('nip', $requestData->email_or_nip)
            ->first();

        if (! $user) {
            return back()->with('error', 'Akun ASN dengan Email/NIP tersebut tidak ditemukan di database.');
        }

        if (ForgotPasswordController::normalizePhone($user->no_hp) !== ForgotPasswordController::normalizePhone($requestData->phone)) {
            return back()->with('error', 'Nomor WhatsApp pada permohonan tidak cocok dengan nomor HP yang terdaftar pada akun tersebut. Reset dibatalkan untuk keamanan.');
        }

        $newPassword = 'Pass'.rand(100000, 999999).'!';
        $user->password = Hash::make($newPassword);
        $user->save();

        DB::table('password_reset_requests')->where('id', $id)->update([
            'status' => 'processed',
            'updated_at' => now(),
        ]);

        $cleanPhone = preg_replace('/[^0-9]/', '', $user->no_hp);
        $phone = preg_replace('/^0/', '62', $cleanPhone);

        $pesanTeks = "Halo {$user->name}, permohonan reset kata sandi Anda pada Portal Layanan Diskominsa telah disetujui.\n\n"
                   ."Kata Sandi Baru: *{$newPassword}*\n\n"
                   .'Silakan masuk dan segera ubah kata sandi Anda pada menu profil demi keamanan.';

        $pesan = urlencode($pesanTeks);

        return redirect()->away("https://wa.me/{$phone}?text={$pesan}");
    }
}
