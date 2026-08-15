<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminPengajuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with('user');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            if ($request->status == 'Proses') {
                $query->whereIn('status', ['Proses Development', 'Proses BSSN', 'Proses']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $pengajuans = $query->latest()->paginate(10);

        return view('admin.dashboard', compact('pengajuans'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with('user')->findOrFail($id);

        return view('admin.pengajuan.show', compact('pengajuan'));
    }

    public function updateProgres(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in([
                'Pending', 'Verifikasi Doc', 'Proses Development', 'Proses BSSN',
                'Proses', 'Selesai', 'Ditolak',
            ])],
            'catatan' => ['nullable', 'string', 'max:500'],
            'pesan' => ['nullable', 'string', 'max:1000'],
            'file_hasil' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = $request->status;

        $dataPengajuan = is_array($pengajuan->data_pengajuan)
            ? $pengajuan->data_pengajuan
            : (json_decode((string) $pengajuan->getRawOriginal('data_pengajuan') ?? '{}', true) ?: []);

        if ($request->hasFile('file_hasil')) {
            if (isset($dataPengajuan['file_hasil']) && Storage::disk('public')->exists($dataPengajuan['file_hasil'])) {
                Storage::disk('public')->delete($dataPengajuan['file_hasil']);
            }

            $file = $request->file('file_hasil');
            $fileName = time().'_'.uniqid().'_'.$file->getClientOriginalName();
            $dataPengajuan['file_hasil'] = $file->storeAs('dokumen_hasil', $fileName, 'public');
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
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $pengajuan->delete();

        return back()->with('sukses', 'Data permohonan berhasil dihapus permanen.');
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
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
        $proses = (clone $baseQuery)->where('status', 'Proses Development')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip')->get();

        return view('admin.website.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    public function storeWebsite(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'nullable|string',
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.no_hp' => 'required|string',
            'data_pengajuan.nama_pimpinan' => 'required|string|max:255',
            'data_pengajuan.domain' => 'required|string',
            'file_pendukung' => 'required|file|mimes:pdf|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.nama_pimpinan.required' => 'Kolom Nama Pimpinan wajib diisi.',
            'data_pengajuan.domain.required' => 'Kolom Domain wajib diisi.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('pengajuan/website', $fileName, 'public');
        }

        Pengajuan::create([
            'user_id' => $request->user_id,
            'nomor_tiket' => '#WEB-'.strtoupper(substr(md5(uniqid(rand(), true)), 0, 5)),
            'jenis_layanan' => 'Pembuatan Website',
            'data_pengajuan' => $request->data_pengajuan,
            'file_pendukung' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('sukses', 'Permohonan Website berhasil ditambahkan manual.');
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
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
        $proses = (clone $baseQuery)->where('status', 'Proses Development')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip')->get();

        return view('admin.email.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    public function storeEmailResmi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.no_hp' => 'required|string',
            'data_pengajuan.usulan_email' => 'required|string',
            'file_pendukung' => 'required|file|mimes:pdf|max:2048',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.usulan_email.required' => 'Kolom Usulan Email wajib diisi.',
            'file_pendukung.required' => 'Surat Permohonan (PDF) wajib diunggah.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('pengajuan/email', $fileName, 'public');
        }

        Pengajuan::create([
            'user_id' => $request->user_id,
            'nomor_tiket' => '#EML-'.strtoupper(substr(md5(uniqid(rand(), true)), 0, 5)),
            'jenis_layanan' => 'Pembuatan Email Resmi',
            'data_pengajuan' => $request->data_pengajuan,
            'file_pendukung' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('sukses', 'Permohonan Email Resmi berhasil ditambahkan manual.');
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
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
        $proses = (clone $baseQuery)->where('status', 'Proses BSSN')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip')->get();

        return view('admin.tte.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    public function storeTte(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.no_hp' => 'required|string',
            'data_pengajuan.email' => 'required|email',
            'data_pengajuan.alamat' => 'required|string',
            'file_pendukung' => 'required|file|mimes:pdf|max:2048',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.email.required' => 'Kolom Email wajib diisi.',
            'data_pengajuan.alamat.required' => 'Kolom Alamat wajib diisi.',
            'file_pendukung.required' => 'Dokumen Persyaratan (PDF) wajib diunggah.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('pengajuan/tte', $fileName, 'public');
        }

        Pengajuan::create([
            'user_id' => $request->user_id,
            'nomor_tiket' => '#TTE-'.strtoupper(substr(md5(uniqid(rand(), true)), 0, 5)),
            'jenis_layanan' => 'Layanan TTE',
            'data_pengajuan' => $request->data_pengajuan,
            'file_pendukung' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('sukses', 'Permohonan TTE berhasil ditambahkan manual.');
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
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
        $proses = (clone $baseQuery)->where('status', 'Proses Development')->count();
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
            'data_pengajuan.kapasitas' => 'required|string',
            'file_pendukung' => 'required|file|mimes:pdf|max:2048',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Penanggung Jawab wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.email.required' => 'Kolom Email Aktif wajib diisi.',
            'data_pengajuan.kapasitas.required' => 'Kolom Kapasitas Penyimpanan wajib dipilih.',
            'file_pendukung.required' => 'Surat Permohonan Cloud (PDF) wajib diunggah.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('pengajuan/cloud', $fileName, 'public');
        }

        Pengajuan::create([
            'user_id' => $request->user_id,
            'nomor_tiket' => '#CLD-'.strtoupper(substr(md5(uniqid(rand(), true)), 0, 5)),
            'jenis_layanan' => 'Cloud Government',
            'data_pengajuan' => $request->data_pengajuan,
            'file_pendukung' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('sukses', 'Permohonan Cloud Government berhasil ditambahkan manual.');
    }

    public function layananBantuan(Request $request)
    {
        $query = Pengajuan::whereIn('jenis_layanan', ['Pusat Bantuan', 'Reset Password'])->with('user');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::whereIn('jenis_layanan', ['Pusat Bantuan', 'Reset Password']);

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
        $proses = (clone $baseQuery)->where('status', 'Proses Development')->count();
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
            'data_pengajuan.kategori' => 'required|string',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => 'required|string',
            'data_pengajuan.email' => 'required|email',
            'file_pendukung' => 'required|file|mimes:pdf|max:2048',
        ], [
            'data_pengajuan.kategori.required' => 'Kategori kendala wajib dipilih.',
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.email.required' => 'Kolom Email Resmi wajib diisi.',
            'file_pendukung.required' => 'Surat Permohonan / Bukti (PDF) wajib diunggah.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('pengajuan/bantuan', $fileName, 'public');
        }

        Pengajuan::create([
            'user_id' => $request->user_id,
            'nomor_tiket' => '#HLP-'.strtoupper(substr(md5(uniqid(rand(), true)), 0, 5)),
            'jenis_layanan' => 'Pusat Bantuan',
            'data_pengajuan' => $request->data_pengajuan,
            'file_pendukung' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('sukses', 'Tiket Pusat Bantuan berhasil ditambahkan manual.');
    }
}
