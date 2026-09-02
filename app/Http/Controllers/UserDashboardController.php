<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\PengajuanMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function riwayat()
    {
        $pengajuans = Pengajuan::with(['layanan', 'pemohon', 'messages', 'riwayatStatus'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        $chatAktif = Setting::get('chat_global_aktif', '1') === '1';

        return view('user.riwayat', compact('pengajuans', 'chatAktif'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with(['layanan', 'pemohon', 'messages', 'riwayatStatus'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
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

        $pesanBaru = $pengajuan->messages()->create([
            'sender_id' => auth()->id(),
            'sender_role' => 'user',
            'isi' => $request->pesan,
        ]);

        $pesanBaru = [
            'role' => 'user',
            'pengirim' => auth()->user()->name,
            'isi' => $pesanBaru->isi,
            'waktu' => $pesanBaru->waktu,
        ];

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
        $pengajuan = Pengajuan::with('messages.sender')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $pesan = $pengajuan->messages->map(fn (PengajuanMessage $chat) => [
            'role' => $chat->role,
            'pengirim' => $chat->pengirim,
            'isi' => $chat->isi,
            'waktu' => $chat->waktu,
        ])->values()->all();

        return response()->json([
            'status' => 'success',
            'pesan' => $pesan,
        ]);
    }
}
