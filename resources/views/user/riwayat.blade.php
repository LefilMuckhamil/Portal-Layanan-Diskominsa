@extends('layouts.user')

@section('title', 'Riwayat Pengajuan Saya')
@section('max_width', 'max-w-7xl')

@section('content')
    
    <!-- GRID CONTAINER: Membagi layar jadi Kiri (Tabel) dan Kanan (Panel Interaktif) -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8 w-full items-start">
        
        <!-- ========================================== -->
        <!-- KOLOM KIRI: TABEL RIWAYAT -->
        <!-- ========================================== -->
        <div class="xl:col-span-2 relative overflow-hidden bg-gradient-to-br from-white via-[#f8fafc] to-[#f1f8ff] rounded-[2.5rem] p-6 sm:p-8 lg:p-10 shadow-[0_20px_50px_-15px_rgba(7,30,61,0.08)] border border-white/80">
            
            <!-- Ornamen Blobs -->
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
                            @php
                                // Cek apakah baris ini sedang aktif dipilih
                                $isActive = request('id') == $item->id;
                            @endphp
                            
                            <tr onclick="pilihPengajuan('{{ $item->id }}')" class="group bg-white/90 backdrop-blur-md shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-[0_10px_25px_rgba(7,30,61,0.06)] hover:-translate-y-0.5 transition-all duration-300 cursor-pointer {{ $isActive ? 'ring-2 ring-cyan-400' : '' }}">
                                
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
                                            'Pending', 'Menunggu Validasi' => 'bg-amber-50 text-amber-600 border-amber-200/80',
                                            'Verifikasi Doc', 'Diproses', 'Proses Development' => 'bg-blue-50 text-blue-600 border-blue-200/80',
                                            'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-200/80',
                                            'Ditolak' => 'bg-rose-50 text-rose-600 border-rose-200/80',
                                            default   => 'bg-gray-50 text-gray-600 border-gray-200/80'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-lg border text-[11px] font-bold {{ $badgeColor }}">
                                        {{ $item->status }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 rounded-r-2xl border-y border-r border-gray-100 group-hover:border-cyan-300/60 text-center">
                                    <button type="button" class="bg-gray-50 group-hover:bg-cyan-500 text-gray-400 group-hover:text-white border border-gray-200/80 group-hover:border-cyan-500 w-8 h-8 rounded-lg transition-all duration-300 shadow-sm flex items-center justify-center mx-auto" title="Lihat Aktivitas">
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
        <!-- KOLOM KANAN: PANEL AKTIVITAS & CHAT ADMIN -->
        <!-- ========================================== -->
        <div class="xl:col-span-1 bg-white rounded-[2.5rem] shadow-[0_20px_50px_-15px_rgba(7,30,61,0.08)] border border-gray-100 h-[600px] flex flex-col overflow-hidden sticky top-28">
            
            <!-- Header Panel Kanan -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-regular fa-comments"></i>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-[#071E3D] leading-tight">Pusat Bantuan</h3>
                    <p class="text-[12px] text-gray-500 font-medium" id="panel-subtitle">Aktivitas & Pesan Admin</p>
                </div>
            </div>

            <!-- Area Konten Dinamis (Default / Saat Belum Dipilih) -->
            <div id="panel-kosong" class="flex-grow flex flex-col items-center justify-center p-8 text-center bg-gray-50/30">
                <div class="w-24 h-24 mb-4 opacity-40">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full text-cyan-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                    </svg>
                </div>
                <h4 class="font-extrabold text-[#071E3D] text-[15px] mb-2">Pilih Pengajuan</h4>
                <p class="text-[13px] text-gray-500 leading-relaxed">
                    Klik tombol panah <i class="fa-solid fa-arrow-right mx-1"></i> pada tabel di sebelah kiri untuk melihat detail aktivitas dan mengirim pesan ke Admin.
                </p>
            </div>

            <!-- Area Detail & Timeline & Chat (Disembunyikan sebelum diklik) -->
            @foreach ($pengajuans as $item)
                <div id="panel-detail-{{ $item->id }}" class="panel-detail hidden flex-grow flex-col overflow-y-auto p-5 space-y-4 bg-gray-50/30 custom-scrollbar">
                    
                    <!-- Info ID -->
                  <!-- Info ID -->
                    <div class="bg-cyan-50/60 border border-cyan-100 p-3.5 rounded-2xl">
                        <p class="text-[11px] font-bold text-cyan-800 uppercase tracking-wider">Permohonan Aktif</p>
                        <p class="text-[13px] font-extrabold text-[#071E3D]">#REQ-{{ strtoupper(substr($item->id, -5)) }} ({{ str_replace('_', ' ', $item->jenis_layanan) }})</p>
                    </div>

                    @php
                        // Decode data JSON untuk mengecek apakah ada file hasil dari admin
                        $dataPengajuan = is_string($item->data_pengajuan) ? json_decode($item->data_pengajuan, true) : ($item->data_pengajuan ?? []);
                    @endphp

                    <!-- TOMBOL DOWNLOAD (Hanya Muncul Jika Selesai & Ada File Hasil) -->
                    @if($item->status === 'Selesai' && !empty($dataPengajuan['file_hasil']))
                    <div class="bg-emerald-50/60 border border-emerald-100 p-4 rounded-2xl flex items-center justify-between shadow-sm">
                        <div>
                            <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider mb-0.5">Dokumen Selesai</p>
                            <p class="text-[13px] font-extrabold text-[#071E3D]">File TTE Tersedia</p>
                        </div>
                        <a href="{{ asset('storage/' . $dataPengajuan['file_hasil']) }}" target="_blank" download class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl text-[12px] font-bold shadow-md shadow-emerald-500/20 transition-all flex items-center gap-2 shrink-0">
                            <i class="fa-solid fa-download"></i> Unduh File
                        </a>
                    </div>
                    @endif

                    <!-- Timeline Progress dari Admin -->
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <h5 class="text-[12px] font-extrabold text-[#071E3D] mb-3 uppercase tracking-wider">Riwayat Progress Admin</h5>
                        <div class="relative border-l-2 border-gray-100 ml-2 space-y-4">
                            <div class="relative pl-5">
                                <div class="absolute -left-[7px] top-1 w-3 h-3 rounded-full bg-cyan-500 ring-2 ring-white"></div>
                                <p class="text-[10px] font-bold text-gray-400">{{ $item->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-[12px] font-bold text-[#071E3D]">Pengajuan Dibuat</p>
                            </div>
                            
                            @if(!empty($item->logs) && is_array($item->logs))
                                @foreach($item->logs as $log)
                                <div class="relative pl-5">
                                    <div class="absolute -left-[7px] top-1 w-3 h-3 rounded-full bg-blue-600 ring-2 ring-white"></div>
                                    <p class="text-[10px] font-bold text-gray-400">{{ \Carbon\Carbon::parse($log['created_at'])->format('d M Y, H:i') }}</p>
                                    <p class="text-[12px] font-bold text-[#071E3D]">Status: {{ $log['status'] }}</p>
                                    <p class="text-[11.5px] text-gray-600 bg-gray-50 p-2 rounded-lg mt-1 border border-gray-100">"{{ $log['catatan'] }}"</p>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Riwayat Pesan / Chat -->
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm space-y-3">
                        <h5 class="text-[12px] font-extrabold text-[#071E3D] uppercase tracking-wider">Diskusi / Pesan</h5>
                        
                        @if(!empty($item->pesan) && is_array($item->pesan))
                            @foreach($item->pesan as $chat)
                                <div class="flex flex-col {{ $chat['role'] === 'user' ? 'items-end' : 'items-start' }}">
                                    <div class="max-w-[85%] p-3 rounded-2xl text-[12px] {{ $chat['role'] === 'user' ? 'bg-[#071E3D] text-white rounded-br-none' : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                                        <p class="font-bold text-[10px] opacity-80 mb-0.5">{{ $chat['pengirim'] }}</p>
                                        <p>{{ $chat['isi'] }}</p>
                                    </div>
                                    <span class="text-[9px] text-gray-400 mt-1">{{ $chat['waktu'] }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-[12px] text-gray-400 text-center py-4 italic">Belum ada pesan diskusi.</p>
                        @endif
                    </div>

                </div>

                <!-- Kotak Ketik Pesan Aktif DENGAN PENGECEKAN SAKLAR GLOBAL CHAT -->
                <div id="panel-form-{{ $item->id }}" class="panel-form hidden p-4 border-t border-gray-100 bg-white">
                    @if($chatAktif ?? true)
                        <form action="{{ route('user.kirim.pesan', $item->id) }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="pesan" required placeholder="Tulis pesan ke admin..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] outline-none focus:border-cyan-400">
                            <button type="submit" class="w-12 h-12 shrink-0 bg-[#071E3D] hover:bg-[#1F4287] text-white rounded-xl flex items-center justify-center transition-colors shadow-md">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                            </button>
                        </form>
                    @else
                        <!-- Tampilan Jika Chat Dinonaktifkan Admin -->
                        <div class="bg-rose-50 border border-rose-100 p-3 rounded-xl text-center">
                            <p class="text-[12px] font-bold text-rose-500">
                                <i class="fa-solid fa-lock mr-1"></i> Fitur obrolan sedang dinonaktifkan sementara oleh Admin.
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Placeholder Kotak Ketik (Saat belum dipilih) -->
            <div id="panel-form-kosong" class="p-5 border-t border-gray-100 bg-white">
                <div class="flex gap-2 opacity-50 cursor-not-allowed">
                    <input type="text" disabled placeholder="Pilih pengajuan terlebih dahulu..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] outline-none">
                    <button disabled class="w-12 h-12 shrink-0 bg-gray-300 text-white rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </div>
            </div>

        </div>

    </div>

    <!-- SCRIPT INTERAKTIF PANEL KANAN -->
    <script>
        function pilihPengajuan(id) {
            // Sembunyikan state kosong
            document.getElementById('panel-kosong').classList.add('hidden');
            document.getElementById('panel-form-kosong').classList.add('hidden');

            // Sembunyikan semua detail & form pengajuan lain
            document.querySelectorAll('.panel-detail').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.panel-form').forEach(el => el.classList.add('hidden'));

            // Tampilkan detail & form sesuai ID yang diklik
            const targetDetail = document.getElementById('panel-detail-' + id);
            const targetForm = document.getElementById('panel-form-' + id);

            if (targetDetail) targetDetail.classList.remove('hidden');
            if (targetForm) targetForm.classList.remove('hidden');
        }
    </script>

    <!-- Scrollbar halus -->
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
@endsection