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

        abort_if(! $path || ! Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path);
    }
}
