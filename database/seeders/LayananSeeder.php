<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanan = [
            ['kode' => 'WEB', 'nama' => 'Pembuatan Website', 'deskripsi' => 'Pembuatan website resmi instansi'],
            ['kode' => 'SUB', 'nama' => 'Pembuatan Subdomain', 'deskripsi' => 'Pembuatan subdomain .acehbaratkab.go.id'],
            ['kode' => 'HST', 'nama' => 'Pembuatan Hosting', 'deskripsi' => 'Penyediaan hosting & server aplikasi'],
            ['kode' => 'EML', 'nama' => 'Pembuatan Email Resmi', 'deskripsi' => 'Pembuatan email resmi @acehbaratkab.go.id'],
            ['kode' => 'TTE', 'nama' => 'Layanan TTE', 'deskripsi' => 'Layanan sertifikat & tanda tangan elektronik'],
            ['kode' => 'CLD', 'nama' => 'Cloud Government', 'deskripsi' => 'Penyediaan akun cloud / gov storage'],
            ['kode' => 'HLP', 'nama' => 'Pusat Bantuan', 'deskripsi' => 'Pusat bantuan & reset password'],
        ];

        foreach ($layanan as $item) {
            Layanan::updateOrCreate(
                ['kode' => $item['kode']],
                $item
            );
        }
    }
}
