<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model; 

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

    /**
     * Accessor untuk meng-generate nomor tiket cantik secara otomatis.
     * Tidak disimpan di database, murni untuk tampilan visual.
     */
    public function getNomorTiketAttribute()
    {
        $kodeLayanan = match($this->jenis_layanan) {
            'Pembuatan Website'     => 'WEB',
            'Pembuatan Email Resmi' => 'EML',
            'Layanan TTE'           => 'TTE',
            'Cloud Government'      => 'CLD',
            'Reset Password'  => 'HLP'
        };

        // Ambil 5 karakter terakhir dari ID, ubah ke huruf besar
        $idUnik = strtoupper(substr($this->id, -5));

        return '#' . $kodeLayanan . '-' . $idUnik;
    }

}