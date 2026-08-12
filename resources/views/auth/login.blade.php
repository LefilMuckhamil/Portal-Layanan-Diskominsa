<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Portal Layanan Diskominsa') }} - Masuk</title>
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
    </style>
</head>
<body class="min-h-screen md:h-screen w-screen overflow-y-auto md:overflow-hidden bg-gradient-to-br from-[#071E3D] via-[#0D2B52] to-[#1F4287] flex items-center justify-center p-4 lg:p-8 relative selection:bg-cyan-300 selection:text-[#071E3D]">

    <div class="fixed -top-32 -left-32 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-cyan-400/20 rounded-full blur-[100px] sm:blur-[140px] pointer-events-none animate-pulse"></div>
    <div class="fixed -bottom-32 -right-32 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-blue-500/20 rounded-full blur-[100px] sm:blur-[140px] pointer-events-none animate-pulse" style="animation-delay: 2s;"></div>

    <div class="bg-white w-full max-w-7xl md:h-full md:max-h-[780px] rounded-[2rem] lg:rounded-[2.5rem] shadow-[0_25px_70px_-15px_rgba(0,0,0,0.5)] p-3 lg:p-4 flex flex-col md:flex-row gap-4 lg:gap-6 overflow-hidden relative z-10 my-auto">

        <div class="w-full md:w-1/2 rounded-[1.5rem] lg:rounded-[2rem] p-6 sm:p-8 lg:p-14 flex flex-col justify-between relative overflow-hidden group shrink-0 min-h-[250px] sm:min-h-[300px] md:min-h-0">
            <img src="{{ asset('image/diskominsa.jpeg') }}" alt="Gedung Diskominsa" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-br from-[#071E3D]/95 via-[#071E3D]/85 to-[#1F4287]/90 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-[#071E3D]/50"></div>
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-cyan-400 rounded-full mix-blend-screen filter blur-[80px] opacity-25 pointer-events-none animate-pulse"></div>

            <div class="relative z-10 flex flex-col h-full justify-center">
                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-2 sm:mb-3 tracking-tight">
                    Sederhanakan<br>layanan dengan<br>portal terpadu.
                </h1>
                <svg class="w-36 sm:w-52 h-3 text-cyan-400 mb-3 sm:mb-6 drop-shadow-md" viewBox="0 0 200 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 6.5C48.5 2.5 108.5 1.5 198 6.5" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                </svg>
                <p class="text-white/85 text-xs sm:text-sm lg:text-[15px] max-w-md leading-relaxed font-light">
                    Akses seluruh layanan digital instansi Pemerintah Kabupaten Aceh Barat dengan mudah melalui satu pintu. Transparan, cepat, dan aman.
                </p>
            </div>
        </div>

        <div class="w-full md:w-1/2 flex flex-col justify-center px-4 sm:px-12 py-4 sm:py-6 lg:py-8 bg-white rounded-[1.5rem] lg:rounded-[2rem]">
            <div class="w-full max-w-md mx-auto flex flex-col justify-between h-full md:max-h-[620px]">
                
                <div class="flex flex-col items-center mt-1 sm:mt-2 mb-3 sm:mb-4">
                    <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo" class="h-16 sm:h-24 md:h-28 w-auto object-contain drop-shadow-sm mb-2 sm:mb-3 hover:scale-105 transition-transform duration-300">
                    <h2 class="text-xl sm:text-3xl font-extrabold text-[#071E3D] tracking-tight">Selamat Datang</h2>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium text-center mt-1">Silakan masuk menggunakan email resmi Anda</p>
                </div>

                <div>
                    @if (session('sukses'))
                        <div class="mb-3 sm:mb-4 p-3 sm:p-3.5 bg-green-50 border border-green-100 text-green-600 text-xs rounded-xl font-bold flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-circle-check text-sm"></i> {{ session('sukses') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="mb-3 sm:mb-4 p-3 sm:p-3.5 bg-red-50 border border-red-100 text-red-500 text-xs rounded-xl font-bold flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-circle-exclamation text-sm"></i> {{ $errors->first('email') }}
                        </div>
                    @endif

                    <form action="{{ url('/login') }}" method="POST" autocomplete="off" class="space-y-3 sm:space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1 sm:mb-1.5 ml-1">Email (@acehbaratkab.go.id)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-regular fa-envelope text-xs sm:text-sm"></i>
                                </div>
                                <input type="email" name="email" required maxlength="255" value="{{ old('email') }}" placeholder="nama@acehbaratkab.go.id"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 sm:py-3.5 text-xs sm:text-sm text-[#071E3D] font-semibold placeholder-gray-400 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all duration-200">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1 sm:mb-1.5 ml-1">Kata Sandi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-lock text-xs sm:text-sm"></i>
                                </div>
                                <input type="password" id="password" name="password" required maxlength="128" placeholder="••••••••"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-11 py-3 sm:py-3.5 text-xs sm:text-sm text-[#071E3D] font-semibold placeholder-gray-400 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all duration-200">
                                
                                <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-cyan-600 p-1.5 transition-colors focus:outline-none">
                                    <i id="eye-icon" class="fa-regular fa-eye-slash text-xs sm:text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-end pt-0.5 sm:pt-1">
                            <a href="{{ Route::has('password.request') ? route('password.request') : '#' }}" class="text-xs font-bold text-gray-400 hover:text-[#071E3D] transition-colors">
                                Lupa kata sandi?
                            </a>
                        </div>

                        <button type="submit" class="w-full bg-[#071E3D] hover:bg-[#1F4287] active:scale-[0.99] text-white font-extrabold rounded-xl py-3 sm:py-3.5 text-xs sm:text-sm tracking-wide transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 group">
                            Masuk Sekarang
                            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>

                <div class="mt-4 sm:mt-6 text-center">
                    <p class="text-xs text-gray-500 font-medium">
                        Belum punya akun? 
                        <a href="{{ Route::has('register') ? route('register') : '#' }}" class="text-cyan-600 hover:text-[#071E3D] font-extrabold underline decoration-2 underline-offset-4 transition-colors ml-0.5">
                            Daftar Disini
                        </a>
                    </p>

                    <div class="mt-3 sm:mt-4 pt-3 border-t border-gray-100 mb-2 sm:mb-0">
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-[#071E3D] transition-colors">
                            <i class="fa-solid fa-arrow-left-long"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const isPassword = passwordInput.type === 'password';
            
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.classList.toggle('fa-eye-slash', !isPassword);
            eyeIcon.classList.toggle('fa-eye', isPassword);
        }
    </script>
</body>
</html>