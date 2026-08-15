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
        'pesan',
    ];

    protected $casts = [
        'data_pengajuan' => 'array',
        'logs' => 'array',
        'pesan' => 'array',
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
        static::creating(function (Pengajuan $pengajuan) {
            do {
                $tiket = '#'.static::kodeLayanan($pengajuan->jenis_layanan).'-'.strtoupper(bin2hex(random_bytes(4)));
            } while (static::where('nomor_tiket', $tiket)->exists());

            $pengajuan->nomor_tiket = $tiket;
        });
    }

    public static function kodeLayanan(string $jenis): string
    {
        return match ($jenis) {
            'Pembuatan Website', 'pembuatan_website' => 'WEB',
            'Pembuatan Email Resmi', 'pembuatan_email' => 'EML',
            'Layanan TTE', 'layanan_tte' => 'TTE',
            'Cloud Government', 'cloud_government' => 'CLD',
            'Reset Password', 'Pusat Bantuan' => 'HLP',
            default => throw new \InvalidArgumentException("Jenis layanan tidak dikenal: {$jenis}"),
        };
    }
}
