<section id="tracking" class="py-20 bg-[#040914] text-white border-t border-white/10">
    <div class="container mx-auto px-6 max-w-4xl">
        
        <div class="bg-[#0B1528] border border-white/10 rounded-2xl p-6 md:p-10 shadow-2xl">
            
            <div class="text-center mb-8">
                <span class="text-xs font-semibold text-cyan-400 uppercase tracking-wider">Lacak Tiket</span>
                <h2 class="text-2xl font-bold text-white mt-1">E-Tracking Pengajuan Layanan</h2>
            </div>

            <div class="max-w-xl mx-auto mb-8">
                <div class="flex flex-col sm:flex-row gap-3">
                    <input 
                        type="text" 
                        id="input-tiket" 
                        placeholder="Masukkan ID / Nomor Tiket..." 
                        class="flex-1 bg-white/5 border border-white/15 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-none focus:border-cyan-400 font-mono transition-colors"
                    >
                    <button 
                        type="button" 
                        onclick="lacakTiketCard()" 
                        class="bg-cyan-400 hover:bg-cyan-300 text-[#040914] px-6 py-3 rounded-xl font-bold text-xs sm:text-sm transition-colors flex items-center justify-center gap-2 cursor-pointer shrink-0"
                    >
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        <span>Cari Tiket</span>
                    </button>
                </div>
                <p id="error-tracking" class="text-rose-400 text-xs mt-2 hidden text-center"></p>
            </div>

            <div id="hasil-tracking-card" class="hidden max-w-md mx-auto bg-white text-slate-800 rounded-2xl p-6 shadow-2xl transition-all duration-300">
                
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-regular fa-comments"></i>
                    </div>
                    <div>
                        <h3 id="card-nama-layanan" class="font-extrabold text-sm text-slate-900 leading-tight">Pusat Bantuan</h3>
                        <p class="text-[11px] text-gray-500 font-medium">Aktivitas & Pesan Admin</p>
                    </div>
                </div>

                <div class="bg-sky-50/80 border border-sky-100 rounded-xl p-3.5 mb-5">
                    <p class="text-[10px] font-bold text-sky-800 tracking-wider uppercase">PERMOHONAN AKTIF</p>
                    <p id="card-id-tiket" class="text-xs font-black text-slate-900 mt-0.5">#HLP-A833B (Pusat Bantuan)</p>
                </div>

                <div class="border border-gray-100 rounded-2xl p-4 bg-white mb-4">
                    <h4 class="text-[11px] font-black text-slate-900 tracking-wider uppercase mb-4">RIWAYAT PROGRESS ADMIN</h4>
                    <div id="card-timeline-list" class="relative pl-5 space-y-5 before:absolute before:left-1.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                    </div>
                </div>

                <div class="border border-gray-100 rounded-xl p-3.5 bg-gray-50/50 flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-700">DISKUSI / PESAN</span>
                    <span class="text-[10px] font-bold text-cyan-600 bg-cyan-50 px-2.5 py-1 rounded-md">Terhubung</span>
                </div>

            </div>

        </div>

    </div>
</section>

<script>
    async function lacakTiketCard() {
        const inputTiket = document.getElementById('input-tiket').value.trim();
        const errorMsg = document.getElementById('error-tracking');
        const cardContainer = document.getElementById('hasil-tracking-card');
        const timelineList = document.getElementById('card-timeline-list');

        if (!inputTiket) {
            errorMsg.textContent = 'Silakan masukkan nomor tiket.';
            errorMsg.classList.remove('hidden');
            return;
        }

        errorMsg.classList.add('hidden');

        try {
            const response = await fetch(`/track-tiket/${encodeURIComponent(inputTiket)}`);
            const result = await response.json();

            if (!response.ok || !result.success) {
                errorMsg.textContent = result.message || 'Nomor tiket tidak ditemukan.';
                errorMsg.classList.remove('hidden');
                cardContainer.classList.add('hidden');
                return;
            }

            document.getElementById('card-nama-layanan').textContent = result.data.layanan;
            document.getElementById('card-id-tiket').textContent = `#${result.data.nomor_tiket} (${result.data.layanan})`;

            timelineList.innerHTML = '';
            
            if (result.data.riwayat && result.data.riwayat.length > 0) {
                result.data.riwayat.forEach((item, index) => {
                    const isFirst = index === 0;
                    
                    const itemHTML = `
                        <div class="relative">
                            <span class="absolute -left-[19px] top-1 w-2.5 h-2.5 rounded-full ${isFirst ? 'bg-sky-400 ring-4 ring-sky-100' : 'bg-slate-700'}"></span>
                            <div>
                                <p class="text-[10px] font-semibold text-gray-400">${item.waktu}</p>
                                <p class="text-xs font-extrabold text-slate-800 mt-0.5">${item.judul}</p>
                                ${item.pesan_admin ? `
                                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-2.5 mt-1.5">
                                        <p class="text-[11px] text-slate-600 font-medium">"${item.pesan_admin}"</p>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                    timelineList.insertAdjacentHTML('beforeend', itemHTML);
                });
            }

            cardContainer.classList.remove('hidden');
            cardContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        } catch (error) {
            errorMsg.textContent = 'Gagal terhubung ke server.';
            errorMsg.classList.remove('hidden');
        }
    }
</script>