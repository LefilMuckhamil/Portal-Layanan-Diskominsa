<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    public function store(Request $request, $jenis)
    {
        // 1. Deteksi Layanan & Kode Awalan Tiket
        $jenisLayanan = match($jenis) {
            'website' => 'Pembuatan Website',
            'email'   => 'Pembuatan Email Resmi',
            'tte'     => 'Layanan TTE',
            'cloud'   => 'Cloud Government',
            'bantuan' => 'Reset Password / OTP',
            default   => 'Layanan Umum'
        };

        $kodeTiket = match($jenis) {
            'website' => 'WEB',
            'email'   => 'EML',
            'tte'     => 'TTE',
            'cloud'   => 'CLD',
            'bantuan' => 'HLP',
            default   => 'REQ'
        };

        // 2. Ambil Input & File
        $dataPengajuan = $request->input('data_pengajuan', []);
        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $filePath = $file->storeAs('file_pendukung', time() . '_' . $file->getClientOriginalName(), 'public');
        }

        // 3. Simpan ke Database
        $pengajuan = Pengajuan::create([
            'user_id'        => Auth::id(),
            'jenis_layanan'  => $jenisLayanan,
            'status'         => 'Pending',
            'data_pengajuan' => $dataPengajuan,
            'file_pendukung' => $filePath,
            'logs'           => [
                [
                    'status'     => 'Pending',
                    'catatan'    => 'Pengajuan berhasil dibuat oleh pemohon.',
                    'created_at' => now()->toDateTimeString(),
                    'updated_by' => 'Sistem'
                ]
            ]
        ]);

        // 4. Generate Nomor Tiket Manual (Dijamin Muncul!)
        $nomorTiketGenerated = '#' . $kodeTiket . '-' . strtoupper(substr($pengajuan->id, -5));

        // 5. Kembalikan Pop-Up Sukses
        return back()
            ->with('sukses', "Pengajuan $jenisLayanan berhasil dikirim!")
            ->with('tiket', $nomorTiketGenerated); 
    }
}