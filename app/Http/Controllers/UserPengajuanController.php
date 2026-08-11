<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPengajuanController extends Controller
{
    /**
     * 1. Pengajuan Website Instansi (G2G)
     */
    public function storeWebsite(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string',
            'nip'        => 'required|string',
            'instansi'   => 'required|string',
            'no_hp'      => 'required|string',
            'pimpinan'   => 'required|string',
            'domain'     => 'required|string',
            'file_surat' => 'required|mimes:pdf|max:2048',
        ]);

        $filePath = $request->file('file_surat')->store('dokumen_pengajuan', 'public');

        Pengajuan::create([
            'user_id'        => Auth::id(),
            'jenis_layanan'  => 'Pembuatan Website',
            'status'         => 'Pending',
            'file_pendukung' => $filePath, // Simpan ke field utama
            'data_pengajuan' => [          // Langsung Array Murni (Hapus json_encode)
                'nama'          => $request->nama,
                'nip'           => $request->nip,
                'instansi'      => $request->instansi,
                'nomor_hp'      => $request->no_hp,
                'nama_pimpinan' => $request->pimpinan,
                'domain'        => $request->domain . '.go.id',
            ]
        ]);

        return back()->with('sukses', 'Pengajuan Website Instansi berhasil dikirim!');
    }

    /**
     * 2. Pengajuan Email Resmi
     */
    public function storeEmail(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string',
            'nip'          => 'required|string',
            'instansi'     => 'required|string',
            'no_hp'        => 'required|string',
            'usulan_email' => 'required|string',
            'file_surat'   => 'required|mimes:pdf|max:2048',
        ]);

        $filePath = $request->file('file_surat')->store('dokumen_pengajuan', 'public');

        Pengajuan::create([
            'user_id'        => Auth::id(),
            'jenis_layanan'  => 'Pembuatan Email Resmi',
            'status'         => 'Pending',
            'file_pendukung' => $filePath,
            'data_pengajuan' => [
                'nama'         => $request->nama,
                'nip'          => $request->nip,
                'instansi'     => $request->instansi,
                'nomor_hp'     => $request->no_hp,
                'usulan_email' => $request->usulan_email . '@acehbaratkab.go.id',
            ]
        ]);

        return back()->with('sukses', 'Pengajuan Email Resmi berhasil dikirim!');
    }

    /**
     * 3. Pengajuan Layanan TTE
     */
    public function storeTte(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string',
            'nip'        => 'required|string',
            'instansi'   => 'required|string',
            'no_hp'      => 'required|string',
            'email'      => 'required|email',
            'alamat'     => 'required|string',
            'file_surat' => 'required|mimes:pdf|max:2048',
        ]);

        $filePath = $request->file('file_surat')->store('dokumen_pengajuan', 'public');

        Pengajuan::create([
            'user_id'        => Auth::id(),
            'jenis_layanan'  => 'Layanan TTE',
            'status'         => 'Pending',
            'file_pendukung' => $filePath,
            'data_pengajuan' => [
                'nama'     => $request->nama,
                'nip'      => $request->nip,
                'instansi' => $request->instansi,
                'nomor_hp' => $request->no_hp,
                'email'    => $request->email,
                'alamat'   => $request->alamat,
            ]
        ]);

        return back()->with('sukses', 'Pengajuan Layanan TTE berhasil dikirim!');
    }

    /**
     * 4. Pengajuan Cloud Government
     */
    public function storeCloud(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string',
            'nip'        => 'required|string',
            'email'      => 'required|email',
            'kapasitas'  => 'required|string',
            'file_surat' => 'required|mimes:pdf|max:2048',
        ]);

        $filePath = $request->file('file_surat')->store('dokumen_pengajuan', 'public');

        Pengajuan::create([
            'user_id'        => Auth::id(),
            'jenis_layanan'  => 'Cloud Government',
            'status'         => 'Pending',
            'file_pendukung' => $filePath,
            'data_pengajuan' => [
                'nama_pemohon' => $request->nama,
                'nip'          => $request->nip,
                'email'        => $request->email,
                'kapasitas'    => $request->kapasitas,
                'jenis_cloud'  => 'Personal',
            ]
        ]);

        return back()->with('sukses', 'Pengajuan Cloud Gov berhasil dikirim!');
    }

    /**
     * 5. Pengajuan Pusat Bantuan Reset Password
     */
    public function storeBantuan(Request $request)
    {
        $request->validate([
            'kategori'   => 'required|string',
            'nama'       => 'required|string',
            'nip'        => 'required|string',
            'email'      => 'required|email',
            'file_surat' => 'required|mimes:pdf|max:2048',
        ]);

        $filePath = $request->file('file_surat')->store('dokumen_pengajuan', 'public');

        Pengajuan::create([
            'user_id'        => Auth::id(),
            'jenis_layanan'  => 'Reset Password',
            'status'         => 'Pending',
            'file_pendukung' => $filePath,
            'data_pengajuan' => [
                'kendala'       => $request->kategori,
                'nama_pelapor'  => $request->nama,
                'nip'           => $request->nip,
                'email'         => $request->email,
                'pesan_kendala' => 'Permohonan ' . $request->kategori,
            ]
        ]);

        return back()->with('sukses', 'Tiket Bantuan berhasil dikirim!');
    }
}