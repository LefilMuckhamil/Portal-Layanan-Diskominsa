<?php

namespace Tests\Feature;

use App\Models\KategoriBantuan;
use App\Models\Layanan;
use App\Models\Pengajuan;
use App\Models\PengajuanBantuan;
use App\Models\PengajuanCloud;
use App\Models\PengajuanEmail;
use App\Models\PengajuanHosting;
use App\Models\PengajuanLog;
use App\Models\PengajuanPemohon;
use App\Models\PengajuanSubdomain;
use App\Models\PengajuanTte;
use App\Models\PengajuanWebsite;
use App\Models\User;
use Database\Seeders\LayananSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortalFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(LayananSeeder::class);
    }

    private function layanan(string $kode): int
    {
        return Layanan::idKode($kode);
    }

    private function buatPengajuan(User $user, string $kode, array $data = [], array $attrs = []): Pengajuan
    {
        $pemohonKeys = ['nama', 'nip', 'no_hp', 'email_dinas', 'instansi', 'jabatan'];
        $pemohon = array_intersect_key($data, array_flip($pemohonKeys));
        $detail = array_diff_key($data, array_flip($pemohonKeys));

        $pengajuan = Pengajuan::create(array_merge([
            'user_id' => $user->id,
            'layanan_id' => $this->layanan($kode),
            'status' => 'Pending',
        ], $attrs));

        if ($pemohon) {
            PengajuanPemohon::create(array_merge([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => $user->id,
            ], $pemohon));
        }

        $detailModel = match ($kode) {
            'WEB' => PengajuanWebsite::class,
            'SUB' => PengajuanSubdomain::class,
            'HST' => PengajuanHosting::class,
            'EML' => PengajuanEmail::class,
            'TTE' => PengajuanTte::class,
            'CLD' => PengajuanCloud::class,
            'HLP' => PengajuanBantuan::class,
            default => null,
        };

        if ($detailModel && $detail) {
            $detailModel::create(array_merge(['pengajuan_id' => $pengajuan->id], $detail));
        }

        return $pengajuan;
    }

    private function buatUser(string $role = 'user', string $email = 'pegawai@acehbaratkab.go.id', array $attrs = []): User
    {
        // status_akun tidak fillable sehingga diekstrak & di-set via forceFill.
        $statusAkun = $attrs['status_akun'] ?? 'aktif';
        unset($attrs['status_akun']);

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
            $user->forceFill(['role' => 'admin', 'status_akun' => 'aktif'])->save();
        } else {
            $user->forceFill(['status_akun' => $statusAkun])->save();
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
            'layanan_id' => $this->layanan('WEB'),
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#WEB-', $pengajuan->nomor_tiket);
        $this->assertSame('Website Resmi Dinas Kesehatan', $pengajuan->website->nama_website);
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
            'layanan_id' => $this->layanan('SUB'),
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#SUB-', $pengajuan->nomor_tiket);
        $this->assertSame('dinkes.acehbaratkab.go.id', $pengajuan->subdomain->domain);
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
            'layanan_id' => $this->layanan('HST'),
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
            'layanan_id' => $this->layanan('CLD'),
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#CLD-', $pengajuan->nomor_tiket);
        $this->assertSame('10GB', $pengajuan->cloud->kapasitas);
        $this->assertSame('081234567890', $pengajuan->pemohon->no_hp);
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
            'layanan_id' => $this->layanan('EML'),
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#EML-', $pengajuan->nomor_tiket);
        $this->assertSame('pegawai.diskom@acehbaratkab.go.id', $pengajuan->email->usulan_email);
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

        $pengajuan = $this->buatPengajuan($pemilik, 'WEB', ['nama' => 'Pegawai Diskominsa'], [
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
            'layanan_id' => $this->layanan('TTE'),
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#TTE-', $pengajuan->nomor_tiket);
        $this->assertSame('1108070101010001', $pengajuan->tte->nik);
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

        $pengajuan = $this->buatPengajuan($user, 'TTE', ['nama' => 'Pegawai Diskominsa'], ['status' => 'Proses']);

        PengajuanLog::create([
            'pengajuan_id' => $pengajuan->id,
            'status' => 'Pending',
            'catatan_admin' => 'Pengajuan diterima',
            'created_at' => now()->subDay(),
        ]);
        PengajuanLog::create([
            'pengajuan_id' => $pengajuan->id,
            'status' => 'Proses',
            'catatan_admin' => 'Sedang diverifikasi',
            'created_at' => now(),
        ]);

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

        $pengajuan = $this->buatPengajuan($user, 'WEB', ['nama' => 'Pegawai Diskominsa']);

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
            'phone' => '081234567890',
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

        $pengajuan = $this->buatPengajuan($pemilik, 'WEB', ['nama' => 'Pegawai Diskominsa']);

        $response = $this->actingAs($penyerang)->post(route('user.pengajuan.pesan', $pengajuan->id), [
            'pesan' => 'Pesan mencoba akses tiket orang lain',
        ]);

        $response->assertNotFound();

        $pengajuan->refresh();
        $this->assertSame(0, $pengajuan->messages()->count());
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
            'layanan_id' => $this->layanan('HLP'),
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertStringStartsWith('#HLP-', $pengajuan->nomor_tiket);
        $this->assertSame($kategori->id, $pengajuan->bantuan->kategori_bantuan_id);
        $this->assertSame('Reset Password Email', $pengajuan->bantuan->kategori->nama_kategori);
        $this->assertSame('081234567890', $pengajuan->pemohon->no_hp);
        $this->assertSame('pegawai@gmail.com', $pengajuan->bantuan->email_reset);
        $this->assertSame('Tidak bisa masuk email resmi.', $pengajuan->bantuan->deskripsi_kendala);
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
            'status_akun' => 'aktif',
        ]);

        $response->assertSessionHas('error');
        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_admin_dapat_mengakses_halaman_index_subdomain_dan_hosting(): void
    {
        Storage::fake('local');

        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $user = $this->buatUser();

        $this->buatPengajuan($user, 'SUB', [
            'nama' => 'Pegawai Diskominsa',
            'nip' => '198501012010011001',
            'email_dinas' => 'pegawai@acehbaratkab.go.id',
            'email_google' => 'pegawai@gmail.com',
            'no_hp' => '081234567890',
            'instansi' => 'Dinas Kesehatan',
            'jabatan' => 'Pranata Komputer',
            'domain' => 'dinkes.acehbaratkab.go.id',
            'ip_address' => '103.10.10.1',
            'nama_aplikasi' => 'Sistem Informasi Publik',
        ], ['file_pendukung' => 'dokumen_pengajuan/subdomain/surat.pdf']);

        $this->buatPengajuan($user, 'HST', [
            'nama' => 'Pegawai Diskominsa',
            'nip' => '198501012010011001',
            'email_dinas' => 'pegawai@acehbaratkab.go.id',
            'email_google' => 'pegawai@gmail.com',
            'no_hp' => '081234567890',
            'instansi' => 'Dinas Kesehatan',
            'jabatan' => 'Pranata Komputer',
            'nama_aplikasi' => 'Sistem Informasi Publik',
            'runtime' => 'PHP',
            'database_type' => 'MySQL',
            'storage_quota' => '10GB',
            'domain_terkait' => 'dinkes.acehbaratkab.go.id',
        ], ['file_pendukung' => 'dokumen_pengajuan/hosting/surat.pdf']);

        $this->actingAs($admin)->get(route('admin.subdomain.index'))
            ->assertOk()
            ->assertSee('dinkes.acehbaratkab.go.id')
            ->assertSee('Sistem Informasi Publik');

        $this->actingAs($admin)->get(route('admin.hosting.index'))
            ->assertOk()
            ->assertSee('PHP')
            ->assertSee('MySQL')
            ->assertSee('10GB');
    }

    public function test_dashboard_menampilkan_hitungan_subdomain_dan_hosting(): void
    {
        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $user = $this->buatUser();

        $this->buatPengajuan($user, 'SUB', ['nama' => 'Pegawai Diskominsa', 'domain' => 'sub.acehbaratkab.go.id', 'ip_address' => '103.10.10.1', 'nama_aplikasi' => 'SubAplikasi']);
        $this->buatPengajuan($user, 'HST', ['nama' => 'Pegawai Diskominsa', 'email_google' => 'pegawai@gmail.com', 'nama_aplikasi' => 'SIAP', 'runtime' => 'PHP', 'database_type' => 'MySQL', 'storage_quota' => '10GB']);

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('countSubdomain', 1)
            ->assertViewHas('countHosting', 1);
    }

    public function test_admin_dapat_memperbarui_progres_pengajuan_subdomain(): void
    {
        Storage::fake('local');

        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $user = $this->buatUser();

        $pengajuan = $this->buatPengajuan($user, 'SUB', [
            'nama' => 'Pegawai Diskominsa',
            'nip' => '198501012010011001',
            'no_hp' => '081234567890',
            'instansi' => 'Dinas Kesehatan',
            'domain' => 'dinkes.acehbaratkab.go.id',
            'ip_address' => '103.10.10.5',
            'nama_aplikasi' => 'SIAP',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.pengajuan.update', $pengajuan->id), [
            'status' => 'Selesai',
            'catatan' => 'Subdomain telah diaktifkan.',
            'pesan' => 'Subdomain Anda sudah aktif.',
            'file_hasil' => UploadedFile::fake()->create('hasil.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHas('sukses');

        $this->assertSame('Selesai', $pengajuan->fresh()->status);
        $this->assertStringStartsWith('dokumen_hasil/', $pengajuan->fresh()->file_hasil);
        Storage::disk('local')->assertExists($pengajuan->fresh()->file_hasil);

        $latest = $pengajuan->fresh();
        $log = $latest->riwayatStatus()->latest('id')->first();
        $this->assertSame('Selesai', $log->status);
        $this->assertSame('Subdomain telah diaktifkan.', $log->catatan_admin);
    }

    public function test_form_pengajuan_terisi_otomatis_data_profil_asn(): void
    {
        $user = $this->buatUser('user', 'pegawai@acehbaratkab.go.id', [
            'name' => 'Nama Lengkap ASN',
            'nip' => '198501012010011001',
            'unit_kerja' => 'Dinas Pendidikan',
            'jabatan' => 'Pranata Komputer',
            'no_hp' => '081234567890',
        ]);

        $profilUmum = [
            'value="Nama Lengkap ASN"',
            'value="198501012010011001"',
            'value="081234567890"',
        ];

        foreach (['pengajuan.website', 'pengajuan.subdomain', 'pengajuan.hosting', 'pengajuan.email', 'pengajuan.tte', 'pengajuan.cloud', 'pengajuan.bantuan'] as $route) {
            foreach ($profilUmum as $profil) {
                $this->actingAs($user)->get(route($route))->assertOk()->assertSee($profil, false);
            }
        }

        foreach (['pengajuan.email', 'pengajuan.tte', 'pengajuan.cloud'] as $route) {
            $this->actingAs($user)->get(route($route))->assertOk()->assertSee('value="Dinas Pendidikan"', false);
        }

        foreach (['pengajuan.website', 'pengajuan.subdomain', 'pengajuan.hosting'] as $route) {
            $this->actingAs($user)->get(route($route))
                ->assertOk()
                ->assertSee('value="Dinas Pendidikan"', false)
                ->assertSee('value="Pranata Komputer"', false)
                ->assertSee('value="pegawai@acehbaratkab.go.id"', false);
        }

        $this->actingAs($user)->get(route('pengajuan.tte'))->assertOk()->assertSee('value="pegawai@acehbaratkab.go.id"', false);
        $this->actingAs($user)->get(route('pengajuan.cloud'))->assertOk()->assertSee('value="pegawai@acehbaratkab.go.id"', false);
    }

    public function test_halaman_detail_user_menampilkan_tombol_unduh_berkas_hasil(): void
    {
        $user = $this->buatUser();

        $selesai = $this->buatPengajuan($user, 'WEB', ['nama' => 'Pegawai Diskominsa'], [
            'file_hasil' => 'dokumen_hasil/hasil.pdf',
        ]);
        $selesai->status = 'Selesai';
        $selesai->save();

        $proses = $this->buatPengajuan($user, 'WEB', ['nama' => 'Pegawai Diskominsa'], [
            'file_hasil' => 'dokumen_hasil/proses.pdf',
        ]);
        $proses->status = 'Proses';
        $proses->save();

        $urlUnduh = route('dokumen.unduh', ['pengajuan' => $selesai->id, 'jenis' => 'hasil']);

        $this->actingAs($user)->get(route('user.pengajuan.show', $selesai->id))
            ->assertOk()
            ->assertSee('Unduh Berkas Hasil Resmi (PDF)', false)
            ->assertSee($urlUnduh, false);

        $this->actingAs($user)->get(route('user.pengajuan.show', $proses->id))
            ->assertOk()
            ->assertDontSee('Unduh Berkas Hasil Resmi (PDF)', false);
    }

    public function test_modal_admin_menampilkan_link_lihat_file_hasil_kondisional(): void
    {
        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $user = $this->buatUser();

        $selesai = $this->buatPengajuan($user, 'WEB', ['nama' => 'Pegawai Diskominsa'], [
            'file_hasil' => 'dokumen_hasil/hasil.pdf',
        ]);
        $selesai->status = 'Selesai';
        $selesai->save();

        $pending = $this->buatPengajuan($user, 'WEB', ['nama' => 'Pegawai Diskominsa']);

        $response = $this->actingAs($admin)->get(route('admin.website.index'))->assertOk();

        $this->assertStringContainsString('id="hasil-upload-'.$selesai->id.'" class=""', $response->getContent());
        $this->assertStringContainsString('id="hasil-upload-'.$pending->id.'" class="hidden"', $response->getContent());

        $response
            ->assertSee('Lihat File Hasil', false)
            ->assertSee(route('dokumen.unduh', ['pengajuan' => $selesai->id, 'jenis' => 'hasil']), false);
    }

    public function test_admin_berhasil_menambah_pengajuan_subdomain_manual(): void
    {
        Storage::fake('local');

        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $pemohon = $this->buatUser('user', 'pegawai2@acehbaratkab.go.id');

        $response = $this->actingAs($admin)->post(route('admin.pengajuan.storeSubdomain'), [
            'user_id' => $pemohon->id,
            'data_pengajuan' => [
                'perketat_nip' => '1',
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
            'file_pendukung' => UploadedFile::fake()->create('surat.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('pengajuan', [
            'user_id' => $pemohon->id,
            'layanan_id' => $this->layanan('SUB'),
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::latest('id')->first();
        $this->assertStringStartsWith('#SUB-', $pengajuan->nomor_tiket);
        $this->assertSame('dinkes.acehbaratkab.go.id', $pengajuan->subdomain->domain);
        $this->assertSame('Dinas Kesehatan', $pengajuan->pemohon->instansi);
        $this->assertDatabaseHas('pengajuan_pemohon', [
            'pengajuan_id' => $pengajuan->id,
            'nip' => '198501012010011001',
        ]);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\.pdf$/', basename($pengajuan->file_pendukung));
        Storage::disk('local')->assertExists($pengajuan->file_pendukung);
    }

    public function test_admin_berhasil_menambah_pengajuan_hosting_manual(): void
    {
        Storage::fake('local');

        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $pemohon = $this->buatUser('user', 'pegawai2@acehbaratkab.go.id');

        $response = $this->actingAs($admin)->post(route('admin.pengajuan.storeHosting'), [
            'user_id' => $pemohon->id,
            'data_pengajuan' => [
                'perketat_nip' => '1',
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'email_dinas' => 'pegawai@acehbaratkab.go.id',
                'email_google' => 'pegawai@gmail.com',
                'no_hp' => '081234567890',
                'instansi' => 'Dinas Kesehatan',
                'jabatan' => 'Pranata Komputer',
                'nama_aplikasi' => 'Sistem Informasi Publik',
                'runtime' => 'PHP/Laravel',
                'database_type' => 'MySQL',
                'storage_quota' => '10GB',
                'domain_terkait' => 'dinkes.acehbaratkab.go.id',
            ],
            'file_pendukung' => UploadedFile::fake()->create('surat.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('pengajuan', [
            'user_id' => $pemohon->id,
            'layanan_id' => $this->layanan('HST'),
            'status' => 'Pending',
        ]);

        $pengajuan = Pengajuan::latest('id')->first();
        $this->assertStringStartsWith('#HST-', $pengajuan->nomor_tiket);
        $this->assertSame('PHP/Laravel', $pengajuan->hosting->runtime);
        $this->assertSame('MySQL', $pengajuan->hosting->database_type);
        $this->assertSame('10GB', $pengajuan->hosting->storage_quota);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\.pdf$/', basename($pengajuan->file_pendukung));
        Storage::disk('local')->assertExists($pengajuan->file_pendukung);
    }

    public function test_admin_subdomain_ditolak_saat_perketat_nip_dan_email_tidak_valid(): void
    {
        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $pemohon = $this->buatUser('user', 'pegawai2@acehbaratkab.go.id');

        $response = $this->actingAs($admin)->post(route('admin.pengajuan.storeSubdomain'), [
            'user_id' => $pemohon->id,
            'data_pengajuan' => [
                'perketat_nip' => '1',
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011',
                'email_dinas' => 'pegawai@gmail.com',
                'email_google' => 'pegawai@gmail.com',
                'no_hp' => '081234567890',
                'instansi' => 'Dinas Kesehatan',
                'jabatan' => 'Pranata Komputer',
                'domain' => 'dinkes',
                'ip_address' => '103.10.10.5',
                'nama_aplikasi' => 'SIAP',
            ],
            'file_pendukung' => UploadedFile::fake()->create('surat.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors(['data_pengajuan.nip', 'data_pengajuan.email_dinas']);
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_admin_website_manual_menerima_nip_perketat_nonaktif_dan_menyimpan_field_baru(): void
    {
        Storage::fake('local');

        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $pemohon = $this->buatUser('user', 'pegawai2@acehbaratkab.go.id');

        $response = $this->actingAs($admin)->post(route('admin.pengajuan.storeWebsite'), [
            'user_id' => $pemohon->id,
            'data_pengajuan' => [
                'perketat_nip' => '0',
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011',
                'email_dinas' => 'pegawai@acehbaratkab.go.id',
                'email_google' => 'pegawai@gmail.com',
                'no_hp' => '081234567890',
                'instansi' => 'Dinas Kesehatan',
                'jabatan' => 'Pranata Komputer',
                'nama_pimpinan' => 'dr. H. A. Rahman',
                'nama_website' => 'Website Resmi Dinas Kesehatan',
            ],
            'file_pendukung' => UploadedFile::fake()->create('surat.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHas('sukses');

        $pengajuan = Pengajuan::latest('id')->first();
        $this->assertStringStartsWith('#WEB-', $pengajuan->nomor_tiket);
        $this->assertSame('Website Resmi Dinas Kesehatan', $pengajuan->website->nama_website);
        $this->assertSame('pegawai@acehbaratkab.go.id', $pengajuan->pemohon->email_dinas);
        $this->assertSame('Pranata Komputer', $pengajuan->pemohon->jabatan);
        $this->assertSame('198501012010011', $pengajuan->pemohon->nip);
        $this->assertNull($pengajuan->subdomain);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\.pdf$/', basename($pengajuan->file_pendukung));
    }

    public function test_admin_tte_manual_menyimpan_nik(): void
    {
        Storage::fake('local');

        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $pemohon = $this->buatUser('user', 'pegawai2@acehbaratkab.go.id');

        $response = $this->actingAs($admin)->post(route('admin.pengajuan.storeTte'), [
            'user_id' => $pemohon->id,
            'data_pengajuan' => [
                'perketat_nip' => '1',
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'nik' => '1108070101010001',
                'instansi' => 'Dinas Kesehatan',
                'no_hp' => '081234567890',
                'email' => 'pegawai@acehbaratkab.go.id',
                'alamat' => 'Jl. Teuku Umar No. 1, Meulaboh',
            ],
            'file_pendukung' => UploadedFile::fake()->create('surat.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHas('sukses');

        $pengajuan = Pengajuan::latest('id')->first();
        $this->assertStringStartsWith('#TTE-', $pengajuan->nomor_tiket);
        $this->assertSame('1108070101010001', $pengajuan->tte->nik);
    }

    public function test_admin_cloud_manual_menyimpan_no_hp(): void
    {
        Storage::fake('local');

        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $pemohon = $this->buatUser('user', 'pegawai2@acehbaratkab.go.id');

        $response = $this->actingAs($admin)->post(route('admin.pengajuan.storeCloud'), [
            'user_id' => $pemohon->id,
            'data_pengajuan' => [
                'perketat_nip' => '1',
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'instansi' => 'Dinas Kominfo',
                'no_hp' => '081234567890',
                'email' => 'pegawai@acehbaratkab.go.id',
                'kapasitas' => '10GB',
            ],
            'file_pendukung' => UploadedFile::fake()->create('surat.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHas('sukses');

        $pengajuan = Pengajuan::latest('id')->first();
        $this->assertStringStartsWith('#CLD-', $pengajuan->nomor_tiket);
        $this->assertSame('081234567890', $pengajuan->pemohon->no_hp);
    }

    public function test_admin_bantuan_manual_menyimpan_kategori_dari_master(): void
    {
        Storage::fake('local');

        $kategori = KategoriBantuan::create([
            'nama_kategori' => 'Reset OTP',
            'is_active' => true,
        ]);

        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $pemohon = $this->buatUser('user', 'pegawai2@acehbaratkab.go.id');

        $response = $this->actingAs($admin)->post(route('admin.bantuan.store'), [
            'user_id' => $pemohon->id,
            'data_pengajuan' => [
                'perketat_nip' => '1',
                'kategori_bantuan_id' => $kategori->id,
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'no_hp' => '081234567890',
                'email_reset' => 'Pegawai@gmail.com',
                'deskripsi_kendala' => 'Tidak bisa masuk email resmi.',
            ],
            'file_pendukung' => UploadedFile::fake()->create('bukti.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHas('sukses');

        $pengajuan = Pengajuan::latest('id')->first();
        $this->assertStringStartsWith('#HLP-', $pengajuan->nomor_tiket);
        $this->assertSame($kategori->id, $pengajuan->bantuan->kategori_bantuan_id);
        $this->assertSame('Reset OTP', $pengajuan->bantuan->kategori->nama_kategori);
        $this->assertSame('pegawai@gmail.com', $pengajuan->bantuan->email_reset);
        $this->assertSame('081234567890', $pengajuan->pemohon->no_hp);
    }

    public function test_registrasi_berhasil_berstatus_pending_dan_tidak_bisa_langsung_login(): void
    {
        $response = $this->post(route('register.process'), [
            'name' => 'Pegawai Baru <script>alert("xss")</script>',
            'unit_kerja' => 'Dinas Kominfo',
            'jabatan' => 'Analis',
            'no_hp' => '081234567899',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'nip' => '198501012010011002',
            'email' => 'pegawaibaru@acehbaratkab.go.id',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('info');

        $this->assertDatabaseHas('users', [
            'email' => 'pegawaibaru@acehbaratkab.go.id',
            'role' => 'user',
            'status_akun' => 'pending',
        ]);

        $user = User::where('email', 'pegawaibaru@acehbaratkab.go.id')->first();
        $this->assertNotNull($user);
        // Sanitasi anti-XSS: konten <script> dibuang.
        $this->assertSame('Pegawai Baru', $user->name);

        $login = $this->post('/login', [
            'email' => 'pegawaibaru@acehbaratkab.go.id',
            'password' => 'Password123',
        ]);
        $login->assertRedirect(route('login'));
        $login->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_login_pending_dan_ditolak_ditendang_kembali_ke_login(): void
    {
        $this->buatUser('user', 'pending@acehbaratkab.go.id', ['status_akun' => 'pending']);
        $this->buatUser('user', 'ditolak@acehbaratkab.go.id', ['status_akun' => 'ditolak']);

        foreach ([
            ['pending@acehbaratkab.go.id', 'belum diaktivasi'],
            ['ditolak@acehbaratkab.go.id', 'ditolak'],
        ] as [$email, $fragment]) {
            $response = $this->post('/login', [
                'email' => $email,
                'password' => 'password123',
            ]);

            $response->assertRedirect(route('login'));
            $response->assertSessionHas('error', function ($pesan) use ($fragment) {
                return is_string($pesan) && str_contains($pesan, $fragment);
            });
            $this->assertGuest();
        }
    }

    public function test_middleware_akun_aktif_memutus_sesi_user_yang_dinonaktifkan(): void
    {
        $pending = $this->buatUser('user', 'pending2@acehbaratkab.go.id', ['status_akun' => 'pending']);

        $this->actingAs($pending)->get(route('user.riwayat'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('error');

        $this->assertGuest();
    }

    public function test_admin_menyetujui_akun_pending_sehingga_user_bisa_login(): void
    {
        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $calon = $this->buatUser('user', 'calon@acehbaratkab.go.id', ['status_akun' => 'pending']);

        $this->actingAs($admin)->post(route('admin.users.aktivasi', $calon->id))
            ->assertSessionHas('sukses');

        $calon->refresh();
        $this->assertSame('aktif', $calon->status_akun);
        $this->assertNotNull($calon->approved_at);
        $this->assertSame($admin->id, $calon->approved_by);

        // Keluar dari sesi admin lalu login sebagai user yang baru diaktivasi.
        $this->post(route('logout'));

        $this->post('/login', [
            'email' => 'calon@acehbaratkab.go.id',
            'password' => 'password123',
        ])->assertRedirect('/');

        $this->assertAuthenticatedAs($calon);
    }

    public function test_admin_menolak_akun_pending_sehingga_user_tidak_bisa_login(): void
    {
        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $calon = $this->buatUser('user', 'ditolak2@acehbaratkab.go.id', ['status_akun' => 'pending']);

        $this->actingAs($admin)->post(route('admin.users.tolak', $calon->id))
            ->assertSessionHas('sukses');

        $calon->refresh();
        $this->assertSame('ditolak', $calon->status_akun);
        $this->assertSame($admin->id, $calon->approved_by);

        $this->post(route('logout'));

        $this->post('/login', [
            'email' => 'ditolak2@acehbaratkab.go.id',
            'password' => 'password123',
        ])->assertRedirect(route('login'))->assertSessionHas('error');

        $this->assertGuest();
    }

    public function test_admin_tidak_dapat_mengubah_status_verifikasi_akun_sendiri(): void
    {
        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');

        $this->actingAs($admin)->post(route('admin.users.tolak', $admin->id))
            ->assertSessionHas('error');
        $this->actingAs($admin)->post(route('admin.users.aktivasi', $admin->id))
            ->assertSessionHas('error');

        $this->assertSame('aktif', $admin->fresh()->status_akun);
    }

    public function test_admin_dapat_mengubah_status_akun_melalui_update(): void
    {
        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');
        $user = $this->buatUser('user', 'pegawai@acehbaratkab.go.id');

        $this->actingAs($admin)->put(route('admin.users.update', $user->id), [
            'name' => $user->name,
            'email' => $user->email,
            'no_hp' => $user->no_hp,
            'role' => 'user',
            'status_akun' => 'ditolak',
        ])->assertSessionHas('sukses');

        $this->assertSame('ditolak', $user->fresh()->status_akun);
        $this->assertSame($admin->id, $user->fresh()->approved_by);
    }

    public function test_admin_tidak_dapat_mengunci_status_akun_sendiri_saat_update(): void
    {
        $admin = $this->buatUser('admin', 'admin@acehbaratkab.go.id');

        $this->actingAs($admin)->put(route('admin.users.update', $admin->id), [
            'name' => $admin->name,
            'email' => $admin->email,
            'no_hp' => $admin->no_hp,
            'role' => 'admin',
            'status_akun' => 'pending',
        ])->assertSessionHas('sukses');

        $this->assertSame('aktif', $admin->fresh()->status_akun);
    }
}
