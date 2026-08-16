<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Portal Layanan Diskominsa') }} - Lupa Kata Sandi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        ::-webkit-scrollbar { display: none; }
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #f9fafb inset !important;
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-12px) scale(0.98);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-card-down {
            animation: fadeInDown 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="min-h-screen md:h-screen w-screen overflow-y-auto md:overflow-hidden flex items-center justify-center p-4 relative selection:bg-cyan-300 selection:text-[#071E3D]">

    <!-- BACKGROUND FOTO GEDUNG DISKOMINSA -->
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('image/diskominsa.jpeg') }}" alt="Gedung Diskominsa" class="w-full h-full object-cover scale-105 filter blur-[3px]">
    </div>

    <!-- OVERLAY GRADIENT BIRU DISKOMINSA (Transparan agar foto gedung tetap terbayang) -->
    <div class="fixed inset-0 z-0 bg-gradient-to-br from-[#071E3D]/90 via-[#0D2B52]/85 to-[#1F4287]/90 mix-blend-multiply"></div>
    <div class="fixed inset-0 z-0 bg-[#071E3D]/40"></div>

    <!-- ORNAMEN EFEK CAHAYA GLOWING -->
    <div class="fixed -top-32 -left-32 w-[300px] sm:w-[450px] h-[300px] sm:h-[450px] bg-cyan-400/25 rounded-full blur-[100px] sm:blur-[130px] pointer-events-none animate-pulse z-0"></div>
    <div class="fixed -bottom-32 -right-32 w-[300px] sm:w-[450px] h-[300px] sm:h-[450px] bg-blue-500/25 rounded-full blur-[100px] sm:blur-[130px] pointer-events-none animate-pulse z-0" style="animation-delay: 2s;"></div>

    <!-- CARD UTAMA INI LEBIH RINGKAS & GLASSMORPHISM SMOOTH -->
    <div class="animate-card-down bg-white/95 backdrop-blur-md w-full max-w-md rounded-2xl sm:rounded-3xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6)] p-5 sm:p-7 relative z-10 my-auto border border-white/50">
        
        <div class="flex flex-col items-center mb-4">
            <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo" class="h-12 sm:h-14 w-auto object-contain drop-shadow-sm mb-2 hover:scale-105 transition-transform duration-300">
            <h2 class="text-xl sm:text-2xl font-extrabold text-[#071E3D] tracking-tight text-center">Lupa Kata Sandi?</h2>
            <p class="text-xs text-gray-500 font-medium text-center mt-1 max-w-xs">Masukkan data terdaftar Anda. Admin akan memverifikasi dan mengirimkan akses via WhatsApp.</p>
        </div>

        @if (session('sukses'))
            <div class="mb-3 p-3 bg-green-50 border border-green-100 text-green-600 text-xs rounded-xl font-bold flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-sm shrink-0"></i> {{ session('status') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="mb-3 p-3 bg-red-50 border border-red-100 text-red-500 text-xs rounded-xl font-bold flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-sm shrink-0"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ Route::has('password.email') ? route('password.email') : url('/forgot-password') }}" method="POST" autocomplete="off" class="space-y-3">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1 ml-0.5">Email Terdaftar / NIP</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-regular fa-envelope text-xs"></i>
                    </div>
                    <input type="text" name="email" required maxlength="255" value="{{ old('email') }}" placeholder="nama@acehbaratkab.go.id / NIP"
                        class="w-full bg-gray-50/90 border border-gray-200 rounded-xl pl-10 pr-3 py-2.5 text-xs sm:text-sm text-[#071E3D] font-semibold placeholder-gray-400 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all duration-200">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1 ml-0.5">Nomor WhatsApp Aktif</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-green-600">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                    </div>
                    <input type="tel" name="phone" required maxlength="15" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                        class="w-full bg-gray-50/90 border border-gray-200 rounded-xl pl-10 pr-3 py-2.5 text-xs sm:text-sm text-[#071E3D] font-semibold placeholder-gray-400 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all duration-200">
                </div>
            </div>

            <div class="bg-blue-50/80 border border-blue-100 rounded-xl p-2.5 flex items-start gap-2">
                <i class="fa-solid fa-circle-info text-cyan-600 text-xs mt-0.5 shrink-0"></i>
                <p class="text-[11px] text-gray-500 font-medium leading-normal">
                    Sandi baru dikirimkan ke WhatsApp setelah diverifikasi Admin pada jam kerja resmi.
                </p>
            </div>

            <button type="submit" class="w-full bg-[#071E3D] hover:bg-[#1F4287] active:scale-[0.99] text-white font-extrabold rounded-xl py-2.5 sm:py-3 text-xs sm:text-sm tracking-wide transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 group">
                Kirim Permintaan Reset Sandi
                <i class="fa-solid fa-paper-plane text-xs group-hover:translate-x-1 transition-transform"></i>
            </button>
        </form>

        <div class="mt-4 text-center">
            <p class="text-xs text-gray-500 font-medium">
                Sudah ingat kata sandi? 
                <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="text-cyan-600 hover:text-[#071E3D] font-extrabold underline decoration-2 underline-offset-4 transition-colors ml-0.5">
                    Masuk Disini
                </a>
            </p>

            <div class="mt-3 pt-2.5 border-t border-gray-100">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-[#071E3D] transition-colors">
                    <i class="fa-solid fa-arrow-left-long"></i> Kembali ke Beranda
                </a>
            </div>
        </div>

    </div>

</body>
</html>