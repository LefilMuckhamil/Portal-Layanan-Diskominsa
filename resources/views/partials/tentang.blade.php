<!-- SECTION 2: TENTANG KAMI -->
<section id="tentang" class="py-20 bg-white relative z-10 overflow-hidden">
    
    <!-- Ornamen Watermark -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-[120px] md:text-[200px] font-black text-gray-50 opacity-60 pointer-events-none select-none z-0">
        G2G
    </div>

    <div class="container mx-auto px-6 max-w-6xl relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <!-- Kolom Teks (Kiri) -->
            <div class="text-left">
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#071E3D] mb-4">Tentang Diskominsa</h2>
                <div class="w-20 h-1.5 bg-cyan-500 rounded-full mb-8"></div>
                <p class="text-lg text-gray-600 leading-relaxed mb-6">
                    Dinas Komunikasi, Informatika dan Persandian (<span class="font-bold text-[#1F4287]">Diskominsa</span>) Kabupaten Aceh Barat merupakan unsur pelaksana urusan pemerintahan bidang komunikasi, informatika, statistik, dan persandian. 
                </p>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Melalui portal G2G (Government to Government) ini, kami berkomitmen untuk mewujudkan digitalisasi pelayanan birokrasi yang lebih <strong>Cepat, Transparan, dan Terintegrasi</strong> untuk seluruh instansi pemerintah daerah.
                </p>
            </div>

            <!-- Kolom Galeri Foto (Kanan) -->
            <div class="grid grid-cols-2 gap-4 relative">
                <!-- Ornamen hiasan di belakang foto -->
                <div class="absolute -top-6 -right-6 w-32 h-32 bg-blue-50 rounded-full -z-10"></div>
                <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-cyan-50 rounded-full -z-10"></div>

                <!-- Foto 1 -->
                <a href="{{ asset('image/diskominsa.jpeg') }}" target="_blank" class="group block overflow-hidden rounded-2xl shadow-lg border border-gray-100 transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <img src="{{ asset('image/diskominsa.jpeg') }}" alt="Kantor Diskominsa" class="w-full aspect-square object-cover group-hover:scale-110 transition duration-500">
                    <!-- Overlay hitam transparan saat dihover -->
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition duration-300 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition duration-300 transform scale-50 group-hover:scale-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                    </div>
                </a>

                <!-- Foto 2 -->
                <a href="{{ asset('image/diskominsa1.jpeg') }}" target="_blank" class="group block overflow-hidden rounded-2xl shadow-lg border border-gray-100 transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl mt-8">
                    <img src="{{ asset('image/diskominsa1.jpeg') }}" alt="Kantor Diskominsa" class="w-full aspect-square object-cover group-hover:scale-110 transition duration-500">
                    <!-- Overlay hitam transparan saat dihover -->
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition duration-300 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition duration-300 transform scale-50 group-hover:scale-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>