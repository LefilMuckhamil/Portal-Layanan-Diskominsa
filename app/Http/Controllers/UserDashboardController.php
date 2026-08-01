<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    // 1. Fungsi untuk menampilkan tabel riwayat (Sudah ada sebelumnya)
    public function riwayat()
    {
        // Mengambil data pengajuan khusus milik user yang sedang login, diurutkan dari yang terbaru
        $pengajuans = Pengajuan::where('user_id', Auth::id())->latest()->get();
        
        return view('user.riwayat', compact('pengajuans'));
    }

    // 2. Fungsi untuk menampilkan halaman detail & timeline
    public function show($id)
    {
        // Cari data pengajuan berdasarkan ID, pastikan itu milik user yang sedang login
        $pengajuan = Pengajuan::where('user_id', Auth::id())->findOrFail($id);
        
        return view('user.detail', compact('pengajuan'));
    }

    // 3. Fungsi untuk memproses pengiriman pesan chat
    public function kirimPesan(Request $request, $id)
    {
        // Validasi input pesan tidak boleh kosong
        $request->validate([
            'pesan' => 'required|string'
        ]);

        // Cari data pengajuan
        $pengajuan = Pengajuan::where('user_id', Auth::id())->findOrFail($id);

        // Ambil array pesan lama (jika ada), lalu tambahkan pesan yang baru diketik
        $semuaPesan = $pengajuan->pesan ?? [];
        $semuaPesan[] = [
            'pengirim' => Auth::user()->name,
            'role' => 'user',
            'isi' => $request->pesan,
            'waktu' => now()->format('d M Y, H:i')
        ];

        // Simpan array pesan terbaru ke dalam database
        $pengajuan->pesan = $semuaPesan;
        $pengajuan->save();

        // Kembalikan user ke halaman yang sama (refresh)
        return back()->with('sukses', 'Pesan berhasil dikirim!');
    }
}