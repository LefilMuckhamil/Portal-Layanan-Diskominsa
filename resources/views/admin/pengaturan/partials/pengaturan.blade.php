    @if(session('sukses'))
        <div class="bg-green-50 text-green-600 px-6 py-3 rounded-2xl border border-green-100 text-[13px] font-bold flex items-center gap-2 mb-6 shadow-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('sukses') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- KARTU MASTER SWITCH GLOBAL CHAT -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 flex flex-col justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-sm transition-colors {{ $chatAktif ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500' }}">
                    <i class="fa-solid {{ $chatAktif ? 'fa-comments' : 'fa-comment-slash' }}"></i>
                </div>
                <div>
                    <h3 class="text-[16px] font-extrabold text-[#071E3D] flex items-center gap-2 mb-1">
                        Fitur Global Chat
                        <span class="px-2 py-0.5 rounded-md text-[10px] uppercase tracking-wider {{ $chatAktif ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                            Status: {{ $chatAktif ? 'ON' : 'OFF' }}
                        </span>
                    </h3>
                    <p class="text-[12px] text-gray-500 leading-relaxed">Kontrol penuh untuk mengaktifkan atau melumpuhkan fitur obrolan di seluruh tiket pengajuan. Gunakan saat admin sedang *overload*.</p>
                </div>
            </div>
            
            <form action="{{ route('admin.toggleChat') }}" method="POST" class="mt-auto">
                @csrf
                <button type="submit" class="w-full py-3.5 rounded-xl font-bold text-[13px] transition-all shadow-sm flex items-center justify-center gap-2 {{ $chatAktif ? 'bg-white text-rose-600 hover:bg-rose-50 border-2 border-rose-100 hover:border-rose-300' : 'bg-[#071E3D] hover:bg-[#1F4287] text-white shadow-lg' }}">
                    @if($chatAktif)
                        <i class="fa-solid fa-lock"></i> Matikan Fitur Chat Sekarang
                    @else
                        <i class="fa-solid fa-unlock"></i> Aktifkan Kembali Chat
                    @endif
                </button>
            </form>
        </div>

        <!-- Space kosong buat nambah pengaturan lain kedepannya -->
        <div class="bg-gray-50/50 rounded-3xl border border-dashed border-gray-200 flex flex-col items-center justify-center p-8 text-center min-h-[200px]">
            <div class="w-12 h-12 bg-gray-100 text-gray-300 rounded-full flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-plus"></i>
            </div>
            <p class="text-[13px] font-bold text-gray-400">Pengaturan Lainnya</p>
            <p class="text-[11px] text-gray-400 mt-1">Dapat ditambahkan di kemudian hari.</p>
        </div>
    </div>
