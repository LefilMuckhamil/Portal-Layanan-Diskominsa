<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    private const JENIS_SAH = ['hasil', 'lampiran'];

    private const PREFIX_LAMPIRAN = [
        'dokumen_pengajuan/',
        'pengajuan/',
        'file_pendukung/',
    ];

    public function unduh(Pengajuan $pengajuan, string $jenis)
    {
        abort_unless(auth()->user()->role === 'admin' || $pengajuan->user_id === auth()->id(), 403);

        abort_if(! in_array($jenis, self::JENIS_SAH, true), 404);

        $dataPengajuan = is_array($pengajuan->data_pengajuan)
            ? $pengajuan->data_pengajuan
            : (json_decode((string) $pengajuan->getRawOriginal('data_pengajuan') ?? '{}', true) ?: []);

        $path = $jenis === 'hasil'
            ? ($dataPengajuan['file_hasil'] ?? null)
            : $pengajuan->file_pendukung;

        abort_if(! is_string($path) || trim($path) === '', 404);

        // Cegah path traversal / path poisoning.
        abort_if(str_starts_with($path, '/') || str_contains($path, '\\') || str_contains($path, '..'), 404);

        // Allowlist direktori yang sah sesuai jenis berkas.
        $prefixes = $jenis === 'hasil' ? ['dokumen_hasil/'] : self::PREFIX_LAMPIRAN;
        $dalamAllowlist = false;
        foreach ($prefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $dalamAllowlist = true;
                break;
            }
        }
        abort_if(! $dalamAllowlist, 404);

        // Dokumen hanya dilayani dari disk privat 'local'.
        abort_if(! Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path);
    }
}
