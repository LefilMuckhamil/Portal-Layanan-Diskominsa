<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'unit_kerja',
        'jabatan',
        'no_hp',
    ];

    // status_akun/approved_at/approved_by sengaja TIDAK masuk $fillable (anti mass-assignment).
    // Status verifikasi hanya boleh diubah lewat forceFill() atau AdminUserController.
    public const STATUS_PENDING = 'pending';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_DITOLAK = 'ditolak';

    public function isStatusAktif(): bool
    {
        return $this->status_akun === self::STATUS_AKTIF;
    }

    public function isStatusPending(): bool
    {
        return $this->status_akun === self::STATUS_PENDING;
    }

    public function isStatusDitolak(): bool
    {
        return $this->status_akun === self::STATUS_DITOLAK;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approved_at' => 'datetime',
        ];
    }
}
