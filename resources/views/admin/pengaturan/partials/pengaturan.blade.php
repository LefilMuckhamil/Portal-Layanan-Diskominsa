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
            
            <button type="button" onclick="bukaModalToggleChat()" class="w-full py-3.5 rounded-xl font-bold text-[13px] transition-all shadow-sm flex items-center justify-center gap-2 mt-auto {{ $chatAktif ? 'bg-white text-rose-600 hover:bg-rose-50 border-2 border-rose-100 hover:border-rose-300' : 'bg-[#071E3D] hover:bg-[#1F4287] text-white shadow-lg' }}">
                @if($chatAktif)
                    <i class="fa-solid fa-lock"></i> Matikan Fitur Chat Sekarang
                @else
                    <i class="fa-solid fa-unlock"></i> Aktifkan Kembali Chat
                @endif
            </button>
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

    <!-- MODAL KONFIRMASI TOGGLE CHAT -->
    <div id="modal-toggle-chat" class="fixed inset-0 z-[200] hidden items-center justify-center">
        <div onclick="tutupModalToggleChat()" class="absolute inset-0 bg-[#071E3D]/60 backdrop-blur-sm transition-opacity"></div>
        <div class="relative bg-white rounded-2xl p-6 shadow-2xl w-full max-w-sm mx-4 z-10 animate-fade-in-down">
            <div class="flex flex-col items-center text-center">
                <div id="toggle-chat-icon" class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl mb-4 border-4 border-white shadow-md {{ $chatAktif ? 'bg-rose-50 text-rose-500' : 'bg-amber-50 text-amber-500' }}">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <h3 class="text-[16px] font-extrabold text-[#071E3D] mb-1">Konfirmasi Fitur Chat</h3>
                <p id="toggle-chat-text" class="text-[13px] text-gray-500 font-medium mb-6 leading-relaxed">
                    @if($chatAktif)
                        Apakah Anda yakin ingin melumpuhkan fitur obrolan untuk seluruh tiket pengajuan?
                    @else
                        Apakah Anda yakin ingin mengaktifkan kembali fitur obrolan?
                    @endif
                </p>
                <div class="flex gap-3 w-full">
                    <button onclick="tutupModalToggleChat()" class="flex-1 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-[13px] transition-colors cursor-pointer">Batal</button>
                    <button onclick="document.getElementById('form-toggle-chat').submit()" class="flex-1 py-2.5 rounded-xl {{ $chatAktif ? 'bg-rose-500 hover:bg-rose-600' : 'bg-[#071E3D] hover:bg-[#1F4287]' }} text-white font-bold text-[13px] transition-colors cursor-pointer">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <form id="form-toggle-chat" action="{{ route('admin.toggleChat') }}" method="POST" class="hidden">
        @csrf
    </form>

    <style>
        @keyframes fadeInDown {
            0% { opacity: 0; transform: translateY(-12px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fadeInDown 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <script>
        function bukaModalToggleChat() {
            const m = document.getElementById('modal-toggle-chat');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
        function tutupModalToggleChat() {
            const m = document.getElementById('modal-toggle-chat');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const m = document.getElementById('modal-toggle-chat');
                if (m && !m.classList.contains('hidden')) { tutupModalToggleChat(); }
            }
        });
    </script>
