<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanTte extends Model
{
    protected $table = 'pengajuan_tte';

    protected $fillable = [
        'pengajuan_id',
        'nik',
        'email',
        'alamat',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function fieldLabels(): array
    {
        return [
            'NIK' => $this->nik,
            'Email' => $this->email,
            'Alamat Domisili' => $this->alamat,
        ];
    }
}
