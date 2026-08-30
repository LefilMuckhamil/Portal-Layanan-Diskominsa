<aside id="adminSidebar" class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full lg:translate-x-0 lg:static lg:z-20 bg-[#071E3D] text-white flex flex-col h-full flex-shrink-0 transition-transform duration-300 ease-in-out shadow-xl">
    <div class="h-24 flex items-center px-6 border-b border-white/10">
        <div class="flex items-center gap-3 flex-1">
            <img src="{{ asset('image/icon.png') }}" alt="Logo" class="w-10 h-10 object-contain bg-white rounded-lg p-1">
            <div class="flex flex-col">
                <span class="text-[16px] font-extrabold tracking-wide leading-tight">Portal Layanan</span>
                <span class="text-[11px] text-cyan-400 font-medium">Diskominsa Aceh Barat</span>
            </div>
        </div>
        <button onclick="closeSidebar()" class="lg:hidden w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 text-white transition-colors cursor-pointer" aria-label="Tutup menu">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
        <p class="px-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>
        
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white hover:bg-white/5 font-medium' }} rounded-xl transition-all duration-300">
            <i class="fa-solid fa-chart-pie w-5 text-center"></i>
            <span class="text-[13px]">Dashboard Overview</span>
        </a>

        @php
            $teknisAktif = request()->routeIs('admin.website.*', 'admin.subdomain.*', 'admin.hosting.*');
        @endphp

        <div class="rounded-xl overflow-hidden {{ $teknisAktif ? 'bg-cyan-500/10' : '' }} transition-colors duration-300">
            <button type="button" id="toggleTeknisDigital" class="w-full flex items-center gap-3 px-4 py-3 {{ $teknisAktif ? 'text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white hover:bg-white/5 font-medium' }} rounded-xl transition-all duration-300 group cursor-pointer">
                <i class="fa-solid fa-laptop-code w-5 text-center group-hover:text-cyan-400 transition-colors shrink-0"></i>
                <div class="flex flex-col leading-tight flex-1 min-w-0 text-left">
                    <span class="text-[13px]">Teknis & Digital</span>
                    <span class="text-[10px] text-gray-400">Website / Subdomain / Hosting</span>
                </div>
                <i id="chevronTeknisDigital" class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform duration-300 shrink-0 {{ $teknisAktif ? 'rotate-180' : '' }}"></i>
            </button>

            <div id="submenuTeknisDigital" class="px-4 pb-3 space-y-1 {{ $teknisAktif ? '' : 'hidden' }}">
                <a href="{{ route('admin.website.index') }}" class="flex items-center gap-3 pl-8 pr-4 py-2.5 {{ request()->routeIs('admin.website.*') ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white hover:bg-white/5 font-medium' }} rounded-xl transition-all duration-300 text-[12.5px]">
                    <i class="fa-solid fa-globe w-4 text-center shrink-0"></i> Website
                </a>
                <a href="{{ route('admin.subdomain.index') }}" class="flex items-center gap-3 pl-8 pr-4 py-2.5 {{ request()->routeIs('admin.subdomain.*') ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white hover:bg-white/5 font-medium' }} rounded-xl transition-all duration-300 text-[12.5px]">
                    <i class="fa-solid fa-eject w-4 text-center shrink-0"></i> Subdomain
                </a>
                <a href="{{ route('admin.hosting.index') }}" class="flex items-center gap-3 pl-8 pr-4 py-2.5 {{ request()->routeIs('admin.hosting.*') ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white hover:bg-white/5 font-medium' }} rounded-xl transition-all duration-300 text-[12.5px]">
                    <i class="fa-solid fa-server w-4 text-center shrink-0"></i> Hosting
                </a>
            </div>
        </div>

        <a href="{{ route('admin.email.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.email.*') ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white hover:bg-white/5 font-medium' }} rounded-xl transition-all duration-300 group hover:translate-x-1">
            <i class="fa-solid fa-envelope-circle-check w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
            <div class="flex flex-col leading-tight">
                <span class="text-[13px]">Email Resmi</span>
                <span class="text-[10px] text-gray-400">Asn & Instansi</span>
            </div>
        </a>

        <a href="{{ route('admin.tte.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.tte.*') ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white hover:bg-white/5 font-medium' }} rounded-xl transition-all duration-300 group hover:translate-x-1">
            <i class="fa-solid fa-file-signature w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
            <div class="flex flex-col leading-tight">
                <span class="text-[13px]">TTE</span>
                <span class="text-[10px] text-gray-400">Tanda Tangan Elektronik</span>
            </div>
        </a>

        <a href="{{ route('admin.cloud.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.cloud.*') ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white hover:bg-white/5 font-medium' }} rounded-xl transition-all duration-300 group hover:translate-x-1">
            <i class="fa-solid fa-cloud-arrow-up w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
            <div class="flex flex-col leading-tight">
                <span class="text-[13px]">Cloud Goverment</span>
                <span class="text-[10px] text-gray-400">Penyimpanan Instansi</span>
            </div>
        </a>

        <a href="{{ route('admin.bantuan.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.bantuan.*') ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white hover:bg-white/5 font-medium' }} rounded-xl transition-all duration-300 group hover:translate-x-1">
            <i class="fa-solid fa-headset w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
            <div class="flex flex-col leading-tight">
                <span class="text-[13px]">Pusat Bantuan</span>
                <span class="text-[10px] text-gray-400">Pengajuan Reset Sandi / Tiket Bantuan</span>
            </div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.users.*') ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white hover:bg-white/5 font-medium' }} rounded-xl transition-all duration-300 group hover:translate-x-1">
            <i class="fa-solid fa-users w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
            <div class="flex flex-col leading-tight">
                <span class="text-[13px]">Manajemen Akun</span>
                <span class="text-[10px] text-gray-400">Kelola Pengguna ASN</span>
            </div>
        </a>

        <a href="{{ route('admin.reset-password.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.reset-password.*') ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white hover:bg-white/5 font-medium' }} rounded-xl transition-all duration-300 group hover:translate-x-1">
            <i class="fa-solid fa-key-skeleton w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
            <div class="flex flex-col leading-tight">
                <span class="text-[13px]">Reset Password</span>
                <span class="text-[10px] text-gray-400">Verifikasi Sandi ASN</span>
            </div>
        </a>

        <a href="{{ route('admin.pengaturan') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.pengaturan*') ? 'bg-cyan-500/10 text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white hover:bg-white/5 font-medium' }} rounded-xl transition-all duration-300 group hover:translate-x-1">
            <i class="fa-solid fa-gear w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
            <div class="flex flex-col leading-tight">
                <span class="text-[13px]">Pengaturan</span>
                <span class="text-[10px] text-gray-400">Konfigurasi Sistem</span>
            </div>
        </a>
    </div>

    <div class="p-4 border-t border-white/10">
        <form method="POST" action="{{ route('logout') }}" id="form-logout" class="w-full">
            @csrf
            <button type="button" onclick="bukaModalLogout()" class="flex items-center justify-center gap-2 w-full py-2.5 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white border border-red-500/20 hover:border-red-500 rounded-xl text-[12px] font-bold transition-all duration-300 group cursor-pointer">
                <i class="fa-solid fa-arrow-right-from-bracket group-hover:-translate-x-1 transition-transform"></i>
                Keluar Sistem
            </button>
        </form>
    </div>

    <script>
        (function () {
            const btn = document.getElementById('toggleTeknisDigital');
            const sub = document.getElementById('submenuTeknisDigital');
            const chev = document.getElementById('chevronTeknisDigital');
            if (!btn || !sub || !chev) return;

            btn.addEventListener('click', function () {
                sub.classList.toggle('hidden');
                chev.classList.toggle('rotate-180');
            });
        })();
    </script>
</aside>
