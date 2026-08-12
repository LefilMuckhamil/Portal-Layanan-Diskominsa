@extends('layouts.user')

@section('title', 'Detail Pengajuan')
@section('max_width', 'max-w-7xl')

@section('content')

    <style>
        .dk-input, label, button, a, div {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #F1F5F9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-12px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

    <div class="animate-fade-in-down">
        <a href="{{ route('user.riwayat') }}" class="inline-flex items-center gap-2 text-[#667085] hover:text-[#16324F] font-bold text-[13.5px] mb-6 transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Riwayat
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-2xl p-7 sm:p-9 shadow-[0_2px_8px_rgba(16,24,40,0.04)] border border-[#E4E7EC]">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span class="bg-slate-100 text-[#16324F] font-black px-3.5 py-1.5 rounded-lg text-[12px] uppercase tracking-wider border border-slate-200">
                                {{ $pengajuan->nomor_tiket ?? '#REQ-' . strtoupper(substr($pengajuan->id, -5)) }}
                            </span>
                            <h2 class="text-2xl font-black text-[#101828] mt-3.5 capitalize">
                                Pengajuan {{ str_replace('_', ' ', $pengajuan->jenis_layanan) }}
                            </h2>
                            <p class="text-[13px] text-[#667085] font-bold mt-1">Diajukan pada {{ $pengajuan->created_at->translatedFormat('d F Y, H:i') }}</p>
                        </div>

                        @php
                            $badgeColor = match($pengajuan->status) {
                                'Pending', 'Menunggu Validasi' => 'bg-amber-50 text-amber-700 border-amber-300',
                                'Verifikasi Doc', 'Diproses', 'Proses Development' => 'bg-sky-50 text-sky-700 border-sky-300',
                                'Selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-300',
                                'Ditolak' => 'bg-rose-50 text-rose-700 border-rose-300',
                                default   => 'bg-slate-50 text-slate-700 border-slate-300'
                            };
                        @endphp
                        <span class="px-4 py-1.5 rounded-xl border text-[12px] font-black shadow-sm {{ $badgeColor }}">
                            {{ $pengajuan->status }}
                        </span>
                    </div>

                    <hr class="border-[#E4E7EC] mb-6">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-6">
                        @if($pengajuan->data_pengajuan)
                            @php
                                $dataPengajuan = is_string($pengajuan->data_pengajuan) ? json_decode($pengajuan->data_pengajuan, true) : $pengajuan->data_pengajuan;
                            @endphp
                            @if(is_array($dataPengajuan))
                                @foreach($dataPengajuan as $kunci => $nilai)
                                    @if($kunci !== 'file_hasil')
                                        <div>
                                            <p class="text-[11px] text-[#667085] font-black uppercase tracking-wider">{{ str_replace('_', ' ', $kunci) }}</p>
                                            <p class="text-[14px] text-[#101828] font-bold mt-1">{{ is_array($nilai) ? implode(', ', $nilai) : $nilai }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-7 sm:p-9 shadow-[0_2px_8px_rgba(16,24,40,0.04)] border border-[#E4E7EC]">
                    <h3 class="text-lg font-black text-[#101828] mb-6 flex items-center gap-2.5">
                        <i class="fa-solid fa-bars-progress text-sky-600"></i> Timeline Aktivitas
                    </h3>
                    
                    <div class="relative border-l-2 border-slate-200 ml-3 space-y-6">
                        <div class="relative pl-6">
                            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-sky-500 ring-4 ring-white"></div>
                            <p class="font-black text-[14px] text-[#101828]">Pengajuan Berhasil Dikirim</p>
                            <p class="text-[12px] text-[#667085] font-bold mt-0.5">{{ $pengajuan->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>

                        @if(!empty($pengajuan->logs) && is_array($pengajuan->logs))
                            @foreach($pengajuan->logs as $log)
                                <div class="relative pl-6">
                                    <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-[#16324F] ring-4 ring-white"></div>
                                    <p class="font-black text-[14px] text-[#101828]">{{ $log['judul'] ?? 'Pembaruan Status: ' . ($log['status'] ?? '') }}</p>
                                    <p class="text-[13px] text-slate-700 font-medium mt-1 bg-slate-50 p-3 rounded-xl border border-slate-200">"{{ $log['deskripsi'] ?? $log['catatan'] ?? '' }}"</p>
                                    <p class="text-[11px] text-[#667085] font-bold mt-1.5">{{ $log['waktu'] ?? \Carbon\Carbon::parse($log['created_at'] ?? now())->format('d M Y, H:i') }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1 h-[600px] flex flex-col bg-white rounded-2xl shadow-[0_2px_8px_rgba(16,24,40,0.04)] border border-[#E4E7EC] overflow-hidden relative">
                
                <div class="bg-slate-50/60 p-5 border-b border-[#E4E7EC] flex items-center gap-3.5 z-10">
                    <div class="w-10 h-10 bg-sky-50 text-sky-700 border border-sky-200 rounded-xl flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-[14px] text-[#101828]">Pusat Bantuan</h3>
                        <p class="text-[11.5px] text-[#667085] font-bold">Diskusi Langsung dengan Admin</p>
                    </div>
                </div>

                <div class="flex-grow p-5 overflow-y-auto space-y-4 flex flex-col custom-scrollbar">
                    
                    <div class="flex flex-col items-center mb-2">
                        <span class="text-[10.5px] bg-slate-100 border border-slate-200 text-[#667085] px-3 py-0.5 rounded-full font-bold">Informasi System</span>
                        <div class="mt-3 bg-sky-50 border border-sky-200 text-sky-900 text-[12px] font-medium p-3.5 rounded-xl max-w-[95%] text-center leading-relaxed">
                            Halo! Jika ada data yang perlu diklarifikasi atau direvisi, silakan tinggalkan pesan di bawah ini.
                        </div>
                    </div>

                    @if(!empty($pengajuan->pesan) && is_array($pengajuan->pesan))
                        @foreach($pengajuan->pesan as $chat)
                            @if(($chat['role'] ?? '') == 'user')
                                <div class="flex flex-col items-end">
                                    <div class="bg-[#16324F] text-white p-3.5 rounded-2xl rounded-tr-none max-w-[85%] shadow-sm">
                                        <p class="text-[12.5px] font-medium leading-relaxed">{{ $chat['isi'] }}</p>
                                    </div>
                                    <span class="text-[9.5px] text-[#667085] font-bold mt-1">{{ $chat['waktu'] }}</span>
                                </div>
                            @else
                                <div class="flex flex-col items-start">
                                    <div class="bg-slate-100 border border-slate-200 text-slate-900 p-3.5 rounded-2xl rounded-tl-none max-w-[85%] shadow-sm">
                                        <p class="text-[10px] font-black text-sky-700 mb-0.5">{{ $chat['pengirim'] ?? 'Admin Diskominsa' }}</p>
                                        <p class="text-[12.5px] font-medium leading-relaxed">{{ $chat['isi'] }}</p>
                                    </div>
                                    <span class="text-[9.5px] text-[#667085] font-bold mt-1">{{ $chat['waktu'] }}</span>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                <div class="bg-white p-4 border-t border-[#E4E7EC] z-10">
                    <form action="{{ route('user.pengajuan.pesan', $pengajuan->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="pesan" required autocomplete="off" placeholder="Ketik pesan Anda..." class="w-full bg-slate-50 border border-[#DCE1E8] rounded-xl px-4 py-2.5 text-[13px] font-medium text-[#101828] outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10">
                        <button type="submit" class="w-11 h-11 shrink-0 bg-[#16324F] hover:bg-[#0F2438] active:scale-95 text-white rounded-xl flex items-center justify-center transition-all shadow-md">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
@endsection