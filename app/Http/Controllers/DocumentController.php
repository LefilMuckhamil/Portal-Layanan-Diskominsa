<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function unduh(Pengajuan $pengajuan, string $jenis)
    {
        abort_unless(auth()->user()->role === 'admin' || $pengajuan->user_id === auth()->id(), 403);

        $dataPengajuan = is_array($pengajuan->data_pengajuan)
            ? $pengajuan->data_pengajuan
            : (json_decode((string) $pengajuan->getRawOriginal('data_pengajuan') ?? '{}', true) ?: []);

        $path = $jenis === 'hasil'
            ? ($dataPengajuan['file_hasil'] ?? null)
            : $pengajuan->file_pendukung;

        abort_if(! $path, 404);

        // Legacy files live on the 'public' disk; newer ones on 'local' — check both.
        foreach (['local', 'public'] as $disk) {
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->download($path);
            }
        }

        abort(404);
    }
}
