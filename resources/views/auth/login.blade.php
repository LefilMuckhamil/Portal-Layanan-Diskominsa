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
        body { font-family: 'Outfit', sans-serif; background-color: #f4f7f6; }
        ::-webkit-scrollbar { display: none; }
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #f9fafb inset !important;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 selection:bg-cyan-300 selection:text-[#071E3D]">

    <div class="bg-white w-full max-w-[1050px] rounded-[2.5rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] p-3 sm:p-4 md:p-5 flex flex-col md:flex-row gap-6 min-h-[650px]">

        <div class="w-full md:w-1/2 rounded-[2rem] p-8 lg:p-12 flex flex-col relative overflow-hidden group">
            <img src="{{ asset('image/diskominsa.jpeg') }}" alt="Gedung Diskominsa" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-br from-[#071E3D]/95 via-[#071E3D]/80 to-[#1F4287]/90 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-[#071E3D]/60"></div>
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-cyan-400 rounded-full mix-blend-screen filter blur-[80px] opacity-20 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col h-full justify-center pb-10">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-white leading-[1.15] mb-2 tracking-tight">
                    Sederhanakan<br>layanan dengan<br>portal terpadu.
                </h1>
                <svg class="w-48 h-3 text-cyan-400 mb-6 drop-shadow-md" viewBox="0 0 200 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 6.5C48.5 2.5 108.5 1.5 198 6.5" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                </svg>
                <p class="text-white/90 text-sm lg:text-[15px] max-w-[300px] leading-relaxed font-light">
                    Akses seluruh layanan digital instansi Pemerintah Kabupaten Aceh Barat dengan mudah melalui satu pintu. Transparan, cepat, dan aman.
                </p>
            </div>
        </div>

        <div class="w-full md:w-1/2 flex flex-col justify-center px-4 sm:px-8 py-10 lg:py-12 bg-white rounded-[2rem]">
            <div class="w-full max-w-[380px] mx-auto">
                
                <div class="flex flex-col items-center mb-10">
                    <div class="flex items-center justify-center w-full mb-6">
                        <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo" class="h-24 md:h-28 w-auto object-contain drop-shadow-sm">
                    </div>
                    <h2 class="text-3xl font-extrabold text-[#071E3D] mb-2 tracking-tight">Selamat Datang</h2>
                    <p class="text-[14px] text-gray-500 font-medium text-center">Silakan masuk menggunakan email resmi Anda</p>
                </div>

                @if (session('sukses'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 text-[13px] rounded-2xl font-bold flex items-center gap-3 shadow-sm">
                        <i class="fa-solid fa-circle-check text-lg"></i> {{ session('sukses') }}
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-500 text-[13px] rounded-2xl font-bold flex items-center gap-3 shadow-sm">
                        <i class="fa-solid fa-circle-exclamation text-lg"></i> {{ $errors->first('email') }}
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-[13px] font-bold text-gray-600 mb-2 ml-1">Email (@acehbaratkab.go.id)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-regular fa-envelope text-[14px]"></i>
                            </div>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="nama@acehbaratkab.go.id"
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl pl-12 pr-5 py-4 text-[14px] text-[#071E3D] font-bold placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-50 outline-none transition-all duration-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-bold text-gray-600 mb-2 ml-1">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-solid fa-lock text-[14px]"></i>
                            </div>
                            <input type="password" id="password" name="password" required placeholder="••••••••"
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl pl-12 pr-12 py-4 text-[14px] text-[#071E3D] font-bold placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-50 outline-none transition-all duration-300">
                            
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-cyan-600 p-2 transition-colors focus:outline-none">
                                <i id="eye-icon" class="fa-regular fa-eye-slash text-[15px]"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end pt-1 pb-2">
                        <a href="{{ route('password.request') ?? '#' }}" class="text-[13px] font-bold text-gray-400 hover:text-[#071E3D] transition-colors">
                            Lupa kata sandi?
                        </a>
                    </div>

                    <button type="submit" class="w-full bg-[#071E3D] hover:bg-[#1F4287] text-white font-extrabold rounded-2xl py-4 text-[15px] tracking-wide transition-all duration-300 shadow-[0_8px_20px_rgba(7,30,61,0.15)] hover:shadow-[0_8px_25px_rgba(7,30,61,0.25)] transform hover:-translate-y-0.5 flex items-center justify-center gap-2 group">
                        Masuk Sekarang
                        <i class="fa-solid fa-arrow-right text-[13px] group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                <p class="text-center text-[13px] text-gray-500 font-medium mt-8">
                    Belum punya akun? 
                    <a href="{{ route('register') ?? '#' }}" class="text-cyan-600 hover:text-[#071E3D] font-extrabold underline decoration-2 underline-offset-4 transition-colors ml-1">
                        Daftar Disini
                    </a>
                </p>

                <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[13px] font-bold text-gray-400 hover:text-[#071E3D] transition-colors">
                        <i class="fa-solid fa-arrow-left-long"></i> Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        }
    </script>
</body>
</html>