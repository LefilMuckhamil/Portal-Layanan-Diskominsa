<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanBantuan extends Model
{
    protected $table = 'pengajuan_bantuan';

    protected $fillable = [
        'pengajuan_id',
        'kategori_bantuan_id',
        'email_reset',
        'deskripsi_kendala',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBantuan::class, 'kategori_bantuan_id');
    }

    public function fieldLabels(): array
    {
        return [
            'Kategori Kendala' => $this->kategori?->nama_kategori,
            'Email yang Direset' => $this->email_reset,
            'Deskripsi Kendala' => $this->deskripsi_kendala,
        ];
    }
}
