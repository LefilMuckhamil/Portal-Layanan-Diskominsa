<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $fillable = [
        'nomor_tiket',
        'user_id',
        'jenis_layanan',
        'status',
        'data_pengajuan',
        'file_pendukung',
        'logs',
        'pesan'
    ];

    protected $casts = [
        'data_pengajuan' => 'array',
        'logs'           => 'array',
        'pesan'          => 'array',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI DISESUAIKAN DENGAN MODEL PengajuanLog
    public function riwayatStatus()
    {
        return $this->hasMany(PengajuanLog::class, 'pengajuan_id');
    }

    // PEMBUAT NOMOR TIKET OTOMATIS
    protected static function booted()
    {
        static::creating(function ($pengajuan) {
            $kode = match($pengajuan->jenis_layanan) {
                'Pembuatan Website', 'pembuatan_website'   => 'WEB',
                'Pembuatan Email Resmi', 'pembuatan_email' => 'EML',
                'Layanan TTE', 'layanan_tte'               => 'TTE',
                'Cloud Government', 'cloud_government'     => 'CLD',
                'Reset Password', 'Pusat Bantuan'          => 'HLP',
                default                                    => 'REQ'
            };

            $pengajuan->nomor_tiket = '#' . $kode . '-' . strtoupper(substr(md5(uniqid()), 0, 5));
        });
    }
}