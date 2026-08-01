@extends('layouts.user')

@section('title', 'Detail Pengajuan')
@section('max_width', 'max-w-7xl')

@section('content')
    <!-- Header Kembali -->
    <a href="{{ route('user.riwayat') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-cyan-600 font-bold text-[14px] mb-6 transition-colors">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- KOLOM KIRI: DETAIL & TIMELINE (Porsi 2/3) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Kartu Info Utama -->
            <div class="bg-white rounded-[2rem] p-8 shadow-[0_15px_40px_-15px_rgba(0,0,0,0.05)] border border-gray-100">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <span class="bg-cyan-50 text-cyan-600 font-bold px-3 py-1 rounded-lg text-[12px] uppercase tracking-wider">
                            #REQ-{{ strtoupper(substr($pengajuan->id, -5)) }}
                        </span>
                        <h2 class="text-2xl font-extrabold text-[#071E3D] mt-3 capitalize">
                            Pengajuan {{ str_replace('_', ' ', $pengajuan->jenis_layanan) }}
                        </h2>
                        <p class="text-[13px] text-gray-500 font-medium mt-1">Diajukan pada {{ $pengajuan->created_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-xl border text-[12px] font-bold bg-gray-50 text-gray-700 border-gray-200 shadow-sm">
                        {{ $pengajuan->status }}
                    </span>
                </div>

                <hr class="border-gray-100 mb-6">

                <!-- Loop Data Spesifik Form -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4">
                    @if($pengajuan->data_pengajuan)
                        @foreach($pengajuan->data_pengajuan as $kunci => $nilai)
                            <div>
                                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">{{ str_replace('_', ' ', $kunci) }}</p>
                                <p class="text-[14px] text-[#071E3D] font-semibold mt-1">{{ $nilai }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Kartu Timeline Aktivitas -->
            <div class="bg-white rounded-[2rem] p-8 shadow-[0_15px_40px_-15px_rgba(0,0,0,0.05)] border border-gray-100">
                <h3 class="text-lg font-extrabold text-[#071E3D] mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-bars-progress text-cyan-500"></i> Timeline Aktivitas
                </h3>
                
                <div class="relative border-l-2 border-gray-100 ml-3 space-y-6">
                    <!-- Log Pengajuan Pertama -->
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-cyan-400 ring-4 ring-white"></div>
                        <p class="font-bold text-[14px] text-[#071E3D]">Pengajuan Berhasil Dikirim</p>
                        <p class="text-[12px] text-gray-500 mt-0.5">{{ $pengajuan->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>

                    <!-- Looping Logs dari Admin (Jika Ada) -->
                    @if($pengajuan->logs)
                        @foreach($pengajuan->logs as $log)
                            <div class="relative pl-6">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-blue-500 ring-4 ring-white"></div>
                                <p class="font-bold text-[14px] text-[#071E3D]">{{ $log['judul'] }}</p>
                                <p class="text-[13px] text-gray-600 mt-1">{{ $log['deskripsi'] }}</p>
                                <p class="text-[11px] text-gray-400 mt-1">{{ $log['waktu'] }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>


        <!-- KOLOM KANAN: TIKET CHAT (Porsi 1/3) -->
        <div class="lg:col-span-1 h-[600px] flex flex-col bg-[#f8fafc] rounded-[2rem] shadow-[0_15px_40px_-15px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden relative">
            
            <!-- Header Chat -->
            <div class="bg-white p-5 border-b border-gray-100 flex items-center gap-3 z-10 shadow-sm">
                <div class="w-10 h-10 bg-cyan-50 text-cyan-600 rounded-full flex items-center justify-center text-lg">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-[14px] text-[#071E3D]">Pusat Bantuan</h3>
                    <p class="text-[11px] text-gray-400">Tinggalkan pesan untuk Admin</p>
                </div>
            </div>

            <!-- Area Chat Box -->
            <div class="flex-grow p-5 overflow-y-auto space-y-4 flex flex-col">
                
                <!-- Pesan Otomatis Sistem -->
                <div class="flex flex-col items-center mb-2">
                    <span class="text-[10px] bg-gray-200 text-gray-500 px-3 py-1 rounded-full font-medium">Hari ini</span>
                    <div class="mt-3 bg-blue-50 border border-blue-100 text-blue-800 text-[12px] p-3 rounded-xl max-w-[90%] text-center leading-relaxed">
                        Halo! Jika ada data yang salah atau kurang jelas, silakan tinggalkan pesan di sini. Admin kami akan segera merespons.
                    </div>
                </div>

                <!-- Looping Data Chat -->
                @if($pengajuan->pesan)
                    @foreach($pengajuan->pesan as $chat)
                        <!-- Jika pesan dari USER (Kanan) -->
                        @if($chat['role'] == 'user')
                            <div class="flex flex-col items-end">
                                <div class="bg-[#071E3D] text-white p-3.5 rounded-2xl rounded-tr-sm max-w-[85%] shadow-md">
                                    <p class="text-[13px] leading-relaxed">{{ $chat['isi'] }}</p>
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1 font-medium">{{ $chat['waktu'] }}</span>
                            </div>
                        <!-- Jika pesan dari ADMIN (Kiri) -->
                        @else
                            <div class="flex flex-col items-start">
                                <div class="bg-white border border-gray-100 text-gray-700 p-3.5 rounded-2xl rounded-tl-sm max-w-[85%] shadow-sm">
                                    <p class="text-[10px] font-bold text-cyan-600 mb-1">Admin Diskominsa</p>
                                    <p class="text-[13px] leading-relaxed">{{ $chat['isi'] }}</p>
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1 font-medium">{{ $chat['waktu'] }}</span>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>

            <!-- Form Ketik Pesan -->
            <div class="bg-white p-4 border-t border-gray-100 z-10">
                <form action="{{ route('user.pengajuan.pesan', $pengajuan->id) }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="pesan" required autocomplete="off" placeholder="Ketik pesan Anda..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] outline-none focus:border-cyan-400 focus:bg-white transition-colors">
                    <button type="submit" class="w-12 h-12 shrink-0 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl flex items-center justify-center transition-colors shadow-md">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>

        </div>

    </div>
@endsection