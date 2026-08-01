<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPengajuanController extends Controller
{
    // 1. Menampilkan semua data pengajuan dari semua user
    public function index()
    {
        // Berbeda dengan user, admin mengambil semua data tanpa 'where(user_id)'
        $pengajuans = Pengajuan::latest()->get(); 
        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    // 2. Menampilkan detail pengajuan untuk Admin
    public function show($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        return view('admin.pengajuan.detail', compact('pengajuan'));
    }

    // 3. Fungsi untuk Admin membalas pesan chat
    public function balasPesan(Request $request, $id)
    {
        $request->validate(['pesan' => 'required|string']);
        
        $pengajuan = Pengajuan::findOrFail($id);

        $semuaPesan = $pengajuan->pesan ?? [];
        $semuaPesan[] = [
            'pengirim' => Auth::user()->name ?? 'Admin Diskominsa',
            'role'     => 'admin', // Penanda bahwa ini pesan dari Admin
            'isi'      => $request->pesan,
            'waktu'    => now()->format('d M Y, H:i')
        ];

        $pengajuan->pesan = $semuaPesan;
        $pengajuan->save();

        return back()->with('sukses', 'Pesan berhasil dikirim ke User!');
    }

    // 4. Fungsi untuk Admin menambah riwayat progress (Timeline)
    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'judul_progress' => 'required|string',
            'deskripsi'      => 'required|string',
            'status_baru'    => 'nullable|string'
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        // Jika admin memilih status baru (misal: "Diproses" atau "Selesai"), update status utama
        if ($request->status_baru) {
            $pengajuan->status = $request->status_baru;
        }

        // Tambahkan riwayat baru ke dalam array logs
        $semuaLogs = $pengajuan->logs ?? [];
        $semuaLogs[] = [
            'judul'     => $request->judul_progress,
            'deskripsi' => $request->deskripsi,
            'waktu'     => now()->format('d M Y, H:i')
        ];

        $pengajuan->logs = $semuaLogs;
        $pengajuan->save();

        return back()->with('sukses', 'Progress berhasil diupdate!');
    }
}