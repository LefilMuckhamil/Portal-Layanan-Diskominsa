<style>
    @keyframes infiniteTicker {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .animate-ticker {
        display: flex;
        width: max-content;
        animation: infiniteTicker 25s linear infinite;
    }

    .animate-ticker:hover {
        animation-play-state: paused;
    }
</style>

<section id="layanan" class="py-28 bg-[#040914] text-white relative z-10 overflow-hidden selection:bg-cyan-400 selection:text-[#040914]">

    <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f293715_1px,transparent_1px),linear-gradient(to_bottom,#1f293715_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>

    <div class="absolute -top-12 left-1/2 -translate-x-1/2 text-[140px] sm:text-[200px] lg:text-[280px] font-black text-white/[0.02] uppercase tracking-tighter select-none pointer-events-none whitespace-nowrap">
        SERVICES
    </div>

    <div class="container mx-auto px-6 max-w-7xl relative z-10">
        
        <div class="flex items-center gap-3 mb-8">
            <span class="w-10 h-[2px] bg-white"></span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end mb-14">
            <div class="lg:col-span-8">
                <h2 class="text-4xl sm:text-5xl lg:text-[58px] font-black leading-[1.08] tracking-tight text-white">
                    Ekosistem Layanan <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-sky-200 to-blue-500">
                        Digital Terpadu.
                    </span>
                </h2>
            </div>
            <div class="lg:col-span-4">
                <p class="text-gray-400 text-sm sm:text-base leading-relaxed border-l-2 border-cyan-400/40 pl-4 font-normal">
                    Solusi ekosistem teknologi terdedikasi untuk efisiensi operasional ASN dan Instansi Pemerintah Kabupaten Aceh Barat.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
            
            <div class="group bg-[#0B1528]/80 border border-white/10 rounded-[2rem] p-8 hover:border-cyan-400/60 transition-all duration-300 flex flex-col justify-between relative overflow-hidden backdrop-blur-xl shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/5">
                        <div class="w-11 h-11 rounded-xl bg-cyan-400/10 border border-cyan-400/20 text-cyan-300 flex items-center justify-center text-lg group-hover:bg-cyan-400 group-hover:text-[#040914] transition-all duration-300 shadow-sm">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                    </div>
                    <h3 class="font-extrabold text-white text-xl mb-3 tracking-tight group-hover:text-cyan-300 transition-colors">Teknis & Digital</h3>
                    <p class="text-xs text-slate-400 leading-relaxed font-normal mb-8">Pengembangan dan pengelolaan website resmi profil desa, gampong, serta instansi daerah.</p>
                </div>
                <a href="{{ route('pengajuan.website') }}" class="inline-flex items-center justify-between w-full pt-4 border-t border-white/10 text-xs font-bold text-white group-hover:text-white transition-colors">
                    <span class="tracking-wide">Ajukan Permohonan</span>
                    <i class="fa-solid fa-arrow-right text-xs transform group-hover:translate-x-1.5 transition-transform"></i>
                </a>
            </div>

            <div class="group bg-[#0B1528]/80 border border-white/10 rounded-[2rem] p-8 hover:border-cyan-400/60 transition-all duration-300 flex flex-col justify-between relative overflow-hidden backdrop-blur-xl shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/5">
                        <div class="w-11 h-11 rounded-xl bg-blue-400/10 border border-blue-400/20 text-blue-300 flex items-center justify-center text-lg group-hover:bg-cyan-400 group-hover:text-[#040914] transition-all duration-300 shadow-sm">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                    </div>
                    <h3 class="font-extrabold text-white text-xl mb-3 tracking-tight group-hover:text-cyan-300 transition-colors">Email Resmi</h3>
                    <p class="text-xs text-slate-400 leading-relaxed font-normal mb-8">Pembuatan dan aktivasi akun email resmi korporat berkategori go.id untuk seluruh ASN daerah.</p>
                </div>
                <a href="{{ route('pengajuan.email') }}" class="inline-flex items-center justify-between w-full pt-4 border-t border-white/10 text-xs font-bold text-white group-hover:text-white transition-colors">
                    <span class="tracking-wide">Ajukan Permohonan</span>
                    <i class="fa-solid fa-arrow-right text-xs transform group-hover:translate-x-1.5 transition-transform"></i>
                </a>
            </div>

            <div class="group bg-[#0B1528]/80 border border-white/10 rounded-[2rem] p-8 hover:border-cyan-400/60 transition-all duration-300 flex flex-col justify-between relative overflow-hidden backdrop-blur-xl shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/5">
                        <div class="w-11 h-11 rounded-xl bg-sky-400/10 border border-sky-400/20 text-sky-300 flex items-center justify-center text-lg group-hover:bg-cyan-400 group-hover:text-[#040914] transition-all duration-300 shadow-sm">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                    </div>
                    <h3 class="font-extrabold text-white text-xl mb-3 tracking-tight group-hover:text-cyan-300 transition-colors">Layanan TTE</h3>
                    <p class="text-xs text-slate-400 leading-relaxed font-normal mb-8">Fasilitasi penerbitan Tanda Tangan Elektronik tersertifikasi BSrE BSSN untuk pejabat instansi.</p>
                </div>
                <a href="{{ route('pengajuan.tte') }}" class="inline-flex items-center justify-between w-full pt-4 border-t border-white/10 text-xs font-bold text-white group-hover:text-white transition-colors">
                    <span class="tracking-wide">Ajukan Permohonan</span>
                    <i class="fa-solid fa-arrow-right text-xs transform group-hover:translate-x-1.5 transition-transform"></i>
                </a>
            </div>

            <div class="group bg-[#0B1528]/80 border border-white/10 rounded-[2rem] p-8 hover:border-cyan-400/60 transition-all duration-300 flex flex-col justify-between relative overflow-hidden backdrop-blur-xl shadow-xl lg:col-span-1">
                <div>
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/5">
                        <div class="w-11 h-11 rounded-xl bg-indigo-400/10 border border-indigo-400/20 text-indigo-300 flex items-center justify-center text-lg group-hover:bg-cyan-400 group-hover:text-[#040914] transition-all duration-300 shadow-sm">
                            <i class="fa-solid fa-cloud"></i>
                        </div>
                    </div>
                    <h3 class="font-extrabold text-white text-xl mb-3 tracking-tight group-hover:text-cyan-300 transition-colors">Cloud Gov</h3>
                    <p class="text-xs text-slate-400 leading-relaxed font-normal mb-8">Penyimpanan server cloud terdedikasi untuk manajemen dokumen dan kolaborasi file perangkat daerah.</p>
                </div>
                <a href="{{ route('pengajuan.cloud') }}" class="inline-flex items-center justify-between w-full pt-4 border-t border-white/10 text-xs font-bold text-white group-hover:text-white transition-colors">
                    <span class="tracking-wide">Ajukan Permohonan</span>
                    <i class="fa-solid fa-arrow-right text-xs transform group-hover:translate-x-1.5 transition-transform"></i>
                </a>
            </div>

            <div class="group bg-[#0B1528]/80 border border-white/10 rounded-[2rem] p-8 hover:border-cyan-400/60 transition-all duration-300 flex flex-col justify-between relative overflow-hidden backdrop-blur-xl shadow-xl md:col-span-2 lg:col-span-2">
                <div>
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/5">
                        <div class="w-11 h-11 rounded-xl bg-emerald-400/10 border border-emerald-400/20 text-emerald-300 flex items-center justify-center text-lg group-hover:bg-cyan-400 group-hover:text-[#040914] transition-all duration-300 shadow-sm">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                    </div>
                    <h3 class="font-extrabold text-white text-xl mb-3 tracking-tight group-hover:text-cyan-300 transition-colors">Pusat Bantuan</h3>
                    <p class="text-xs text-slate-400 leading-relaxed font-normal mb-8 max-w-xl">Layanan pertolongan cepat untuk kendala sistem, reset sandi email resmi, bantuan kendala OTP, serta permasalahan teknis jaringan ASN.</p>
                </div>
                <a href="{{ route('pengajuan.bantuan') }}" class="inline-flex items-center justify-between w-full pt-4 border-t border-white/10 text-xs font-bold text-white group-hover:text-white transition-colors">
                    <span class="tracking-wide">Ajukan Permohonan</span>
                    <i class="fa-solid fa-arrow-right text-xs transform group-hover:translate-x-1.5 transition-transform"></i>
                </a>
            </div>

        </div>

        <div class="bg-[#0B1528]/80 border border-white/10 rounded-2xl p-4 sm:p-5 backdrop-blur-xl">
            <div class="flex items-center justify-between mb-3 px-2">
                <span class="text-gray-400 font-mono font-bold text-[10px] tracking-widest uppercase flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span> LAYANAN TERSEDIA
                </span>
            </div>
            
            <div class="relative w-full overflow-hidden [mask-image:_linear-gradient(to_right,transparent_0,_black_64px,_black_calc(100%-64px),transparent_100%)]">
                <div class="animate-ticker gap-4 py-1">
                    
                    <div class="flex gap-4">
                        <div class="bg-white/5 border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-white/10 transition-colors cursor-default shrink-0">
                            <div class="w-7 h-7 bg-blue-500/20 text-blue-400 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-laptop-code text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Teknis & Digital</h4>
                                <p class="text-gray-400 text-[9px] mt-0.5">Website Profil</p>
                            </div>
                        </div>

                        <div class="bg-white/5 border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-white/10 transition-colors cursor-default shrink-0">
                            <div class="w-7 h-7 bg-rose-500/20 text-rose-400 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-envelope-circle-check text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Email Resmi</h4>
                                <p class="text-gray-400 text-[9px] mt-0.5">ASN & Instansi</p>
                            </div>
                        </div>

                        <div class="bg-white/5 border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-white/10 transition-colors cursor-default shrink-0">
                            <div class="w-7 h-7 bg-emerald-500/20 text-emerald-400 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-file-signature text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-[11px] whitespace-nowrap">TTE</h4>
                                <p class="text-gray-400 text-[9px] mt-0.5">Tanda Tangan Elektronik</p>
                            </div>
                        </div>

                        <div class="bg-white/5 border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-white/10 transition-colors cursor-default shrink-0">
                            <div class="w-7 h-7 bg-cyan-500/20 text-cyan-400 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Cloud Government</h4>
                                <p class="text-gray-400 text-[9px] mt-0.5">Penyimpanan Instansi</p>
                            </div>
                        </div>

                        <div class="bg-white/5 border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-white/10 transition-colors cursor-default shrink-0">
                            <div class="w-7 h-7 bg-amber-500/20 text-amber-400 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-headset text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Bantuan</h4>
                                <p class="text-gray-400 text-[9px] mt-0.5">Reset Sandi</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="bg-white/5 border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-white/10 transition-colors cursor-default shrink-0">
                            <div class="w-7 h-7 bg-blue-500/20 text-blue-400 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-laptop-code text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Teknis & Digital</h4>
                                <p class="text-gray-400 text-[9px] mt-0.5">Website Profil</p>
                            </div>
                        </div>

                        <div class="bg-white/5 border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-white/10 transition-colors cursor-default shrink-0">
                            <div class="w-7 h-7 bg-rose-500/20 text-rose-400 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-envelope-circle-check text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Email Resmi</h4>
                                <p class="text-gray-400 text-[9px] mt-0.5">ASN & Instansi</p>
                            </div>
                        </div>

                        <div class="bg-white/5 border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-white/10 transition-colors cursor-default shrink-0">
                            <div class="w-7 h-7 bg-emerald-500/20 text-emerald-400 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-file-signature text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-[11px] whitespace-nowrap">TTE</h4>
                                <p class="text-gray-400 text-[9px] mt-0.5">Tanda Tangan Elektronik</p>
                            </div>
                        </div>

                        <div class="bg-white/5 border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-white/10 transition-colors cursor-default shrink-0">
                            <div class="w-7 h-7 bg-cyan-500/20 text-cyan-400 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Cloud Government</h4>
                                <p class="text-gray-400 text-[9px] mt-0.5">Penyimpanan Instansi</p>
                            </div>
                        </div>

                        <div class="bg-white/5 border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-white/10 transition-colors cursor-default shrink-0">
                            <div class="w-7 h-7 bg-amber-500/20 text-amber-400 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-headset text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Bantuan</h4>
                                <p class="text-gray-400 text-[9px] mt-0.5">Reset Sandi</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>