<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function track($nomor_tiket)
    {
        $cleanKey = trim(str_replace('#', '', $nomor_tiket));

        if (empty($cleanKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor tiket tidak valid.'
            ], 400);
        }

        $pengajuan = Pengajuan::with(['riwayatStatus' => function ($q) {
            $q->latest();
        }])
        ->where('nomor_tiket', 'like', '%' . $cleanKey . '%')
        ->first();

        if (!$pengajuan) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor tiket #' . $cleanKey . ' tidak ditemukan.'
            ], 404);
        }

        $riwayatData = [];

        // 1. Ambil riwayat dari relasi riwayatStatus
        if ($pengajuan->riwayatStatus && $pengajuan->riwayatStatus->count() > 0) {
            foreach ($pengajuan->riwayatStatus as $item) {
                $riwayatData[] = [
                    'waktu'       => $item->created_at ? $item->created_at->format('d M Y, H:i') : '-',
                    'judul'       => 'Status: ' . ucfirst($item->status),
                    'pesan_admin' => $item->catatan ?? $item->keterangan ?? null,
                ];
            }
        }

        // 2. Ambil riwayat dari kolom logs (JSON) jika ada
        if (is_array($pengajuan->logs) && count($pengajuan->logs) > 0) {
            foreach ($pengajuan->logs as $log) {
                $riwayatData[] = [
                    'waktu'       => $log['waktu'] ?? ($log['created_at'] ?? '-'),
                    'judul'       => 'Status: ' . ucfirst($log['status'] ?? 'Proses'),
                    'pesan_admin' => $log['catatan'] ?? $log['pesan'] ?? null,
                ];
            }
        }

        // 3. Fallback jika riwayat belum ada
        if (empty($riwayatData)) {
            $riwayatData[] = [
                'waktu'       => $pengajuan->created_at ? $pengajuan->created_at->format('d M Y, H:i') : '-',
                'judul'       => 'Pengajuan Dibuat',
                'pesan_admin' => 'Permohonan berhasil masuk ke sistem portal terpadu.',
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'nomor_tiket' => $pengajuan->nomor_tiket,
                'layanan'     => $pengajuan->jenis_layanan,
                'status'      => $pengajuan->status,
                'riwayat'     => $riwayatData
            ]
        ]);
    }
}