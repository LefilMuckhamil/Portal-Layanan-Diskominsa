<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanCloud extends Model
{
    protected $table = 'pengajuan_cloud';

    protected $fillable = [
        'pengajuan_id',
        'kapasitas',
        'email',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function fieldLabels(): array
    {
        return [
            'Kapasitas Penyimpanan' => $this->kapasitas,
            'Email Aktif' => $this->email,
        ];
    }
}
