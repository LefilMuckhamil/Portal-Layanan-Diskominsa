<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model; // <-- PENTING: Gunakan Model khusus MongoDB

class Pengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_layanan',
        'status',
        'data_pengajuan',
        'file_pendukung',
        'logs',
        'pesan'
    ];

    // Beritahu Laravel bahwa data_pengajuan adalah array/JSON
    protected $casts = [
        'data_pengajuan' => 'array',
    ];

    // Relasi ke User yang mengajukan
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}