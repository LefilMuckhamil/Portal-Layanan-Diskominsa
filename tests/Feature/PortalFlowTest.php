<?php

namespace Tests\Feature;

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
                'instansi' => 'Dinas Kesehatan',
                'no_hp' => '081234567890',
                'nama_pimpinan' => 'Dr. Andi Wijaya',
                'domain' => 'dinkes',
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
        $this->assertSame('dinkes.go.id', $pengajuan->data_pengajuan['domain']);
        Storage::disk('local')->assertExists($pengajuan->file_pendukung);
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

    public function test_tracking_tiket_publik_mengembalikan_200_dan_data_json(): void
    {
        $user = $this->buatUser();

        $pengajuan = Pengajuan::create([
            'user_id' => $user->id,
            'jenis_layanan' => 'Layanan TTE',
            'status' => 'Proses',
            'data_pengajuan' => ['nama' => 'Pegawai Diskominsa'],
            'logs' => [
                ['status' => 'Pending', 'catatan' => 'Pengajuan diterima', 'created_at' => now()->subDay()->toDateTimeString()],
                ['status' => 'Proses', 'catatan' => 'Sedang diverifikasi', 'created_at' => now()->toDateTimeString()],
            ],
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

        $response->assertSessionHasErrors('phone');
        $this->assertDatabaseCount('password_reset_requests', 0);
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

    public function test_pengajuan_bantuan_ditolak_saat_kategori_tidak_valid(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('pengajuan.bantuan.store'), [
            'data_pengajuan' => [
                'kategori' => 'Kategori Hacker',
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'email' => 'pegawai@acehbaratkab.go.id',
                'pesan_kendala' => 'Menguji validasi kategori',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.kategori');
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_pengajuan_cloud_ditolak_saat_kapasitas_tidak_valid(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('pengajuan.cloud.store'), [
            'data_pengajuan' => [
                'nama' => 'Pegawai Diskominsa',
                'nip' => '198501012010011001',
                'instansi' => 'Dinas Kominfo',
                'email' => 'pegawai@acehbaratkab.go.id',
                'kapasitas' => '999TB',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.kapasitas');
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
                'instansi' => 'Dinas Kesehatan',
                'no_hp' => 'no-hp-salah',
                'nama_pimpinan' => 'Dr. Andi Wijaya',
                'domain' => 'dinkes',
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
                'instansi' => 'Dinas Kesehatan',
                'no_hp' => '081234567890',
                'nama_pimpinan' => 'Dr. Andi Wijaya',
                'domain' => 'dinkes',
                'file_hasil' => 'dokumen_hasil/hasil-rahasia.pdf',
            ],
            'file_pendukung' => UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('data_pengajuan.file_hasil');
        $this->assertDatabaseCount('pengajuan', 0);
    }

    public function test_user_dapat_mengubah_password_sendiri_dengan_password_saat_ini_yang_benar(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('user.password.update'), [
            'current_password' => 'password123',
            'password' => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        $response->assertSessionHas('sukses');
        $this->assertTrue(Hash::check('passwordBaru123', $user->fresh()->password));
    }

    public function test_user_tidak_bisa_ubah_password_saat_password_saat_ini_salah(): void
    {
        $user = $this->buatUser();

        $response = $this->actingAs($user)->post(route('user.password.update'), [
            'current_password' => 'salah123',
            'password' => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
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
