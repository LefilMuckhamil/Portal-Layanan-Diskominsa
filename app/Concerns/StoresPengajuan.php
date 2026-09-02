<?php

namespace App\Concerns;

use App\Models\Layanan;
use App\Models\Pengajuan;
use App\Models\PengajuanBantuan;
use App\Models\PengajuanCloud;
use App\Models\PengajuanEmail;
use App\Models\PengajuanHosting;
use App\Models\PengajuanPemohon;
use App\Models\PengajuanSubdomain;
use App\Models\PengajuanTte;
use App\Models\PengajuanWebsite;
use Illuminate\Support\Facades\DB;

trait StoresPengajuan
{
    /**
     * Simpan pengajuan baru beserta profil pemohon dan detail layanan 1:1
     * dalam satu transaksi. Pemanggil tetap menyimpan berkas terlebih dahulu.
     */
    protected function simpanPengajuan(string $layananKode, array $data, ?string $filePendukung = null, ?int $userId = null): Pengajuan
    {
        $pemohonKeys = ['nama', 'nip', 'no_hp', 'email_dinas', 'instansi', 'jabatan'];

        $detailKeys = match ($layananKode) {
            'WEB' => ['email_google', 'nama_pimpinan', 'nama_website'],
            'SUB' => ['email_google', 'domain', 'ip_address', 'nama_aplikasi'],
            'HST' => ['email_google', 'nama_aplikasi', 'runtime', 'database_type', 'storage_quota', 'domain_terkait'],
            'EML' => ['usulan_email'],
            'TTE' => ['nik', 'email', 'alamat'],
            'CLD' => ['kapasitas', 'email'],
            'HLP' => ['kategori_bantuan_id', 'email_reset', 'deskripsi_kendala'],
            default => [],
        };

        $detailModel = match ($layananKode) {
            'WEB' => PengajuanWebsite::class,
            'SUB' => PengajuanSubdomain::class,
            'HST' => PengajuanHosting::class,
            'EML' => PengajuanEmail::class,
            'TTE' => PengajuanTte::class,
            'CLD' => PengajuanCloud::class,
            'HLP' => PengajuanBantuan::class,
            default => null,
        };

        $pemohon = collect($data)->only($pemohonKeys)->filter(fn ($v) => $v !== null && $v !== '')->all();
        $detail = collect($data)->only($detailKeys)->filter(fn ($v) => $v !== null && $v !== '')->all();

        return DB::transaction(function () use ($layananKode, $pemohon, $detail, $detailModel, $filePendukung, $userId) {
            $pengajuan = Pengajuan::create([
                'user_id' => $userId,
                'layanan_id' => Layanan::idKode($layananKode),
                'file_pendukung' => $filePendukung,
            ]);

            if (count($pemohon) > 0) {
                PengajuanPemohon::create(array_merge([
                    'pengajuan_id' => $pengajuan->id,
                    'user_id' => $userId,
                ], $pemohon));
            }

            if ($detailModel && count($detail) > 0) {
                $detailModel::create(array_merge([
                    'pengajuan_id' => $pengajuan->id,
                ], $detail));
            }

            return $pengajuan;
        });
    }
}
