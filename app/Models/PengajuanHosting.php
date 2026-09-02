<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanHosting extends Model
{
    protected $table = 'pengajuan_hosting';

    protected $fillable = [
        'pengajuan_id',
        'email_google',
        'nama_aplikasi',
        'runtime',
        'database_type',
        'storage_quota',
        'domain_terkait',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function fieldLabels(): array
    {
        return [
            'Email Alternatif' => $this->email_google,
            'Nama Aplikasi' => $this->nama_aplikasi,
            'Bahasa Pemrograman' => $this->runtime,
            'Database' => $this->database_type,
            'Kebutuhan Storage' => $this->storage_quota,
            'Domain Terkait' => $this->domain_terkait,
        ];
    }
}
