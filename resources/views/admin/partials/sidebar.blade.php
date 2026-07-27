<aside class="w-72 bg-[#071E3D] text-white flex flex-col h-full flex-shrink-0 transition-all duration-300 shadow-xl z-20">
    <div class="h-24 flex items-center px-6 border-b border-white/10">
        <div class="flex items-center gap-3">
            <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo" class="w-10 h-10 object-contain bg-white rounded-lg p-1">
            <div class="flex flex-col">
                <span class="text-[16px] font-extrabold tracking-wide leading-tight">Portal Layanan</span>
                <span class="text-[11px] text-cyan-400 font-medium">Diskominsa Aceh Barat</span>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
        <p class="px-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>
        
        <!-- Tambahkan class active dinamis jika diperlukan nanti -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-cyan-500/10 text-cyan-400 rounded-xl font-semibold transition-all duration-300">
            <i class="fa-solid fa-chart-pie w-5 text-center"></i>
            <span class="text-[13px]">Dashboard Overview</span>
        </a>

        <a href="{{ route('admin.web-desa.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-xl font-medium transition-all duration-300 group hover:translate-x-1">
            <i class="fa-solid fa-laptop-code w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
            <div class="flex flex-col leading-tight">
                <span class="text-[13px]">Teknis & Digital</span>
                <span class="text-[10px] text-gray-400">Website Desa & Pesantren</span>
            </div>
        </a>

        <a href="{{ route('admin.email.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-xl font-medium transition-all duration-300 group hover:translate-x-1">
            <i class="fa-solid fa-envelope-circle-check w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
            <div class="flex flex-col leading-tight">
                <span class="text-[13px]">Email Resmi</span>
                <span class="text-[10px] text-gray-400">Akun ASN & Instansi</span>
            </div>
        </a>

        <a href="{{ route('admin.tte.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-xl font-medium transition-all duration-300 group hover:translate-x-1">
            <i class="fa-solid fa-file-signature w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
            <span class="text-[13px]">Layanan TTE</span>
        </a>

        <a href="{{ route('admin.bantuan.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-white/5 rounded-xl font-medium transition-all duration-300 group hover:translate-x-1">
            <i class="fa-solid fa-headset w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
            <div class="flex flex-col leading-tight">
                <span class="text-[13px]">Layanan Bantuan</span>
                <span class="text-[10px] text-gray-400">Permohonan Reset PW / OTP</span>
            </div>
        </a>
    </div>

    <div class="p-4 border-t border-white/10 flex flex-col gap-3">
    <!-- Info Profil -->
    <div class="flex items-center gap-3 px-3 py-2 bg-white/5 rounded-xl">
        <img src="https://ui-avatars.com/api/?name=Admin+Portal&background=0D8ABC&color=fff" alt="User" class="w-9 h-9 rounded-full object-cover shrink-0">
        <div class="flex-1 min-w-0">
            <p class="text-[13px] font-bold text-white truncate">Admin Portal</p>
            <p class="text-[11px] text-gray-400 truncate">Administrator</p>
        </div>
    </div>

    <!-- Tombol Keluar / Logout -->
    <!-- Catatan untuk Backend nanti: Sebaiknya gunakan <form method="POST"> untuk keamanan -->
    <a href="{{ url('/') }}" class="flex items-center justify-center gap-2 w-full py-2.5 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white border border-red-500/20 hover:border-red-500 rounded-xl text-[12px] font-bold transition-all duration-300 group">
        <i class="fa-solid fa-arrow-right-from-bracket group-hover:-translate-x-1 transition-transform"></i>
        Keluar Sistem
    </a>
</div>
</aside>