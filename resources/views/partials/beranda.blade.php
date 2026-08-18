<style>
    @keyframes fadeUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes scrollX {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    @keyframes modalScale {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }

    .fade-up {
        animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    .scroller-content-x {
        animation: scrollX 20s linear infinite;
        width: max-content;
    }

    .scroller-content-x:hover {
        animation-play-state: paused;
    }

    .bento-card {
        background: #0B1324;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 1.5rem;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .bento-card:hover {
        border-color: rgba(255, 255, 255, 0.1);
    }

    .mask-x {
        -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
        mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
    }

    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
</style>

<section id="beranda" class="relative bg-[#040914] min-h-[calc(100vh-80px)] flex items-center pt-8 pb-8 font-sans selection:bg-cyan-500/30">
    
    <div class="container mx-auto px-5 relative z-10 w-full max-w-7xl">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
            
            <div class="bento-card col-span-1 lg:col-span-7 p-8 md:p-10 flex flex-col justify-center fade-up relative min-h-[380px] lg:min-h-[440px]">
                <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-900/20 blur-3xl rounded-full pointer-events-none"></div>
                
                <div class="relative z-10 flex-1 flex flex-col justify-between">
                    <div class="mb-4">
                        <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo Diskominsa Aceh Barat" class="h-16 md:h-24 w-auto object-contain object-left">
                    </div>

                    <h1 class="text-3xl md:text-5xl font-black text-white leading-tight tracking-tight mb-3">
                        Layanan <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">
                            Diskominsa
                        </span>
                    </h1>
                    
                    <p class="text-gray-400 text-xs md:text-sm max-w-md leading-relaxed font-medium mb-6">
                        Akses satu pintu seluruh layanan digital Pemerintah Kabupaten Aceh Barat. Cepat, terintegrasi, dan dijamin keamanannya.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-3 mt-auto">
                        <a href="#layanan" onclick="document.getElementById('layanan')?.scrollIntoView({ behavior: 'smooth' }); return false;" class="bg-cyan-400 text-[#040914] px-6 py-3.5 rounded-xl font-bold hover:bg-cyan-300 transition-colors text-center text-xs md:text-sm flex items-center justify-center gap-2 cursor-pointer">
                            Mulai Pengajuan
                        </a>

                        <a href="#tracking" onclick="document.getElementById('tracking')?.scrollIntoView({ behavior: 'smooth' }); return false;" class="bg-[#1A253A] text-white border border-[#2A3B5A] px-6 py-3.5 rounded-xl font-bold hover:bg-[#202D45] transition-colors text-center text-xs md:text-sm flex items-center justify-center gap-2 cursor-pointer">
                            Lacak Tiket
                        </a>

                        <a href="{{ asset('docs/panduan-portal-layanan.pdf') }}" target="_blank" rel="noopener noreferrer" class="bg-[#1A253A] text-white border border-[#2A3B5A] px-6 py-3.5 rounded-xl font-bold hover:bg-[#202D45] transition-colors text-center text-xs md:text-sm flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-book-open"></i> Panduan Penggunaan Layanan
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-span-1 lg:col-span-5 flex flex-col gap-5">
                
                <div class="bento-card flex-1 fade-up delay-100 group relative p-0 overflow-hidden min-h-[220px]">
                    <img src="{{ asset('image/diskominsa.jpeg') }}" alt="Kantor Diskominsa" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0B1324] via-[#0B1324]/40 to-transparent opacity-90 group-hover:opacity-80 transition-opacity duration-500"></div>
                    
                    <div class="absolute bottom-5 left-5 right-5">
                        <div class="flex items-center gap-3 bg-[#0B1324]/80 backdrop-blur-md border border-white/10 p-3 rounded-xl w-max">
                            <div class="w-9 h-9 bg-[#1A2E4F] rounded-lg flex items-center justify-center text-cyan-400">
                                <i class="fa-solid fa-building text-sm"></i>
                            </div>
                            <div class="pr-2">
                                <h3 class="text-white font-bold text-xs leading-tight">Kantor Diskominsa</h3>
                                <p class="text-cyan-400 text-[10px] font-medium mt-0.5">Kabupaten Aceh Barat</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bento-card p-4 h-[120px] flex flex-col justify-center fade-up delay-200 shrink-0">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-gray-400 font-bold text-[10px] tracking-widest uppercase">Layanan Tersedia</h3>
                    </div>
                    
                    <div class="mask-x overflow-hidden">
                        <div class="scroller-content-x flex gap-4">
                            
                            <div class="flex gap-4">
                                <div class="bg-[#101F38] border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-[#1A2E4F] transition-colors cursor-default">
                                    <div class="w-7 h-7 bg-blue-500/20 text-blue-400 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-laptop-code text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Teknis & Digital</h4>
                                        <p class="text-gray-400 text-[9px] mt-0.5">Website</p>
                                    </div>
                                </div>

                                <div class="bg-[#101F38] border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-[#1A2E4F] transition-colors cursor-default">
                                    <div class="w-7 h-7 bg-rose-500/20 text-rose-400 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-envelope-circle-check text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Email Resmi</h4>
                                        <p class="text-gray-400 text-[9px] mt-0.5">Asn & Instansi</p>
                                    </div>
                                </div>

                                <div class="bg-[#101F38] border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-[#1A2E4F] transition-colors cursor-default">
                                    <div class="w-7 h-7 bg-emerald-500/20 text-emerald-400 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-file-signature text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-[11px] whitespace-nowrap">TTE</h4>
                                        <p class="text-gray-400 text-[9px] mt-0.5">Tanda Tangan Elektronik</p>
                                    </div>
                                </div>

                                <div class="bg-[#101F38] border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-[#1A2E4F] transition-colors cursor-default">
                                    <div class="w-7 h-7 bg-cyan-500/20 text-cyan-400 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Cloud Goverment</h4>
                                        <p class="text-gray-400 text-[9px] mt-0.5">Penyimpanan Instansi</p>
                                    </div>
                                </div>

                                <div class="bg-[#101F38] border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-[#1A2E4F] transition-colors cursor-default">
                                    <div class="w-7 h-7 bg-amber-500/20 text-amber-400 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-headset text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Bantuan</h4>
                                        <p class="text-gray-400 text-[9px] mt-0.5">Pengajuan Reset Sandi</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="bg-[#101F38] border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-[#1A2E4F] transition-colors cursor-default">
                                    <div class="w-7 h-7 bg-blue-500/20 text-blue-400 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-laptop-code text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Teknis & Digital</h4>
                                        <p class="text-gray-400 text-[9px] mt-0.5">Website</p>
                                    </div>
                                </div>

                                <div class="bg-[#101F38] border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-[#1A2E4F] transition-colors cursor-default">
                                    <div class="w-7 h-7 bg-rose-500/20 text-rose-400 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-envelope-circle-check text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Email Resmi</h4>
                                        <p class="text-gray-400 text-[9px] mt-0.5">Asn & Instansi</p>
                                    </div>
                                </div>

                                <div class="bg-[#101F38] border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-[#1A2E4F] transition-colors cursor-default">
                                    <div class="w-7 h-7 bg-emerald-500/20 text-emerald-400 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-file-signature text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-[11px] whitespace-nowrap">TTE</h4>
                                        <p class="text-gray-400 text-[9px] mt-0.5">Tanda Tangan Elektronik</p>
                                    </div>
                                </div>

                                <div class="bg-[#101F38] border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-[#1A2E4F] transition-colors cursor-default">
                                    <div class="w-7 h-7 bg-cyan-500/20 text-cyan-400 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Cloud Goverment</h4>
                                        <p class="text-gray-400 text-[9px] mt-0.5">Penyimpanan Instansi</p>
                                    </div>
                                </div>

                                <div class="bg-[#101F38] border border-white/5 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:bg-[#1A2E4F] transition-colors cursor-default">
                                    <div class="w-7 h-7 bg-amber-500/20 text-amber-400 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-headset text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-[11px] whitespace-nowrap">Bantuan</h4>
                                        <p class="text-gray-400 text-[9px] mt-0.5">Pengajuan Reset Sandi</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<div id="authModal" class="fixed inset-0 z-[100] hidden items-center justify-center">
    <div class="absolute inset-0 bg-[#040914]/80 backdrop-blur-sm transition-opacity" onclick="closeAuthModal()"></div>
    
    <div id="authModalBox" class="relative bg-white border border-gray-100 rounded-[2rem] p-8 max-w-md w-full mx-4 shadow-2xl opacity-0 transform scale-95 transition-all">
        
        <div class="flex items-center justify-between mb-6">
            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-800 text-lg border border-gray-100">
                <i class="fa-solid fa-lock"></i>
            </div>
            <button type="button" onclick="closeAuthModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-white text-gray-400 hover:bg-gray-100 hover:text-gray-800 transition-colors border border-gray-100">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <h3 class="text-2xl font-black text-gray-900 tracking-tight mb-2">Akses Terbatas</h3>
        <p class="text-gray-500 text-sm mb-8 leading-relaxed font-medium">
            Fitur pelacakan tiket E-Tracking didedikasikan untuk ASN terdaftar. Silakan masuk ke akun Anda terlebih dahulu.
        </p>
        
        <div class="flex flex-col gap-3">
            <a href="{{ route('login') }}" class="w-full py-3.5 rounded-xl font-bold text-white bg-[#0B1324] hover:bg-[#101F38] transition-colors flex items-center justify-center gap-2 text-[15px]">
                Masuk ke Akun
            </a>
            <button type="button" onclick="closeAuthModal()" class="w-full py-3.5 rounded-xl font-bold text-gray-600 bg-gray-50 border border-gray-200 hover:bg-gray-100 transition-colors text-[15px]">
                Batal
            </button>
        </div>
    </div>
</div>

<script>
    function openAuthModal() {
        const modal = document.getElementById('authModal');
        const modalBox = document.getElementById('authModalBox');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        requestAnimationFrame(() => {
            modalBox.style.animation = 'modalScale 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards';
        });
    }
    
    function closeAuthModal() {
        const modal = document.getElementById('authModal');
        const modalBox = document.getElementById('authModalBox');
        
        modalBox.style.animation = '';
        modalBox.style.opacity = '0';
        modalBox.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
</script>