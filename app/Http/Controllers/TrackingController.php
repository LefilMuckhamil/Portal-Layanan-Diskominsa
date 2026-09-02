<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TrackingController extends Controller
{
    public function track($nomor_tiket)
    {
        try {
            $cleanKey = trim(str_replace('#', '', $nomor_tiket));

            if (empty($cleanKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor tiket tidak valid.',
                ], 400);
            }

            // Normalisasi selalu ke format '#KODE-...' agar query langsung memanfaatkan index unik nomor_tiket.
            $queryKey = '#'.strtoupper($cleanKey);

            $pengajuan = Pengajuan::with(['layanan', 'riwayatStatus'])->where('nomor_tiket', $queryKey)->first();

            if (! $pengajuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor tiket #'.$cleanKey.' tidak ditemukan.',
                ], 404);
            }

            $riwayatData = [];

            $logs = $pengajuan->riwayatStatus->reverse()->values();
            if ($logs->count() > 0) {
                foreach ($logs as $log) {
                    $waktuFormatted = $log->created_at
                        ? $log->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i').' WIB'
                        : '-';

                    $riwayatData[] = [
                        'waktu' => $waktuFormatted,
                        'judul' => 'Status: '.ucfirst($log->status ?? 'Proses'),
                        // Catatan internal admin TIDAK dikembalikan ke publik.
                        'pesan_admin' => null,
                    ];
                }
            }

            if (empty($riwayatData)) {
                $riwayatData[] = [
                    'waktu' => $pengajuan->created_at ? Carbon::parse($pengajuan->created_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i').' WIB' : '-',
                    'judul' => 'Pengajuan Dibuat',
                    'pesan_admin' => 'Pengajuan berhasil masuk ke sistem portal terpadu Diskominsa.',
                ];
            }

            $waktuStatus = '-';
            if (count($riwayatData) > 0 && ! empty($riwayatData[0]['waktu'])) {
                $waktuStatus = $riwayatData[0]['waktu'];
            } elseif ($pengajuan->updated_at) {
                $waktuStatus = Carbon::parse($pengajuan->updated_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i').' WIB';
            } elseif ($pengajuan->created_at) {
                $waktuStatus = Carbon::parse($pengajuan->created_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i').' WIB';
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'nomor_tiket' => $pengajuan->nomor_tiket,
                    'layanan' => $pengajuan->layanan?->nama ?? '-',
                    'status' => $pengajuan->status,
                    'waktu_status' => $waktuStatus,
                    'riwayat' => $riwayatData,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal melacak tiket: '.$e->getMessage(), [
                'nomor_tiket' => $nomor_tiket ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kendala saat memproses pelacakan tiket.',
            ], 500);
        }
    }
}
