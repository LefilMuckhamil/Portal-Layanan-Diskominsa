<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Layanan Digital') - Diskominsa</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F1F5F9; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #e2e8f0; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
    </style>
</head>
<body class="text-slate-900 antialiased min-h-screen flex flex-col">

    <!-- NAVBAR HIGH-CONTRAST -->
    <nav class="bg-white shadow-md border-b-2 border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <!-- LOGO DISKOMINSA -->
                <div class="flex items-center gap-3.5 cursor-pointer hover:opacity-90 transition-opacity" onclick="window.location.href='/'">
                    <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo Diskominsa" class="h-11 w-auto object-contain drop-shadow-sm">
                    
                    <div class="border-l-2 border-slate-300 pl-3.5 hidden sm:block">
                        <h1 class="text-[16px] font-black text-[#071E3D] leading-tight tracking-tight">Layanan Digital</h1>
                        <p class="text-[12px] text-cyan-700 font-bold">Diskominsa Kab. Aceh Barat</p>
                    </div>
                </div>

                <!-- MENU NAVIGASI (Warna Dipertegas untuk Aksesibilitas) -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="{{ route('pengajuan.website') }}" class="{{ request()->routeIs('pengajuan.website') ? 'bg-[#071E3D] text-white font-extrabold shadow-md' : 'text-slate-700 hover:bg-slate-100 hover:text-[#071E3D] font-bold' }} px-4 py-2.5 rounded-xl text-[14px] transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-globe text-base"></i> Web Desa
                    </a>
                    <a href="{{ route('pengajuan.email') }}" class="{{ request()->routeIs('pengajuan.email') ? 'bg-[#071E3D] text-white font-extrabold shadow-md' : 'text-slate-700 hover:bg-slate-100 hover:text-[#071E3D] font-bold' }} px-4 py-2.5 rounded-xl text-[14px] transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-base"></i> Email
                    </a>
                    <a href="{{ route('pengajuan.tte') }}" class="{{ request()->routeIs('pengajuan.tte') ? 'bg-[#071E3D] text-white font-extrabold shadow-md' : 'text-slate-700 hover:bg-slate-100 hover:text-[#071E3D] font-bold' }} px-4 py-2.5 rounded-xl text-[14px] transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-pen-nib text-base"></i> TTE
                    </a>
                    <a href="{{ route('pengajuan.cloud') }}" class="{{ request()->routeIs('pengajuan.cloud') ? 'bg-[#071E3D] text-white font-extrabold shadow-md' : 'text-slate-700 hover:bg-slate-100 hover:text-[#071E3D] font-bold' }} px-4 py-2.5 rounded-xl text-[14px] transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-cloud text-base"></i> Cloud Gov
                    </a>
                    <a href="{{ route('pengajuan.bantuan') }}" class="{{ request()->routeIs('pengajuan.bantuan') ? 'bg-[#071E3D] text-white font-extrabold shadow-md' : 'text-slate-700 hover:bg-slate-100 hover:text-[#071E3D] font-bold' }} px-4 py-2.5 rounded-xl text-[14px] transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-headset text-base"></i> Bantuan
                    </a>
                </div>

                <!-- NOTIFIKASI & PROFIL USER -->
                <div class="flex items-center gap-4">
                    
                    <!-- Tombol Notifikasi -->
                    <div class="relative">
                        <button id="notification-btn" class="w-11 h-11 rounded-xl bg-slate-100 hover:bg-cyan-100 text-slate-800 hover:text-cyan-800 border-2 border-slate-300 flex items-center justify-center transition-all duration-200 relative focus:outline-none">
                            <i class="fa-regular fa-bell text-lg"></i>
                            <span class="absolute top-2.5 right-2.5 w-3 h-3 bg-rose-600 rounded-full ring-2 ring-white animate-pulse"></span>
                        </button>

                        <div id="notification-dropdown" class="invisible opacity-0 translate-y-2 absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border-2 border-slate-200 py-3 z-50 origin-top-right transition-all duration-200">
                            <div class="px-4 pb-3 border-b-2 border-slate-100 flex justify-between items-center">
                                <h4 class="font-black text-[#071E3D] text-sm">Notifikasi</h4>
                                <span class="text-[11px] bg-cyan-100 text-cyan-900 font-extrabold px-2.5 py-0.5 rounded-md">1 Baru</span>
                            </div>

                            <div class="max-h-64 overflow-y-auto divide-y divide-slate-100">
                                <a href="#" class="block p-3.5 hover:bg-cyan-50/80 transition-colors">
                                    <div class="flex gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-900 flex items-center justify-center shrink-0 mt-0.5">
                                            <i class="fa-solid fa-headset text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-[13px] font-bold text-[#071E3D] leading-snug">Admin membalas pesan Anda</p>
                                            <p class="text-[12px] text-slate-600 font-medium mt-0.5">"Dokumen SK sudah kami verifikasi..."</p>
                                            <p class="text-[10px] text-slate-500 font-bold mt-1">Baru saja</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profil User -->
                    <div class="flex items-center relative group z-50">
                        <button class="flex items-center gap-3 hover:opacity-90 transition-opacity py-2 focus:outline-none">
                            <div class="text-right hidden sm:block">
                                <p class="text-[14px] font-extrabold text-[#071E3D] leading-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[12px] text-slate-600 font-bold">
                                    @if(Auth::user()->nip)
                                        NIP. {{ Auth::user()->nip }}
                                    @else
                                        {{ Auth::user()->role === 'instansi' ? 'Akun Instansi' : 'ASN / User' }}
                                    @endif
                                </p>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=071E3D&color=fff" class="w-11 h-11 rounded-xl shadow-md border-2 border-slate-300 object-cover">
                            <i class="fa-solid fa-chevron-down text-xs text-slate-600 group-hover:rotate-180 transition-transform duration-200"></i>
                        </button>

                        <div class="invisible opacity-0 translate-y-3 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 absolute right-0 top-[60px] w-60 bg-white rounded-2xl shadow-2xl border-2 border-slate-200 py-2 origin-top-right transition-all duration-200 ease-out">
                            <a href="{{ route('user.riwayat') }}" class="w-full text-left px-5 py-3 text-[14px] font-extrabold text-slate-700 hover:bg-cyan-50 hover:text-cyan-800 transition-colors flex items-center gap-3 border-b border-slate-100">
                                <i class="fa-solid fa-clock-rotate-left text-lg text-cyan-700"></i> Riwayat Pengajuan
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-5 py-3 text-[14px] font-extrabold text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors flex items-center gap-3 rounded-b-2xl">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <!-- CONTAINER UTAMA (Default diperluas ke max-w-6xl agar form tidak tertekan/sempit) -->
    <main class="flex-grow w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 @yield('max_width', 'max-w-6xl')">
        @yield('content')
    </main>

    <footer class="py-6 text-center text-slate-600 text-[13px] font-bold border-t-2 border-slate-200 mt-auto bg-white">
        &copy; {{ date('Y') }} Diskominsa Kabupaten Aceh Barat. All Rights Reserved.
    </footer>

    <script>
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

    <!-- MODAL SUKSES -->
    @if(session('sukses'))
    <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/75 backdrop-blur-sm p-4 transition-opacity duration-300">
        <div class="bg-white rounded-[2rem] p-8 max-w-md w-full shadow-2xl text-center relative overflow-hidden border-2 border-slate-200">
            
            <div class="w-20 h-20 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto mb-5 text-3xl shadow-inner">
                <i class="fa-solid fa-check"></i>
            </div>

            <h3 class="text-2xl font-black text-[#071E3D] mb-2">Berhasil!</h3>
            <p class="text-slate-600 text-sm mb-5 font-bold leading-relaxed">{{ session('sukses') }}</p>

            @if(session('nomor_tiket'))
            <div class="bg-cyan-50 border-2 border-cyan-200 rounded-2xl p-4 mb-6 relative overflow-hidden text-center">
                <p class="text-[12px] font-black text-cyan-900 uppercase tracking-wider mb-0.5">Nomor Tiket Anda</p>
                <p class="text-2xl font-black text-[#071E3D] tracking-tight">{{ session('nomor_tiket') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-3">
                <button onclick="document.getElementById('successModal').remove()" class="w-full py-3.5 px-4 bg-slate-200 hover:bg-slate-300 text-slate-800 font-extrabold rounded-xl text-sm transition-colors">
                    Tutup
                </button>
                <a href="{{ route('user.riwayat') }}" class="w-full py-3.5 px-4 bg-[#071E3D] hover:bg-[#1F4287] text-white font-extrabold rounded-xl text-sm transition-colors flex items-center justify-center gap-2 shadow-lg">
                    Cek Riwayat <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

        </div>
    </div>
    @endif

</body>
</html>