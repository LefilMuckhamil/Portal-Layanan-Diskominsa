<section id="alur" class="py-20 bg-white">
        <div class="container mx-auto px-6 max-w-6xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-primary-dark mb-4">Alur Pengajuan Layanan</h2>
                <div class="w-16 h-1.5 bg-primary-light mx-auto rounded-full"></div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start text-center relative">
                <!-- Dotted Line -->
                <div class="hidden md:block absolute top-10 left-[10%] w-[80%] border-t-2 border-dashed border-gray-300 z-0"></div>

                @php
                    $steps = [
                        ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'title' => 'Login / Daftar', 'desc' => 'Gunakan akun instansi.'],
                        ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Pilih Layanan', 'desc' => 'Tentukan layanan.'],
                        ['icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'title' => 'Isi Formulir', 'desc' => 'Lengkapi dokumen.'],
                        ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Verifikasi', 'desc' => 'Diproses oleh admin.'],
                        ['icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'title' => 'Selesai', 'desc' => 'Layanan siap digunakan.']
                    ];
                @endphp

                @foreach($steps as $index => $step)
                <div class="relative z-10 flex flex-col items-center w-full md:w-1/5 mb-10 md:mb-0 bg-white px-2">
                    <div class="w-20 h-20 rounded-full bg-blue-50 text-primary-light flex items-center justify-center mb-6 shadow-md border border-gray-100">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step['icon'] }}"></path></svg>
                    </div>
                    <h4 class="font-bold text-primary-dark mb-2 flex items-center justify-center gap-2">
                        <span class="w-5 h-5 bg-primary-light text-white rounded-full text-[10px] flex items-center justify-center">{{ $index + 1 }}</span> 
                        {{ $step['title'] }}
                    </h4>
                    <p class="text-sm text-gray-500">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>