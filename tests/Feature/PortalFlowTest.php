<?php

namespace Tests\Feature;

use App\Models\KategoriBantuan;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortalFlowTest extends TestCase
{
    use RefreshDatabase;

    private function buatUser(string $role = 'user', string $email = 'pegawai@acehbaratkab.go.id', array $attrs = []): User
    {
        $user = User::create(array_merge([
            'name' => 'Pegawai Diskominsa',
            'email' => $email,
            'password' => Hash::make('password123'),
            'nip' => fake()->unique()->numerify(str_repeat('#', 18)),
            'unit_kerja' => 'Dinas Kominfo',
            'jabatan' => 'Fungsional',
            'no_hp' => '08'.fake()->unique()->numerify(str_repeat('#', 10)),
        ], $attrs));

        if ($role === 'admin') {
            $user->forceFill(['role' => 'admin'])->save();
        }

        return $user;
    }

    public function test_user_biasa_tidak_bisa_akses_rute_admin(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Anda tidak memiliki hak akses administrator.');
    }

    public function test_admin_dengan_role_valid_dapat_mengakses_dashboard_admin(): void
    {
        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_guest_dialihkan_ke_halaman_login_saat_akses_rute_admin(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_user_login_berhasil_membuat_pengajuan_website_berstatus_pending(): void
    {
        Storage::fake('local');

        $user = $this->buatUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect('/');

        $response = $this->post(route('pengajuan.website.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'email_dinas' => 'pegawai@acehbaratkab.go.id',
                'email_google' => 'pegawai@gmail.com',
                'no_hp' => '081234567890',
                'instansi' => 'Dinas Kesehatan',
                'jabatan' => 'Pranata Komputer',
                'nama_pimpinan' => 'dr. H. A. Rahman',
                'nama_website' => 'Website Resmi Dinas Kesehatan',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sukses');
        $response->assertSessionHas('nomor_tiket');

        $this->assertDatabaseHas('pengajuan', [
            'user_id' => $user->id,
            'jenis_layanan' => 'Pembuatan Website',
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#WEB-', $pengajuan->nomor_tiket);
        $this->assertSame('Website Resmi Dinas Kesehatan', $pengajuan->data_pengajuan['nama_website']);
        Storage::disk('local')->assertExists($pengajuan->file_pendukung);
    }

    public function test_user_login_berhasil_membuat_pengajuan_subdomain_berstatus_pending(): void
    {
        Storage::fake('local');

        $user = $this->buatUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect('/');

        $response = $this->post(route('pengajuan.subdomain.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'email_dinas' => 'pegawai@acehbaratkab.go.id',
                'email_google' => 'pegawai@gmail.com',
                'no_hp' => '081234567890',
                'instansi' => 'Dinas Kesehatan',
                'jabatan' => 'Pranata Komputer',
                'domain' => 'dinkes',
                'ip_address' => '103.10.10.5',
                'nama_aplikasi' => 'SIAP - Sistem Informasi App',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sukses');
        $response->assertSessionHas('nomor_tiket');

        $this->assertDatabaseHas('pengajuan', [
            'user_id' => $user->id,
            'jenis_layanan' => 'Pembuatan Subdomain',
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#SUB-', $pengajuan->nomor_tiket);
        $this->assertSame('dinkes.acehbaratkab.go.id', $pengajuan->data_pengajuan['domain']);
        Storage::disk('local')->assertExists($pengajuan->file_pendukung);
    }

    public function test_user_login_berhasil_membuat_pengajuan_hosting_berstatus_pending(): void
    {
        Storage::fake('local');

        $user = $this->buatUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect('/');

        $response = $this->post(route('pengajuan.hosting.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'email_dinas' => 'pegawai@acehbaratkab.go.id',
                'email_google' => 'pegawai@gmail.com',
                'no_hp' => '081234567890',
                'instansi' => 'Dinas Kesehatan',
                'jabatan' => 'Pranata Komputer',
                'nama_aplikasi' => 'SIAP - Sistem Informasi App',
                'runtime' => 'PHP 8.2',
                'database_type' => 'MySQL/MariaDB',
                'storage_quota' => '5 GB',
                'domain_terkait' => 'dinkes.acehbaratkab.go.id',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sukses');
        $response->assertSessionHas('nomor_tiket');

        $this->assertDatabaseHas('pengajuan', [
            'user_id' => $user->id,
            'jenis_layanan' => 'Pembuatan Hosting',
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#HST-', $pengajuan->nomor_tiket);
        Storage::disk('local')->assertExists($pengajuan->file_pendukung);
    }

    public function test_user_login_berhasil_membuat_pengajuan_cloud_berstatus_pending(): void
    {
        Storage::fake('local');

        $user = $this->buatUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect('/');

        $response = $this->post(route('pengajuan.cloud.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'instansi' => 'Dinas Kesehatan',
                'no_hp' => '081234567890',
                'email' => 'pegawai@gmail.com',
                'kapasitas' => '10GB',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sukses');
        $response->assertSessionHas('nomor_tiket');

        $this->assertDatabaseHas('pengajuan', [
            'user_id' => $user->id,
            'jenis_layanan' => 'Cloud Government',
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#CLD-', $pengajuan->nomor_tiket);
        $this->assertSame('10GB', $pengajuan->data_pengajuan['kapasitas']);
        $this->assertSame('6281234567890', $pengajuan->data_pengajuan['no_hp']);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\.pdf$/', basename($pengajuan->file_pendukung));
        Storage::disk('local')->assertExists($pengajuan->file_pendukung);
    }

    public function test_pengajuan_cloud_ditolak_saat_kapasitas_melebihi_20_karakter(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('pengajuan.cloud.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'instansi' => 'Dinas Kesehatan',
                'no_hp' => '081234567890',
                'email' => 'pegawai@gmail.com',
                'kapasitas' => str_repeat('A', 21),
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.kapasitas');
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_pengajuan_cloud_ditolak_saat_no_hp_tidak_valid(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('pengajuan.cloud.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'instansi' => 'Dinas Kesehatan',
                'no_hp' => '081234',
                'email' => 'pegawai@gmail.com',
                'kapasitas' => '10GB',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.no_hp');
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_user_login_berhasil_membuat_pengajuan_email_berstatus_pending(): void
    {
        Storage::fake('local');

        $user = $this->buatUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect('/');

        $response = $this->post(route('pengajuan.email.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'instansi' => 'Dinas Kesehatan',
                'no_hp' => '081234567890',
                'usulan_email' => 'pegawai.diskom',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sukses');
        $response->assertSessionHas('nomor_tiket');

        $this->assertDatabaseHas('pengajuan', [
            'user_id' => $user->id,
            'jenis_layanan' => 'Pembuatan Email Resmi',
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#EML-', $pengajuan->nomor_tiket);
        $this->assertSame('pegawai.diskom@acehbaratkab.go.id', $pengajuan->data_pengajuan['usulan_email']);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\.pdf$/', basename($pengajuan->file_pendukung));
        Storage::disk('local')->assertExists($pengajuan->file_pendukung);
    }

    public function test_pengajuan_email_ditolak_saat_usulan_email_mengandung_karakter_terlarang(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('pengajuan.email.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'instansi' => 'Dinas Kesehatan',
                'no_hp' => '081234567890',
                'usulan_email' => 'pegawai@evil.com',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.usulan_email');
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_pengajuan_email_ditolak_saat_no_hp_tidak_valid(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('pengajuan.email.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'instansi' => 'Dinas Kesehatan',
                'no_hp' => '0812',
                'usulan_email' => 'pegawai.diskom',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.no_hp');
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_user_lain_tidak_bisa_mengunduh_file_pengajuan_milik_user_pertama(): void
    {
        Storage::fake('local');

        $pemilik = $this->buatUser('user', 'pemilik@acehbaratkab.go.id');
        $penyerang = $this->buatUser('user', 'penyerang@acehbaratkab.go.id');

        Storage::disk('local')->put('dokumen_pengajuan/website/dokumen-rahasia.pdf', 'isi-pdf');

        $pengajuan = Pengajuan::create([
            'user_id' => $pemilik->id,
            'jenis_layanan' => 'Pembuatan Website',
            'status' => 'Pending',
            'data_pengajuan' => ['nama' => 'Pegawai Diskominsa'],
            'file_pendukung' => 'dokumen_pengajuan/website/dokumen-rahasia.pdf',
        ]);

        $response = $this->actingAs($penyerang)->get(route('dokumen.unduh', [
            'pengajuan' => $pengajuan->id,
            'jenis' => 'lampiran',
        ]));

        $response->assertForbidden();

        $this->actingAs($pemilik)->get(route('dokumen.unduh', [
            'pengajuan' => $pengajuan->id,
            'jenis' => 'lampiran',
        ]))->assertOk();
    }

    public function test_user_login_berhasil_membuat_pengajuan_tte_berstatus_pending(): void
    {
        Storage::fake('local');

        $user = $this->buatUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect('/');

        $response = $this->post(route('pengajuan.tte.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'nik' => '1108070101010001',
                'instansi' => 'Dinas Kesehatan',
                'no_hp' => '081234567890',
                'email' => 'pegawai@gmail.com',
                'alamat' => 'Jl. Teuku Umar No. 1, Meulaboh',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sukses');
        $response->assertSessionHas('nomor_tiket');

        $this->assertDatabaseHas('pengajuan', [
            'user_id' => $user->id,
            'jenis_layanan' => 'Layanan TTE',
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#TTE-', $pengajuan->nomor_tiket);
        $this->assertSame('1108070101010001', $pengajuan->data_pengajuan['nik']);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\.pdf$/', basename($pengajuan->file_pendukung));
        Storage::disk('local')->assertExists($pengajuan->file_pendukung);
    }

    public function test_pengajuan_tte_ditolak_saat_nik_tidak_berjumlah_16_digit(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('pengajuan.tte.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'nik' => '11080701010100',
                'instansi' => 'Dinas Kesehatan',
                'no_hp' => '081234567890',
                'email' => 'pegawai@gmail.com',
                'alamat' => 'Jl. Teuku Umar No. 1, Meulaboh',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.nik');
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_tracking_tiket_publik_mengembalikan_200_dan_data_json(): void
    {
        $user = $this->buatUser();

        $pengajuan = Pengajuan::create([
            'user_id' => $user->id,
            'jenis_layanan' => 'Layanan TTE',
            'data_pengajuan' => ['nama' => 'Pegawai Diskominsa'],
            'logs' => [
                ['status' => 'Pending', 'catatan' => 'Pengajuan diterima', 'created_at' => now()->subDay()->toDateTimeString()],
                ['status' => 'Proses', 'catatan' => 'Sedang diverifikasi', 'created_at' => now()->toDateTimeString()],
            ],
        ]);
        $pengajuan->forceFill(['status' => 'Proses'])->save();

        $response = $this->get(route('track.tiket', ['nomor_tiket' => rawurlencode($pengajuan->nomor_tiket)]));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonPath('data.nomor_tiket', $pengajuan->nomor_tiket);
        $response->assertJsonPath('data.layanan', 'Layanan TTE');
        $response->assertJsonPath('data.status', 'Proses');
        $response->assertJsonCount(2, 'data.riwayat');
    }

    public function test_tracking_tiket_tetap_ditemukan_walaupun_diketik_tanpa_karakter_pagar(): void
    {
        $user = $this->buatUser();

        $pengajuan = Pengajuan::create([
            'user_id' => $user->id,
            'jenis_layanan' => 'Pembuatan Website',
            'status' => 'Pending',
            'data_pengajuan' => ['nama' => 'Pegawai Diskominsa'],
        ]);

        $nomorTanpaPagar = str_replace('#', '', $pengajuan->nomor_tiket);

        $response = $this->get(route('track.tiket', ['nomor_tiket' => $nomorTanpaPagar]));

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.nomor_tiket', $pengajuan->nomor_tiket);
    }

    public function test_tracking_tiket_yang_tidak_ada_mengembalikan_404(): void
    {
        $this->get(route('track.tiket', ['nomor_tiket' => 'WEB-TIDAKADA']))
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_permintaan_reset_sandi_ditolak_saat_no_wa_bukan_milik_akun(): void
    {
        $this->buatUser('user', 'korban@acehbaratkab.go.id');

        $response = $this->post(route('password.email'), [
            'email' => 'korban@acehbaratkab.go.id',
            'phone' => '081399998888',
        ]);

        $response->assertSessionHasErrors('phone');
        $this->assertDatabaseMissing('password_reset_requests', [
            'email_or_nip' => 'korban@acehbaratkab.go.id',
        ]);
    }

    public function test_permintaan_reset_sandi_ditolak_saat_akun_tidak_ditemukan(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'tidakada@acehbaratkab.go.id',
            'phone' => '081234567890',
        ]);

        // Mitigasi account enumeration: respons sukses semu, bukan error.
        $response->assertSessionHas('sukses');
        $this->assertDatabaseCount('password_reset_requests', 0);
    }

    public function test_permintaan_reset_sandi_ditolak_saat_masih_ada_permohonan_pending(): void
    {
        $this->buatUser('user', 'korban@acehbaratkab.go.id', ['no_hp' => '081234567890']);

        $this->post(route('password.email'), [
            'email' => 'korban@acehbaratkab.go.id',
            'phone' => '081234567890',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'korban@acehbaratkab.go.id',
            'phone' => '081234567890',
        ]);

        $response->assertSessionHas('warning');
        $this->assertDatabaseCount('password_reset_requests', 1);
    }

    public function test_permintaan_reset_sandi_berhasil_dengan_no_wa_yang_terdaftar(): void
    {
        $user = $this->buatUser('user', 'korban@acehbaratkab.go.id', ['no_hp' => '081234567890']);

        $response = $this->post(route('password.email'), [
            'email' => 'korban@acehbaratkab.go.id',
            'phone' => '081234567890',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertDatabaseHas('password_reset_requests', [
            'email_or_nip' => 'korban@acehbaratkab.go.id',
            'phone' => '6281234567890',
            'status' => 'pending',
        ]);

        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
    }

    public function test_admin_tidak_bisa_reset_sandi_akun_jika_no_wa_tidak_cocok(): void
    {
        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $korban = $this->buatUser('user', 'korban@acehbaratkab.go.id');

        $requestId = DB::table('password_reset_requests')->insertGetId([
            'email_or_nip' => 'korban@acehbaratkab.go.id',
            'phone' => '081399998888',
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.reset-password.process', $requestId));

        $response->assertSessionHas('error');
        $this->assertTrue(Hash::check('password123', $korban->fresh()->password));
    }

    public function test_user_a_tidak_bisa_mengirim_chat_ke_tiket_milik_user_b(): void
    {
        $pemilik = $this->buatUser('user', 'pemilik@acehbaratkab.go.id');
        $penyerang = $this->buatUser('user', 'penyerang@acehbaratkab.go.id');

        $pengajuan = Pengajuan::create([
            'user_id' => $pemilik->id,
            'jenis_layanan' => 'Pembuatan Website',
            'status' => 'Pending',
            'data_pengajuan' => ['nama' => 'Pegawai Diskominsa'],
        ]);

        $response = $this->actingAs($penyerang)->post(route('user.pengajuan.pesan', $pengajuan->id), [
            'pesan' => 'Pesan mencoba akses tiket orang lain',
        ]);

        $response->assertNotFound();

        $pengajuan->refresh();
        $this->assertEmpty($pengajuan->pesan);
    }

    public function test_user_login_berhasil_membuat_pengajuan_bantuan_berstatus_pending(): void
    {
        Storage::fake('local');

        $kategori = KategoriBantuan::create([
            'nama_kategori' => 'Reset Password Email',
            'is_active' => true,
        ]);

        $user = $this->buatUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect('/');

        $response = $this->post(route('pengajuan.bantuan.store'), [
            'data_pengajuan' => [
                'kategori_bantuan_id' => $kategori->id,
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'no_hp' => '081234567890',
                'email_reset' => 'Pegawai@gmail.com',
                'deskripsi_kendala' => 'Tidak bisa masuk email resmi.',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sukses');
        $response->assertSessionHas('nomor_tiket');

        $this->assertDatabaseHas('pengajuan', [
            'user_id' => $user->id,
            'jenis_layanan' => 'Pusat Bantuan',
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#HLP-', $pengajuan->nomor_tiket);
        $this->assertSame($kategori->id, $pengajuan->data_pengajuan['kategori_bantuan_id']);
        $this->assertSame('Reset Password Email', $pengajuan->data_pengajuan['kategori']);
        $this->assertSame('6281234567890', $pengajuan->data_pengajuan['no_hp']);
        $this->assertSame('pegawai@gmail.com', $pengajuan->data_pengajuan['email_reset']);
        $this->assertSame('Tidak bisa masuk email resmi.', $pengajuan->data_pengajuan['deskripsi_kendala']);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\.pdf$/', basename($pengajuan->file_pendukung));
        Storage::disk('local')->assertExists($pengajuan->file_pendukung);
    }

    public function test_pengajuan_bantuan_ditolak_saat_kategori_tidak_valid(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('pengajuan.bantuan.store'), [
            'data_pengajuan' => [
                'kategori_bantuan_id' => 999,
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'no_hp' => '081234567890',
                'email_reset' => 'pegawai@gmail.com',
                'deskripsi_kendala' => 'Menguji validasi kategori',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.kategori_bantuan_id');
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_pengajuan_bantuan_ditolak_saat_email_reset_tidak_valid(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('pengajuan.bantuan.store'), [
            'data_pengajuan' => [
                'kategori_bantuan_id' => KategoriBantuan::create([
                    'nama_kategori' => 'Reset OTP',
                    'is_active' => true,
                ])->id,
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'no_hp' => '081234567890',
                'email_reset' => 'bukan-email',
                'deskripsi_kendala' => 'Menguji validasi email reset',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.email_reset');
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_pengajuan_cloud_ditolak_saat_nip_tidak_berjumlah_18_digit(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('pengajuan.cloud.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011',
                'instansi' => 'Dinas Kominfo',
                'no_hp' => '081234567890',
                'email' => 'pegawai@gmail.com',
                'kapasitas' => '10GB',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.nip');
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_registrasi_ditolak_saat_no_hp_tidak_valid(): void
    {
        $response = $this->post(route('register.process'), [
            'name' => 'Pegawai Baru',
            'unit_kerja' => 'Dinas Kominfo',
            'jabatan' => 'Analis',
            'no_hp' => '08123',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nip' => '198501012010011002',
            'email' => 'pegawaibaru@acehbaratkab.go.id',
        ]);

        $response->assertSessionHasErrors('no_hp');
        $this->assertDatabaseMissing('users', ['email' => 'pegawaibaru@acehbaratkab.go.id']);
    }

    public function test_pengajuan_website_ditolak_saat_no_hp_tidak_valid(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('pengajuan.website.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'email_dinas' => 'pegawai@acehbaratkab.go.id',
                'email_google' => 'pegawai@gmail.com',
                'no_hp' => 'no-hp-salah',
                'instansi' => 'Dinas Kesehatan',
                'jabatan' => 'Pranata Komputer',
                'nama_pimpinan' => 'dr. H. A. Rahman',
                'nama_website' => 'Website Resmi Dinas Kesehatan',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.no_hp');
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_pengajuan_website_ditolak_saat_menyuntikkan_file_hasil(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('pengajuan.website.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'email_dinas' => 'pegawai@acehbaratkab.go.id',
                'email_google' => 'pegawai@gmail.com',
                'no_hp' => '081234567890',
                'instansi' => 'Dinas Kesehatan',
                'jabatan' => 'Pranata Komputer',
                'nama_pimpinan' => 'dr. H. A. Rahman',
                'nama_website' => 'Website Resmi Dinas Kesehatan',
                'file_hasil' => 'dokumen_hasil/hasil-rahasia.pdf',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.file_hasil');
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_admin_tidak_dapat_menurunkan_role_akun_sendiri(): void
    {
        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id', ['no_hp' => '081234567890']);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $admin->id), [
            'name' => 'Administrator Layanan',
            'email' => 'admin@acehbaratkab.go.id',
            'no_hp' => '081234567890',
            'role' => 'user',
        ]);

        $response->assertSessionHas('error');
        $this->assertSame('admin', $admin->fresh()->role);
    }
}
