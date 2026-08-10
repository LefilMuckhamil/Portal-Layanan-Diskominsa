<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

// PERUBAHAN PENTING: Gunakan Authenticatable milik MongoDB
use MongoDB\Laravel\Auth\User as Authenticatable;

// 👇 SEMUA KOLOM DARI FORM SUDAH DITAMBAHKAN DI SINI 👇
#[Fillable([
    'name', 
    'email', 
    'password', 
    'role', 
    'nip', 
    'unit_kerja', 
    'jabatan', 
    'no_hp',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}