@extends('layouts.admin')

@section('header_title', 'Dashboard Portal Layanan Diskominsa Aceh Barat')
@section('header_subtitle', 'Pantau seluruh statistik dan permohonan layanan secara real-time.')

@section('content')
    <!-- Deretan Kartu Statistik Dinamis (5 Kolom) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-6">
        
        <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
            <div class="flex justify-between items-start mb-3">
                <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Website</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-extrabold text-[#071E3D] leading-none">{{ $countWeb ?? 0 }}</h3>
                    <span class="text-[10px] font-bold text-indigo-500 mb-1">Ajuan</span>
                </div>
            </div>
        </div>

        <!-- 2. Email Resmi -->
        <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
            <div class="flex justify-between items-start mb-3">
                <div class="w-11 h-11 rounded-2xl bg-cyan-50 text-cyan-500 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-cyan-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-envelope-circle-check"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Email Resmi</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-extrabold text-[#071E3D] leading-none">{{ $countEmail ?? 0 }}</h3>
                    <span class="text-[10px] font-bold text-cyan-500 mb-1">Ajuan</span>
                </div>
            </div>
        </div>

        <!-- 3. Layanan TTE -->
        <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
            <div class="flex justify-between items-start mb-3">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Layanan TTE</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-extrabold text-[#071E3D] leading-none">{{ $countTTE ?? 0 }}</h3>
                    <span class="text-[10px] font-bold text-emerald-500 mb-1">Ajuan</span>
                </div>
            </div>
        </div>

        <!-- 4. Cloud Gov -->
        <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
            <div class="flex justify-between items-start mb-3">
                <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-sky-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Cloud Gov</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-extrabold text-[#071E3D] leading-none">{{ $countCloud ?? 0 }}</h3>
                    <span class="text-[10px] font-bold text-sky-500 mb-1">Ajuan</span>
                </div>
            </div>
        </div>

        <!-- 5. Reset PW / OTP -->
        <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
            <div class="flex justify-between items-start mb-3">
                <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-unlock-keyhole"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Bantuan / OTP</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-extrabold text-[#071E3D] leading-none">{{ $countBantuan ?? 0 }}</h3>
                    <span class="text-[10px] font-bold text-rose-500 mb-1">Ajuan</span>
                </div>
            </div>
        </div>

    </div>

    <!-- TABEL MANAJEMEN E-TRACKING UMUM -->
    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col mt-6">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-[#071E3D]">Permohonan Terbaru Terpadu</h3>
                <p class="text-[12px] text-gray-400 font-medium mt-1">Daftar semua permohonan masuk dari berbagai layanan.</p>
            </div>
            
            <!-- Notifikasi Sukses Update (Opsional) -->
            @if(session('sukses'))
                <span class="px-4 py-2 bg-green-50 text-green-600 text-[12px] font-bold rounded-lg border border-green-100">
                    <i class="fa-solid fa-check mr-1"></i> {{ session('sukses') }}
                </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead class="bg-gray-50/50 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                    <tr>
                        <th class="py-3 px-6">Id Permohonan</th>
                        <th class="py-3 px-6">Nama</th>
                        <th class="py-3 px-6">Layanan Dipesan</th>
                        <th class="py-3 px-6">Tgl Masuk</th>
                        <th class="py-3 px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    
                    @forelse ($pengajuans as $item)
                    <tr class="hover:bg-cyan-50/10 transition-colors duration-200">
                        <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D]">
                            {{ $item->nomor_tiket }}
                        </td>
                        <td class="py-4 px-6 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-sm">
                                <i class="fa-regular fa-user"></i>
                            </div>
                            <div>
                                <p class="text-[13px] font-bold text-[#071E3D]">
                                    {{ $item->user->name ?? 'User Tidak Ditemukan' }}
                                </p>
                                <p class="text-[11px] text-gray-500 font-medium">
                                    {{ $item->user->unit_kerja ?? 'Instansi' }}
                                </p>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-[12px] font-bold text-gray-700 capitalize">
                                {{ str_replace('_', ' ', $item->jenis_layanan) }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-[12px] text-gray-500 font-bold">
                            {{ $item->created_at->format('d M Y') }}
                        </td>
                        
                        <!-- Form Update Status Cepat (Auto Submit) -->
                        <td class="py-4 px-6">
                            <form action="{{ route('admin.pengajuan.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="relative inline-block w-40">
                                    <select name="status" onchange="this.form.submit()" class="appearance-none w-full bg-white text-gray-700 border border-gray-200 hover:border-cyan-400 focus:border-cyan-400 font-extrabold text-[11px] uppercase tracking-wider py-2 pl-3 pr-8 rounded-xl cursor-pointer outline-none transition-all shadow-sm">
                                        <option value="Pending" {{ $item->status == 'Pending' ? 'selected' : '' }}>PENDING</option>
                                        <option value="Verifikasi Doc" {{ $item->status == 'Verifikasi Doc' ? 'selected' : '' }}>VERIFIKASI DOC</option>
                                        <option value="Proses Development" {{ $item->status == 'Proses Development' ? 'selected' : '' }}>PROSES</option>
                                        <option value="Selesai" {{ $item->status == 'Selesai' ? 'selected' : '' }}>SELESAI</option>
                                        <option value="Ditolak" {{ $item->status == 'Ditolak' ? 'selected' : '' }}>DITOLAK</option>
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-regular fa-folder-open text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="font-bold text-[14px] text-gray-600">Belum ada permohonan</h3>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
@endsection