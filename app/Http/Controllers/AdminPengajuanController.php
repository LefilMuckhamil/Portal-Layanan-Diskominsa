<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminPengajuanController extends Controller
{
    // Nampilin semua daftar permohonan masuk secara global
    public function index()
    {
        $pengajuans = Pengajuan::latest()->get(); 
        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    // Nampilin halaman detail buat satu pengajuan spesifik
    public function show($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        return view('admin.pengajuan.detail', compact('pengajuan'));
    }

    // Handle segala update dari admin (status, catatan log, chat balas ke user, dan upload file hasil TTE)
    public function updateProgres(Request $request, $id)
    {
        $request->validate([
            'status'     => ['required', 'string'],
            'catatan'    => ['nullable', 'string', 'max:500'],
            'pesan'      => ['nullable', 'string', 'max:1000'],
            'file_hasil' => ['nullable', 'mimes:pdf', 'max:5120'], 
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = $request->status;

        // Amankan dan decode data JSON bawaan
        $dataPengajuan = is_array($pengajuan->data_pengajuan) 
            ? $pengajuan->data_pengajuan 
            : json_decode($pengajuan->data_pengajuan ?? '{}', true);

        // Kalau admin upload file hasil, simpan pakai nama aslinya
        if ($request->hasFile('file_hasil')) {
            if (isset($dataPengajuan['file_hasil']) && Storage::disk('public')->exists($dataPengajuan['file_hasil'])) {
                Storage::disk('public')->delete($dataPengajuan['file_hasil']);
            }
            
            $file = $request->file('file_hasil');
            $dataPengajuan['file_hasil'] = $file->storeAs('dokumen_hasil', $file->getClientOriginalName(), 'public');
            $pengajuan->data_pengajuan = $dataPengajuan;
        }

        // Bikin jejak rekam atau timeline log biar user tahu progresnya
        $logs = is_array($pengajuan->logs) ? $pengajuan->logs : json_decode($pengajuan->logs ?? '[]', true);
        $logs[] = [
            'status'     => $request->status,
            'catatan'    => $request->catatan ?? 'Status diperbarui menjadi ' . $request->status,
            'created_at' => now()->toDateTimeString(),
            'updated_by' => Auth::user()->name ?? 'Admin', 
        ];
        $pengajuan->logs = $logs;

        // Kalau admin ngetik pesan, simpan juga ke obrolan
        if ($request->filled('pesan')) {
            $pesan = is_array($pengajuan->pesan) ? $pengajuan->pesan : json_decode($pengajuan->pesan ?? '[]', true);
            $pesan[] = [
                'pengirim' => Auth::user()->name ?? 'Admin Diskominsa',
                'role'     => 'admin',
                'isi'      => $request->pesan,
                'waktu'    => now()->format('d M Y, H:i')
            ];
            $pengajuan->pesan = $pesan;
        }

        $pengajuan->save();

        return back()->with('sukses', 'Update progres dan file hasil sukses disimpan!');
    }

    // Hapus data permohonan permanen dari sistem
    public function destroy($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->delete();

        return back()->with('sukses', 'Data permohonan berhasil dihapus permanen.');
    }

    // Nampilin halaman kelola daftar ajuan Website Instansi
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

        // Contoh pada method website:
        $pengajuans = $query->latest()->paginate(10); 
        $baseQuery = Pengajuan::where('jenis_layanan', 'Pembuatan Website');
    
        $total   = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
        $proses  = (clone $baseQuery)->where('status', 'Proses Development')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();
        
        // 6. Ambil data ASN untuk Modal Tambah Manual Admin
        $users = User::where('role', '!=', 'admin')->get();
                        
        return view('admin.website.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    // Admin bikin tiket ajuan Website secara manual 
        public function storeWebsite(Request $request)
        {
            $request->validate([
                'user_id'                       => 'required|exists:users,id',
                'jenis_layanan'                 => 'required|string',
                'data_pengajuan'                => 'required|array',
                'data_pengajuan.nama'           => 'required|string|max:255',
                'data_pengajuan.nip'            => 'nullable|string',
                'data_pengajuan.instansi'       => 'required|string|max:255',
                'data_pengajuan.no_hp'          => 'required|string',
                'data_pengajuan.nama_pimpinan'  => 'required|string|max:255',
                'data_pengajuan.domain'         => 'required|string',
                'file_pendukung'                => 'required|mimes:pdf|max:5120',
            ], [
                'data_pengajuan.nama.required'          => 'Kolom Nama Pemohon wajib diisi.',
                'data_pengajuan.instansi.required'      => 'Kolom Instansi wajib diisi.',
                'data_pengajuan.no_hp.required'         => 'Kolom Nomor HP/WhatsApp wajib diisi.',
                'data_pengajuan.nama_pimpinan.required' => 'Kolom Nama Pimpinan wajib diisi.',
                'data_pengajuan.domain.required'        => 'Kolom Domain wajib diisi.',
            ]);

            // 2. Upload file PDF
            $filePath = null;
            if ($request->hasFile('file_pendukung')) {
                $file = $request->file('file_pendukung');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('pengajuan/website', $fileName, 'public');
            }

            // 3. Simpan ke database (Tanpa json_encode & nomor_tiket dibuat otomatis oleh Model)
            Pengajuan::create([
                'user_id'        => $request->user_id,
                'jenis_layanan'  => $request->jenis_layanan,
                'data_pengajuan' => $request->data_pengajuan, // Array murni langsung disimpan sebagai BSON
                'file_pendukung' => $filePath,
                'status'         => 'Pending', 
            ]);

            return back()->with('sukses', 'Permohonan Website berhasil ditambahkan manual.');
        }


    // Nampilin halaman kelola daftar ajuan Email Resmi
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
    $baseQuery  = Pengajuan::where('jenis_layanan', 'Pembuatan Email Resmi');
    
    $total   = (clone $baseQuery)->count();
    $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
    $proses  = (clone $baseQuery)->where('status', 'Proses Development')->count();
    $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
    $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();
    
   
    $users = User::where('role', '!=', 'admin')->get();
                    
    return view('admin.email.index', compact(
        'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
    ));
}

    // admin bikin tiker email mandiri wok
    public function storeEmailResmi(Request $request)
    {
        // PERBAIKAN: Validasi data sesuai form modal create Email Resmi
        $request->validate([
            'user_id'                      => 'required|exists:users,id',
            'data_pengajuan'               => 'required|array',
            'data_pengajuan.nama'          => 'required|string|max:255',
            'data_pengajuan.nip'           => 'required|string',
            'data_pengajuan.instansi'      => 'required|string|max:255',
            'data_pengajuan.no_hp'         => 'required|string',
            'data_pengajuan.usulan_email'  => 'required|string',
            'file_pendukung'               => 'required|mimes:pdf|max:2048',
        ], [
            'data_pengajuan.nama.required'         => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required'          => 'Kolom NIP wajib diisi.',
            'data_pengajuan.instansi.required'     => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.no_hp.required'        => 'Kolom Nomor HP/WhatsApp wajib diisi.',
            'data_pengajuan.usulan_email.required' => 'Kolom Usulan Email wajib diisi.',
            'file_pendukung.required'              => 'Surat Permohonan (PDF) wajib diunggah.',
        ]);

        // Upload berkas PDF
        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('pengajuan/email', $fileName, 'public');
        }

        // Simpan data permohonan ke database
        Pengajuan::create([
            'user_id'        => $request->user_id,
            'jenis_layanan'  => 'Pembuatan Email Resmi',
            'data_pengajuan' => $request->data_pengajuan,
            'file_pendukung' => $filePath,
            'status'         => 'Pending',
        ]);

        return back()->with('sukses', 'Permohonan Email Resmi berhasil ditambahkan manual.');
    }

    // Nampilin halaman kelola daftar ajuan TTE
    public function layananTte(Request $request)
    {
        $query = Pengajuan::where('jenis_layanan', 'Layanan TTE')->with('user');

        // Filter pencarian khusus berdasarkan Nomor Tiket saja
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        // Filter berdasarkan status (jika ada)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery = Pengajuan::where('jenis_layanan', 'Layanan TTE');
    
        $total   = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
        $proses  = (clone $baseQuery)->where('status', 'Proses BSSN')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();
    
        $users = User::where('role', '!=', 'admin')->get();
                    
        return view('admin.tte.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    // Admin bikin tiket ajuan TTE secara manual
    public function storeTte(Request $request)
    {
       $request->validate([
        'user_id'                 => 'required|exists:users,id',
        'data_pengajuan'          => 'required|array',
        'data_pengajuan.nama'     => 'required|string|max:255',
        'data_pengajuan.nip'      => 'required|string',
        'data_pengajuan.instansi' => 'required|string|max:255',
        'data_pengajuan.no_hp'    => 'required|string',
        'data_pengajuan.email'    => 'required|email',
        'data_pengajuan.alamat'   => 'required|string',
        'file_pendukung'          => 'required|mimes:pdf|max:2048',
    ], [
        'data_pengajuan.nama.required'     => 'Kolom Nama Pemohon wajib diisi.',
        'data_pengajuan.nip.required'      => 'Kolom NIP wajib diisi.',
        'data_pengajuan.instansi.required' => 'Kolom Instansi wajib diisi.',
        'data_pengajuan.no_hp.required'    => 'Kolom Nomor HP/WhatsApp wajib diisi.',
        'data_pengajuan.email.required'    => 'Kolom Email wajib diisi.',
        'data_pengajuan.alamat.required'   => 'Kolom Alamat wajib diisi.',
        'file_pendukung.required'          => 'Dokumen Persyaratan (PDF) wajib diunggah.',
    ]);

        // Upload file PDF persyaratan TTE
        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('pengajuan/tte', $fileName, 'public');
        }

    // Simpan ke database
        Pengajuan::create([
            'user_id'        => $request->user_id,
            'jenis_layanan'  => 'Layanan TTE',
            'data_pengajuan' => $request->data_pengajuan,
            'file_pendukung' => $filePath,
            'status'         => 'Pending',
        ]);

        return back()->with('sukses', 'Permohonan TTE berhasil ditambahkan manual.');
    }

    // Admin bikin tiket ajuan Cloud secara manual dan langsung ngisi format bawaan
    public function layananCloud(Request $request)
{
    $query = Pengajuan::where('jenis_layanan', 'Cloud Government')->with('user');

    // Filter pencarian berdasarkan Nomor Tiket
    if ($request->filled('search')) {
        $search = trim($request->search);
        $query->where('nomor_tiket', 'like', "%{$search}%");
    }

    // Filter berdasarkan status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $pengajuans = $query->latest()->paginate(10);
    $baseQuery  = Pengajuan::where('jenis_layanan', 'Cloud Government');
    
    $total   = (clone $baseQuery)->count();
    $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
    $proses  = (clone $baseQuery)->where('status', 'Proses Development')->count();
    $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
    $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();
    
    $users = User::where('role', '!=', 'admin')->get();
                    
    return view('admin.cloud.index', compact(
        'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
    ));
}

    // admin guweh bikin tiket cloud manual wok
    public function storeCloud(Request $request)
    {
        $request->validate([
            'user_id'                   => 'required|exists:users,id',
            'data_pengajuan'            => 'required|array',
            'data_pengajuan.nama'       => 'required|string|max:255',
            'data_pengajuan.nip'        => 'required|string',
            'data_pengajuan.instansi'   => 'required|string|max:255',
            'data_pengajuan.email'      => 'required|email',
            'data_pengajuan.kapasitas'  => 'required|string',
            'file_pendukung'            => 'required|mimes:pdf|max:2048',
        ], [
            'data_pengajuan.nama.required'      => 'Kolom Nama Penanggung Jawab wajib diisi.',
            'data_pengajuan.nip.required'       => 'Kolom NIP wajib diisi.',
            'data_pengajuan.instansi.required'  => 'Kolom Instansi wajib diisi.',
            'data_pengajuan.email.required'     => 'Kolom Email Aktif wajib diisi.',
            'data_pengajuan.kapasitas.required'  => 'Kolom Kapasitas Penyimpanan wajib dipilih.',
            'file_pendukung.required'           => 'Surat Permohonan Cloud (PDF) wajib diunggah.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('pengajuan/cloud', $fileName, 'public');
        }

        Pengajuan::create([
            'user_id'        => $request->user_id,
            'jenis_layanan'  => 'Cloud Government',
            'data_pengajuan' => $request->data_pengajuan,
            'file_pendukung' => $filePath,
            'status'         => 'Pending',
        ]);

        return back()->with('sukses', 'Permohonan Cloud Government berhasil ditambahkan manual.');
    }

    // Admin bikin tiket kendala bantuan secara manual buat bantu user
    public function layananBantuan(Request $request)
    {
        $query = Pengajuan::where('jenis_layanan', 'Pusat Bantuan')->with('user');

        // Filter pencarian berdasarkan Nomor Tiket
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nomor_tiket', 'like', "%{$search}%");
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $baseQuery  = Pengajuan::where('jenis_layanan', 'Pusat Bantuan');
        
        $total   = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
        $proses  = (clone $baseQuery)->where('status', 'Proses Development')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();
        
        $users = User::where('role', '!=', 'admin')->get();
                        
        return view('admin.bantuan.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    public function storeBantuan(Request $request)
    {
        $request->validate([
            'user_id'                   => 'required|exists:users,id',
            'data_pengajuan'            => 'required|array',
            'data_pengajuan.kategori'   => 'required|string',
            'data_pengajuan.nama'       => 'required|string|max:255',
            'data_pengajuan.nip'        => 'required|string',
            'data_pengajuan.email'      => 'required|email',
            'file_pendukung'            => 'required|mimes:pdf|max:2048',
        ], [
            'data_pengajuan.kategori.required' => 'Kategori kendala wajib dipilih.',
            'data_pengajuan.nama.required'     => 'Kolom Nama Pemohon wajib diisi.',
            'data_pengajuan.nip.required'      => 'Kolom NIP wajib diisi.',
            'data_pengajuan.email.required'    => 'Kolom Email Resmi wajib diisi.',
            'file_pendukung.required'          => 'Surat Permohonan / Bukti (PDF) wajib diunggah.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('pengajuan/bantuan', $fileName, 'public');
        }

        Pengajuan::create([
            'user_id'        => $request->user_id,
            'jenis_layanan'  => 'Pusat Bantuan',
            'data_pengajuan' => $request->data_pengajuan,
            'file_pendukung' => $filePath,
            'status'         => 'Pending',
        ]);

        return back()->with('sukses', 'Tiket Pusat Bantuan berhasil ditambahkan manual.');
    }

}