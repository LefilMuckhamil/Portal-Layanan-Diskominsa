<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Layanan Digital') - Diskominsa</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Google & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F3F4F6; }
        /* Animasi Dropdown Profil */
        .dropdown-menu { display: none; }
        .group:hover .dropdown-menu { display: block; }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- NAVBAR USER -->
    <nav class="bg-white shadow-[0_4px_20px_rgba(0,0,0,0.03)] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <!-- Kiri: Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#071E3D] rounded-xl flex items-center justify-center text-white font-bold">
                        D
                    </div>
                    <div>
                        <h1 class="text-[15px] font-extrabold text-[#071E3D] leading-tight">Layanan Digital</h1>
                        <p class="text-[11px] text-gray-500 font-medium">Diskominsa Kab. Aceh Barat</p>
                    </div>
                </div>

                <!-- Tengah: Menu Navigasi Form -->
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

                <!-- Kanan: Profil & Logout -->
                <div class="flex items-center relative group z-50">
                    <button class="flex items-center gap-3 hover:opacity-80 transition-opacity py-2">
                        <div class="text-right hidden sm:block">
                            <!-- Nama dan Role otomatis dari database -->
                            <p class="text-[13px] font-bold text-[#071E3D]">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-gray-500 font-medium capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <!-- Avatar otomatis mengikuti huruf depan nama User -->
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=071E3D&color=fff" class="w-10 h-10 rounded-full border-2 border-gray-100 object-cover">
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="dropdown-menu absolute right-0 top-[60px] w-52 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-50 py-2 origin-top-right transition-all">
                        
                        <!-- Tambahan: Menu Riwayat Pengajuan -->
                        <a href="{{ route('user.riwayat') }}" class="w-full text-left px-5 py-2.5 text-[13px] font-bold text-gray-600 hover:bg-cyan-50 hover:text-cyan-600 transition-colors flex items-center gap-2 border-b border-gray-50">
                            <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pengajuan
                        </a>

                        <!-- Form Logout Laravel -->
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
    </nav>

    <!-- KONTEN FORM DINAMIS (LEBAR SUDAH DIUBAH MENJADI DINAMIS) -->
    <main class="flex-grow w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 @yield('max_width', 'max-w-4xl')">
        @yield('content')
    </main>

    <!-- FOOTER KECIL -->
    <footer class="py-6 text-center text-gray-400 text-[12px] font-medium border-t border-gray-200 mt-auto">
        &copy; {{ date('Y') }} Diskominsa Kabupaten Aceh Barat.
    </footer>

</body>
</html>