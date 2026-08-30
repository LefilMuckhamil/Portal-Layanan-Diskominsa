<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        if ($requestData->status !== 'pending') {
            return back()->with('error', 'Permintaan reset sandi ini sudah diproses sebelumnya.');
        }

        $user = User::where('email', $requestData->email_or_nip)
            ->orWhere('nip', $requestData->email_or_nip)
            ->first();

        if (! $user) {
            return back()->with('error', 'Akun ASN dengan Email/NIP tersebut tidak ditemukan di database.');
        }

        if (ForgotPasswordController::normalizePhone($user->no_hp) !== ForgotPasswordController::normalizePhone($requestData->phone)) {
            return back()->with('error', 'Nomor WhatsApp pada pengajuan tidak cocok dengan nomor HP yang terdaftar pada akun tersebut. Reset dibatalkan untuk keamanan.');
        }

        // Generator kriptografis aman (bukan rand()): 12 karakter alfanumerik.
        $newPassword = Str::password(12, letters: true, numbers: true, symbols: false);
        $user->password = Hash::make($newPassword);
        $user->save();

        DB::table('password_reset_requests')->where('id', $id)->update([
            'status' => 'processed',
            'updated_at' => now(),
        ]);

        // Tautan WhatsApp memerlukan format internasional 62 (tanpa awalan 0).
        $phone = PhoneNumber::wa($user->no_hp);

        $pesanTeks = "Halo {$user->name}, permintaan reset kata sandi Anda pada Portal Layanan Diskominsa telah disetujui.\n\n"
                   ."Kata Sandi Baru: *{$newPassword}*\n\n"
                   .'Silakan masuk dan segera ubah kata sandi Anda pada menu profil demi keamanan.';

        $pesan = rawurlencode($pesanTeks);

        $waUrl = "https://wa.me/{$phone}?text={$pesan}";

        return response()->view('admin.reset-password.wa-redirect', [
            'waUrl' => $waUrl,
        ]);
    }

    public function destroy($id)
    {
        $requestData = DB::table('password_reset_requests')->where('id', $id)->first();

        if (! $requestData) {
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        DB::table('password_reset_requests')->where('id', $id)->delete();

        return back()->with('sukses', 'Permohonan reset password berhasil dihapus/dibatalkan.');
    }
}
