<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserDashboardController extends Controller
{
    /**
     * Ambil semua data riwayat pengajuan khusus buat user yang lagi login.
     * Sekalian ngecek saklar fitur chat dari admin lagi nyala atau dimatiin.
     */
    public function riwayat()
    {
        $pengajuans = Pengajuan::where('user_id', Auth::id())->latest()->get();
        $chatAktif = Cache::get('chat_global_aktif', true);

        return view('user.riwayat', compact('pengajuans', 'chatAktif'));
    }

    /**
     * Nampilin halaman detail dan timeline dari satu pengajuan spesifik.
     * Ada validasi otomatis biar user cuma bisa ngecek data miliknya sendiri.
     */
    public function show($id)
    {
        $pengajuan = Pengajuan::where('user_id', Auth::id())->findOrFail($id);
        
        return view('user.detail', compact('pengajuan'));
    }

    /**
     * Proses pengiriman pesan dari user ke admin.
     * Pesan barunya bakal ditimpa dan ditambahin ke dalam array JSON yang udah ada di database.
     */
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