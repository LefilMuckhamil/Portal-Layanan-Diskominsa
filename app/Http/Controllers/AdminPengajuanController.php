<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminPengajuanController extends Controller
{
    public function updateProgres(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in([
                'Pending', 'Proses', 'Selesai', 'Ditolak',
            ])],
            'catatan' => ['nullable', 'string', 'max:500'],
            'pesan' => ['nullable', 'string', 'max:1000'],
            'file_hasil' => ['nullable', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'min:10', 'max:5120'],
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = $request->status;

        $dataPengajuan = is_array($pengajuan->data_pengajuan)
            ? $pengajuan->data_pengajuan
            : (json_decode((string) $pengajuan->getRawOriginal('data_pengajuan') ?? '{}', true) ?: []);

        if ($request->hasFile('file_hasil')) {
            if (isset($dataPengajuan['file_hasil'])) {
                $this->hapusBerkasAman($dataPengajuan['file_hasil']);
            }

            $file = $request->file('file_hasil');
            $dataPengajuan['file_hasil'] = $file->store('dokumen_hasil', 'local');
            $pengajuan->data_pengajuan = $dataPengajuan;
        }

        $logs = is_array($pengajuan->logs)
            ? $pengajuan->logs
            : (json_decode((string) $pengajuan->getRawOriginal('logs') ?? '[]', true) ?: []);
        $logs[] = [
            'status' => $request->status,
            'catatan' => $request->catatan ?? 'Status diperbarui menjadi '.$request->status,
            'created_at' => now()->toDateTimeString(),
            'updated_by' => Auth::user()->name ?? 'Admin',
        ];
        $pengajuan->logs = $logs;

        if ($request->filled('pesan')) {
            $pesan = is_array($pengajuan->pesan)
                ? $pengajuan->pesan
                : (json_decode((string) $pengajuan->getRawOriginal('pesan') ?? '[]', true) ?: []);
            $pesan[] = [
                'pengirim' => Auth::user()->name ?? 'Admin Diskominsa',
                'role' => 'admin',
                'isi' => $request->pesan,
                'waktu' => now()->format('d M Y, H:i'),
            ];
            $pengajuan->pesan = $pesan;
        }

        $pengajuan->save();

        return back()->with('sukses', 'Update progres dan file hasil sukses disimpan!');
    }

    public function destroy($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $dataPengajuan = is_array($pengajuan->data_pengajuan)
            ? $pengajuan->data_pengajuan
            : (json_decode((string) $pengajuan->getRawOriginal('data_pengajuan') ?? '{}', true) ?: []);

        foreach (array_filter([$pengajuan->file_pendukung, $dataPengajuan['file_hasil'] ?? null]) as $path) {
            $this->hapusBerkasAman($path);
        }

        $pengajuan->delete();

        return back()->with('sukses', 'Pengajuan berhasil dihapus permanen.');
    }

    private function hapusBerkasAman(mixed $path): void
    {
        if (! is_string($path) || trim($path) === '' || str_starts_with($path, '/') || str_contains($path, '..') || str_contains($path, '\\')) {
            return;
        }

        $prefixes = ['dokumen_hasil/', 'dokumen_pengajuan/', 'pengajuan/', 'file_pendukung/'];
        $dalamAllowlist = false;
        foreach ($prefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $dalamAllowlist = true;
                break;
            }
        }

        if (! $dalamAllowlist) {
            return;
        }

        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }
    }

    public function website(Request $request)
    {
        $query = Pengajuan::where('jenis_layanan', 'Pembuatan Website')->with('user');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('jenis_layanan', 'Pembuatan Website');

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $proses = (clone $baseQuery)->where('status', 'Proses')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip')->get();

        return view('admin.website.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    public function storeWebsite(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'nullable|string',
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/', 'min:10', 'max:16'],
            'data_pengajuan.nama_pimpinan' => 'required|string|max:255',
            'data_pengajuan.domain' => 'required|string',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Format nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau 62xxxxxxxxxx.',
            'data_pengajuan.nama_pimpinan.required' => 'Kolom Nama Pimpinan wajib diisi.',
            'data_pengajuan.domain.required' => 'Kolom Domain wajib diisi.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/website', 'local');
        }

        if (isset($dataPengajuan['domain'])) {
            $domainInput = trim($dataPengajuan['domain']);
            $dataPengajuan['domain'] = str_contains($domainInput, '.go.id')
                                    ? $domainInput
                                    : $domainInput.'.go.id';
        }

        Pengajuan::create([
            'user_id' => $request->user_id,
            'jenis_layanan' => 'Pembuatan Website',
            'data_pengajuan' => $dataPengajuan,
            'file_pendukung' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('sukses', 'Pengajuan Pembuatan Website berhasil ditambahkan manual.');
    }

    public function emailResmi(Request $request)
    {
        $query = Pengajuan::where('jenis_layanan', 'Pembuatan Email Resmi')->with('user');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('jenis_layanan', 'Pembuatan Email Resmi');

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $proses = (clone $baseQuery)->where('status', 'Proses')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip')->get();

        return view('admin.email.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    public function storeEmailResmi(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/', 'min:10', 'max:16'],
            'data_pengajuan.usulan_email' => 'required|string',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Format nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau 62xxxxxxxxxx.',
            'data_pengajuan.usulan_email.required' => 'Kolom Usulan Email wajib diisi.',
            'file_pendukung.required' => 'Surat Permohonan (PDF) wajib diunggah.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/email', 'local');
        }

        if (isset($dataPengajuan['usulan_email'])) {
            $emailInput = trim($dataPengajuan['usulan_email']);
            $dataPengajuan['usulan_email'] = str_contains($emailInput, '@acehbaratkab.go.id')
                                           ? $emailInput
                                           : $emailInput.'@acehbaratkab.go.id';
        }

        Pengajuan::create([
            'user_id' => $request->user_id,
            'jenis_layanan' => 'Pembuatan Email Resmi',
            'data_pengajuan' => $dataPengajuan,
            'file_pendukung' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('sukses', 'Pengajuan Pembuatan Email Resmi berhasil ditambahkan manual.');
    }

    public function layananTte(Request $request)
    {
        $query = Pengajuan::where('jenis_layanan', 'Layanan TTE')->with('user');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('jenis_layanan', 'Layanan TTE');

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $proses = (clone $baseQuery)->where('status', 'Proses')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip')->get();

        return view('admin.tte.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    public function storeTte(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/', 'min:10', 'max:16'],
            'data_pengajuan.email' => 'required|email',
            'data_pengajuan.alamat' => 'required|string',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Format nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau 62xxxxxxxxxx.',
            'data_pengajuan.email.required' => 'Kolom Email wajib diisi.',
            'data_pengajuan.alamat.required' => 'Kolom Alamat wajib diisi.',
            'file_pendukung.required' => 'Dokumen Persyaratan (PDF) wajib diunggah.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/tte', 'local');
        }

        Pengajuan::create([
            'user_id' => $request->user_id,
            'jenis_layanan' => 'Layanan TTE',
            'data_pengajuan' => $dataPengajuan,
            'file_pendukung' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('sukses', 'Pengajuan Layanan TTE berhasil ditambahkan manual.');
    }

    public function layananCloud(Request $request)
    {
        $query = Pengajuan::where('jenis_layanan', 'Cloud Government')->with('user');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('jenis_layanan', 'Cloud Government');

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $proses = (clone $baseQuery)->where('status', 'Proses')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip')->get();

        return view('admin.cloud.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    public function storeCloud(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.email' => 'required|email',
            'data_pengajuan.kapasitas' => ['required', 'string', Rule::in(['10GB', '50GB', '100GB'])],
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Penanggung Jawab wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.email.required' => 'Kolom Email Aktif wajib diisi.',
            'data_pengajuan.kapasitas.required' => 'Kolom Kapasitas Penyimpanan wajib dipilih.',
            'data_pengajuan.kapasitas.in' => 'Pilihan kapasitas penyimpanan tidak valid.',
            'file_pendukung.required' => 'Surat Permohonan Cloud (PDF) wajib diunggah.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/cloud', 'local');
        }

        Pengajuan::create([
            'user_id' => $request->user_id,
            'jenis_layanan' => 'Cloud Government',
            'data_pengajuan' => $request->data_pengajuan,
            'file_pendukung' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('sukses', 'Pengajuan Cloud Government berhasil ditambahkan manual.');
    }

    public function layananBantuan(Request $request)
    {
        $query = Pengajuan::where('jenis_layanan', 'Pusat Bantuan')->with('user');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('jenis_layanan', 'Pusat Bantuan');

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $proses = (clone $baseQuery)->where('status', 'Proses')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip')->get();

        return view('admin.bantuan.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    public function storeBantuan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.kategori' => ['required', 'string', Rule::in(['Reset Password Email'])],
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.email' => 'required|email',
            'data_pengajuan.pesan_kendala' => 'nullable|string',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.kategori.required' => 'Kategori kendala wajib dipilih.',
            'data_pengajuan.kategori.in' => 'Pilihan kategori tidak valid.',
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.email.required' => 'Kolom Email Resmi wajib diisi.',
            'file_pendukung.required' => 'Surat Permohonan / Bukti (PDF) wajib diunggah.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('dokumen_pengajuan/bantuan', 'local');
        }

        Pengajuan::create([
            'user_id' => $request->user_id,
            'jenis_layanan' => 'Pusat Bantuan',
            'data_pengajuan' => $request->data_pengajuan,
            'file_pendukung' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('sukses', 'Pengajuan Pusat Bantuan berhasil ditambahkan manual.');
    }
}
