<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserDashboardController extends Controller
{
    public function riwayat()
    {
        $pengajuans = Pengajuan::where('user_id', Auth::id())->latest()->get();
        $chatAktif = Cache::get('chat_global_aktif', true);

        return view('user.riwayat', compact('pengajuans', 'chatAktif'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::where('user_id', Auth::id())->findOrFail($id);
        $chatAktif = Cache::get('chat_global_aktif', true);

        return view('user.detail', compact('pengajuan', 'chatAktif'));
    }

    public function kirimPesan(Request $request, $id)
    {
        if (! Cache::get('chat_global_aktif', true)) {
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
}
