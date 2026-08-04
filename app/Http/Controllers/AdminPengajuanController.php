<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPengajuanController extends Controller
{
    // 1. Menampilkan semua data pengajuan
    public function index()
    {
        $pengajuans = Pengajuan::latest()->get(); 
        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    // 2. Menampilkan detail pengajuan
    public function show($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        return view('admin.pengajuan.detail', compact('pengajuan'));
    }

    // 3. Fungsi Terpadu: Update Status, Timeline (Logs), dan Balas Chat
    public function updateProgres(Request $request, $id)
    {
        $request->validate([
            'status'  => ['required', 'string'],
            'catatan' => ['nullable', 'string', 'max:500'],
            'pesan'   => ['nullable', 'string', 'max:1000'],
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        
        // Update status utama
        $pengajuan->status = $request->status;

        // Jika admin mengisi catatan progres (Masuk ke E-Tracking User)
        if ($request->filled('catatan')) {
            $logs = $pengajuan->logs ?? [];
            $logs[] = [
                'status'     => $request->status,
                'catatan'    => $request->catatan,
                'created_at' => now()->toDateTimeString(),
            ];
            $pengajuan->logs = $logs;
        }

        // Jika admin mengisi balasan chat (Masuk ke Panel Bantuan User)
        if ($request->filled('pesan')) {
            $pesan = $pengajuan->pesan ?? [];
            $pesan[] = [
                'pengirim' => Auth::user()->name ?? 'Admin Diskominsa',
                'role'     => 'admin',
                'isi'      => $request->pesan,
                'waktu'    => now()->format('d M Y, H:i')
            ];
            $pengajuan->pesan = $pesan;
        }

        $pengajuan->save();

        return back()->with('sukses', 'Progres layanan dan pesan berhasil diperbarui!');
    }

    // 4. Menampilkan halaman khusus tabel Web Desa
    public function webDesa()
    {
        // Ambil data pengajuan yang JENIS LAYANAN-nya hanya "Pembuatan Web Desa"
        $pengajuans = Pengajuan::where('jenis_layanan', 'Pembuatan Web Desa')
                        ->latest()
                        ->get(); 
                        
        return view('admin.web-desa.index', compact('pengajuans'));
    }
}