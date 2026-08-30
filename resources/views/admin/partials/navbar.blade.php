<header class="h-24 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-4 lg:px-8 z-10 shrink-0">
    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="hidden lg:flex w-10 h-10 items-center justify-center rounded-xl bg-[#071E3D] text-white hover:bg-[#0a2a4f] transition-colors shadow-md cursor-pointer" aria-label="Buka-tutup sidebar">
            <i class="fa-solid fa-bars-staggered text-base"></i>
        </button>
        <button onclick="openSidebar()" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-[#071E3D] text-white hover:bg-[#0a2a4f] transition-colors shadow-md cursor-pointer" aria-label="Buka menu">
            <i class="fa-solid fa-bars text-base"></i>
        </button>
        <div>
            <h1 class="text-lg sm:text-2xl font-extrabold text-[#071E3D]">@yield('header_title', 'Dashboard')</h1>
            <p class="text-[11px] sm:text-[13px] text-gray-500 font-medium">@yield('header_subtitle', 'Kelola pengajuan layanan instansi.')</p>
        </div>
    </div>
</header>