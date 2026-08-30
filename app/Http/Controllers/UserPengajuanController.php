<?php

namespace App\Http\Controllers;

use App\Concerns\ResolvesPengajuanEmail;
use App\Models\Pengajuan;
use App\Notifications\TiketDibuatNotification;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserPengajuanController extends Controller
{
    use ResolvesPengajuanEmail;

    public function storeWebsite(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.file_hasil' => 'prohibited',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => ['required', 'string', 'regex:/^[0-9]{18}$/'],
            'data_pengajuan.email_dinas' => ['required', 'email', 'regex:/^[^@\s]+@acehbaratkab\.go\.id$/'],
            'data_pengajuan.email_google' => ['required', 'email', 'regex:/^[^@\s]+@gmail\.com$/'],
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/', 'min:10', 'max:16'],
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.jabatan' => 'required|string|max:255',
            'data_pengajuan.nama_pimpinan' => 'required|string|max:255',
            'data_pengajuan.nama_website' => 'required|string|max:255',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Lengkap wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.regex' => 'NIP harus terdiri dari 18 digit angka.',
            'data_pengajuan.email_dinas.required' => 'Kolom Email Dinas wajib diisi.',
            'data_pengajuan.email_dinas.email' => 'Format Email Dinas tidak valid.',
            'data_pengajuan.email_dinas.regex' => 'Email Dinas harus menggunakan domain @acehbaratkab.go.id.',
            'data_pengajuan.email_google.required' => 'Kolom Email Alternatif wajib diisi.',
            'data_pengajuan.email_google.email' => 'Format Email Alternatif tidak valid.',
            'data_pengajuan.email_google.regex' => 'Email Alternatif harus menggunakan domain @gmail.com.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Format nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau 62xxxxxxxxxx.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi / Unit Kerja wajib diisi.',
            'data_pengajuan.jabatan.required' => 'Kolom Jabatan Operator wajib diisi.',
            'data_pengajuan.nama_pimpinan.required' => 'Kolom Nama Pimpinan Instansi wajib diisi.',
            'data_pengajuan.nama_website.required' => 'Kolom Nama Website Usulan wajib diisi.',
            'file_pendukung.required' => 'Surat Permohonan Resmi (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/website', 'local');
        }

        $pengajuan = Pengajuan::create([
            'user_id' => Auth::id(),
            'jenis_layanan' => 'Pembuatan Website',
            'file_pendukung' => $filePath,
            'data_pengajuan' => collect($dataPengajuan)->only([
                'nama', 'nip', 'email_dinas', 'email_google', 'no_hp', 'instansi',
                'jabatan', 'nama_pimpinan', 'nama_website',
            ])->all(),
        ]);

        $this->kirimNotifikasiTiketDibuat($pengajuan);

        return back()->with('sukses', 'Pengajuan Website Instansi berhasil dikirim!')
            ->with('nomor_tiket', $pengajuan->nomor_tiket);
    }

    public function storeSubdomain(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.file_hasil' => 'prohibited',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => ['required', 'string', 'regex:/^[0-9]{18}$/'],
            'data_pengajuan.email_dinas' => ['required', 'email', 'regex:/^[^@\s]+@acehbaratkab\.go\.id$/'],
            'data_pengajuan.email_google' => ['required', 'email', 'regex:/^[^@\s]+@gmail\.com$/'],
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/', 'min:10', 'max:16'],
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.jabatan' => 'required|string|max:255',
            'data_pengajuan.domain' => 'required|string|max:255',
            'data_pengajuan.ip_address' => ['required', 'ip'],
            'data_pengajuan.nama_aplikasi' => 'required|string|max:255',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Lengkap wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.regex' => 'NIP harus terdiri dari 18 digit angka.',
            'data_pengajuan.email_dinas.required' => 'Kolom Email Dinas wajib diisi.',
            'data_pengajuan.email_dinas.email' => 'Format Email Dinas tidak valid.',
            'data_pengajuan.email_dinas.regex' => 'Email Dinas harus menggunakan domain @acehbaratkab.go.id.',
            'data_pengajuan.email_google.required' => 'Kolom Email Alternatif wajib diisi.',
            'data_pengajuan.email_google.email' => 'Format Email Alternatif tidak valid.',
            'data_pengajuan.email_google.regex' => 'Email Alternatif harus menggunakan domain @gmail.com.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Format nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau 62xxxxxxxxxx.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi / Unit Kerja wajib diisi.',
            'data_pengajuan.jabatan.required' => 'Kolom Jabatan Operator wajib diisi.',
            'data_pengajuan.domain.required' => 'Kolom Nama Subdomain Usulan wajib diisi.',
            'data_pengajuan.ip_address.required' => 'Kolom IP Address Server Tujuan wajib diisi.',
            'data_pengajuan.ip_address.ip' => 'Format IP Address Server Tujuan tidak valid.',
            'data_pengajuan.nama_aplikasi.required' => 'Kolom Nama Sistem / Aplikasi wajib diisi.',
            'file_pendukung.required' => 'Surat Permohonan Resmi (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        if (isset($dataPengajuan['domain'])) {
            $domainInput = strtolower(trim($dataPengajuan['domain']));
            $dataPengajuan['domain'] = str_contains($domainInput, '.go.id')
                                    ? $domainInput
                                    : $domainInput.'.acehbaratkab.go.id';
        }

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/subdomain', 'local');
        }

        $pengajuan = Pengajuan::create([
            'user_id' => Auth::id(),
            'jenis_layanan' => 'Pembuatan Subdomain',
            'file_pendukung' => $filePath,
            'data_pengajuan' => collect($dataPengajuan)->only([
                'nama', 'nip', 'email_dinas', 'email_google', 'no_hp', 'instansi',
                'jabatan', 'domain', 'ip_address', 'nama_aplikasi',
            ])->all(),
        ]);

        $this->kirimNotifikasiTiketDibuat($pengajuan);

        return back()->with('sukses', 'Pengajuan Subdomain berhasil dikirim!')
            ->with('nomor_tiket', $pengajuan->nomor_tiket);
    }

    public function storeHosting(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.file_hasil' => 'prohibited',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => ['required', 'string', 'regex:/^[0-9]{18}$/'],
            'data_pengajuan.email_dinas' => ['required', 'email', 'regex:/^[^@\s]+@acehbaratkab\.go\.id$/'],
            'data_pengajuan.email_google' => ['required', 'email', 'regex:/^[^@\s]+@gmail\.com$/'],
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/', 'min:10', 'max:16'],
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.jabatan' => 'required|string|max:255',
            'data_pengajuan.nama_aplikasi' => 'required|string|max:255',
            'data_pengajuan.runtime' => 'required|string|max:255',
            'data_pengajuan.database_type' => 'required|string|max:255',
            'data_pengajuan.storage_quota' => 'required|string|max:255',
            'data_pengajuan.domain_terkait' => 'nullable|string|max:255',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Lengkap wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.regex' => 'NIP harus terdiri dari 18 digit angka.',
            'data_pengajuan.email_dinas.required' => 'Kolom Email Dinas wajib diisi.',
            'data_pengajuan.email_dinas.email' => 'Format Email Dinas tidak valid.',
            'data_pengajuan.email_dinas.regex' => 'Email Dinas harus menggunakan domain @acehbaratkab.go.id.',
            'data_pengajuan.email_google.required' => 'Kolom Email Alternatif wajib diisi.',
            'data_pengajuan.email_google.email' => 'Format Email Alternatif tidak valid.',
            'data_pengajuan.email_google.regex' => 'Email Alternatif harus menggunakan domain @gmail.com.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Format nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau 62xxxxxxxxxx.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi / Unit Kerja wajib diisi.',
            'data_pengajuan.jabatan.required' => 'Kolom Jabatan Operator wajib diisi.',
            'data_pengajuan.nama_aplikasi.required' => 'Kolom Nama Aplikasi / Sistem wajib diisi.',
            'data_pengajuan.runtime.required' => 'Kolom Bahasa Pemrograman wajib diisi.',
            'data_pengajuan.database_type.required' => 'Kolom Database wajib diisi.',
            'data_pengajuan.storage_quota.required' => 'Kolom Kebutuhan Storage wajib diisi.',
            'file_pendukung.required' => 'Surat Permohonan Resmi (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/hosting', 'local');
        }

        $pengajuan = Pengajuan::create([
            'user_id' => Auth::id(),
            'jenis_layanan' => 'Pembuatan Hosting',
            'file_pendukung' => $filePath,
            'data_pengajuan' => collect($dataPengajuan)->only([
                'nama', 'nip', 'email_dinas', 'email_google', 'no_hp', 'instansi',
                'jabatan', 'nama_aplikasi', 'runtime', 'database_type', 'storage_quota', 'domain_terkait',
            ])->all(),
        ]);

        $this->kirimNotifikasiTiketDibuat($pengajuan);

        return back()->with('sukses', 'Pengajuan Hosting & Server berhasil dikirim!')
            ->with('nomor_tiket', $pengajuan->nomor_tiket);
    }

    public function storeEmail(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;

        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.file_hasil' => 'prohibited',
            'data_pengajuan.nama' => 'required|string|max:150',
            'data_pengajuan.nip' => 'required|digits:18',
            'data_pengajuan.instansi' => 'required|string|max:150',
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^(08|\+628)[0-9]{8,13}$/'],
            'data_pengajuan.usulan_email' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9._-]+$/',
            ],
            'file_pendukung' => 'required|file|mimes:pdf|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Lengkap wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'data_pengajuan.instansi.required' => 'Kolom Asal Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Format Nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau +628xxxxxxxxxx.',
            'data_pengajuan.usulan_email.required' => 'Kolom Usulan Alamat Email wajib diisi.',
            'data_pengajuan.usulan_email.max' => 'Usulan alamat email maksimal 50 karakter.',
            'data_pengajuan.usulan_email.regex' => 'Usulan alamat email hanya boleh huruf, angka, titik, garis bawah, dan strip.',
            'file_pendukung.required' => 'Surat Permohonan (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $usulanEmail = trim($dataPengajuan['usulan_email'] ?? '');
        $dataPengajuan['usulan_email'] = $usulanEmail.'@acehbaratkab.go.id';

        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
        }

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = Str::uuid().'.'.strtolower($file->getClientOriginalExtension());
            $filePath = $file->storeAs('dokumen_pengajuan/email', $fileName, 'local');
        }

        try {
            $pengajuan = DB::transaction(function () use ($dataPengajuan, $filePath) {
                return Pengajuan::create([
                    'user_id' => Auth::id(),
                    'jenis_layanan' => 'Pembuatan Email Resmi',
                    'file_pendukung' => $filePath,
                    'data_pengajuan' => collect($dataPengajuan)->only([
                        'nama', 'nip', 'instansi', 'no_hp', 'usulan_email',
                    ])->all(),
                ]);
            });
        } catch (\Throwable $e) {
            if ($filePath) {
                Storage::disk('local')->delete($filePath);
            }

            throw $e;
        }

        $this->kirimNotifikasiTiketDibuat($pengajuan);

        return back()->with('sukses', 'Pengajuan Email Resmi berhasil dikirim!')
            ->with('nomor_tiket', $pengajuan->nomor_tiket);
    }

    public function storeTte(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;

        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.file_hasil' => 'prohibited',
            'data_pengajuan.nama' => ['required', 'string', 'max:150', 'regex:/^[a-zA-Z\s\.,]+$/'],
            'data_pengajuan.nip' => 'required|digits:18',
            'data_pengajuan.nik' => 'required|digits:16',
            'data_pengajuan.instansi' => 'required|string|max:150',
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^(08|\+628)[0-9]{8,13}$/'],
            'data_pengajuan.email' => 'required|email:rfc,dns|max:150',
            'data_pengajuan.alamat' => 'required|string|max:500',
            'file_pendukung' => 'required|file|mimes:pdf|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Lengkap wajib diisi.',
            'data_pengajuan.nama.regex' => 'Kolom Nama Lengkap hanya boleh huruf, spasi, titik, dan koma.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'data_pengajuan.nik.required' => 'Kolom NIK wajib diisi.',
            'data_pengajuan.nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi / Unit Kerja wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Format Nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau +628xxxxxxxxxx.',
            'data_pengajuan.email.required' => 'Kolom Email Aktif / Kedinasan wajib diisi.',
            'data_pengajuan.email.email' => 'Format Email Aktif / Kedinasan tidak valid.',
            'data_pengajuan.alamat.required' => 'Kolom Alamat Domisili wajib diisi.',
            'file_pendukung.required' => 'Dokumen Persyaratan TTE (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
        }

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = Str::uuid().'.'.strtolower($file->getClientOriginalExtension());
            $filePath = $file->storeAs('dokumen_pengajuan/tte', $fileName, 'local');
        }

        try {
            $pengajuan = DB::transaction(function () use ($dataPengajuan, $filePath) {
                return Pengajuan::create([
                    'user_id' => Auth::id(),
                    'jenis_layanan' => 'Layanan TTE',
                    'file_pendukung' => $filePath,
                    'data_pengajuan' => collect($dataPengajuan)->only([
                        'nama', 'nip', 'nik', 'instansi', 'no_hp', 'email', 'alamat',
                    ])->all(),
                ]);
            });
        } catch (\Throwable $e) {
            if ($filePath) {
                Storage::disk('local')->delete($filePath);
            }

            throw $e;
        }

        $this->kirimNotifikasiTiketDibuat($pengajuan);

        return back()->with('sukses', 'Pengajuan Layanan TTE berhasil dikirim!')
            ->with('nomor_tiket', $pengajuan->nomor_tiket);
    }

    public function storeCloud(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;

        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.file_hasil' => 'prohibited',
            'data_pengajuan.nama' => ['required', 'string', 'max:150', 'regex:/^[a-zA-Z\s\.,]+$/'],
            'data_pengajuan.nip' => 'required|digits:18',
            'data_pengajuan.instansi' => 'required|string|max:150',
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^(08|\+628)[0-9]{8,13}$/'],
            'data_pengajuan.email' => 'required|email:rfc,dns|max:150',
            'data_pengajuan.kapasitas' => 'required|string|max:20',
            'file_pendukung' => 'required|file|mimes:pdf|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Lengkap wajib diisi.',
            'data_pengajuan.nama.regex' => 'Kolom Nama Lengkap hanya boleh huruf, spasi, titik, dan koma.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi / Unit Kerja wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Format Nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau +628xxxxxxxxxx.',
            'data_pengajuan.email.required' => 'Kolom Email Resmi Aktif wajib diisi.',
            'data_pengajuan.email.email' => 'Format Email Resmi Aktif tidak valid.',
            'data_pengajuan.kapasitas.required' => 'Kolom Kapasitas Penyimpanan wajib diisi.',
            'data_pengajuan.kapasitas.max' => 'Kapasitas Penyimpanan maksimal 20 karakter.',
            'file_pendukung.required' => 'Surat Permohonan Akun Cloud (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
        }

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = Str::uuid().'.'.strtolower($file->getClientOriginalExtension());
            $filePath = $file->storeAs('dokumen_pengajuan/cloud', $fileName, 'local');
        }

        try {
            $pengajuan = DB::transaction(function () use ($dataPengajuan, $filePath) {
                return Pengajuan::create([
                    'user_id' => Auth::id(),
                    'jenis_layanan' => 'Cloud Government',
                    'file_pendukung' => $filePath,
                    'data_pengajuan' => collect($dataPengajuan)->only([
                        'nama', 'nip', 'instansi', 'no_hp', 'email', 'kapasitas',
                    ])->all(),
                ]);
            });
        } catch (\Throwable $e) {
            if ($filePath) {
                Storage::disk('local')->delete($filePath);
            }

            throw $e;
        }

        $this->kirimNotifikasiTiketDibuat($pengajuan);

        return back()->with('sukses', 'Pengajuan Cloud Gov berhasil dikirim!')
            ->with('nomor_tiket', $pengajuan->nomor_tiket);
    }

    public function storeBantuan(Request $request)
    {
        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.file_hasil' => 'prohibited',
            'data_pengajuan.kategori' => ['required', 'string', Rule::in(['Reset Password Email'])],
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.email' => 'required|email',
            'data_pengajuan.pesan_kendala' => 'nullable|string',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.kategori.required' => 'Kategori kendala wajib dipilih.',
            'data_pengajuan.kategori.in' => 'Pilihan kategori tidak valid.',
            'data_pengajuan.nama.required' => 'Nama pelapor wajib diisi.',
            'data_pengajuan.nip.required' => 'NIP wajib diisi.',
            'data_pengajuan.email.required' => 'Email wajib diisi.',
            'file_pendukung.required' => 'Surat/Bukti Kendala (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/bantuan', 'local');
        }

        $pengajuan = Pengajuan::create([
            'user_id' => Auth::id(),
            'jenis_layanan' => 'Pusat Bantuan',
            'file_pendukung' => $filePath,
            'data_pengajuan' => collect($request->data_pengajuan)->only([
                'kategori', 'nama', 'nip', 'email', 'pesan_kendala',
            ])->all(),
        ]);

        $this->kirimNotifikasiTiketDibuat($pengajuan);

        return back()->with('sukses', 'Tiket Bantuan berhasil dikirim!')
            ->with('nomor_tiket', $pengajuan->nomor_tiket);
    }

    private function kirimNotifikasiTiketDibuat(Pengajuan $pengajuan): void
    {
        try {
            $targetEmail = $this->resolveTargetEmail($pengajuan);
            if ($targetEmail) {
                Notification::route('mail', $targetEmail)
                    ->notify(new TiketDibuatNotification($pengajuan));
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim notifikasi tiket dibuat: '.$e->getMessage());
        }
    }
}
