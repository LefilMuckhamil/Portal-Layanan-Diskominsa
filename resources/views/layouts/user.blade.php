<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Layanan Digital') - Diskominsa</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F3F4F6; }
        .dropdown-menu { display: none; }
        .group:hover .dropdown-menu { display: block; }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen flex flex-col">

    <nav class="bg-white shadow-[0_4px_20px_rgba(0,0,0,0.03)] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#071E3D] rounded-xl flex items-center justify-center text-white font-bold">
                        D
                    </div>
                    <div>
                        <h1 class="text-[15px] font-extrabold text-[#071E3D] leading-tight">Layanan Digital</h1>
                        <p class="text-[11px] text-gray-500 font-medium">Diskominsa Kab. Aceh Barat</p>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('pengajuan.website') }}" class="{{ request()->routeIs('pengajuan.website') ? 'bg-cyan-50 text-cyan-600 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#071E3D] font-semibold' }} px-4 py-2.5 rounded-lg text-[13px] transition-all">
                        <i class="fa-solid fa-globe mr-1.5"></i> Web Desa
                    </a>
                    <a href="{{ route('pengajuan.email') }}" class="{{ request()->routeIs('pengajuan.email') ? 'bg-cyan-50 text-cyan-600 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#071E3D] font-semibold' }} px-4 py-2.5 rounded-lg text-[13px] transition-all">
                        <i class="fa-solid fa-envelope mr-1.5"></i> Email
                    </a>
                    <a href="{{ route('pengajuan.tte') }}" class="{{ request()->routeIs('pengajuan.tte') ? 'bg-cyan-50 text-cyan-600 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#071E3D] font-semibold' }} px-4 py-2.5 rounded-lg text-[13px] transition-all">
                        <i class="fa-solid fa-pen-nib mr-1.5"></i> TTE
                    </a>
                    <a href="{{ route('pengajuan.cloud') }}" class="{{ request()->routeIs('pengajuan.cloud') ? 'bg-cyan-50 text-cyan-600 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#071E3D] font-semibold' }} px-4 py-2.5 rounded-lg text-[13px] transition-all">
                        <i class="fa-solid fa-cloud mr-1.5"></i> Cloud Gov
                    </a>
                    <a href="{{ route('pengajuan.bantuan') }}" class="{{ request()->routeIs('pengajuan.bantuan') ? 'bg-cyan-50 text-cyan-600 font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-[#071E3D] font-semibold' }} px-4 py-2.5 rounded-lg text-[13px] transition-all">
                        <i class="fa-solid fa-headset mr-1.5"></i> Bantuan
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    <div class="relative">
                        <button id="notification-btn" class="w-10 h-10 rounded-xl bg-gray-50 hover:bg-cyan-50 text-gray-500 hover:text-cyan-600 border border-gray-200 flex items-center justify-center transition-colors relative">
                            <i class="fa-regular fa-bell text-base"></i>
                            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white animate-pulse"></span>
                        </button>

                        <div id="notification-dropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50 origin-top-right transition-all">
                            <div class="px-4 pb-3 border-b border-gray-100 flex justify-between items-center">
                                <h4 class="font-extrabold text-[#071E3D] text-[13px]">Notifikasi</h4>
                                <span class="text-[10px] bg-cyan-50 text-cyan-600 font-bold px-2 py-0.5 rounded-md">1 Baru</span>
                            </div>

                            <div class="max-h-64 overflow-y-auto divide-y divide-gray-50">
                                <a href="#" class="block p-3 hover:bg-cyan-50/50 transition-colors">
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                            <i class="fa-solid fa-headset text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-[12px] font-bold text-[#071E3D] leading-snug">Admin membalas pesan Anda</p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">"Dokumen SK sudah kami verifikasi..."</p>
                                            <p class="text-[10px] text-gray-400 mt-1">Baru saja</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center relative group z-50">
                        <button class="flex items-center gap-3 hover:opacity-80 transition-opacity py-2">
                            <div class="text-right hidden sm:block">
                                <p class="text-[13px] font-bold text-[#071E3D]">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-gray-500 font-medium capitalize">{{ Auth::user()->role }}</p>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=071E3D&color=fff" class="w-10 h-10 rounded-full border-2 border-gray-100 object-cover">
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>

                        <div class="dropdown-menu absolute right-0 top-[60px] w-52 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-50 py-2 origin-top-right transition-all">
                            <a href="{{ route('user.riwayat') }}" class="w-full text-left px-5 py-2.5 text-[13px] font-bold text-gray-600 hover:bg-cyan-50 hover:text-cyan-600 transition-colors flex items-center gap-2 border-b border-gray-50">
                                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pengajuan
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-5 py-2.5 text-[13px] font-bold text-rose-500 hover:bg-rose-50 hover:text-rose-600 transition-colors flex items-center gap-2">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <main class="flex-grow w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 @yield('max_width', 'max-w-4xl')">
        @yield('content')
    </main>

    <footer class="py-6 text-center text-gray-400 text-[12px] font-medium border-t border-gray-200 mt-auto">
        &copy; {{ date('Y') }} Diskominsa Kabupaten Aceh Barat.
    </footer>

    <script>
        const notifBtn = document.getElementById('notification-btn');
        const notifDropdown = document.getElementById('notification-dropdown');

        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
        });

        window.addEventListener('click', () => {
            if (!notifDropdown.classList.contains('hidden')) {
                notifDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>