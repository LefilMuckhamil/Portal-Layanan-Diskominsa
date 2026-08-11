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
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
        /* Scrollbar halus untuk seluruh halaman */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- Efek Glassmorphism pada Navbar -->
    <nav class="bg-white/90 backdrop-blur-lg shadow-[0_4px_20px_rgba(0,0,0,0.02)] border-b border-gray-100 sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <!-- BAGIAN KIRI: LOGO DISKOMINSA -->
                <div class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity" onclick="window.location.href='/'">
                    <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo Diskominsa" class="h-10 w-auto object-contain drop-shadow-sm">
                    
                    <!-- Garis pemisah tipis antara logo dan teks -->
                    <div class="border-l-2 border-gray-200 pl-3 hidden sm:block">
                        <h1 class="text-[15px] font-extrabold text-[#071E3D] leading-tight tracking-tight">Layanan Digital</h1>
                        <p class="text-[11px] text-cyan-600 font-bold">Diskominsa Kab. Aceh Barat</p>
                    </div>
                </div>

                <!-- BAGIAN TENGAH: MENU NAVIGASI -->
                <div class="hidden md:flex items-center space-x-1.5">
                    <a href="{{ route('pengajuan.website') }}" class="{{ request()->routeIs('pengajuan.website') ? 'bg-cyan-50 text-cyan-600 font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-[#071E3D] font-semibold' }} px-4 py-2.5 rounded-xl text-[13px] transition-all duration-300">
                        <i class="fa-solid fa-globe mr-1.5"></i> Web Desa
                    </a>
                    <a href="{{ route('pengajuan.email') }}" class="{{ request()->routeIs('pengajuan.email') ? 'bg-cyan-50 text-cyan-600 font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-[#071E3D] font-semibold' }} px-4 py-2.5 rounded-xl text-[13px] transition-all duration-300">
                        <i class="fa-solid fa-envelope mr-1.5"></i> Email
                    </a>
                    <a href="{{ route('pengajuan.tte') }}" class="{{ request()->routeIs('pengajuan.tte') ? 'bg-cyan-50 text-cyan-600 font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-[#071E3D] font-semibold' }} px-4 py-2.5 rounded-xl text-[13px] transition-all duration-300">
                        <i class="fa-solid fa-pen-nib mr-1.5"></i> TTE
                    </a>
                    <a href="{{ route('pengajuan.cloud') }}" class="{{ request()->routeIs('pengajuan.cloud') ? 'bg-cyan-50 text-cyan-600 font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-[#071E3D] font-semibold' }} px-4 py-2.5 rounded-xl text-[13px] transition-all duration-300">
                        <i class="fa-solid fa-cloud mr-1.5"></i> Cloud Gov
                    </a>
                    <a href="{{ route('pengajuan.bantuan') }}" class="{{ request()->routeIs('pengajuan.bantuan') ? 'bg-cyan-50 text-cyan-600 font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-[#071E3D] font-semibold' }} px-4 py-2.5 rounded-xl text-[13px] transition-all duration-300">
                        <i class="fa-solid fa-headset mr-1.5"></i> Bantuan
                    </a>
                </div>

                <!-- BAGIAN KANAN: NOTIF & PROFIL USER -->
                <div class="flex items-center gap-4">
                    
                    <!-- Notifikasi -->
                    <div class="relative">
                        <button id="notification-btn" class="w-10 h-10 rounded-xl bg-gray-50 hover:bg-cyan-50 text-gray-500 hover:text-cyan-600 border border-gray-200 flex items-center justify-center transition-all duration-300 relative focus:outline-none focus:ring-2 focus:ring-cyan-100">
                            <i class="fa-regular fa-bell text-base"></i>
                            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white animate-pulse"></span>
                        </button>

                        <div id="notification-dropdown" class="invisible opacity-0 translate-y-2 absolute right-0 mt-3 w-80 bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50 origin-top-right transition-all duration-300">
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

                    <!-- Profil User (Dropdown Hover Super Smooth) -->
                    <div class="flex items-center relative group z-50">
                        <button class="flex items-center gap-3 hover:opacity-80 transition-opacity py-2 focus:outline-none">
                            <div class="text-right hidden sm:block">
                                <p class="text-[13px] font-bold text-[#071E3D]">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-gray-500 font-medium">
                                    <!-- Menampilkan NIP jika ada, jika tidak ada tampilkan status/role -->
                                    @if(Auth::user()->nip)
                                        NIP. {{ Auth::user()->nip }}
                                    @else
                                        {{ Auth::user()->role === 'instansi' ? 'Akun Instansi' : 'ASN / User' }}
                                    @endif
                                </p>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=071E3D&color=fff" class="w-10 h-10 rounded-xl shadow-sm border border-gray-200 object-cover transition-transform duration-300 group-hover:scale-105">
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 group-hover:rotate-180 transition-transform duration-300"></i>
                        </button>

                        <!-- Menu Dropdown dengan efek slide & fade in -->
                        <div class="invisible opacity-0 translate-y-3 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 absolute right-0 top-[60px] w-56 bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 py-2 origin-top-right transition-all duration-300 ease-out">
                            <a href="{{ route('user.riwayat') }}" class="w-full text-left px-5 py-3 text-[13px] font-bold text-gray-600 hover:bg-cyan-50 hover:text-cyan-600 transition-colors flex items-center gap-3 border-b border-gray-50">
                                <i class="fa-solid fa-clock-rotate-left text-lg"></i> Riwayat Pengajuan
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-5 py-3 text-[13px] font-bold text-rose-500 hover:bg-rose-50 hover:text-rose-600 transition-colors flex items-center gap-3 rounded-b-2xl">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i> Keluar
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

    <footer class="py-6 text-center text-gray-400 text-[12px] font-medium border-t border-gray-200 mt-auto bg-white/50">
        &copy; {{ date('Y') }} Diskominsa Kabupaten Aceh Barat.
    </footer>

    <script>
        // Animasi klik untuk tombol notifikasi agar lebih smooth
        const notifBtn = document.getElementById('notification-btn');
        const notifDropdown = document.getElementById('notification-dropdown');

        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('invisible');
            notifDropdown.classList.toggle('opacity-0');
            notifDropdown.classList.toggle('translate-y-2');
        });

        window.addEventListener('click', () => {
            if (!notifDropdown.classList.contains('invisible')) {
                notifDropdown.classList.add('invisible', 'opacity-0', 'translate-y-2');
            }
        });
    </script>

           <!-- MODAL BANTUAN/SUKSES UTAMA DI LAYOUT USER -->
            @if(session('sukses'))
            <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity duration-300">
                <div class="bg-white rounded-[2rem] p-8 max-w-md w-full shadow-2xl text-center relative overflow-hidden">
                    
                    <!-- Logo Centang / Berhasil -->
                    <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-5 text-3xl shadow-inner">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <h3 class="text-2xl font-extrabold text-[#071E3D] mb-2">Berhasil!</h3>
                    <p class="text-gray-500 text-sm mb-5 font-medium">{{ session('sukses') }}</p>

                    <!-- TAMBAHKAN BLOK TIKET INI DI DALAM MODAL BAWAAN LAYOUT -->
                    @if(session('nomor_tiket'))
                    <div class="bg-cyan-50/40 border border-cyan-100/80 rounded-2xl p-4 mb-6 relative overflow-hidden text-center">
                        <div class="absolute -right-3 -top-3 text-cyan-500/10 text-6xl"><i class="fa-solid fa-ticket"></i></div>
                        <p class="text-[11px] font-extrabold text-cyan-800 uppercase tracking-wider mb-0.5 relative z-10">Nomor Tiket Anda</p>
                        <p class="text-2xl font-black text-[#071E3D] tracking-tight relative z-10">{{ session('nomor_tiket') }}</p>
                    </div>
                    @endif

                    <!-- Tombol Bawaan UI Layout -->
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="document.getElementById('successModal').remove()" class="w-full py-3.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition-colors">
                            Tutup
                        </button>
                        <a href="{{ route('user.riwayat') }}" class="w-full py-3.5 px-4 bg-[#071E3D] hover:bg-[#1F4287] text-white font-bold rounded-xl text-sm transition-colors flex items-center justify-center gap-2 shadow-lg shadow-blue-900/20">
                            Cek Riwayat <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                </div>
            </div>
            @endif

        <style>
            .animate-bounce-short { animation: bounceShort 0.4s ease-out forwards; }
            @keyframes bounceShort {
                0% { transform: scale(0.8); opacity: 0; }
                50% { transform: scale(1.05); opacity: 1; }
                100% { transform: scale(1); opacity: 1; }
            }
        </style>

</body>
</html>