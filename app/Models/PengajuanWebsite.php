<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanWebsite extends Model
{
    protected $table = 'pengajuan_website';

    protected $fillable = [
        'pengajuan_id',
        'email_google',
        'nama_pimpinan',
        'nama_website',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function fieldLabels(): array
    {
        return [
            'Email Alternatif' => $this->email_google,
            'Nama Pimpinan' => $this->nama_pimpinan,
            'Nama Website' => $this->nama_website,
        ];
    }
}
