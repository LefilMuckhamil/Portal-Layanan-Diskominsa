<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
    ];

    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class, 'layanan_id');
    }

    public static function idKode(string $kode): ?int
    {
        $id = static::where('kode', $kode)->value('id');

        return $id !== null ? (int) $id : null;
    }
}
