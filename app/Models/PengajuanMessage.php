<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanMessage extends Model
{
    protected $table = 'pengajuan_messages';

    protected $fillable = [
        'pengajuan_id',
        'sender_id',
        'sender_role',
        'isi',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getRoleAttribute(): string
    {
        return $this->sender_role;
    }

    public function getPengirimAttribute(): string
    {
        if ($this->sender) {
            return $this->sender->name;
        }

        return $this->sender_role === 'admin' ? 'Admin Diskominsa' : 'Pengguna';
    }

    public function getWaktuAttribute(): string
    {
        return $this->created_at
            ? $this->created_at->format('d M Y, H:i')
            : '-';
    }
}
