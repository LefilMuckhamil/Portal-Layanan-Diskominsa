<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPengajuanController extends Controller
{
    public function storeWebsite(Request $request)
    {
        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.no_hp' => 'required|string',
            'data_pengajuan.nama_pimpinan' => 'required|string|max:255',
            'data_pengajuan.domain' => 'required|string',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP wajib diisi.',
            'data_pengajuan.nama_pimpinan.required' => 'Kolom Nama Pimpinan wajib diisi.',
            'data_pengajuan.domain.required' => 'Kolom Nama Domain wajib diisi.',
            'file_pendukung.required' => 'Surat Permohonan (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/website', 'local');
        }

        $dataPengajuan = $request->data_pengajuan;
        if (isset($dataPengajuan['domain'])) {
            $domainInput = trim($dataPengajuan['domain']);
            $dataPengajuan['domain'] = str_contains($domainInput, '.go.id')
                                    ? $domainInput
                                    : $domainInput.'.go.id';
        }

        $pengajuan = Pengajuan::create([
            'user_id' => Auth::id(),
            'jenis_layanan' => 'Pembuatan Website',
            'status' => 'Pending',
            'file_pendukung' => $filePath,
            'data_pengajuan' => $dataPengajuan,
        ]);

        return back()->with('sukses', 'Pengajuan Website Instansi berhasil dikirim!')
            ->with('nomor_tiket', $pengajuan->nomor_tiket);
    }

    public function storeEmail(Request $request)
    {
        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.no_hp' => 'required|string',
            'data_pengajuan.usulan_email' => 'required|string',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP wajib diisi.',
            'data_pengajuan.usulan_email.required' => 'Kolom Usulan Email wajib diisi.',
            'file_pendukung.required' => 'Surat Permohonan (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/email', 'local');
        }

        $dataPengajuan = $request->data_pengajuan;
        if (isset($dataPengajuan['usulan_email'])) {
            $emailInput = trim($dataPengajuan['usulan_email']);
            $dataPengajuan['usulan_email'] = str_contains($emailInput, '@acehbaratkab.go.id')
                                           ? $emailInput
                                           : $emailInput.'@acehbaratkab.go.id';
        }

        $pengajuan = Pengajuan::create([
            'user_id' => Auth::id(),
            'jenis_layanan' => 'Pembuatan Email Resmi',
            'status' => 'Pending',
            'file_pendukung' => $filePath,
            'data_pengajuan' => $dataPengajuan,
        ]);

        return back()->with('sukses', 'Pengajuan Email Resmi berhasil dikirim!')
            ->with('nomor_tiket', $pengajuan->nomor_tiket);
    }

    public function storeTte(Request $request)
    {
        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.no_hp' => 'required|string',
            'data_pengajuan.email' => 'required|email',
            'data_pengajuan.alamat' => 'required|string',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Nama pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'NIP wajib diisi.',
            'data_pengajuan.instansi.required' => 'Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Nomor HP wajib diisi.',
            'data_pengajuan.email.required' => 'Email wajib diisi.',
            'data_pengajuan.alamat.required' => 'Alamat wajib diisi.',
            'file_pendukung.required' => 'Surat permohonan TTE (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/tte', 'local');
        }

        $pengajuan = Pengajuan::create([
            'user_id' => Auth::id(),
            'jenis_layanan' => 'Layanan TTE',
            'status' => 'Pending',
            'file_pendukung' => $filePath,
            'data_pengajuan' => $request->data_pengajuan,
        ]);

        return back()->with('sukses', 'Pengajuan Layanan TTE berhasil dikirim!')
            ->with('nomor_tiket', $pengajuan->nomor_tiket);
    }

    public function storeCloud(Request $request)
    {
        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.email' => 'required|email',
            'data_pengajuan.kapasitas' => 'required|string',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Nama pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'NIP wajib diisi.',
            'data_pengajuan.instansi.required' => 'Instansi wajib diisi.',
            'data_pengajuan.email.required' => 'Email wajib diisi.',
            'data_pengajuan.kapasitas.required' => 'Kapasitas penyimpan wajib dipilih.',
            'file_pendukung.required' => 'Surat permohonan Cloud (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/cloud', 'local');
        }

        $pengajuan = Pengajuan::create([
            'user_id' => Auth::id(),
            'jenis_layanan' => 'Cloud Government',
            'status' => 'Pending',
            'file_pendukung' => $filePath,
            'data_pengajuan' => $request->data_pengajuan,
        ]);

        return back()->with('sukses', 'Pengajuan Cloud Gov berhasil dikirim!')
            ->with('nomor_tiket', $pengajuan->nomor_tiket);
    }

    public function storeBantuan(Request $request)
    {
        $request->validate([
            'data_pengajuan' => 'required|array',
            'data_pengajuan.kategori' => 'required|string',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.email' => 'required|email',
            'data_pengajuan.pesan_kendala' => 'nullable|string',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.kategori.required' => 'Kategori kendala wajib dipilih.',
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
            'status' => 'Pending',
            'file_pendukung' => $filePath,
            'data_pengajuan' => $request->data_pengajuan,
        ]);

        return back()->with('sukses', 'Tiket Bantuan berhasil dikirim!')
            ->with('nomor_tiket', $pengajuan->nomor_tiket);
    }
}
