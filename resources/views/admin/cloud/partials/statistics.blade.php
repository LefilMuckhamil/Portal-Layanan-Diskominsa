<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-6 mb-8">
    
    <!-- Card 1: Total Permohonan -->
    <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
        <div class="flex justify-between items-start mb-3">
            <div class="w-11 h-11 rounded-2xl bg-cyan-50 text-cyan-500 flex items-center justify-center text-lg">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
        </div>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Total Permohonan</p>
        <div class="flex items-end gap-2 mt-1">
            <h3 class="text-3xl font-extrabold text-[#071E3D]">{{ $pengajuans->count() }}</h3>
            <span class="text-[10px] font-bold text-cyan-500 mb-1">Ajuan</span>
        </div>
    </div>

    <!-- Card 2: Menunggu Alokasi -->
    <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
        <div class="flex justify-between items-start mb-3">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg">
                <i class="fa-solid fa-server"></i>
            </div>
        </div>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Menunggu Alokasi</p>
        <div class="flex items-end gap-2 mt-1">
            <h3 class="text-3xl font-extrabold text-[#071E3D]">{{ $pengajuans->whereIn('status', ['Pending', 'Menunggu Validasi'])->count() }}</h3>
            <span class="text-[10px] font-bold text-amber-500 mb-1">Antrean</span>
        </div>
    </div>

    <!-- Card 3: Proses Pembuatan -->
    <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
        <div class="flex justify-between items-start mb-3">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-lg">
                <i class="fa-solid fa-gears"></i>
            </div>
        </div>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Proses Pembuatan</p>
        <div class="flex items-end gap-2 mt-1">
            <h3 class="text-3xl font-extrabold text-[#071E3D]">{{ $pengajuans->whereIn('status', ['Proses', 'Sedang Diproses'])->count() }}</h3>
            <span class="text-[10px] font-bold text-blue-500 mb-1">Akun</span>
        </div>
    </div>

    <!-- Card 4: Akun Aktif -->
    <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
        <div class="flex justify-between items-start mb-3">
            <div class="w-11 h-11 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-lg">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Akun Aktif</p>
        <div class="flex items-end gap-2 mt-1">
            <h3 class="text-3xl font-extrabold text-[#071E3D]">{{ $pengajuans->where('status', 'Selesai')->count() }}</h3>
            <span class="text-[10px] font-bold text-green-500 mb-1">Pengguna</span>
        </div>
    </div>

    <!-- Card 5: Ajuan Ditolak -->
    <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
        <div class="flex justify-between items-start mb-3">
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        </div>
        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Ajuan Ditolak</p>
        <div class="flex items-end gap-2 mt-1">
            <h3 class="text-3xl font-extrabold text-[#071E3D]">{{ $pengajuans->where('status', 'Ditolak')->count() }}</h3>
            <span class="text-[10px] font-bold text-rose-500 mb-1">Batal</span>
        </div>
    </div>

</div>