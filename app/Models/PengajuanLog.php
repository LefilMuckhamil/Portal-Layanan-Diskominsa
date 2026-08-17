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
        'admin_id',
        'status_lama',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
