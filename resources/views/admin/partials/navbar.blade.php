<header class="h-24 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-8 z-10 shrink-0">
    <div>
        <h1 class="text-2xl font-extrabold text-[#071E3D]">@yield('header_title', 'Dashboard')</h1>
        <p class="text-[13px] text-gray-500 font-medium">@yield('header_subtitle', 'Kelola permohonan layanan instansi.')</p>
    </div>

    <div class="flex items-center gap-6">
        <div class="relative hidden lg:block">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" placeholder="Lacak ID Permohonan..." class="w-72 bg-gray-50 border border-gray-100 rounded-full pl-10 pr-4 py-2.5 text-[13px] font-medium outline-none focus:ring-2 focus:ring-cyan-100 focus:border-cyan-400 transition-all">
        </div>
        <div class="flex items-center gap-4">
            <button class="relative w-10 h-10 rounded-full bg-gray-50 text-gray-500 hover:bg-cyan-50 hover:text-cyan-600 transition-colors flex items-center justify-center">
                <i class="fa-solid fa-bell"></i>
                <span class="absolute top-2 right-2.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
            </button>
        </div>
    </div>
</header>