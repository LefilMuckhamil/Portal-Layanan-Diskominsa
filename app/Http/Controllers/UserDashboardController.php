<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function riwayat()
    {
        $pengajuans = Pengajuan::where('user_id', Auth::id())->latest()->get();
        $chatAktif = Setting::get('chat_global_aktif', '1') === '1';

        return view('user.riwayat', compact('pengajuans', 'chatAktif'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::where('user_id', Auth::id())->findOrFail($id);
        $chatAktif = Setting::get('chat_global_aktif', '1') === '1';

        return view('user.detail', compact('pengajuan', 'chatAktif'));
    }

    public function kirimPesan(Request $request, $id)
    {
        if (Setting::get('chat_global_aktif', '1') !== '1') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'pesan' => 'Fitur chat sedang dinonaktifkan oleh Admin.',
                ], 403);
            }

            return back()->withErrors(['pesan' => 'Fitur chat sedang dinonaktifkan oleh Admin.']);
        }

        $request->validate([
            'pesan' => 'required|string|max:1000',
        ]);

        $pengajuan = Pengajuan::where('user_id', auth()->id())->findOrFail($id);

        $pesanBaru = [
            'role' => 'user',
            'pengirim' => auth()->user()->name,
            'isi' => $request->pesan,
            'waktu' => now()->format('d M Y, H:i'),
        ];

        $pesanLama = is_string($pengajuan->pesan) ? json_decode($pengajuan->pesan, true) : ($pengajuan->pesan ?? []);
        $pesanLama[] = $pesanBaru;

        $pengajuan->update([
            'pesan' => $pesanLama,
        ]);

        // Jika request dikirim via AJAX / Fetch
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'pesan' => $pesanBaru,
            ]);
        }

        return redirect()->back();
    }

    public function getChat($id)
    {
        $pengajuan = Pengajuan::where('user_id', auth()->id())->findOrFail($id);

        $pesan = is_array($pengajuan->pesan)
            ? $pengajuan->pesan
            : (json_decode((string) $pengajuan->getRawOriginal('pesan') ?? '[]', true) ?: []);

        return response()->json([
            'status' => 'success',
            'pesan' => $pesan,
        ]);
    }
}
