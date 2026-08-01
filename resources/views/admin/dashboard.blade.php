@extends('layouts.admin')

@section('header_title', 'Dashboard Portal Layanan Diskominsa Aceh Barat')
@section('header_subtitle', 'Pantau seluruh permohonan layanan Diskominsa.')

@section('content')
    <!-- Deretan Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
            </div>
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Pengajuan Web Desa</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">45</h3>
                <span class="text-[12px] font-bold text-blue-500 mb-1">Unit Web</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-500 flex items-center justify-center text-xl group-hover:scale-110 group-hover:bg-cyan-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-envelope-circle-check"></i>
                </div>
            </div>
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Pembuatan Email Baru</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">128</h3>
                <span class="text-[12px] font-bold text-blue-500 mb-1">Akun Aktif</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
            </div>
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Layanan TTE</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">84</h3>
                <span class="text-[12px] font-bold text-blue-500 mb-1">Sertifikat</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-unlock-keyhole"></i>
                </div>
            </div>
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Reset PW / OTP</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">12</h3>
                <span class="text-[12px] font-bold text-orange-500 mb-1">Menunggu</span>
            </div>
        </div>
    </div>

    <!-- TABEL MANAJEMEN E-TRACKING -->
    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-[#071E3D]">Laporan Permohonan & Tracking</h3>
            <p class="text-[12px] text-gray-400 font-medium mt-1">Daftar lengkap permohonan, ubah status untuk mengupdate E-Tracking.</p>
        </div>
        <div class="flex gap-2">
            <div class="relative">
                <i class="fa-solid fa-filter absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                <select class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="verifikasi">Verifikasi</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                <tr>
                    <th class="py-3 px-6">ID Permohonan</th>
                    <th class="py-3 px-6">Data Pemohon</th>
                    <th class="py-3 px-6">Layanan Dipesan</th>
                    <th class="py-3 px-6">Tgl Masuk</th>
                    <th class="py-3 px-6">Status (Edit)</th>
                    <th class="py-3 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                
                {{-- PERSIAPAN BE: Buka komentar @forelse di bawah ini saat data dikirim dari Controller --}}
                {{-- @forelse ($data_permohonan as $item) --}}
                
                <tr class="hover:bg-cyan-50/20 transition-colors duration-200">
                    <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D]">
                        {{-- {{ $item->id_permohonan }} --}} 
                        #REQ-001
                    </td>
                    <td class="py-4 px-6 flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Ahmad+Fauzi&background=F3F4F6&color=374151" class="w-9 h-9 rounded-full object-cover">
                        <div>
                            <p class="text-[13px] font-bold text-[#071E3D]">
                                {{-- {{ $item->user->nama }} --}} Ahmad Fauzi
                            </p>
                            <p class="text-[11px] text-gray-500 font-medium">
                                {{-- {{ $item->user->instansi }} --}} Desa Panggong
                            </p>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="text-[12px] font-bold text-gray-700 flex items-center gap-2">
                            <i class="fa-solid fa-laptop-code text-indigo-500 bg-indigo-50 p-1.5 rounded-md"></i> 
                            {{-- {{ $item->jenis_layanan }} --}} Pembuatan Web
                        </span>
                    </td>
                    <td class="py-4 px-6 text-[12px] text-gray-500 font-bold">
                        {{-- {{ $item->created_at->format('d M Y') }} --}} 22 Jul 2026
                    </td>
                    
                    <!-- Form Update Status -->
                    <td class="py-4 px-6">
                        {{-- <form action="{{ route('admin.tracking.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT') --}}
                            <div class="relative inline-block">
                                <!-- Atribut onchange="this.form.submit()" membuat sistem langsung menyimpan data begitu Admin memilih status baru -->
                                <select name="status" onchange="/* this.form.submit() */" class="appearance-none bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 hover:border-blue-200 font-extrabold text-[10px] uppercase tracking-wider py-1.5 pl-3 pr-7 rounded-lg cursor-pointer outline-none transition-all">
                                    <option value="pending" {{-- $item->status == 'pending' ? 'selected' : '' --}}>Pending</option>
                                    <option value="verifikasi" selected {{-- $item->status == 'verifikasi' ? 'selected' : '' --}}>Verifikasi Doc</option>
                                    <option value="proses" {{-- $item->status == 'proses' ? 'selected' : '' --}}>Diproses</option>
                                    <option value="selesai" {{-- $item->status == 'selesai' ? 'selected' : '' --}}>Selesai</option>
                                    <option value="ditolak" {{-- $item->status == 'ditolak' ? 'selected' : '' --}}>Ditolak</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-2.5 top-1/2 transform -translate-y-1/2 text-[10px] text-blue-600 pointer-events-none"></i>
                            </div>
                        {{-- </form> --}}
                    </td>
                    
                    <!-- Aksi Detail & Edit -->
                    <td class="py-4 px-6 text-right space-x-1">
                        <a href="#" {{-- href="{{ route('admin.tracking.show', $item->id) }}" --}} class="inline-block p-2 text-gray-400 hover:text-cyan-500 transition-colors bg-white hover:bg-cyan-50 rounded-lg shadow-sm border border-gray-100" title="Detail Tracking">
                            <i class="fa-solid fa-eye text-[13px]"></i>
                        </a>
                        <a href="#" {{-- href="{{ route('admin.tracking.edit', $item->id) }}" --}} class="inline-block p-2 text-gray-400 hover:text-blue-500 transition-colors bg-white hover:bg-blue-50 rounded-lg shadow-sm border border-gray-100" title="Edit Data User">
                            <i class="fa-solid fa-pen text-[13px]"></i>
                        </a>
                    </td>
                </tr>

                {{-- BE: Tampilan jika data masih kosong --}}
                {{-- @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-[13px] font-bold text-gray-400">
                        Belum ada permohonan layanan yang masuk.
                    </td>
                </tr>
                @endforelse --}}

            </tbody>
        </table>
    </div>
</div>
@endsection