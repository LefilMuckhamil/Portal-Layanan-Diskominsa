@extends('layouts.user')

@section('title', 'Riwayat Pengajuan Saya')

@section('content')
    <!-- Background Container Lebih Luas & Estetik -->
    <div class="relative overflow-hidden bg-gradient-to-br from-white via-[#f8fafc] to-[#f1f8ff] rounded-[2.5rem] p-8 sm:p-10 lg:p-12 shadow-[0_20px_50px_-15px_rgba(7,30,61,0.08)] border border-white/80 w-full">
        
        <!-- Ornamen Blobs (Cahaya di Pojok) -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-gradient-to-br from-cyan-300/20 to-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 bg-gradient-to-tr from-cyan-400/15 to-[#071E3D]/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Header -->
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5 mb-10 pb-8 border-b border-gray-200/60">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#071E3D] to-[#1F4287] text-white flex items-center justify-center text-2xl shrink-0 shadow-lg shadow-blue-900/20 transform -rotate-3 hover:rotate-0 transition-all duration-300">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#071E3D] tracking-tight">Riwayat Pengajuan</h2>
                    <p class="text-[14px] text-gray-500 font-medium mt-1">Pantau proses dan status seluruh pengajuan layanan digital Anda secara <span class="text-[#071E3D] font-semibold">real-time</span>.</p>
                </div>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="overflow-x-auto pb-4 relative z-10 custom-scrollbar">
            <!-- W-full agar mengisi penuh card dari kiri ke kanan -->
            <table class="w-full text-left border-separate border-spacing-y-3.5 whitespace-nowrap">
                <thead>
                    <tr class="text-[11px] uppercase tracking-wider text-gray-400 font-extrabold">
                        <th class="py-2 px-5">ID Permohonan</th>
                        <th class="py-2 px-5">Data Pemohon</th>
                        <th class="py-2 px-5">Jenis Layanan</th>
                        <th class="py-2 px-5">Tanggal Pengajuan</th>
                        <th class="py-2 px-5">Status</th>
                        <th class="py-2 px-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-[13.5px]">
                    
                    @forelse ($pengajuans as $item)
                        <!-- Desain Kartu Melayang per Baris -->
                        <tr class="group bg-white/90 backdrop-blur-md shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-[0_10px_25px_rgba(7,30,61,0.06)] hover:-translate-y-0.5 transition-all duration-300">
                            
                            <!-- ID Permohonan -->
                            <td class="py-4 px-5 rounded-l-2xl border-y border-l border-gray-100 group-hover:border-cyan-300/60 transition-colors">
                                <span class="font-mono text-[12.5px] font-bold text-[#1F4287] bg-blue-50/70 px-3 py-1.5 rounded-lg border border-blue-100/80">
                                    #REQ-{{ strtoupper(substr($item->id, -5)) }}
                                </span>
                            </td>

                            <!-- Data Pemohon -->
                            <td class="py-4 px-5 border-y border-gray-100 group-hover:border-cyan-300/60 transition-colors">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=f0fdfa&color=0d9488" class="w-9 h-9 rounded-full border border-teal-100">
                                    <div>
                                        <p class="font-bold text-[#071E3D] leading-snug">{{ Auth::user()->name }}</p>
                                        <p class="text-[11.5px] text-gray-400 capitalize font-medium">{{ Auth::user()->role }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Jenis Layanan -->
                            <td class="py-4 px-5 border-y border-gray-100 group-hover:border-cyan-300/60 transition-colors">
                                <div class="font-bold text-[#071E3D] capitalize flex items-center gap-2.5 bg-gray-50/80 w-max px-3.5 py-1.5 rounded-xl border border-gray-200/60">
                                    @if($item->jenis_layanan == 'website') <i class="fa-solid fa-globe text-cyan-500 text-base"></i>
                                    @elseif($item->jenis_layanan == 'email') <i class="fa-solid fa-envelope text-cyan-500 text-base"></i>
                                    @elseif($item->jenis_layanan == 'tte') <i class="fa-solid fa-pen-nib text-cyan-500 text-base"></i>
                                    @elseif($item->jenis_layanan == 'cloud') <i class="fa-solid fa-cloud text-cyan-500 text-base"></i>
                                    @else <i class="fa-solid fa-headset text-rose-500 text-base"></i>
                                    @endif
                                    {{ str_replace('_', ' ', $item->jenis_layanan) }}
                                </div>
                            </td>

                            <!-- Tanggal Pengajuan -->
                            <td class="py-4 px-5 border-y border-gray-100 group-hover:border-cyan-300/60 transition-colors font-medium">
                                <span class="flex items-center gap-2 text-gray-600">
                                    <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                                        <i class="fa-regular fa-calendar-days text-xs"></i>
                                    </div>
                                    {{ $item->created_at->translatedFormat('d F Y') }}
                                </span>
                            </td>

                            <!-- Status Layanan -->
                            <td class="py-4 px-5 border-y border-gray-100 group-hover:border-cyan-300/60 transition-colors">
                                @php
                                    $badgeColor = match($item->status) {
                                        'Menunggu Validasi' => 'bg-amber-50 text-amber-600 border-amber-200/80',
                                        'Diproses' => 'bg-blue-50 text-blue-600 border-blue-200/80',
                                        'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-200/80',
                                        'Ditolak' => 'bg-rose-50 text-rose-600 border-rose-200/80',
                                        default => 'bg-gray-50 text-gray-600 border-gray-200/80'
                                    };
                                @endphp
                                <span class="px-3.5 py-1.5 rounded-xl border text-[11.5px] font-bold {{ $badgeColor }} flex items-center inline-flex gap-1.5 w-max">
                                    @if($item->status == 'Menunggu Validasi') <i class="fa-solid fa-clock-rotate-left"></i>
                                    @elseif($item->status == 'Diproses') <i class="fa-solid fa-gears"></i>
                                    @elseif($item->status == 'Selesai') <i class="fa-solid fa-check-circle"></i>
                                    @else <i class="fa-solid fa-circle-xmark"></i>
                                    @endif
                                    {{ $item->status }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-5 rounded-r-2xl border-y border-r border-gray-100 group-hover:border-cyan-300/60 transition-colors text-center">
                                <button class="bg-gray-50 hover:bg-[#071E3D] text-gray-400 hover:text-white border border-gray-200/80 hover:border-[#071E3D] w-9 h-9 rounded-xl transition-all duration-300 shadow-sm cursor-pointer flex items-center justify-center mx-auto" title="Detail">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <!-- Jika Data Kosong -->
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <div class="w-20 h-20 bg-white shadow-xl shadow-blue-900/5 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                    <i class="fa-regular fa-folder-open text-3xl text-cyan-500"></i>
                                </div>
                                <h3 class="font-extrabold text-[17px] text-[#071E3D] mb-1">Tidak Ada Riwayat</h3>
                                <p class="text-[13.5px] text-gray-400">Anda belum membuat pengajuan layanan apapun saat ini.</p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <!-- Scrollbar halus -->
    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
@endsection