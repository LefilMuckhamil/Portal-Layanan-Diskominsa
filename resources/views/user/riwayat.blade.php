@extends('layouts.user')

@section('title', 'Riwayat Pengajuan Saya')
@section('max_width', 'max-w-7xl')

@section('content')
    
    <!-- GRID CONTAINER: Membagi layar jadi Kiri (Tabel) dan Kanan (Chat) -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8 w-full items-start">
        
        <!-- ========================================== -->
        <!-- KOLOM KIRI (Garis Orange): TABEL RIWAYAT -->
        <!-- ========================================== -->
        <div class="xl:col-span-2 relative overflow-hidden bg-gradient-to-br from-white via-[#f8fafc] to-[#f1f8ff] rounded-[2.5rem] p-6 sm:p-8 lg:p-10 shadow-[0_20px_50px_-15px_rgba(7,30,61,0.08)] border border-white/80">
            
            <!-- Ornamen Blobs (Cahaya di Pojok) -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-gradient-to-br from-cyan-300/20 to-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
            
            <!-- Header Kiri -->
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5 mb-8 pb-6 border-b border-gray-200/60">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#071E3D] to-[#1F4287] text-white flex items-center justify-center text-xl shrink-0 shadow-lg shadow-blue-900/20 transform -rotate-3">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-extrabold text-[#071E3D] tracking-tight">Riwayat Pengajuan</h2>
                        <p class="text-[13px] text-gray-500 font-medium mt-1">Pantau proses pengajuan Anda di sini.</p>
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="overflow-x-auto pb-4 relative z-10 custom-scrollbar">
                <table class="w-full text-left border-separate border-spacing-y-3 whitespace-nowrap">
                    <thead>
                        <tr class="text-[11px] uppercase tracking-wider text-gray-400 font-extrabold">
                            <th class="py-2 px-4">ID Permohonan</th>
                            <th class="py-2 px-4">Layanan</th>
                            <th class="py-2 px-4">Tanggal</th>
                            <th class="py-2 px-4">Status</th>
                            <th class="py-2 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-[13px]">
                        @forelse ($pengajuans as $item)
                            <tr class="group bg-white/90 backdrop-blur-md shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-[0_10px_25px_rgba(7,30,61,0.06)] hover:-translate-y-0.5 transition-all duration-300">
                                
                                <td class="py-3 px-4 rounded-l-2xl border-y border-l border-gray-100 group-hover:border-cyan-300/60">
                                    <span class="font-mono text-[12px] font-bold text-[#1F4287] bg-blue-50/70 px-2.5 py-1 rounded-md border border-blue-100/80">
                                        #REQ-{{ strtoupper(substr($item->id, -5)) }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 border-y border-gray-100 group-hover:border-cyan-300/60 font-bold text-[#071E3D] capitalize">
                                    {{ str_replace('_', ' ', $item->jenis_layanan) }}
                                </td>

                                <td class="py-3 px-4 border-y border-gray-100 group-hover:border-cyan-300/60 text-gray-500 font-medium">
                                    {{ $item->created_at->translatedFormat('d M Y') }}
                                </td>

                                <td class="py-3 px-4 border-y border-gray-100 group-hover:border-cyan-300/60">
                                    @php
                                        $badgeColor = match($item->status) {
                                            'Menunggu Validasi' => 'bg-amber-50 text-amber-600 border-amber-200/80',
                                            'Diproses'          => 'bg-blue-50 text-blue-600 border-blue-200/80',
                                            'Selesai'           => 'bg-emerald-50 text-emerald-600 border-emerald-200/80',
                                            'Ditolak'           => 'bg-rose-50 text-rose-600 border-rose-200/80',
                                            default             => 'bg-gray-50 text-gray-600 border-gray-200/80'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-lg border text-[11px] font-bold {{ $badgeColor }}">
                                        {{ $item->status }}
                                    </span>
                                </td>

                                <!-- Tombol Aksi Kanan -->
                                <td class="py-3 px-4 rounded-r-2xl border-y border-r border-gray-100 group-hover:border-cyan-300/60 text-center">
                                    <!-- Nanti ini bisa dipicu menggunakan JavaScript untuk membuka chat -->
                                    <button class="bg-gray-50 hover:bg-cyan-500 text-gray-400 hover:text-white border border-gray-200/80 hover:border-cyan-500 w-8 h-8 rounded-lg transition-all duration-300 shadow-sm flex items-center justify-center mx-auto" title="Lihat Aktivitas">
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <div class="w-16 h-16 bg-white shadow-xl shadow-blue-900/5 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-100">
                                        <i class="fa-regular fa-folder-open text-2xl text-cyan-500"></i>
                                    </div>
                                    <h3 class="font-extrabold text-[15px] text-[#071E3D]">Tidak Ada Riwayat</h3>
                                    <p class="text-[12.5px] text-gray-400 mt-1">Belum ada pengajuan dibuat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <!-- ========================================== -->
        <!-- KOLOM KANAN (Garis Hijau): PANEL AKTIVITAS -->
        <!-- ========================================== -->
        <div class="xl:col-span-1 bg-white rounded-[2.5rem] shadow-[0_20px_50px_-15px_rgba(7,30,61,0.08)] border border-gray-100 h-[600px] flex flex-col overflow-hidden sticky top-28">
            
            <!-- Header Panel Kanan -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-regular fa-comments"></i>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-[#071E3D] leading-tight">Pusat Bantuan</h3>
                    <p class="text-[12px] text-gray-500 font-medium">Aktivitas & Pesan Admin</p>
                </div>
            </div>

            <!-- Area Konten Placeholder (Kondisi saat belum ada yang diklik) -->
            <div class="flex-grow flex flex-col items-center justify-center p-8 text-center bg-gray-50/30">
                <div class="w-24 h-24 mb-4 opacity-40">
                    <!-- SVG Ilustrasi Kosong -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full text-cyan-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                    </svg>
                </div>
                <h4 class="font-extrabold text-[#071E3D] text-[15px] mb-2">Pilih Pengajuan</h4>
                <p class="text-[13px] text-gray-500 leading-relaxed">
                    Klik tombol panah <i class="fa-solid fa-arrow-right mx-1"></i> pada tabel di sebelah kiri untuk melihat detail aktivitas dan mengirim pesan ke Admin.
                </p>
            </div>
            
            <!-- Kotak Ketik Pesan (Dimatikan sementara jika belum memilih) -->
            <div class="p-5 border-t border-gray-100 bg-white">
                <div class="flex gap-2 opacity-50 cursor-not-allowed">
                    <input type="text" disabled placeholder="Pilih pengajuan terlebih dahulu..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] outline-none">
                    <button disabled class="w-12 h-12 shrink-0 bg-gray-300 text-white rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </div>

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