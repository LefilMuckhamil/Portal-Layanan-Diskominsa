<footer class="bg-[#040914] text-white border-t border-white/10 relative z-10 selection:bg-cyan-400 selection:text-[#040914]">
    <div class="container mx-auto px-6 max-w-7xl pt-16 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12 pb-12 border-b border-white/10">
            
            <div class="lg:col-span-5 space-y-5">
                <a href="#beranda" class="flex items-center gap-3">
                    <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo Diskominsa" class="h-10 w-auto object-contain">
                    <span class="text-sm font-bold tracking-tight text-white">Diskominsa Aceh Barat</span>
                </a>
                
                <p class="text-xs text-gray-400 leading-relaxed font-normal max-w-sm">
                    Dinas Komunikasi, Informatika dan Persandian Kabupaten Aceh Barat. Mewujudkan tata kelola pemerintahan berbasis elektronik (SPBE) yang transparan dan terintegrasi.
                </p>

                <div class="flex items-center gap-3 pt-2">
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/pemkab.acehbarat/" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-cyan-400 hover:border-cyan-400/40 transition-all" title="Facebook Diskominsa Aceh Barat">
                        <i class="fa-brands fa-facebook-f text-xs"></i>
                    </a>
                    <!-- Instagram -->
                    <a href="https://www.instagram.com/diskominsaacehbarat?igsh=MXQxdGM2MjQ4bWFkNg==" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-cyan-400 hover:border-cyan-400/40 transition-all" title="Instagram Diskominsa Aceh Barat">
                        <i class="fa-brands fa-instagram text-xs"></i>
                    </a>
                    <!-- TikTok -->
                    <a href="https://www.tiktok.com/@diskominsaacehbarat?_r=1&_t=ZS-98zkM6pYTNk" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-cyan-400 hover:border-cyan-400/40 transition-all" title="TikTok Diskominsa Aceh Barat">
                        <i class="fa-brands fa-tiktok text-xs"></i>
                    </a>
                    <!-- YouTube -->
                    <a href="https://www.youtube.com/@pemkabacehbarat" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-cyan-400 hover:border-cyan-400/40 transition-all" title="YouTube Pemkab Aceh Barat">
                        <i class="fa-brands fa-youtube text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-4">
                <h4 class="text-xs font-mono font-bold text-cyan-400 tracking-widest uppercase">Navigasi</h4>
                <ul class="space-y-2.5 text-xs text-gray-400 font-medium">
                    <li><a href="#beranda" class="hover:text-cyan-300 transition-colors">Beranda</a></li>
                    <li><a href="#tentang" class="hover:text-cyan-300 transition-colors">Tentang</a></li>
                    <li><a href="#layanan" class="hover:text-cyan-300 transition-colors">Layanan</a></li>
                    <li><a href="#alur" class="hover:text-cyan-300 transition-colors">Alur Pengajuan</a></li>
                    <li><a href="#tracking" class="hover:text-cyan-300 transition-colors">E-Tracking</a></li>
                </ul>
            </div>

            <div class="lg:col-span-4 space-y-4">
                <h4 class="text-xs font-mono font-bold text-cyan-400 tracking-widest uppercase">Kontak</h4>
                <div class="space-y-3 text-xs text-gray-400 font-normal leading-relaxed">
                    <p class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot text-cyan-400 text-sm mt-0.5 shrink-0"></i>
                        <span>Jl. Gajah Mada, Meulaboh, Kabupaten Aceh Barat, Aceh</span>
                    </p>
                    <p class="flex items-center gap-3">
                        <i class="fa-regular fa-envelope text-cyan-400 text-sm shrink-0"></i>
                        <span>diskominsa@acehbaratkab.go.id</span>
                    </p>
                    <p class="flex items-center gap-3">
                        <i class="fa-solid fa-phone text-cyan-400 text-sm shrink-0"></i>
                        <span>0823 6872 3020</span>
                    </p>
                </div>
            </div>

        </div>

        <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-400 font-normal">
            <p>&copy; {{ date('Y') }} Diskominsa Kabupaten Aceh Barat. All rights reserved.</p>
            
            <div class="flex gap-6">
                <a href="#" class="hover:text-cyan-400 transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-cyan-400 transition-colors">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>