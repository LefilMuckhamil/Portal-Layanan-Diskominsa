<nav class="sticky top-0 z-50 bg-[#040914]/90 backdrop-blur-md shadow-lg border-b border-white/10 transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 py-3.5 flex justify-between items-center text-white relative">
        
        <!-- Logo & Instansi -->
        <a href="#beranda" class="flex items-center gap-3 group">
            <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo Diskominsa" class="h-9 sm:h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
            <div class="hidden sm:flex flex-col justify-center border-l border-white/10 pl-3">
                <h1 class="text-[11px] font-bold tracking-tight leading-tight text-slate-200 group-hover:text-cyan-300 transition-colors">
                    Dinas Komunikasi, Informatika, <br>dan Persandian Aceh Barat
                </h1>
            </div>
        </a>
        
        <!-- Menu Navigasi Desktop -->
        <div class="hidden lg:flex items-center space-x-7 text-xs font-semibold uppercase tracking-wider text-slate-300">
            <a href="#beranda" class="hover:text-cyan-400 transition-colors">Beranda</a>
            <a href="#tentang" class="hover:text-cyan-400 transition-colors">Tentang</a>
            <a href="#layanan" class="hover:text-cyan-400 transition-colors">Layanan</a>
            <a href="#alur" class="hover:text-cyan-400 transition-colors">Alur</a>
            <a href="#tracking" class="hover:text-cyan-400 transition-colors">E-Tracking</a>
        </div>

        <!-- Tombol Aksi Desktop -->
        <div class="hidden lg:flex items-center space-x-3">
            @guest
                <a href="{{ route('login') }}" class="border border-white/20 hover:bg-white/10 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">Masuk</a>
                <a href="{{ route('register') }}" class="bg-cyan-400 text-[#040914] hover:bg-cyan-300 px-4 py-2 rounded-xl text-xs font-extrabold shadow-md shadow-cyan-400/20 transition-all active:scale-95">Daftar</a>
            @endguest

            @auth
                <div class="relative group">
                    <button class="flex items-center gap-2.5 focus:outline-none hover:opacity-80 transition-opacity cursor-pointer py-1">
                        <div class="text-right">
                            <p class="text-xs font-bold text-white leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-cyan-400 font-mono font-medium capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=278EA5&color=fff" class="w-8 h-8 rounded-xl border border-cyan-400/30 object-cover shadow-md">
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>

                    <!-- Dropdown Desktop -->
                    <div class="absolute right-0 top-10 w-52 bg-[#0B1528] rounded-2xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 border border-white/10 backdrop-blur-xl">
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ url('/admin/dashboard') }}" class="block px-4 py-2.5 text-xs font-bold text-slate-200 hover:bg-white/10 hover:text-cyan-300 transition-colors">
                                <i class="fa-solid fa-gauge-high mr-2 text-cyan-400"></i> Dashboard Admin
                            </a>
                        @else
                            <a href="{{ route('pengajuan.website') }}" class="block px-4 py-2.5 text-xs font-bold text-slate-200 hover:bg-white/10 hover:text-cyan-300 transition-colors">
                                <i class="fa-solid fa-file-pen mr-2 text-cyan-400"></i> Form Pengajuan
                            </a>
                        @endif
                        
                        <hr class="my-1 border-white/10">
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-500/10 transition-colors">
                                <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>

        <!-- Tombol Hamburger Mobile -->
        <button id="mobile-menu-btn" type="button" class="lg:hidden text-slate-200 hover:text-white focus:outline-none p-1.5 rounded-lg bg-white/5 border border-white/10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path id="icon-bars" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                <path id="icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Dropdown Menu Mobile -->
    <div id="mobile-menu" class="hidden lg:hidden bg-[#040914]/95 border-b border-white/10 backdrop-blur-xl absolute w-full left-0 shadow-2xl transition-all duration-300">
        <div class="px-5 py-5 flex flex-col space-y-3 text-slate-200 text-xs font-semibold">
            <a href="#beranda" class="hover:text-cyan-400 transition-colors py-1.5 mobile-link">Beranda</a>
            <a href="#tentang" class="hover:text-cyan-400 transition-colors py-1.5 mobile-link">Tentang</a>
            <a href="#layanan" class="hover:text-cyan-400 transition-colors py-1.5 mobile-link">Layanan</a>
            <a href="#alur" class="hover:text-cyan-400 transition-colors py-1.5 mobile-link">Alur</a>
            <a href="#tracking" class="hover:text-cyan-400 transition-colors py-1.5 mobile-link">E-Tracking</a>
            
            <hr class="border-white/10 my-1">
            
            @guest
                <div class="flex gap-2 pt-2">
                    <a href="{{ route('login') }}" class="flex-1 text-center border border-white/20 text-white py-2.5 rounded-xl font-bold transition-all">Masuk</a>
                    <a href="{{ route('register') }}" class="flex-1 text-center bg-cyan-400 text-[#040914] py-2.5 rounded-xl font-extrabold shadow-md transition-all">Daftar</a>
                </div>
            @endguest

            @auth
                <div class="flex items-center gap-3 p-3 bg-white/5 rounded-2xl border border-white/10 mb-2">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=278EA5&color=fff" class="w-10 h-10 rounded-xl border border-cyan-400/30">
                    <div>
                        <p class="text-xs font-bold text-white">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-cyan-400 font-mono capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </div>

                @if(Auth::user()->role === 'admin')
                    <a href="{{ url('/admin/dashboard') }}" class="hover:text-cyan-400 transition-colors py-1.5 mobile-link font-bold">
                        <i class="fa-solid fa-gauge-high mr-2 text-cyan-400"></i> Dashboard Admin
                    </a>
                @else
                    <a href="{{ route('pengajuan.website') }}" class="hover:text-cyan-400 transition-colors py-1.5 mobile-link font-bold">
                        <i class="fa-solid fa-file-pen mr-2 text-cyan-400"></i> Form Pengajuan Layanan
                    </a>
                @endif
                
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full text-center bg-rose-500/20 border border-rose-500/30 text-rose-300 py-2.5 rounded-xl font-bold transition-all">
                        Keluar Akun
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const iconBars = document.getElementById('icon-bars');
        const iconClose = document.getElementById('icon-close');
        const mobileLinks = document.querySelectorAll('.mobile-link');

        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
                iconBars.classList.toggle('hidden');
                iconClose.classList.toggle('hidden');
            });

            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    menu.classList.add('hidden');
                    iconBars.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                });
            });
        }
    });
</script>