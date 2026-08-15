<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrackingController extends Controller
{
    public function track($nomor_tiket)
    {
        try {
            $cleanKey = trim(str_replace('#', '', $nomor_tiket));

            if (empty($cleanKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor tiket tidak valid.'
                ], 400);
            }

            $pengajuan = Pengajuan::where('nomor_tiket', $cleanKey)->first();

            if (!$pengajuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor tiket #' . $cleanKey . ' tidak ditemukan.'
                ], 404);
            }

            $riwayatData = [];

            $logs = is_string($pengajuan->logs) ? json_decode($pengajuan->logs, true) : $pengajuan->logs;
            if (is_array($logs) && count($logs) > 0) {
                foreach ($logs as $log) {
                    $waktuFormatted = '-';
                    if (!empty($log['created_at'])) {
                        $waktuFormatted = Carbon::parse($log['created_at'])->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB';
                    } elseif (!empty($log['waktu'])) {
                        $waktuFormatted = $log['waktu'];
                    }

                    $riwayatData[] = [
                        'waktu'       => $waktuFormatted,
                        'judul'       => 'Status: ' . ucfirst($log['status'] ?? 'Proses'),
                        'pesan_admin' => $log['catatan_admin'] ?? $log['catatan'] ?? $log['pesan'] ?? null,
                    ];
                }
            }

            if (empty($riwayatData)) {
                $riwayatData[] = [
                    'waktu'       => $pengajuan->created_at ? Carbon::parse($pengajuan->created_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' : '-',
                    'judul'       => 'Pengajuan Dibuat',
                    'pesan_admin' => 'Permohonan berhasil masuk ke sistem portal terpadu Diskominsa.',
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

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}