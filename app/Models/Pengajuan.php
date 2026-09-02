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
        'layanan_id',
        'status',
        'file_pendukung',
        'file_hasil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function pemohon()
    {
        return $this->hasOne(PengajuanPemohon::class, 'pengajuan_id');
    }

    public function website()
    {
        return $this->hasOne(PengajuanWebsite::class, 'pengajuan_id');
    }

    public function subdomain()
    {
        return $this->hasOne(PengajuanSubdomain::class, 'pengajuan_id');
    }

    public function hosting()
    {
        return $this->hasOne(PengajuanHosting::class, 'pengajuan_id');
    }

    public function email()
    {
        return $this->hasOne(PengajuanEmail::class, 'pengajuan_id');
    }

    public function tte()
    {
        return $this->hasOne(PengajuanTte::class, 'pengajuan_id');
    }

    public function cloud()
    {
        return $this->hasOne(PengajuanCloud::class, 'pengajuan_id');
    }

    public function bantuan()
    {
        return $this->hasOne(PengajuanBantuan::class, 'pengajuan_id');
    }

    public function messages()
    {
        return $this->hasMany(PengajuanMessage::class, 'pengajuan_id');
    }

    // RELASI DISESUAIKAN DENGAN MODEL PengajuanLog
    public function riwayatStatus()
    {
        return $this->hasMany(PengajuanLog::class, 'pengajuan_id');
    }

    /**
     * Model detail 1:1 sesuai layanan (untuk tampilan umum/detail).
     */
    public function detail(): ?Model
    {
        return match ($this->layanan?->kode) {
            'WEB' => $this->website,
            'SUB' => $this->subdomain,
            'HST' => $this->hosting,
            'EML' => $this->email,
            'TTE' => $this->tte,
            'CLD' => $this->cloud,
            'HLP' => $this->bantuan,
            default => null,
        };
    }

    /**
     * Pasangan label -> nilai untuk tampilan grid halaman detail pengguna.
     */
    public function detailFields(): array
    {
        $fields = [];

        if ($this->pemohon) {
            foreach ($this->pemohon->fieldLabels() as $label => $nilai) {
                if ($nilai !== null && $nilai !== '') {
                    $fields[$label] = $nilai;
                }
            }
        }

        $detail = $this->detail();
        if ($detail && method_exists($detail, 'fieldLabels')) {
            foreach ($detail->fieldLabels() as $label => $nilai) {
                if ($nilai !== null && $nilai !== '') {
                    $fields[$label] = $nilai;
                }
            }
        }

        return $fields;
    }

    /**
     * Bentuk data model relasional menjadi array ber-format lama
     * (dipakai panel admin agar tidak menyimpan data ke JSON).
     */
    public function dataForm(): array
    {
        $pemohon = $this->pemohon;

        $data = [
            'nama' => $pemohon?->nama,
            'nip' => $pemohon?->nip,
            'no_hp' => $pemohon?->no_hp,
            'email_dinas' => $pemohon?->email_dinas,
            'instansi' => $pemohon?->instansi,
            'jabatan' => $pemohon?->jabatan,
        ];

        $detail = $this->detail();
        if ($detail) {
            foreach ($detail->getFillable() as $field) {
                if ($field === 'pengajuan_id') {
                    continue;
                }
                $data[$field] = $detail->{$field};
            }

            if ($this->bantuan && $this->bantuan->kategori) {
                $data['kategori'] = $this->bantuan->kategori->nama_kategori;
            }
        }

        return $data;
    }

    // PEMBUAT NOMOR TIKET OTOMATIS
    protected static function booted()
    {
        static::creating(function (Pengajuan $pengajuan) {
            if (is_null($pengajuan->status)) {
                $pengajuan->status = 'Pending';
            }

            $kode = optional($pengajuan->layanan)->kode ?? 'TIK';

            do {
                $tiket = '#'.$kode.'-'.strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
            } while (static::where('nomor_tiket', $tiket)->exists());

            $pengajuan->nomor_tiket = $tiket;
        });
    }
}
