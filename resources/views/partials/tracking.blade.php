<section id="tracking" class="py-16 container mx-auto px-6 mb-10">
        <div class="bg-primary-dark rounded-[2.5rem] p-10 md:p-14 shadow-2xl flex flex-col md:flex-row items-center text-white relative overflow-hidden">
            <div class="absolute top-1/2 right-1/4 w-96 h-96 bg-primary-light rounded-full mix-blend-screen filter blur-[80px] opacity-40 transform -translate-y-1/2"></div>
            
            <div class="w-full md:w-[40%] mb-12 md:mb-0 z-10 pr-0 md:pr-10">
                <h3 class="text-3xl font-extrabold mb-4 leading-tight">E-Tracking Pengajuan</h3>
                <p class="text-sm opacity-80 mb-10 leading-relaxed font-light">Cek status pengajuan layanan Anda secara real-time.</p>
                <div class="w-48 h-85 bg-white rounded-4xl border-8 border-gray-900 mx-auto md:mx-0 overflow-hidden relative shadow-2xl transform -rotate-6">
                    <div class="absolute top-0 inset-x-0 h-4 bg-gray-900 w-1/2 mx-auto rounded-b-xl z-20"></div>
                    <div class="bg-gray-50 w-full h-full p-4 pt-8 flex flex-col relative z-10">
                        <div class="w-full h-3 bg-gray-200 rounded mb-4"></div>
                        <div class="w-3/4 h-3 bg-gray-200 rounded mb-6"></div>
                        <div class="grow flex items-center justify-center">
                            <div class="w-16 h-16 bg-teal-400 rounded-full flex items-center justify-center shadow-lg shadow-teal-400/50">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-[60%] z-10 md:pl-10 border-t md:border-t-0 md:border-l border-white/10 pt-10 md:pt-0">
                <label class="block text-sm font-semibold mb-4 text-white/90">Masukkan Nomor Tiket / ID Pengajuan</label>
                <div class="flex flex-col sm:flex-row gap-4 mb-16">
                    <input type="text" placeholder="Contoh: TKT-2024-0001" class="grow bg-white/5 border border-white/20 rounded-xl px-5 py-4 text-white placeholder-white/40 focus:outline-none focus:border-cyan-400 transition">
                    <button class="bg-cyan-500 hover:bg-cyan-400 text-primary-dark px-8 py-4 rounded-xl font-bold shadow-lg transition">Cek Status</button>
                </div>

                <p class="text-sm font-semibold mb-8 text-white/90">Status Pengajuan</p>
                <div class="relative max-w-xl">
                    <div class="absolute top-5 left-8 right-8 h-0.5 bg-white/20 -z-10"></div>
                    <div class="flex justify-between">
                        <div class="flex flex-col items-center gap-4"><div class="w-10 h-10 rounded-full border-2 border-white/30 flex items-center justify-center text-white/50 bg-primary-dark z-10"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><span class="text-xs text-white/50">Menunggu</span></div>
                        <div class="flex flex-col items-center gap-4"><div class="w-10 h-10 rounded-full bg-primary-light border-4 border-primary-dark ring-2 ring-primary-light flex items-center justify-center z-10"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div><span class="text-xs font-bold text-white">Diproses</span></div>
                        <div class="flex flex-col items-center gap-4"><div class="w-10 h-10 rounded-full border-2 border-white/30 flex items-center justify-center text-white/50 bg-primary-dark z-10"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div><span class="text-xs text-white/50">Selesai</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>