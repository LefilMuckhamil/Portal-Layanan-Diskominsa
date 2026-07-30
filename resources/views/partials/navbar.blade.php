<nav class="sticky top-0 z-50 bg-[#071E3D] shadow-xl border-b border-white/10 transition-all duration-300">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center text-white relative">
        
        <!-- Logo -->
        <a href="#beranda" class="flex items-center gap-3 md:gap-4 group">
            <!-- Lebar di-set auto agar logo tidak gepeng, tinggi disamakan dengan teks -->
            <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo Diskominsa" class="h-12 md:h-12 w-auto object-contain transition-all duration-300 group-hover:scale-105 group-hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.5)]">
            
            <!-- Teks Instansi sejajar di sebelah kanan -->
            <div class="flex flex-col justify-center">
                <h1 class="text-sm md:text-[10px] tracking-wider leading-none text-white group-hover:text-cyan-100 transition-colors mb-1">
                    Dinas Komunikasi, Informatika, dan Persandian
                </h1>
            </div>
        </a>
        
        <!-- Menu Navigasi Desktop (Hilang di HP) -->
        <div class="hidden lg:flex space-x-8 text-sm font-medium">
            <a href="#beranda" class="hover:text-cyan-400 transition">Beranda</a>
            <a href="#tentang" class="hover:text-cyan-400 transition">Tentang</a>
            <a href="#layanan" class="hover:text-cyan-400 transition">Layanan</a>
            <a href="#alur" class="hover:text-cyan-400 transition">Alur</a>
            <a href="#tracking" class="hover:text-cyan-400 transition">E-Tracking</a>
        </div>


        <!-- TOMBOL AKSI DESKTOP -->
        <div class="hidden lg:flex items-center space-x-4">
            
            <!-- Jika User BELUM Login -->
            @guest
                <a href="{{ route('login') }}" class="border border-white/50 hover:border-white text-white px-5 py-2 rounded-xl text-sm font-medium transition">Login</a>
                <a href="{{ route('register') }}" class="bg-cyan-400 text-[#071E3D] hover:bg-cyan-300 px-5 py-2 rounded-xl text-sm font-bold shadow-lg transition">Daftar</a>
            @endguest

            <!-- Jika User SUDAH Login -->
            @auth
                <div class="relative group">
                    <button class="flex items-center gap-3 focus:outline-none hover:opacity-80 transition-opacity cursor-pointer py-1">
                        <div class="text-right">
                            <p class="text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-cyan-400 font-medium capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=278EA5&color=fff" class="w-10 h-10 rounded-full border-2 border-white/20 object-cover shadow-md">
                        <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Dropdown Desktop -->
                    <div class="absolute right-0 top-12 w-48 bg-white rounded-xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 border border-gray-100">
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ url('/admin/dashboard') }}" class="block px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-cyan-50 hover:text-[#071E3D] transition-colors">Dashboard Admin</a>
                        @else
                            <a href="{{ route('pengajuan.website') }}" class="block px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-cyan-50 hover:text-[#071E3D] transition-colors">Form Pengajuan</a>
                        @endif
                        
                        <hr class="my-1 border-gray-100">
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-5 py-2.5 text-sm font-bold text-rose-500 hover:bg-rose-50 hover:text-rose-600 transition-colors">Keluar</button>
                        </form>
                    </div>
                </div>
            @endauth

        </div>

        <!-- TOMBOL HAMBURGER (MUNCUL HANYA DI HP) -->
        <button id="mobile-menu-btn" class="lg:hidden text-white focus:outline-none">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path id="icon-bars" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                <path id="icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- MENU DROPDOWN MOBILE -->
    <div id="mobile-menu" class="hidden lg:hidden bg-[#071E3D] border-t border-white/10 absolute w-full left-0 shadow-2xl">
        <div class="px-6 py-4 flex flex-col space-y-4 text-white">
            <a href="#beranda" class="hover:text-cyan-400 transition mobile-link">Beranda</a>
            <a href="#tentang" class="hover:text-cyan-400 transition mobile-link">Tentang</a>
            <a href="#layanan" class="hover:text-cyan-400 transition mobile-link">Layanan</a>
            <a href="#alur" class="hover:text-cyan-400 transition mobile-link">Alur</a>
            <a href="#tracking" class="hover:text-cyan-400 transition mobile-link">E-Tracking</a>
            
            <hr class="border-white/10 my-2">
            
            <!-- Bagian Aksi di Mobile -->
            @guest
                <a href="{{ route('login') }}" class="text-center border border-white/50 hover:border-white text-white px-5 py-2.5 rounded-xl text-sm font-medium transition w-full">Login</a>
                <a href="{{ route('register') }}" class="text-center bg-cyan-400 text-[#071E3D] hover:bg-cyan-300 px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg transition w-full">Daftar</a>
            @endguest

            @auth
                <!-- Profil Info di Mobile -->
                <div class="flex items-center gap-3 mb-2 p-3 bg-white/5 rounded-xl border border-white/10">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=278EA5&color=fff" class="w-12 h-12 rounded-full border-2 border-white/20">
                    <div>
                        <p class="text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-cyan-400 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </div>

                <!-- Menu Khusus User/Admin di Mobile -->
                @if(Auth::user()->role === 'admin')
                    <a href="{{ url('/admin/dashboard') }}" class="hover:text-cyan-400 transition mobile-link font-semibold">Dashboard Admin</a>
                @else
                    <a href="{{ route('pengajuan.website') }}" class="hover:text-cyan-400 transition mobile-link font-semibold">Form Pengajuan Layanan</a>
                @endif
                
                <!-- Tombol Keluar Mobile -->
                <form method="POST" action="{{ route('logout') }}" class="w-full mt-4">
                    @csrf
                    <button type="submit" class="text-center bg-rose-500 hover:bg-rose-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg transition w-full">Keluar Akun</button>
                </form>
            @endauth

        </div>
    </div>
</nav>

<!-- SCRIPT UNTUK TOGGLE MENU MOBILE -->
<script>
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    const iconBars = document.getElementById('icon-bars');
    const iconClose = document.getElementById('icon-close');
    const mobileLinks = document.querySelectorAll('.mobile-link');

    // Fungsi klik tombol hamburger
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
        iconBars.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
    });

    // Otomatis tutup menu jika link di klik (agar tidak mengganggu saat scroll)
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            menu.classList.add('hidden');
            iconBars.classList.remove('hidden');
            iconClose.classList.add('hidden');
        });
    });
</script>