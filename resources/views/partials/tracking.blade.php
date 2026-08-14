<section id="tracking" class="py-20 bg-[#040914] text-white border-t border-white/10">

    <div class="container mx-auto px-6 max-w-4xl">
        
        <div class="bg-[#0B1528] border border-white/10 rounded-2xl p-6 md:p-10 shadow-xl">
         
            
            <div class="text-center mb-8">
                <span class="text-xs font-semibold text-cyan-400 uppercase tracking-wider">Lacak Tiket</span>
                <h2 class="text-2xl font-bold text-white mt-1">E-Tracking Pengajuan Layanan</h2>
            </div>

            <div class="max-w-xl mx-auto mb-8">
                <div class="flex flex-col sm:flex-row gap-3">
                    <input 
                        type="text" 
                        id="input-tiket" 
                        placeholder="Masukkan Nomor Tiket..." 
                        class="flex-1 bg-white/5 border border-white/15 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-none focus:border-cyan-400 transition-colors"
                        onkeypress="if(event.key === 'Enter') lacakTiketCard()"
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
                <p id="error-tracking" class="text-rose-400 text-xs mt-2 hidden text-center font-medium"></p>
            </div>


            <div id="hasil-tracking-card" class="hidden max-w-xl mx-auto bg-white/5 border border-white/10 rounded-2xl p-6 md:p-8 shadow-2xl transition-all duration-300">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Permohonan</span>
                        <h3 id="card-id-tiket" class="text-lg md:text-xl font-bold text-white mt-0.5">-</h3>
                        <p id="card-nama-layanan" class="text-xs text-cyan-400 font-medium mt-0.5">-</p>
                    </div>
                    <div>
                        <span id="card-status-terakhir" class="inline-block bg-cyan-400/10 border border-cyan-400/30 text-cyan-300 px-3.5 py-1.5 rounded-full text-xs font-semibold">
                            Sedang Diproses
                        </span>
                    </div>
                </div>

                <div class="pt-6">
                    <h4 class="text-xs font-bold text-slate-300 uppercase tracking-wider mb-6">Riwayat Progress</h4>
                    <div id="card-timeline-list" class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-white/10">
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>

<script>
    async function lacakTiketCard() {
        let inputTiket = document.getElementById('input-tiket').value.trim();
        const errorMsg = document.getElementById('error-tracking');
        const cardContainer = document.getElementById('hasil-tracking-card');
        const timelineList = document.getElementById('card-timeline-list');

        if (!inputTiket) {
            errorMsg.textContent = 'Silakan masukkan nomor tiket.';
            errorMsg.classList.remove('hidden');
            return;
        }

        // Hapus karakter '#' dari input JS agar tidak merusak URL parameter
        const cleanSearchKey = inputTiket.replace(/#/g, '');
        errorMsg.classList.add('hidden');

        try {
            const response = await fetch(`/track-tiket/${encodeURIComponent(cleanSearchKey)}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();

            if (!response.ok || !result.success) {
                errorMsg.textContent = result.message || 'Nomor tiket tidak ditemukan.';
                errorMsg.classList.remove('hidden');
                cardContainer.classList.add('hidden');
                return;
            }

            document.getElementById('card-nama-layanan').textContent = result.data.layanan;
            document.getElementById('card-id-tiket').textContent = result.data.nomor_tiket.startsWith('#') ? result.data.nomor_tiket : `#${result.data.nomor_tiket}`;
            document.getElementById('card-status-terakhir').textContent = result.data.status || 'Sedang Diproses';

            timelineList.innerHTML = '';
            
            if (result.data.riwayat && result.data.riwayat.length > 0) {
                result.data.riwayat.forEach((item, index) => {
                    const isLatest = index === 0;
                    
                    const itemHTML = `
                        <div class="relative">
                            <span class="absolute -left-[23.5px] top-1.5 w-2.5 h-2.5 rounded-full ${isLatest ? 'bg-cyan-400 ring-4 ring-cyan-400/20' : 'bg-slate-600'}"></span>
                            <div>
                                <div class="flex items-baseline justify-between gap-2">
                                    <p class="text-sm font-semibold ${isLatest ? 'text-white' : 'text-slate-400'}">${item.judul}</p>
                                    <span class="text-[11px] text-slate-500 shrink-0">${item.waktu}</span>
                                </div>
                                ${item.pesan_admin ? `
                                    <div class="bg-white/5 border border-white/5 rounded-xl p-3 mt-2">
                                        <p class="text-xs text-slate-300 font-normal leading-relaxed font-sans">${item.pesan_admin}</p>
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
            console.error('Tracking Error:', error);
            errorMsg.textContent = 'Gagal terhubung ke server.';
            errorMsg.classList.remove('hidden');
        }
    }
</script>