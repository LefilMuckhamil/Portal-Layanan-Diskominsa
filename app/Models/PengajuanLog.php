<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanLog extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_logs';

    protected $fillable = [
        'pengajuan_id',
        'status',
        'catatan_admin',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }
}
