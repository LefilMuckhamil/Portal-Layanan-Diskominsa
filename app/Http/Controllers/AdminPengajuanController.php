<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = Pengajuan::latest()->get(); 
        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        return view('admin.pengajuan.detail', compact('pengajuan'));
    }

    public function updateProgres(Request $request, $id)
    {
        $request->validate([
            'status'  => ['required', 'string'],
            'catatan' => ['nullable', 'string', 'max:500'],
            'pesan'   => ['nullable', 'string', 'max:1000'],
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = $request->status;

        if ($request->filled('catatan')) {
            $logs = $pengajuan->logs ?? [];
            $logs[] = [
                'status'     => $request->status,
                'catatan'    => $request->catatan,
                'created_at' => now()->toDateTimeString(),
            ];
            $pengajuan->logs = $logs;
        }

        if ($request->filled('pesan')) {
            $pesan = $pengajuan->pesan ?? [];
            $pesan[] = [
                'pengirim' => Auth::user()->name ?? 'Admin Diskominsa',
                'role'     => 'admin',
                'isi'      => $request->pesan,
                'waktu'    => now()->format('d M Y, H:i')
            ];
            $pengajuan->pesan = $pesan;
        }

        $pengajuan->save();

        return back()->with('sukses', 'Progres layanan dan pesan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->delete();

        return back()->with('sukses', 'Data permohonan berhasil dihapus permanen.');
    }

    public function webDesa(Request $request)
    {
        $query = Pengajuan::where('jenis_layanan', 'Pembuatan Web Desa')->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->get(); 
        $baseQuery = Pengajuan::where('jenis_layanan', 'Pembuatan Web Desa');
        
        $total   = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
        $proses  = (clone $baseQuery)->where('status', 'Proses Development')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();
        
        $users = User::where('role', 'asn')->get();
                        
        return view('admin.web-desa.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    public function storeWebDesa(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        Pengajuan::create([
            'user_id'       => $request->user_id,
            'jenis_layanan' => 'Pembuatan Web Desa',
            'status'        => 'Pending',
        ]);

        return back()->with('sukses', 'Permohonan baru berhasil ditambahkan secara manual.');
    }

    public function emailResmi(Request $request)
    {
        $query = Pengajuan::where('jenis_layanan', 'Pembuatan Email Resmi')->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->get(); 

        $baseQuery = Pengajuan::where('jenis_layanan', 'Pembuatan Email Resmi');
        
        $total   = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
        $proses  = (clone $baseQuery)->where('status', 'Proses Development')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();
        
        $users = User::where('role', 'asn')->get();
                        
        return view('admin.email.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    public function storeEmailResmi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        Pengajuan::create([
            'user_id'       => $request->user_id,
            'jenis_layanan' => 'Pembuatan Email Resmi',
            'status'        => 'Pending',
        ]);

        return back()->with('sukses', 'Permohonan Email Resmi berhasil ditambahkan secara manual.');
    }

    public function layananTte(Request $request)
    {
        $query = Pengajuan::where('jenis_layanan', 'Layanan TTE')->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->get(); 

        $baseQuery = Pengajuan::where('jenis_layanan', 'Layanan TTE');
        
        $total   = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Verifikasi Doc'])->count();
        $proses  = (clone $baseQuery)->where('status', 'Proses BSSN')->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();
        
        $users = User::where('role', 'asn')->get();
                        
        return view('admin.tte.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }

    public function storeTte(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        Pengajuan::create([
            'user_id'       => $request->user_id,
            'jenis_layanan' => 'Layanan TTE',
            'status'        => 'Pending',
        ]);

        return back()->with('sukses', 'Permohonan TTE berhasil ditambahkan secara manual.');
    }

   public function layananCloud(Request $request)
    {
        $query = Pengajuan::where('jenis_layanan', 'Cloud Government');
        
        // Fitur Pencarian Cloud
        if ($request->filled('search')) {
            $query->where('data_pengajuan', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Status Cloud
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->get();

        // Hitung data untuk 5 Kartu Statistik (Sama seperti Email/TTE)
        $baseQuery = Pengajuan::where('jenis_layanan', 'Cloud Government');
        
        $total   = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->whereIn('status', ['Pending', 'Menunggu Validasi'])->count();
        $proses  = (clone $baseQuery)->whereIn('status', ['Proses', 'Sedang Diproses'])->count();
        $selesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        $ditolak = (clone $baseQuery)->where('status', 'Ditolak')->count();
        
        // Ambil data ASN & Instansi untuk Form Tambah Manual (Admin tidak ikut)
        $users = User::whereIn('role', ['asn', 'instansi'])->get();
                        
        return view('admin.cloud.index', compact(
            'pengajuans', 'total', 'pending', 'proses', 'selesai', 'ditolak', 'users'
        ));
    }
}