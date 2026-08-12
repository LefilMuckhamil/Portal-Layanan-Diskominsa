@extends('layouts.user')

@section('title', 'Riwayat Pengajuan Saya')
@section('max_width', 'max-w-7xl')

@section('content')

    <style>
        .dk-input, label, button, a, div, tr {
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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8 w-full items-start animate-fade-in-down">
        
        <div class="xl:col-span-2 relative overflow-hidden bg-white rounded-2xl p-6 sm:p-8 lg:p-9 shadow-[0_2px_8px_rgba(16,24,40,0.04)] border border-[#E4E7EC] flex flex-col h-[600px]">
            
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5 mb-6 pb-5 border-b border-[#E4E7EC] shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#16324F] text-cyan-400 flex items-center justify-center text-xl shrink-0 shadow-md shadow-[#16324F]/20">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-[#101828] tracking-tight">Riwayat Pengajuan</h2>
                        <p class="text-[13px] text-[#667085] font-medium mt-0.5">Pantau status dan perkembangan pengajuan layanan Anda.</p>
                    </div>
                </div>
            </div>

            <div class="overflow-y-auto overflow-x-auto flex-grow custom-scrollbar pr-1">
                <table class="w-full text-left border-separate border-spacing-y-3 whitespace-nowrap">
                    <thead class="sticky top-0 bg-white z-20 shadow-sm">
                        <tr class="text-[11px] uppercase tracking-wider text-[#667085] font-black">
                            <th class="py-2.5 px-4 bg-white">ID Permohonan</th>
                            <th class="py-2.5 px-4 bg-white">Layanan</th>
                            <th class="py-2.5 px-4 bg-white">Tanggal</th>
                            <th class="py-2.5 px-4 bg-white">Status</th>
                            <th class="py-2.5 px-4 text-center bg-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-800 text-[13.5px]">
                        @forelse ($pengajuans as $item)
                            @php
                                $isActive = request('id') == $item->id;
                            @endphp
                            
                            <tr onclick="pilihPengajuan('{{ $item->id }}')" class="group bg-white border border-[#E4E7EC] hover:border-sky-400 hover:bg-sky-50/30 hover:shadow-md transition-all cursor-pointer {{ $isActive ? 'ring-2 ring-sky-500 bg-sky-50/40' : '' }}">
                                
                                <td class="py-3.5 px-4 rounded-l-xl border-y border-l border-[#E4E7EC] group-hover:border-sky-400">
                                    <span class="font-mono text-[12.5px] font-black text-[#16324F] bg-slate-100 px-3 py-1 rounded-md border border-slate-200">
                                        {{ $item->nomor_tiket }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 border-y border-[#E4E7EC] group-hover:border-sky-400 font-extrabold text-[#101828] capitalize">
                                    {{ str_replace('_', ' ', $item->jenis_layanan) }}
                                </td>

                                <td class="py-3.5 px-4 border-y border-[#E4E7EC] group-hover:border-sky-400 text-[#667085] font-bold">
                                    {{ $item->created_at->translatedFormat('d M Y') }}
                                </td>

                                <td class="py-3.5 px-4 border-y border-[#E4E7EC] group-hover:border-sky-400">
                                    @php
                                        $badgeColor = match($item->status) {
                                            'Pending', 'Menunggu Validasi' => 'bg-amber-50 text-amber-700 border-amber-300',
                                            'Verifikasi Doc', 'Diproses', 'Proses Development' => 'bg-sky-50 text-sky-700 border-sky-300',
                                            'Selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-300',
                                            'Ditolak' => 'bg-rose-50 text-rose-700 border-rose-300',
                                            default   => 'bg-slate-50 text-slate-700 border-slate-300'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-lg border text-[11.5px] font-black {{ $badgeColor }}">
                                        {{ $item->status }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 rounded-r-xl border-y border-r border-[#E4E7EC] group-hover:border-sky-400 text-center">
                                    <button type="button" class="bg-slate-100 group-hover:bg-[#16324F] text-[#667085] group-hover:text-white border border-slate-200 group-hover:border-[#16324F] w-8 h-8 rounded-lg transition-all shadow-sm flex items-center justify-center mx-auto" title="Lihat Aktivitas">
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-200">
                                        <i class="fa-regular fa-folder-open text-2xl text-sky-600"></i>
                                    </div>
                                    <h3 class="font-black text-[15px] text-[#101828]">Tidak Ada Riwayat</h3>
                                    <p class="text-[12.5px] text-[#667085] font-medium mt-0.5">Belum ada pengajuan dibuat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="xl:col-span-1 bg-white rounded-2xl shadow-[0_2px_8px_rgba(16,24,40,0.04)] border border-[#E4E7EC] h-[600px] flex flex-col overflow-hidden sticky top-28">
            
            <div class="p-6 border-b border-[#E4E7EC] bg-slate-50/60 flex items-center gap-4 shrink-0">
                <div class="w-11 h-11 rounded-xl bg-sky-50 border border-sky-200 text-sky-700 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-regular fa-comments"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-[#101828] leading-tight">Pusat Bantuan</h3>
                    <p class="text-[12px] text-[#667085] font-bold" id="panel-subtitle">Aktivitas &amp; Pesan Admin</p>
                </div>
            </div>

            <div id="panel-kosong" class="flex-grow flex flex-col items-center justify-center p-8 text-center bg-slate-50/30">
                <div class="w-20 h-20 mb-4 text-sky-600 opacity-30">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                    </svg>
                </div>
                <h4 class="font-black text-[#101828] text-[15px] mb-1.5">Pilih Pengajuan</h4>
                <p class="text-[12.5px] text-[#667085] font-medium leading-relaxed">
                    Klik tombol panah <i class="fa-solid fa-arrow-right mx-1 text-sky-600"></i> pada tabel untuk melihat detail aktivitas dan berdiskusi dengan Admin.
                </p>
            </div>

            @foreach ($pengajuans as $item)
                <div id="panel-detail-{{ $item->id }}" class="panel-detail hidden flex-grow flex-col overflow-y-auto p-5 space-y-4 bg-slate-50/20 custom-scrollbar">
                    
                    <div class="bg-sky-50/80 border border-sky-200 p-3.5 rounded-xl shrink-0">
                        <p class="text-[11px] font-black text-sky-800 uppercase tracking-wider">Permohonan Aktif</p>
                        <p class="text-[13.5px] font-black text-[#101828]">{{ $item->nomor_tiket }} ({{ str_replace('_', ' ', $item->jenis_layanan) }})</p>
                    </div>

                    @php
                        $dataPengajuan = is_string($item->data_pengajuan) ? json_decode($item->data_pengajuan, true) : ($item->data_pengajuan ?? []);
                    @endphp

                    @if($item->status === 'Selesai' && !empty($dataPengajuan['file_hasil']))
                    <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl flex items-center justify-between shadow-sm shrink-0">
                        <div>
                            <p class="text-[11px] font-black text-emerald-700 uppercase tracking-wider mb-0.5">Dokumen Selesai</p>
                            <p class="text-[13px] font-black text-[#101828]">File Hasil Tersedia</p>
                        </div>
                        <a href="{{ asset('storage/' . $dataPengajuan['file_hasil']) }}" target="_blank" download class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-[12px] font-bold shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2 shrink-0">
                            <i class="fa-solid fa-download"></i> Unduh File
                        </a>
                    </div>
                    @endif

                    <div class="bg-white p-4 rounded-xl border border-[#E4E7EC] shadow-sm">
                        <h5 class="text-[12px] font-black text-[#101828] mb-3 uppercase tracking-wider">Riwayat Progress Admin</h5>
                        <div class="relative border-l-2 border-slate-200 ml-2 space-y-4">
                            <div class="relative pl-5">
                                <div class="absolute -left-[7px] top-1 w-3 h-3 rounded-full bg-sky-500 ring-2 ring-white"></div>
                                <p class="text-[10px] font-bold text-[#667085]">{{ $item->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-[12.5px] font-extrabold text-[#101828]">Pengajuan Dibuat</p>
                            </div>
                            
                            @if(!empty($item->logs) && is_array($item->logs))
                                @foreach($item->logs as $log)
                                <div class="relative pl-5">
                                    <div class="absolute -left-[7px] top-1 w-3 h-3 rounded-full bg-[#16324F] ring-2 ring-white"></div>
                                    <p class="text-[10px] font-bold text-[#667085]">{{ \Carbon\Carbon::parse($log['created_at'])->format('d M Y, H:i') }}</p>
                                    <p class="text-[12.5px] font-extrabold text-[#101828]">Status: {{ $log['status'] }}</p>
                                    <p class="text-[12px] text-slate-700 font-medium bg-slate-50 p-2.5 rounded-lg mt-1 border border-slate-200">"{{ $log['catatan'] }}"</p>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-[#E4E7EC] shadow-sm space-y-3">
                        <h5 class="text-[12px] font-black text-[#101828] uppercase tracking-wider">Diskusi / Pesan</h5>
                        
                        <div id="chat-box-{{ $item->id }}" class="space-y-3">
                            @if(!empty($item->pesan) && is_array($item->pesan))
                                @foreach($item->pesan as $chat)
                                    <div class="flex flex-col {{ $chat['role'] === 'user' ? 'items-end' : 'items-start' }}">
                                        <div class="max-w-[85%] p-3 rounded-2xl text-[12.5px] font-medium {{ $chat['role'] === 'user' ? 'bg-[#16324F] text-white rounded-br-none' : 'bg-slate-100 text-slate-900 rounded-bl-none border border-slate-200' }}">
                                            <p class="font-black text-[10px] opacity-80 mb-0.5">{{ $chat['pengirim'] }}</p>
                                            <p class="leading-relaxed">{{ $chat['isi'] }}</p>
                                        </div>
                                        <span class="text-[9.5px] font-bold text-[#667085] mt-1">{{ $chat['waktu'] }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-[12px] text-[#667085] font-medium text-center py-4 italic no-chat-msg">Belum ada pesan diskusi.</p>
                            @endif
                        </div>
                    </div>

                </div>

                <div id="panel-form-{{ $item->id }}" class="panel-form hidden p-4 border-t border-[#E4E7EC] bg-white shrink-0">
                    @if($chatAktif ?? true)
                        <form onsubmit="kirimPesanAjax(event, '{{ $item->id }}', '{{ route('user.pengajuan.pesan', $item->id) }}')" class="flex gap-2">
                            @csrf
                            <input type="text" id="input-pesan-{{ $item->id }}" name="pesan" required placeholder="Tulis pesan ke admin..." class="w-full bg-slate-50 border border-[#DCE1E8] rounded-xl px-4 py-2.5 text-[13px] font-medium text-[#101828] outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10">
                            <button type="submit" id="btn-submit-{{ $item->id }}" class="w-11 h-11 shrink-0 bg-[#16324F] hover:bg-[#0F2438] text-white rounded-xl flex items-center justify-center transition-all shadow-md">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                            </button>
                        </form>
                    @else
                        <div class="bg-rose-50 border border-rose-200 p-3 rounded-xl text-center">
                            <p class="text-[12px] font-black text-rose-700">
                                <i class="fa-solid fa-lock mr-1"></i> Fitur obrolan sedang dinonaktifkan sementara oleh Admin.
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach

            <div id="panel-form-kosong" class="p-4 border-t border-[#E4E7EC] bg-white shrink-0">
                <div class="flex gap-2 opacity-50 cursor-not-allowed">
                    <input type="text" disabled placeholder="Pilih pengajuan terlebih dahulu..." class="w-full bg-slate-50 border border-[#DCE1E8] rounded-xl px-4 py-2.5 text-[13px] font-medium text-[#101828]">
                    <button disabled class="w-11 h-11 shrink-0 bg-slate-300 text-white rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </div>
            </div>

        </div>

    </div>

    <script>
        function pilihPengajuan(id) {
            document.getElementById('panel-kosong').classList.add('hidden');
            document.getElementById('panel-form-kosong').classList.add('hidden');

            document.querySelectorAll('.panel-detail').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.panel-form').forEach(el => el.classList.add('hidden'));

            const targetDetail = document.getElementById('panel-detail-' + id);
            const targetForm = document.getElementById('panel-form-' + id);

            if (targetDetail) targetDetail.classList.remove('hidden');
            if (targetForm) targetForm.classList.remove('hidden');
        }

        function kirimPesanAjax(e, id, urlRoute) {
            e.preventDefault();
            
            const inputField = document.getElementById('input-pesan-' + id);
            const btnSubmit = document.getElementById('btn-submit-' + id);
            const chatBox = document.getElementById('chat-box-' + id);
            const pesanText = inputField.value.trim();

            if (!pesanText) return;

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i>';

            fetch(urlRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ pesan: pesanText })
            })
            .then(response => response.json())
            .then(data => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fa-solid fa-paper-plane text-xs"></i>';

                if (data.status === 'success') {
                    const noChatMsg = chatBox.querySelector('.no-chat-msg');
                    if (noChatMsg) noChatMsg.remove();

                    const bubbleHtml = `
                        <div class="flex flex-col items-end animate-fade-in-down">
                            <div class="max-w-[85%] p-3 rounded-2xl text-[12.5px] font-medium bg-[#16324F] text-white rounded-br-none">
                                <p class="font-black text-[10px] opacity-80 mb-0.5">${data.pesan.pengirim}</p>
                                <p class="leading-relaxed">${data.pesan.isi}</p>
                            </div>
                            <span class="text-[9.5px] font-bold text-[#667085] mt-1">${data.pesan.waktu}</span>
                        </div>
                    `;

                    chatBox.insertAdjacentHTML('beforeend', bubbleHtml);
                    inputField.value = '';

                    const panelDetail = document.getElementById('panel-detail-' + id);
                    panelDetail.scrollTop = panelDetail.scrollHeight;
                }
            })
            .catch(error => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fa-solid fa-paper-plane text-xs"></i>';
                alert('Gagal mengirim pesan. Silakan coba lagi.');
            });
        }
    </script>
@endsection