<section id="alur" class="py-28 bg-[#040914] text-white relative z-10 overflow-hidden selection:bg-cyan-400 selection:text-[#040914]">

    <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f293715_1px,transparent_1px),linear-gradient(to_bottom,#1f293715_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>

    <div class="absolute -top-12 left-1/2 -translate-x-1/2 text-[140px] sm:text-[200px] lg:text-[280px] font-black text-white/[0.02] uppercase tracking-tighter select-none pointer-events-none whitespace-nowrap">
        WORKFLOW
    </div>

    <div class="container mx-auto px-6 max-w-7xl relative z-10">
        
        <div class="flex items-center gap-3 mb-8">
            <span class="w-10 h-[1px] bg-cyan-500"></span>
            <span class="text-cyan-400 text-xs font-mono font-medium tracking-[0.2em] uppercase">
                Proses Pengajuan
            </span>
        </div>

        <div class="max-w-3xl mb-16">
            <h2 class="text-4xl sm:text-5xl font-bold leading-tight tracking-tight text-white mb-5">
                Alur Pengajuan <br>
                <span class="text-gray-400">Mudah & Transparan.</span>
            </h2>
            <p class="text-gray-400 text-base leading-relaxed border-l-[1px] border-gray-700 pl-5">
                Ikuti 5 langkah sederhana di bawah ini untuk mengajukan layanan digital. Anda dapat memantau status pengajuan secara real-time melalui dashboard.
            </p>
        </div>

        @php
            $steps = [
                ['icon' => 'fa-regular fa-id-badge', 'title' => 'Masuk Akun', 'desc' => 'Gunakan akun instansi atau NIP Anda untuk mengakses sistem layanan.'],
                ['icon' => 'fa-solid fa-layer-group', 'title' => 'Pilih Layanan', 'desc' => 'Cari dan pilih jenis layanan digital sesuai dengan kebutuhan Anda.'],
                ['icon' => 'fa-regular fa-folder-open', 'title' => 'Unggah Berkas', 'desc' => 'Lengkapi formulir dan unggah dokumen pendukung yang diminta.'],
                ['icon' => 'fa-solid fa-arrows-rotate', 'title' => 'Proses Verifikasi', 'desc' => 'Tim kami akan mengecek kelengkapan dan keabsahan dokumen Anda.'],
                ['icon' => 'fa-regular fa-circle-check', 'title' => 'Layanan Aktif', 'desc' => 'Pengajuan disetujui, layanan atau dokumen langsung dapat digunakan.']
            ];
        @endphp

        <div class="relative grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-6 mt-8">
            
            <div class="hidden lg:block absolute top-[2.5rem] left-[10%] right-[10%] h-[1px] border-t border-dashed border-gray-800 z-0"></div>

            @foreach($steps as $index => $step)
            <div class="group bg-[#0a1120] border border-gray-800/80 rounded-2xl p-6 hover:bg-[#101a2e] hover:border-gray-700 transition-all duration-300 relative z-10 shadow-sm hover:shadow-xl">
                
                <div class="absolute top-2 right-4 text-[52px] font-black text-gray-800/20 group-hover:text-cyan-900/10 transition-colors duration-300 pointer-events-none select-none">
                    0{{ $index + 1 }}
                </div>
                
                <div class="w-12 h-12 rounded-full bg-[#040914] border border-gray-800 text-gray-500 flex items-center justify-center text-lg group-hover:border-cyan-500/50 group-hover:text-cyan-400 group-hover:shadow-[0_0_15px_rgba(34,211,238,0.15)] transition-all duration-300 mb-6 relative z-20">
                    <i class="{{ $step['icon'] }}"></i>
                </div>

                <div class="relative z-20">
                    <h3 class="font-semibold text-white text-base mb-2 group-hover:text-cyan-100 transition-colors">
                        {{ $step['title'] }}
                    </h3>
                    <p class="text-sm text-gray-400 leading-relaxed font-light">
                        {{ $step['desc'] }}
                    </p>
                </div>

            </div>
            @endforeach

        </div>

    </div>
</section>