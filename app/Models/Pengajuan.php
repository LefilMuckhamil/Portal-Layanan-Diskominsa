<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $fillable = [
        'user_id',
        'jenis_layanan',
        'data_pengajuan',
        'file_pendukung',
        'logs',
        'pesan',
    ];

    protected $hidden = [
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
            if (is_null($pengajuan->status)) {
                $pengajuan->status = 'Pending';
            }

            do {
                $tiket = '#'.static::kodeLayanan($pengajuan->jenis_layanan).'-'.strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
            } while (static::where('nomor_tiket', $tiket)->exists());

            $pengajuan->nomor_tiket = $tiket;
        });
    }

    public static function kodeLayanan(string $jenis): string
    {
        return match ($jenis) {
            'Pembuatan Website', 'pembuatan_website' => 'WEB',
            'Pembuatan Subdomain', 'pembuatan_subdomain' => 'SUB',
            'Pembuatan Hosting', 'pembuatan_hosting' => 'HST',
            'Pembuatan Email Resmi', 'pembuatan_email' => 'EML',
            'Layanan TTE', 'layanan_tte' => 'TTE',
            'Cloud Government', 'cloud_government' => 'CLD',
            'Reset Password', 'Pusat Bantuan' => 'HLP',
            default => throw new \InvalidArgumentException("Jenis layanan tidak dikenal: {$jenis}"),
        };
    }
}
