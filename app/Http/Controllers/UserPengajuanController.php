<?php

namespace App\Http\Controllers;

use App\Concerns\ResolvesPengajuanEmail;
use App\Concerns\StoresPengajuan;
use App\Models\Pengajuan;
use App\Notifications\TiketDibuatNotification;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserPengajuanController extends Controller
{
    use ResolvesPengajuanEmail;
    use StoresPengajuan;

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
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
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
            'data_pengajuan.no_hp.regex' => 'Nomor HP/WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
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

        $pengajuan = $this->simpanPengajuan('WEB', $dataPengajuan, $filePath, Auth::id());

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
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
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
            'data_pengajuan.no_hp.regex' => 'Nomor HP/WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
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

        $pengajuan = $this->simpanPengajuan('SUB', $dataPengajuan, $filePath, Auth::id());

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
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
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
            'data_pengajuan.no_hp.regex' => 'Nomor HP/WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
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

        $pengajuan = $this->simpanPengajuan('HST', $dataPengajuan, $filePath, Auth::id());

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
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
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
            'data_pengajuan.no_hp.regex' => 'Nomor HP/WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
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
            $pengajuan = $this->simpanPengajuan('EML', $dataPengajuan, $filePath, Auth::id());
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
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
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
            'data_pengajuan.no_hp.regex' => 'Nomor HP/WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
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
            $pengajuan = $this->simpanPengajuan('TTE', $dataPengajuan, $filePath, Auth::id());
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
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
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
            'data_pengajuan.no_hp.regex' => 'Nomor HP/WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
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
            $pengajuan = $this->simpanPengajuan('CLD', $dataPengajuan, $filePath, Auth::id());
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
        $dataPengajuan = $request->data_pengajuan;

        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.file_hasil' => 'prohibited',
            'data_pengajuan.kategori_bantuan_id' => 'required|exists:kategori_bantuan,id',
            'data_pengajuan.nama' => ['required', 'string', 'max:150', 'regex:/^[a-zA-Z\s\.,]+$/'],
            'data_pengajuan.nip' => 'required|digits:18',
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
            'data_pengajuan.email_reset' => 'required|email:rfc,dns|max:150',
            'data_pengajuan.deskripsi_kendala' => 'nullable|string|max:1000',
            'file_pendukung' => 'required|file|mimes:pdf|max:5120',
        ], [
            'data_pengajuan.kategori_bantuan_id.required' => 'Kategori kendala wajib dipilih.',
            'data_pengajuan.kategori_bantuan_id.exists' => 'Pilihan kategori tidak valid.',
            'data_pengajuan.nama.required' => 'Kolom Nama Lengkap wajib diisi.',
            'data_pengajuan.nama.regex' => 'Kolom Nama Lengkap hanya boleh huruf, spasi, titik, dan koma.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Nomor HP/WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
            'data_pengajuan.email_reset.required' => 'Kolom Email yang Ingin Direset wajib diisi.',
            'data_pengajuan.email_reset.email' => 'Format Email yang Ingin Direset tidak valid.',
            'data_pengajuan.deskripsi_kendala.max' => 'Deskripsi kendala maksimal 1000 karakter.',
            'file_pendukung.required' => 'Surat Permohonan Bantuan (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
        }

        if (isset($dataPengajuan['email_reset'])) {
            $dataPengajuan['email_reset'] = Str::lower(trim($dataPengajuan['email_reset']));
        }

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = Str::uuid().'.'.strtolower($file->getClientOriginalExtension());
            $filePath = $file->storeAs('dokumen_pengajuan/bantuan', $fileName, 'local');
        }

        try {
            $pengajuan = $this->simpanPengajuan('HLP', $dataPengajuan, $filePath, Auth::id());
        } catch (\Throwable $e) {
            if ($filePath) {
                Storage::disk('local')->delete($filePath);
            }

            throw $e;
        }

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
