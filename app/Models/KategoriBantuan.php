<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBantuan extends Model
{
    protected $table = 'kategori_bantuan';

    protected $fillable = [
        'nama_kategori',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
