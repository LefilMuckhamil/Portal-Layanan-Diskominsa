<style>
    @keyframes textGlowAnimated {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    .animate-text-glow {
        background-size: 200% auto;
        animation: textGlowAnimated 6s linear infinite;
    }

    .magnet-text {
        transition: transform 0.2s cubic-bezier(0.25, 1, 0.5, 1);
        display: inline-block;
    }
</style>

<section id="tentang" class="py-24 bg-[#040914] text-white relative z-10 overflow-hidden selection:bg-cyan-400 selection:text-[#040914]">

    <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f293715_1px,transparent_1px),linear-gradient(to_bottom,#1f293715_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>

    <div class="absolute -top-10 left-1/2 -translate-x-1/2 text-[120px] sm:text-[180px] lg:text-[240px] font-black text-white/[0.02] uppercase tracking-tighter select-none pointer-events-none whitespace-nowrap">
        ACEH BARAT
    </div>

    <div class="container mx-auto px-6 max-w-7xl relative z-10">
        <div class="flex items-center gap-3 mb-8">
            <span class="w-10 h-[2px] bg-cyan-400"></span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">

            <div class="lg:col-span-7 space-y-8">
                
                <h2 class="text-4xl sm:text-5xl lg:text-[62px] font-black leading-[1.08] tracking-tight text-white">
                    Penggerak Utama <br>
                    <span class="magnet-text text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-sky-200 to-blue-500 animate-text-glow">
                        Transformasi Digital
                    </span> <br>
                    Birokrasi Daerah.
                </h2>

                <div class="space-y-5 text-gray-400 text-base sm:text-lg leading-relaxed font-normal max-w-2xl">
                    <p class="border-l-2 border-white pl-5 text-slate-200 font-medium">
                        Dinas Komunikasi, Informatika dan Persandian, Diskominsa Kabupaten Aceh Barat memegang peranan strategis sebagai fondasi teknologi, komunikasi publik, serta keamanan informasi pemerintah daerah.
                    </p>
                    <p class="pl-5 text-gray-400">
                        Melalui portal terpadu ini, kami mengintegrasikan berbagai infrastruktur sistem untuk mewujudkan tata kelola birokrasi yang Cepat, Transparan, dan Terintegrasi.
                    </p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-6 border-t border-white/10">
                    <div class="bg-white/5 border border-white/10 p-4 rounded-2xl backdrop-blur-md hover:border-white transition-colors">
                        <div class="text-2xl sm:text-3xl font-mono font-black text-white">
                            5<span class="text-white text-xl tracking-wider"></span>
                        </div>
                        <div class="text-[10px] sm:text-[11px] font-mono text-gray-400 uppercase tracking-wider mt-1">
                            Layanan Utama
                        </div>
                    </div>

                    <div class="bg-white/5 border border-white/10 p-4 rounded-2xl backdrop-blur-md hover:border-white transition-colors">
                        <div class="text-2xl sm:text-3xl font-mono font-black text-white">
                            100<span class="text-white text-xl">%</span>
                        </div>
                        <div class="text-[10px] sm:text-[11px] font-mono text-gray-400 uppercase tracking-wider mt-1">
                            Integrasi Digital
                        </div>
                    </div>

                    <div class="col-span-2 sm:col-span-1 bg-white/5 border border-white/10 p-4 rounded-2xl backdrop-blur-md hover:border-white transition-colors">
                        <div class="text-2xl sm:text-3xl font-mono font-black text-white">
                            24<span class="text-white text-xl">/7</span>
                        </div>
                        <div class="text-[10px] sm:text-[11px] font-mono text-gray-400 uppercase tracking-wider mt-1">
                            Akses Sistem
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-5 relative w-full pt-2">
                
                <div class="absolute -top-10 -right-10 w-60 h-60 bg-cyan-500/10 rounded-full blur-[90px] pointer-events-none"></div>

                <div class="relative space-y-5">
                    
                    <div class="relative group rounded-3xl overflow-hidden border border-white/15 bg-white/5 shadow-2xl transition-all duration-500 hover:border-cyan-400/50">
                        <a href="{{ asset('image/diskominsa.jpeg') }}" target="_blank" class="block overflow-hidden relative aspect-[16/10]">
                            <img src="{{ asset('image/diskominsa.jpeg') }}" alt="Gedung Diskominsa Aceh Barat" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 filter grayscale contrast-110 group-hover:grayscale-0">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-[#040914] via-transparent to-transparent opacity-85"></div>
                            
                            <div class="absolute bottom-4 left-5 right-5 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-bold text-white">Diskominsa Aceh Barat</p>
                                </div>
                                <span class="w-8 h-8 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white group-hover:bg-cyan-400 group-hover:text-[#040914] transition-all">
                                    <i class="fa-solid fa-arrow-up-right text-xs"></i>
                                </span>
                            </div>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        
                        <div class="sm:col-span-7 relative group rounded-2xl overflow-hidden border border-white/10 bg-white/5">
                            <a href="{{ asset('image/diskominsa1.jpeg') }}" target="_blank" class="block overflow-hidden relative aspect-[4/3]">
                                <img src="{{ asset('image/diskominsa1.jpeg') }}" alt="Gedung Samping Diskominsa" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 filter grayscale contrast-110 group-hover:grayscale-0">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#040914] via-transparent to-transparent opacity-75"></div>
                            </a>
                        </div>

                        <div class="sm:col-span-5 bg-white/5 border border-white/10 p-5 rounded-2xl backdrop-blur-md flex flex-col justify-center h-full">
                            <div class="w-8 h-8 rounded-lg bg-cyan-400/20 text-cyan-300 flex items-center justify-center text-sm mb-2.5">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <p class="text-xs font-bold text-white">Meulaboh</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">Kabupaten Aceh Barat</p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const magnetText = document.querySelector('.magnet-text');
        
        if (magnetText) {
            magnetText.addEventListener('mousemove', function(e) {
                const rect = magnetText.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                magnetText.style.transform = `translate(${x * 0.12}px, ${y * 0.12}px)`;
            });

            magnetText.addEventListener('mouseleave', function() {
                magnetText.style.transform = 'translate(0px, 0px)';
            });
        }
    });
</script>