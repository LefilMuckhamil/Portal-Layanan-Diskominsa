<?php

namespace App\Http\Controllers;

use App\Concerns\ResolvesPengajuanEmail;
use App\Concerns\StoresPengajuan;
use App\Models\KategoriBantuan;
use App\Models\Layanan;
use App\Models\Pengajuan;
use App\Models\PengajuanLog;
use App\Models\PengajuanMessage;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\TiketDitolakNotification;
use App\Notifications\TiketSelesaiNotification;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminPengajuanController extends Controller
{
    use ResolvesPengajuanEmail;
    use StoresPengajuan;

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
        $statusSebelumnya = $pengajuan->status;

        if ($request->hasFile('file_hasil')) {
            if ($pengajuan->file_hasil) {
                $this->hapusBerkasAman($pengajuan->file_hasil);
            }

            $pengajuan->file_hasil = $request->file('file_hasil')->store('dokumen_hasil', 'local');
        }

        $pengajuan->status = $request->status;
        $pengajuan->save();

        PengajuanLog::create([
            'pengajuan_id' => $pengajuan->id,
            'admin_id' => auth()->id(),
            'status_lama' => $statusSebelumnya,
            'status' => $request->status,
            'catatan_admin' => $request->catatan ?? null,
        ]);

        if ($request->filled('pesan')) {
            $pengajuan->messages()->create([
                'sender_id' => Auth::id(),
                'sender_role' => 'admin',
                'isi' => $request->pesan,
            ]);
        }

        $this->kirimNotifikasiPerubahanStatus($pengajuan, $statusSebelumnya, $request->catatan);

        return back()->with('sukses', 'Update progres dan file hasil sukses disimpan!');
    }

    public function destroy($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        foreach (array_filter([$pengajuan->file_pendukung, $pengajuan->file_hasil]) as $path) {
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

    public function getChat($id)
    {
        $pengajuan = Pengajuan::with('messages.sender')->findOrFail($id);

        $pesan = $pengajuan->messages->map(fn (PengajuanMessage $chat) => [
            'role' => $chat->role,
            'pengirim' => $chat->pengirim,
            'isi' => $chat->isi,
            'waktu' => $chat->waktu,
        ])->values()->all();

        return response()->json([
            'status' => 'success',
            'pesan' => $pesan,
        ]);
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'status' => 'nullable|string',
        ]);

        $query = Pengajuan::with(['user', 'layanan', 'pemohon']);

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date.' 23:59:59');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->get();

        $filename = 'rekap_pengajuan_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($pengajuans) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($file, '%s', chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($file, ['Nomor Tiket', 'Nama Pemohon', 'NIP', 'Instansi', 'Layanan', 'Status', 'Tanggal Pengajuan'], ';');

            foreach ($pengajuans as $item) {
                fputcsv($file, [
                    $item->nomor_tiket,
                    $item->pemohon?->nama ?? $item->user?->name ?? '-',
                    $item->pemohon?->nip ?? $item->user?->nip ?? '-',
                    $item->pemohon?->instansi ?? $item->user?->unit_kerja ?? '-',
                    $item->layanan?->nama ?? '-',
                    $item->status,
                    $item->created_at->format('d M Y, H:i'),
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function website(Request $request)
    {
        $query = Pengajuan::where('layanan_id', Layanan::idKode('WEB'))->with(['user', 'layanan', 'pemohon', 'website']);

        if ($request->filled('search')) {
            $search = addcslashes(trim($request->search), '%_');
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('layanan_id', Layanan::idKode('WEB'));

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $proses = (clone $baseQuery)->where('status', 'Proses')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip', 'unit_kerja', 'jabatan', 'no_hp', 'email')->get();
        $chatAktif = Setting::get('chat_global_aktif', '1') === '1';

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.website.partials.table', compact('pengajuans', 'users', 'chatAktif'))->render(),
            ]);
        }

        return view('admin.website.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users', 'chatAktif'
        ));
    }

    public function storeWebsite(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        $perketatNip = (($dataPengajuan['perketat_nip'] ?? null) === '1');
        $nipRules = $perketatNip ? 'required|digits:18' : 'nullable|string|max:18';
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => $nipRules,
            'data_pengajuan.email_dinas' => ['required', 'email', 'regex:/^[^@\s]+@acehbaratkab\.go\.id$/'],
            'data_pengajuan.email_google' => ['required', 'email', 'regex:/^[^@\s]+@gmail\.com$/'],
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.jabatan' => 'required|string|max:255',
            'data_pengajuan.nama_pimpinan' => 'required|string|max:255',
            'data_pengajuan.nama_website' => 'required|string|max:255',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'data_pengajuan.email_dinas.required' => 'Kolom Email Dinas wajib diisi.',
            'data_pengajuan.email_dinas.email' => 'Format Email Dinas tidak valid.',
            'data_pengajuan.email_dinas.regex' => 'Email Dinas harus menggunakan domain @acehbaratkab.go.id.',
            'data_pengajuan.email_google.required' => 'Kolom Email Alternatif wajib diisi.',
            'data_pengajuan.email_google.email' => 'Format Email Alternatif tidak valid.',
            'data_pengajuan.email_google.regex' => 'Email Alternatif harus menggunakan domain @gmail.com.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.jabatan.required' => 'Kolom Jabatan Operator wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Nomor HP/WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
            'data_pengajuan.nama_pimpinan.required' => 'Kolom Nama Pimpinan wajib diisi.',
            'data_pengajuan.nama_website.required' => 'Kolom Nama Website Usulan wajib diisi.',
            'file_pendukung.required' => 'Surat Permohonan (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $uploadedFile = $request->file('file_pendukung');
        $filePath = null;
        if ($uploadedFile) {
            $fileName = Str::uuid().'.'.strtolower($uploadedFile->getClientOriginalExtension());
            $filePath = $uploadedFile->storeAs('dokumen_pengajuan/website', $fileName, 'local');
        }

        $this->simpanPengajuan('WEB', $dataPengajuan, $filePath, $request->user_id);

        return back()->with('sukses', 'Pengajuan Pembuatan Website berhasil ditambahkan manual.');
    }

    public function emailResmi(Request $request)
    {
        $query = Pengajuan::where('layanan_id', Layanan::idKode('EML'))->with(['user', 'layanan', 'pemohon', 'email']);

        if ($request->filled('search')) {
            $search = addcslashes(trim($request->search), '%_');
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('layanan_id', Layanan::idKode('EML'));

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $proses = (clone $baseQuery)->where('status', 'Proses')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip', 'unit_kerja', 'jabatan', 'no_hp', 'email')->get();
        $chatAktif = Setting::get('chat_global_aktif', '1') === '1';

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.email.partials.table', compact('pengajuans', 'users', 'chatAktif'))->render(),
            ]);
        }

        return view('admin.email.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users', 'chatAktif'
        ));
    }

    public function storeEmailResmi(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        $perketatNip = (($dataPengajuan['perketat_nip'] ?? null) === '1');
        $nipRules = $perketatNip ? 'required|digits:18' : 'nullable|string|max:18';
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:150',
            'data_pengajuan.nip' => $nipRules,
            'data_pengajuan.instansi' => 'required|string|max:150',
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
            'data_pengajuan.usulan_email' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9._-]+$/',
            ],
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Nomor HP/WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
            'data_pengajuan.usulan_email.required' => 'Kolom Usulan Email wajib diisi.',
            'data_pengajuan.usulan_email.max' => 'Usulan alamat email maksimal 50 karakter.',
            'data_pengajuan.usulan_email.regex' => 'Usulan alamat email hanya boleh huruf, angka, titik, garis bawah, dan strip.',
            'file_pendukung.required' => 'Surat Permohonan (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $uploadedFile = $request->file('file_pendukung');
        $filePath = null;
        if ($uploadedFile) {
            $fileName = Str::uuid().'.'.strtolower($uploadedFile->getClientOriginalExtension());
            $filePath = $uploadedFile->storeAs('dokumen_pengajuan/email', $fileName, 'local');
        }

        if (isset($dataPengajuan['usulan_email'])) {
            $emailInput = trim($dataPengajuan['usulan_email']);
            $dataPengajuan['usulan_email'] = str_contains($emailInput, '@acehbaratkab.go.id')
                                           ? $emailInput
                                           : $emailInput.'@acehbaratkab.go.id';
        }

        $this->simpanPengajuan('EML', $dataPengajuan, $filePath, $request->user_id);

        return back()->with('sukses', 'Pengajuan Pembuatan Email Resmi berhasil ditambahkan manual.');
    }

    public function layananTte(Request $request)
    {
        $query = Pengajuan::where('layanan_id', Layanan::idKode('TTE'))->with(['user', 'layanan', 'pemohon', 'tte']);

        if ($request->filled('search')) {
            $search = addcslashes(trim($request->search), '%_');
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('layanan_id', Layanan::idKode('TTE'));

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $proses = (clone $baseQuery)->where('status', 'Proses')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip', 'unit_kerja', 'jabatan', 'no_hp', 'email')->get();
        $chatAktif = Setting::get('chat_global_aktif', '1') === '1';

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.tte.partials.table', compact('pengajuans', 'users', 'chatAktif'))->render(),
            ]);
        }

        return view('admin.tte.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users', 'chatAktif'
        ));
    }

    public function storeTte(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        $perketatNip = (($dataPengajuan['perketat_nip'] ?? null) === '1');
        $nipRules = $perketatNip ? 'required|digits:18' : 'nullable|string|max:18';
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => $nipRules,
            'data_pengajuan.nik' => 'required|digits:16',
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
            'data_pengajuan.email' => 'required|email',
            'data_pengajuan.alamat' => 'required|string',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'data_pengajuan.nik.required' => 'Kolom NIK wajib diisi.',
            'data_pengajuan.nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Nomor HP/WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
            'data_pengajuan.email.required' => 'Kolom Email wajib diisi.',
            'data_pengajuan.alamat.required' => 'Kolom Alamat wajib diisi.',
            'file_pendukung.required' => 'Dokumen Persyaratan (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $uploadedFile = $request->file('file_pendukung');
        $filePath = null;
        if ($uploadedFile) {
            $fileName = Str::uuid().'.'.strtolower($uploadedFile->getClientOriginalExtension());
            $filePath = $uploadedFile->storeAs('dokumen_pengajuan/tte', $fileName, 'local');
        }

        $this->simpanPengajuan('TTE', $dataPengajuan, $filePath, $request->user_id);

        return back()->with('sukses', 'Pengajuan Layanan TTE berhasil ditambahkan manual.');
    }

    public function layananCloud(Request $request)
    {
        $query = Pengajuan::where('layanan_id', Layanan::idKode('CLD'))->with(['user', 'layanan', 'pemohon', 'cloud']);

        if ($request->filled('search')) {
            $search = addcslashes(trim($request->search), '%_');
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('layanan_id', Layanan::idKode('CLD'));

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $proses = (clone $baseQuery)->where('status', 'Proses')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip', 'unit_kerja', 'jabatan', 'no_hp', 'email')->get();
        $chatAktif = Setting::get('chat_global_aktif', '1') === '1';

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.cloud.partials.table', compact('pengajuans', 'users', 'chatAktif'))->render(),
            ]);
        }

        return view('admin.cloud.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users', 'chatAktif'
        ));
    }

    public function storeCloud(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        $perketatNip = (($dataPengajuan['perketat_nip'] ?? null) === '1');
        $nipRules = $perketatNip ? 'required|digits:18' : 'nullable|string|max:18';
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => $nipRules,
            'data_pengajuan.instansi' => 'required|string|max:255',
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
            'data_pengajuan.email' => 'required|email',
            'data_pengajuan.kapasitas' => 'required|string|max:20',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.nama.required' => 'Kolom Nama Penanggung Jawab wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Nomor HP/WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
            'data_pengajuan.email.required' => 'Kolom Email Aktif wajib diisi.',
            'data_pengajuan.kapasitas.required' => 'Kolom Kapasitas Penyimpanan wajib diisi.',
            'data_pengajuan.kapasitas.max' => 'Kapasitas Penyimpanan maksimal 20 karakter.',
            'file_pendukung.required' => 'Surat Permohonan Cloud (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $uploadedFile = $request->file('file_pendukung');
        $filePath = null;
        if ($uploadedFile) {
            $fileName = Str::uuid().'.'.strtolower($uploadedFile->getClientOriginalExtension());
            $filePath = $uploadedFile->storeAs('dokumen_pengajuan/cloud', $fileName, 'local');
        }

        $this->simpanPengajuan('CLD', $dataPengajuan, $filePath, $request->user_id);

        return back()->with('sukses', 'Pengajuan Cloud Government berhasil ditambahkan manual.');
    }

    public function subdomain(Request $request)
    {
        $query = Pengajuan::where('layanan_id', Layanan::idKode('SUB'))->with(['user', 'layanan', 'pemohon', 'subdomain']);

        if ($request->filled('search')) {
            $search = addcslashes(trim($request->search), '%_');
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('layanan_id', Layanan::idKode('SUB'));

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $proses = (clone $baseQuery)->where('status', 'Proses')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip', 'unit_kerja', 'jabatan', 'no_hp', 'email')->get();
        $chatAktif = Setting::get('chat_global_aktif', '1') === '1';

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.subdomain.partials.table', compact('pengajuans', 'users', 'chatAktif'))->render(),
            ]);
        }

        return view('admin.subdomain.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users', 'chatAktif'
        ));
    }

    public function hosting(Request $request)
    {
        $query = Pengajuan::where('layanan_id', Layanan::idKode('HST'))->with(['user', 'layanan', 'pemohon', 'hosting']);

        if ($request->filled('search')) {
            $search = addcslashes(trim($request->search), '%_');
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('layanan_id', Layanan::idKode('HST'));

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $proses = (clone $baseQuery)->where('status', 'Proses')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip', 'unit_kerja', 'jabatan', 'no_hp', 'email')->get();
        $chatAktif = Setting::get('chat_global_aktif', '1') === '1';

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.hosting.partials.table', compact('pengajuans', 'users', 'chatAktif'))->render(),
            ]);
        }

        return view('admin.hosting.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users', 'chatAktif'
        ));
    }

    public function storeSubdomain(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        $perketatNip = (($dataPengajuan['perketat_nip'] ?? null) === '1');
        $nipRules = $perketatNip ? 'required|digits:18' : 'nullable|string|max:18';
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => $nipRules,
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
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
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
            'file_pendukung.required' => 'Surat Permohonan (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        if (isset($dataPengajuan['domain'])) {
            $domainInput = strtolower(trim($dataPengajuan['domain']));
            $dataPengajuan['domain'] = str_contains($domainInput, '.go.id')
                                    ? $domainInput
                                    : $domainInput.'.acehbaratkab.go.id';
        }

        $uploadedFile = $request->file('file_pendukung');
        $filePath = null;
        if ($uploadedFile) {
            $fileName = Str::uuid().'.'.strtolower($uploadedFile->getClientOriginalExtension());
            $filePath = $uploadedFile->storeAs('dokumen_pengajuan/subdomain', $fileName, 'local');
        }

        $this->simpanPengajuan('SUB', $dataPengajuan, $filePath, $request->user_id);

        return back()->with('sukses', 'Pengajuan Pembuatan Subdomain berhasil ditambahkan manual.');
    }

    public function storeHosting(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        $perketatNip = (($dataPengajuan['perketat_nip'] ?? null) === '1');
        $nipRules = $perketatNip ? 'required|digits:18' : 'nullable|string|max:18';
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => $nipRules,
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
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
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
            'file_pendukung.required' => 'Surat Permohonan (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $uploadedFile = $request->file('file_pendukung');
        $filePath = null;
        if ($uploadedFile) {
            $fileName = Str::uuid().'.'.strtolower($uploadedFile->getClientOriginalExtension());
            $filePath = $uploadedFile->storeAs('dokumen_pengajuan/hosting', $fileName, 'local');
        }

        $this->simpanPengajuan('HST', $dataPengajuan, $filePath, $request->user_id);

        return back()->with('sukses', 'Pengajuan Pembuatan Hosting berhasil ditambahkan manual.');
    }

    public function layananBantuan(Request $request)
    {
        $query = Pengajuan::where('layanan_id', Layanan::idKode('HLP'))->with(['user', 'layanan', 'pemohon', 'bantuan.kategori']);

        if ($request->filled('search')) {
            $search = addcslashes(trim($request->search), '%_');
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('layanan_id', Layanan::idKode('HLP'));

        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('status', 'Pending')->count();
        $proses = (clone $baseQuery)->where('status', 'Proses')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();

        $users = User::where('role', '!=', 'admin')->select('id', 'name', 'nip', 'unit_kerja', 'jabatan', 'no_hp', 'email')->get();
        $chatAktif = Setting::get('chat_global_aktif', '1') === '1';
        $kategoriBantuans = KategoriBantuan::where('is_active', true)->orderBy('nama_kategori')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.bantuan.partials.table', compact('pengajuans', 'users', 'chatAktif', 'kategoriBantuans'))->render(),
            ]);
        }

        return view('admin.bantuan.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users', 'chatAktif', 'kategoriBantuans'
        ));
    }

    public function storeBantuan(Request $request)
    {
        $dataPengajuan = $request->data_pengajuan;
        $perketatNip = (($dataPengajuan['perketat_nip'] ?? null) === '1');
        $nipRules = $perketatNip ? 'required|digits:18' : 'nullable|string|max:18';
        if (isset($dataPengajuan['no_hp'])) {
            $dataPengajuan['no_hp'] = PhoneNumber::normalize($dataPengajuan['no_hp']);
            $request->merge(['data_pengajuan' => $dataPengajuan]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data_pengajuan' => 'required|array',
            'data_pengajuan.kategori_bantuan_id' => 'required|exists:kategori_bantuan,id',
            'data_pengajuan.nama' => 'required|string|max:255',
            'data_pengajuan.nip' => $nipRules,
            'data_pengajuan.no_hp' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/', 'min:10', 'max:15'],
            'data_pengajuan.email_reset' => 'required|email',
            'data_pengajuan.deskripsi_kendala' => 'nullable|string|max:1000',
            'file_pendukung' => 'required|file|mimes:pdf|mimetypes:application/pdf|min:10|max:5120',
        ], [
            'data_pengajuan.kategori_bantuan_id.required' => 'Kategori kendala wajib dipilih.',
            'data_pengajuan.kategori_bantuan_id.exists' => 'Pilihan kategori tidak valid.',
            'data_pengajuan.nama.required' => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required' => 'Kolom NIP wajib diisi.',
            'data_pengajuan.nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'data_pengajuan.no_hp.required' => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.no_hp.regex' => 'Nomor HP/WhatsApp harus diawali dengan 08 (contoh: 081234567890).',
            'data_pengajuan.email_reset.required' => 'Kolom Email yang Ingin Direset wajib diisi.',
            'data_pengajuan.email_reset.email' => 'Format Email yang Ingin Direset tidak valid.',
            'data_pengajuan.deskripsi_kendala.max' => 'Deskripsi kendala maksimal 1000 karakter.',
            'file_pendukung.required' => 'Surat Permohonan / Bukti (PDF) wajib diunggah.',
            'file_pendukung.mimes' => 'Format file surat harus PDF.',
            'file_pendukung.max' => 'Ukuran file PDF maksimal 5MB.',
        ]);

        $dataPengajuan['email_reset'] = Str::lower(trim($dataPengajuan['email_reset'] ?? ''));

        $uploadedFile = $request->file('file_pendukung');
        $filePath = null;
        if ($uploadedFile) {
            $fileName = Str::uuid().'.'.strtolower($uploadedFile->getClientOriginalExtension());
            $filePath = $uploadedFile->storeAs('dokumen_pengajuan/bantuan', $fileName, 'local');
        }

        $this->simpanPengajuan('HLP', $dataPengajuan, $filePath, $request->user_id);

        return back()->with('sukses', 'Pengajuan Pusat Bantuan berhasil ditambahkan manual.');
    }

    private function kirimNotifikasiPerubahanStatus(Pengajuan $pengajuan, string $statusLama, ?string $catatan): void
    {
        $baru = $pengajuan->status;

        if ($statusLama === $baru) {
            return;
        }

        try {
            $targetEmail = $this->resolveTargetEmail($pengajuan);
            if (! $targetEmail) {
                return;
            }

            if ($baru === 'Selesai') {
                Notification::route('mail', $targetEmail)
                    ->notify(new TiketSelesaiNotification($pengajuan, $catatan));
            } elseif ($baru === 'Ditolak') {
                Notification::route('mail', $targetEmail)
                    ->notify(new TiketDitolakNotification($pengajuan, $catatan));
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim notifikasi perubahan status: '.$e->getMessage());
        }
    }
}
