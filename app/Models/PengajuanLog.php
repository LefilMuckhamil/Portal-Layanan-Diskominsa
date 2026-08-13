<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}