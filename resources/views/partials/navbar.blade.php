<nav class="sticky top-0 z-50 bg-[#071E3D] shadow-xl border-b border-white/10 transition-all duration-300">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center text-white relative">
        
        <!-- Logo -->
    <a href="#beranda" class="flex items-center gap-3 md:gap-4 group">
    <!-- Lebar di-set auto agar logo tidak gepeng, tinggi disamakan dengan teks -->
    <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo Diskominsa" class="h-12 md:h-12 w-auto object-contain transition-all duration-300 group-hover:scale-105 group-hover:drop-shadow-[0_0_10px_rgba(255,255,255,0.5)]">
    
    <!-- Teks Instansi sejajar di sebelah kanan -->
    <div class="flex flex-col justify-center">
        <h1 class=" text-sm md:text-[10px] tracking-wider leading-none text-white group-hover:text-cyan-100 transition-colors mb-1">
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

        <!-- Tombol Aksi Desktop (Hilang di HP) -->
        <div class="space-x-3 hidden lg:block">
            <a href="{{ route('login') }}" class="border border-white/50 hover:border-white text-white px-5 py-2 rounded-xl text-sm font-medium transition">Login</a>
            <a href="{{ route('register') }}" class="bg-cyan-400 text-[#071E3D] hover:bg-cyan-300 px-5 py-2 rounded-xl text-sm font-bold shadow-lg transition">Daftar</a>
        </div>

        <!-- TOMBOL HAMBURGER (MUNCUL HANYA DI HP) -->
        <button id="mobile-menu-btn" class="lg:hidden text-white focus:outline-none">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path id="icon-bars" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                <path id="icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- MENU DROPDOWN MOBILE (Tersembunyi secara default) -->
    <div id="mobile-menu" class="hidden lg:hidden bg-[#071E3D] border-t border-white/10 absolute w-full left-0 shadow-2xl">
        <div class="px-6 py-4 flex flex-col space-y-4 text-white">
            <a href="#beranda" class="hover:text-cyan-400 transition mobile-link">Beranda</a>
            <a href="#tentang" class="hover:text-cyan-400 transition mobile-link">Tentang</a>
            <a href="#layanan" class="hover:text-cyan-400 transition mobile-link">Layanan</a>
            <a href="#alur" class="hover:text-cyan-400 transition mobile-link">Alur</a>
            <a href="#tracking" class="hover:text-cyan-400 transition mobile-link">E-Tracking</a>
            
            <hr class="border-white/10 my-2">
            
            <a href="{{ route('login') }}" class="text-center border border-white/50 hover:border-white text-white px-5 py-2 rounded-full text-sm font-medium transition w-full">Login</a>
            <a href="{{ route('register') }}" class="text-center bg-cyan-400 text-[#071E3D] hover:bg-cyan-300 px-5 py-2 rounded-full text-sm font-bold shadow-lg transition w-full">Daftar</a>
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