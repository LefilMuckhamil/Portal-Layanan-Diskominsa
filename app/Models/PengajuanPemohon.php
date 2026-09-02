<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPemohon extends Model
{
    protected $table = 'pengajuan_pemohon';

    protected $fillable = [
        'pengajuan_id',
        'user_id',
        'nama',
        'nip',
        'no_hp',
        'email_dinas',
        'instansi',
        'jabatan',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fieldLabels(): array
    {
        return [
            'Nama Lengkap' => $this->nama,
            'NIP' => $this->nip,
            'Nomor HP/WhatsApp' => $this->no_hp,
            'Email Dinas' => $this->email_dinas,
            'Instansi / Unit Kerja' => $this->instansi,
            'Jabatan' => $this->jabatan,
        ];
    }
}
