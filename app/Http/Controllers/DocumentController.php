<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    private const JENIS_SAH = ['hasil', 'lampiran', 'pendukung'];

    private const PREFIX_LAMPIRAN = [
        'dokumen_pengajuan/',
        'pengajuan/',
        'file_pendukung/',
    ];

    public function unduh(string $pengajuanParam, string $jenis)
    {
        abort_unless(auth()->check(), 401);

        $pengajuan = is_numeric($pengajuanParam)
            ? Pengajuan::find($pengajuanParam)
            : Pengajuan::where('nomor_tiket', $pengajuanParam)->first();

        abort_if(! $pengajuan, 404);

        abort_unless(
            auth()->user()->role === 'admin' || $pengajuan->user_id === auth()->id(),
            403
        );

        abort_if(! in_array($jenis, self::JENIS_SAH, true), 404);

        if ($jenis === 'hasil') {
            $path = $pengajuan->file_hasil ?? null;
        } else {
            $path = $pengajuan->file_pendukung;
        }

        abort_if(! is_string($path) || trim($path) === '', 404, 'File tidak ditemukan.');

        abort_if(
            str_starts_with($path, '/') || str_contains($path, '\\') || str_contains($path, '..'),
            404
        );

        $prefixes = $jenis === 'hasil' ? ['dokumen_hasil/'] : self::PREFIX_LAMPIRAN;
        $dalamAllowlist = false;
        foreach ($prefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $dalamAllowlist = true;
                break;
            }
        }
        abort_if(! $dalamAllowlist, 404);

        abort_if(
            ! Storage::disk('local')->exists($path),
            404,
            'File tidak ditemukan di storage server.'
        );

        $namaFile = ($jenis === 'hasil' ? 'Hasil_' : 'Surat_Permohonan_')
            .preg_replace('/[^A-Za-z0-9_-]/', '_', $pengajuan->nomor_tiket)
            .'.pdf';

        return Storage::disk('local')->response($path, $namaFile, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$namaFile.'"',
        ]);
    }
}
