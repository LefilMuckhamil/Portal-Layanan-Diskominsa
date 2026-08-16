<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Layanan Digital') - Diskominsa</title>
    <link rel="icon" type="image/png" href="{{ asset('image/icon.png') }}">
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

    <nav class="bg-white shadow-md border-b-2 border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <div class="flex items-center gap-3.5 cursor-pointer hover:opacity-90 transition-opacity" onclick="window.location.href='/'">
                    <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo Diskominsa" class="h-10 sm:h-11 w-auto object-contain drop-shadow-sm">
                    
                    <div class="border-l-2 border-slate-300 pl-3.5 hidden sm:block">
                        <h1 class="text-[16px] font-black text-[#071E3D] leading-tight tracking-tight">Layanan Digital</h1>
                        <p class="text-[12px] text-cyan-700 font-bold">Diskominsa Kab. Aceh Barat</p>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-2">
                    <a href="{{ route('pengajuan.website') }}" class="{{ request()->routeIs('pengajuan.website') ? 'bg-[#071E3D] text-white font-extrabold shadow-md' : 'text-slate-700 hover:bg-slate-100 hover:text-[#071E3D] font-bold' }} px-4 py-2.5 rounded-xl text-[14px] transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-globe text-base"></i> Website
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

                <div class="flex items-center gap-2 sm:gap-4">
                    
                    <button id="mobile-menu-btn" onclick="toggleMobileMenu(event)" class="md:hidden w-10 h-10 rounded-xl bg-slate-100 text-slate-800 border border-slate-300 flex items-center justify-center text-lg active:scale-95 transition-transform">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div class="relative z-50">
                        <button id="user-menu-btn" onclick="toggleUserDropdown(event)" class="flex items-center gap-2.5 hover:opacity-90 transition-opacity py-2 focus:outline-none">
                            <div class="text-right hidden sm:block">
                                <p class="text-[14px] font-extrabold text-[#071E3D] leading-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[12px] text-slate-600 font-bold">
                                    @if(Auth::user()->nip)
                                        NIP. {{ Auth::user()->nip }}
                                    @else
                                        ASN / User
                                    @endif
                                </p>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=071E3D&color=fff" class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl shadow-md border-2 border-slate-300 object-cover">
                            <i class="fa-solid fa-chevron-down text-xs text-slate-600"></i>
                        </button>

                        <div id="user-dropdown" class="hidden absolute right-0 top-[55px] w-60 bg-white rounded-2xl shadow-2xl border-2 border-slate-200 py-2 origin-top-right transition-all duration-200">
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

        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-slate-200 px-4 pt-3 pb-5 space-y-2 shadow-lg">
            <p class="text-[11px] font-black uppercase text-slate-400 px-3 pt-1">Pilih Layanan Formulir</p>
            <a href="{{ route('pengajuan.website') }}" class="{{ request()->routeIs('pengajuan.website') ? 'bg-[#071E3D] text-white font-extrabold' : 'text-slate-700 hover:bg-slate-100 font-bold' }} block px-4 py-3 rounded-xl text-[14px] transition-all flex items-center gap-3">
                <i class="fa-solid fa-globe text-base w-5"></i> Web Desa
            </a>
            <a href="{{ route('pengajuan.email') }}" class="{{ request()->routeIs('pengajuan.email') ? 'bg-[#071E3D] text-white font-extrabold' : 'text-slate-700 hover:bg-slate-100 font-bold' }} block px-4 py-3 rounded-xl text-[14px] transition-all flex items-center gap-3">
                <i class="fa-solid fa-envelope text-base w-5"></i> Email Resmi
            </a>
            <a href="{{ route('pengajuan.tte') }}" class="{{ request()->routeIs('pengajuan.tte') ? 'bg-[#071E3D] text-white font-extrabold' : 'text-slate-700 hover:bg-slate-100 font-bold' }} block px-4 py-3 rounded-xl text-[14px] transition-all flex items-center gap-3">
                <i class="fa-solid fa-pen-nib text-base w-5"></i> TTE BSrE
            </a>
            <a href="{{ route('pengajuan.cloud') }}" class="{{ request()->routeIs('pengajuan.cloud') ? 'bg-[#071E3D] text-white font-extrabold' : 'text-slate-700 hover:bg-slate-100 font-bold' }} block px-4 py-3 rounded-xl text-[14px] transition-all flex items-center gap-3">
                <i class="fa-solid fa-cloud text-base w-5"></i> Cloud Gov
            </a>
            <a href="{{ route('pengajuan.bantuan') }}" class="{{ request()->routeIs('pengajuan.bantuan') ? 'bg-[#071E3D] text-white font-extrabold' : 'text-slate-700 hover:bg-slate-100 font-bold' }} block px-4 py-3 rounded-xl text-[14px] transition-all flex items-center gap-3">
                <i class="fa-solid fa-headset text-base w-5"></i> Pusat Bantuan
            </a>
        </div>
    </nav>

    <main class="flex-grow w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 @yield('max_width', 'max-w-6xl')">
        @if(session('error'))
        <div id="flash-error" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 flex items-center justify-between gap-3" role="alert">
            <p class="font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </p>
            <button type="button" onclick="document.getElementById('flash-error').remove()" class="text-red-500 hover:text-red-800 transition-colors cursor-pointer" aria-label="Tutup notifikasi">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        @endif

        @yield('content')
    </main>

    <footer class="py-6 text-center text-slate-600 text-[13px] font-bold border-t-2 border-slate-200 mt-auto bg-white">
        &copy; {{ date('Y') }} Diskominsa Kabupaten Aceh Barat. All Rights Reserved.
    </footer>

    @if(session('sukses'))
    <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/75 backdrop-blur-sm p-4 transition-opacity duration-300">
        <div class="bg-white rounded-[2rem] p-8 max-w-md w-full shadow-2xl text-center relative overflow-hidden border-2 border-slate-200">
            <button type="button" onclick="document.getElementById('successModal').remove()" class="absolute top-4 right-4 w-9 h-9 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-full flex items-center justify-center transition-colors cursor-pointer" aria-label="Tutup">
                <i class="fa-solid fa-xmark"></i>
            </button>
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

    <script>
        function toggleMobileMenu(e) {
            e.stopPropagation();
            const mobileMenu = document.getElementById('mobile-menu');
            const userDropdown = document.getElementById('user-dropdown');
            userDropdown.classList.add('hidden');
            mobileMenu.classList.toggle('hidden');
        }

        function toggleUserDropdown(e) {
            e.stopPropagation();
            const userDropdown = document.getElementById('user-dropdown');
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.add('hidden');
            userDropdown.classList.toggle('hidden');
        }

        window.addEventListener('click', function () {
            document.getElementById('mobile-menu').classList.add('hidden');
            document.getElementById('user-dropdown').classList.add('hidden');
        });
    </script>

</body>
</html>