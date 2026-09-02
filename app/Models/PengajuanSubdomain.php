<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSubdomain extends Model
{
    protected $table = 'pengajuan_subdomain';

    protected $fillable = [
        'pengajuan_id',
        'email_google',
        'domain',
        'ip_address',
        'nama_aplikasi',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function fieldLabels(): array
    {
        return [
            'Email Alternatif' => $this->email_google,
            'Subdomain' => $this->domain,
            'IP Address' => $this->ip_address,
            'Nama Aplikasi' => $this->nama_aplikasi,
        ];
    }
}
