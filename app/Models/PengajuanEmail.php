<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanEmail extends Model
{
    protected $table = 'pengajuan_email';

    protected $fillable = [
        'pengajuan_id',
        'usulan_email',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function fieldLabels(): array
    {
        return [
            'Usulan Email' => $this->usulan_email,
        ];
    }
}
