<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-6">
    <!-- Kartu 1: Total -->
    <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
        <div class="flex justify-between items-start mb-3">
            <div class="w-11 h-11 rounded-2xl bg-cyan-50 text-cyan-500 flex items-center justify-center text-lg">
                <i class="fa-solid fa-envelope-circle-check"></i>
            </div>
        </div>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Total Permohonan</p>
        <div class="flex items-end gap-2 mt-1">
            <h3 class="text-3xl font-extrabold text-[#071E3D]">{{ $total ?? 0 }}</h3>
            <span class="text-[10px] font-bold text-cyan-500 mb-1">Akun</span>
        </div>
    </div>

    <!-- Kartu 2: Menunggu -->
    <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
        <div class="flex justify-between items-start mb-3">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Menunggu Verif</p>
        <div class="flex items-end gap-2 mt-1">
            <h3 class="text-3xl font-extrabold text-[#071E3D]">{{ $pending ?? 0 }}</h3>
            <span class="text-[10px] font-bold text-amber-500 mb-1">Antrean</span>
        </div>
    </div>

    <!-- Kartu 3: Proses -->
    <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
        <div class="flex justify-between items-start mb-3">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-lg">
                <i class="fa-solid fa-gears"></i>
            </div>
        </div>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Proses Pembuatan</p>
        <div class="flex items-end gap-2 mt-1">
            <h3 class="text-3xl font-extrabold text-[#071E3D]">{{ $proses ?? 0 }}</h3>
            <span class="text-[10px] font-bold text-blue-500 mb-1">Dikerjakan</span>
        </div>
    </div>

    <!-- Kartu 4: Selesai -->
    <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
        <div class="flex justify-between items-start mb-3">
            <div class="w-11 h-11 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-lg">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Akun Aktif</p>
        <div class="flex items-end gap-2 mt-1">
            <h3 class="text-3xl font-extrabold text-[#071E3D]">{{ $selesai ?? 0 }}</h3>
            <span class="text-[10px] font-bold text-green-500 mb-1">Berhasil</span>
        </div>
    </div>

    <!-- Kartu 5: Ditolak -->
    <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
        <div class="flex justify-between items-start mb-3">
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        </div>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Ajuan Ditolak</p>
        <div class="flex items-end gap-2 mt-1">
            <h3 class="text-3xl font-extrabold text-[#071E3D]">{{ $ditolak ?? 0 }}</h3>
            <span class="text-[10px] font-bold text-rose-500 mb-1">Dibatalkan</span>
        </div>
    </div>
</div>