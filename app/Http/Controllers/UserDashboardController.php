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
        
        return view('user.detail', compact('pengajuan'));
    }

    public function kirimPesan(Request $request, $id)
    {
        $request->validate([
            'pesan' => ['required', 'string']
        ]);

        $pengajuan = Pengajuan::where('user_id', Auth::id())->findOrFail($id);

        $semuaPesan = $pengajuan->pesan ?? [];
        
        $semuaPesan[] = [
            'pengirim' => Auth::user()->name,
            'role'     => 'user',
            'isi'      => $request->pesan,
            'waktu'    => now()->format('d M Y, H:i')
        ];

        $pengajuan->update([
            'pesan' => $semuaPesan
        ]);

        return back()->with('sukses', 'Pesan kamu berhasil terkirim!');
    }
}