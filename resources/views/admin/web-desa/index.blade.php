@extends('layouts.admin')

@section('header_title', 'Manajemen Web Desa & Pesantren')
@section('header_subtitle', 'Kelola permohonan, verifikasi berkas, dan pantau progres pembuatan website.')

@section('content')
    <!-- Deretan Kartu Statistik Khusus Web -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Kartu 1 -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
            </div>
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Total Permohonan</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">45</h3>
                <span class="text-[12px] font-bold text-indigo-500 mb-1">Unit Web</span>
            </div>
        </div>

        <!-- Kartu 2 -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-file-circle-exclamation"></i>
                </div>
            </div>
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Menunggu Verifikasi</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">8</h3>
                <span class="text-[12px] font-bold text-amber-500 mb-1">Permohonan</span>
            </div>
        </div>

        <!-- Kartu 3 -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-code"></i>
                </div>
            </div>
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Proses Development</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">12</h3>
                <span class="text-[12px] font-bold text-blue-500 mb-1">Sedang dikerjakan</span>
            </div>
        </div>

        <!-- Kartu 4 -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
            </div>
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Selesai / Online</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">25</h3>
                <span class="text-[12px] font-bold text-green-500 mb-1">Web Aktif</span>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Permohonan Web Desa -->
    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-[#071E3D]">Daftar Ajuan Website</h3>
                <p class="text-[12px] text-gray-400 font-medium mt-1">Kelola data dan perbarui status progres pembuatan website.</p>
            </div>
            
            <div class="flex gap-3">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <input type="text" placeholder="Cari nama desa..." class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none focus:border-cyan-400 focus:bg-white w-48 transition-all">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-filter absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <select class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="verifikasi">Verifikasi Dokumen</option>
                        <option value="proses">Proses Development</option>
                        <option value="selesai">Selesai / Online</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead class="bg-gray-50/50 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                    <tr>
                        <th class="py-3 px-6">ID Ajuan</th>
                        <th class="py-3 px-6">Perangkat / Instansi</th>
                        <th class="py-3 px-6">Domain Ajuan</th>
                        <th class="py-3 px-6">Tgl Masuk</th>
                        <th class="py-3 px-6">Status (Edit)</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    
                    {{-- PERSIAPAN BE: Looping Data --}}
                    {{-- @forelse ($data_web as $item) --}}
                    
                    <tr class="hover:bg-cyan-50/20 transition-colors duration-200">
                        <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D]">
                            #WEB-001
                        </td>
                        <td class="py-4 px-6 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center font-bold">
                                P
                            </div>
                            <div>
                                <p class="text-[13px] font-bold text-[#071E3D]">
                                    Ahmad Fauzi (Keuchik)
                                </p>
                                <p class="text-[11px] text-gray-500 font-medium">
                                    Desa Panggong
                                </p>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <a href="#" class="text-[12px] font-bold text-cyan-600 hover:underline">
                                panggong.desa.id
                            </a>
                        </td>
                        <td class="py-4 px-6 text-[12px] text-gray-500 font-bold">
                            22 Jul 2026
                        </td>
                        
                        <!-- Form Update Status Khusus Web -->
                        <td class="py-4 px-6">
                            <div class="relative inline-block">
                                <select name="status" onchange="/* this.form.submit() */" class="appearance-none bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 hover:border-blue-200 font-extrabold text-[10px] uppercase tracking-wider py-1.5 pl-3 pr-7 rounded-lg cursor-pointer outline-none transition-all">
                                    <option value="pending">Pending</option>
                                    <option value="verifikasi" selected>Verifikasi Doc</option>
                                    <option value="proses">Proses Develop</option>
                                    <option value="selesai">Selesai / Online</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-2.5 top-1/2 transform -translate-y-1/2 text-[10px] text-blue-600 pointer-events-none"></i>
                            </div>
                        </td>
                        
                        <td class="py-4 px-6 text-right space-x-1">
                            <button class="p-2 text-gray-400 hover:text-green-500 transition-colors bg-white hover:bg-green-50 rounded-lg shadow-sm border border-gray-100" title="Cek Dokumen Kelengkapan">
                                <i class="fa-solid fa-file-lines text-[13px]"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-cyan-500 transition-colors bg-white hover:bg-cyan-50 rounded-lg shadow-sm border border-gray-100" title="Detail Tracking">
                                <i class="fa-solid fa-eye text-[13px]"></i>
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-cyan-50/20 transition-colors duration-200">
                        <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D]">
                            #WEB-002
                        </td>
                        <td class="py-4 px-6 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center font-bold">
                                A
                            </div>
                            <div>
                                <p class="text-[13px] font-bold text-[#071E3D]">
                                    Ust. Rahmat
                                </p>
                                <p class="text-[11px] text-gray-500 font-medium">
                                    Ponpes Al-Ikhlas
                                </p>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-[12px] font-bold text-gray-400">
                                alikhlas.ponpes.id
                            </span>
                        </td>
                        <td class="py-4 px-6 text-[12px] text-gray-500 font-bold">
                            20 Jul 2026
                        </td>
                        
                        <td class="py-4 px-6">
                            <div class="relative inline-block">
                                <select name="status" class="appearance-none bg-amber-50 text-amber-600 border border-amber-100 hover:bg-amber-100 hover:border-amber-200 font-extrabold text-[10px] uppercase tracking-wider py-1.5 pl-3 pr-7 rounded-lg cursor-pointer outline-none transition-all">
                                    <option value="pending" selected>Pending</option>
                                    <option value="verifikasi">Verifikasi Doc</option>
                                    <option value="proses">Proses Develop</option>
                                    <option value="selesai">Selesai / Online</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-2.5 top-1/2 transform -translate-y-1/2 text-[10px] text-amber-600 pointer-events-none"></i>
                            </div>
                        </td>
                        
                        <td class="py-4 px-6 text-right space-x-1">
                            <button class="p-2 text-gray-400 hover:text-green-500 transition-colors bg-white hover:bg-green-50 rounded-lg shadow-sm border border-gray-100" title="Cek Dokumen Kelengkapan">
                                <i class="fa-solid fa-file-lines text-[13px]"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-cyan-500 transition-colors bg-white hover:bg-cyan-50 rounded-lg shadow-sm border border-gray-100" title="Detail Tracking">
                                <i class="fa-solid fa-eye text-[13px]"></i>
                            </button>
                        </td>
                    </tr>

                    {{-- @empty --}}
                    {{-- @endforelse --}}

                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/30 text-[12px] font-medium text-gray-500">
            <p>Menampilkan 1-10 dari 10 permohonan</p>
            <div class="flex gap-1">
                <button class="w-8 h-8 rounded-lg border border-gray-200 bg-white text-gray-400 cursor-not-allowed"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="w-8 h-8 rounded-lg bg-[#071E3D] text-white shadow-md">1</button>
                <button class="w-8 h-8 rounded-lg bg-[#071E3D] text-white shadow-md">1</button>
                <button class="w-8 h-8 rounded-lg border border-gray-200 bg-white hover:bg-cyan-50 hover:text-cyan-600 hover:border-cyan-200 transition-colors">2</button>
                <button class="w-8 h-8 rounded-lg border border-gray-200 bg-white hover:bg-cyan-50 hover:text-cyan-600 hover:border-cyan-200 transition-colors"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
@endsection