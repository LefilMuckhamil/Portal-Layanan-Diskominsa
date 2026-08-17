<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Portal Layanan Diskominsa') }} - Daftar Akun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('image/icon.png') }}">
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        ::-webkit-scrollbar { display: none; }
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #f9fafb inset !important;
        }

        /* Animasi Slide Down & Fade In Smooth */
        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-16px) scale(0.99);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes contentDropIn {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-card-down {
            animation: fadeInDown 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-form-down {
            animation: contentDropIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.15s both;
        }
    </style>
</head>
<body class="min-h-screen md:h-screen w-screen overflow-y-auto md:overflow-hidden bg-gradient-to-br from-[#071E3D] via-[#0D2B52] to-[#1F4287] flex items-center justify-center p-3 lg:p-6 relative selection:bg-cyan-300 selection:text-[#071E3D]">

    <div class="fixed -top-32 -left-32 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-cyan-400/20 rounded-full blur-[100px] sm:blur-[140px] pointer-events-none animate-pulse"></div>
    <div class="fixed -bottom-32 -right-32 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-blue-500/20 rounded-full blur-[100px] sm:blur-[140px] pointer-events-none animate-pulse" style="animation-delay: 2s;"></div>

    <div class="animate-card-down bg-white w-full max-w-7xl md:h-full md:max-h-[800px] rounded-[2rem] lg:rounded-[2.5rem] shadow-[0_25px_70px_-15px_rgba(0,0,0,0.5)] p-2.5 lg:p-3.5 flex flex-col md:flex-row gap-3 lg:gap-5 overflow-hidden relative z-10 my-auto transition-all">

        <div class="hidden md:flex w-full md:w-5/12 rounded-[1.5rem] lg:rounded-[2rem] p-6 lg:p-10 flex-col justify-between relative overflow-hidden group shrink-0">
            <img src="{{ asset('image/diskominsa.jpeg') }}" alt="Gedung Diskominsa" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-br from-[#071E3D]/95 via-[#071E3D]/85 to-[#1F4287]/90 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-[#071E3D]/50"></div>
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-cyan-400 rounded-full mix-blend-screen filter blur-[80px] opacity-25 pointer-events-none animate-pulse"></div>

            <div class="relative z-10 flex flex-col h-full justify-center">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-2 tracking-tight">
                    Mulai<br>digitalisasi<br>instansi Anda.
                </h1>
                <svg class="w-40 sm:w-48 h-3 text-cyan-400 mb-4 drop-shadow-md" viewBox="0 0 200 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 6.5C48.5 2.5 108.5 1.5 198 6.5" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                </svg>
                <p class="text-white/85 text-xs sm:text-sm lg:text-[15px] max-w-sm leading-relaxed font-light mb-6">
                    Daftarkan akun Anda untuk mendapatkan akses penuh ke seluruh layanan digital G2G Kabupaten Aceh Barat.
                </p>

                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-3.5 flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-cyan-400/20 text-cyan-400 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-xs mb-0.5">Verifikasi Keamanan</h4>
                        <p class="text-white/75 text-[11px] leading-relaxed">Seluruh pendaftaran akun baru akan melalui proses verifikasi oleh Admin untuk memastikan validitas data ASN.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="animate-form-down w-full md:w-7/12 flex flex-col justify-between px-4 sm:px-8 py-3 lg:py-4 bg-white rounded-[1.5rem] lg:rounded-[2rem]">
            <div class="w-full max-w-xl mx-auto flex flex-col justify-between h-full">
                
                <div class="flex flex-col items-center mb-2 shrink-0">
                    <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo" class="h-14 sm:h-16 md:h-20 w-auto object-contain drop-shadow-sm mb-1 hover:scale-105 transition-transform duration-300">
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#071E3D] tracking-tight text-center">Pendaftaran Akun ASN</h2>
                    <p class="text-xs text-gray-500 font-medium text-center">Lengkapi formulir dengan data diri dan instansi yang valid</p>
                </div>

                <div class="my-auto">
                    @if ($errors->any())
                        <div class="mb-2 p-2.5 bg-red-50 border border-red-100 text-red-500 text-xs rounded-xl font-semibold shadow-sm">
                            <div class="flex items-center gap-1.5 font-bold">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i> Terdapat kesalahan:
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 ml-1 mt-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ Route::has('register.process') ? route('register.process') : url('/register') }}" method="POST" autocomplete="off" class="space-y-2 sm:space-y-2.5">
                        @csrf
                        <input type="hidden" name="role" value="asn">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1 ml-0.5">Nama Lengkap</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-regular fa-user text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" name="name" required maxlength="100" value="{{ old('name') }}" placeholder="Budi Santoso, S.Kom"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-3 py-2 sm:py-2.5 text-xs sm:text-sm text-[#071E3D] font-semibold placeholder-gray-400 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all duration-200">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1 ml-0.5">NIP</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-regular fa-id-badge text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" name="nip" required maxlength="18" pattern="[0-9]{18}" value="{{ old('nip') }}" placeholder="18 Digit NIP"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-3 py-2 sm:py-2.5 text-xs sm:text-sm text-[#071E3D] font-semibold placeholder-gray-400 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all duration-200">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1 ml-0.5">Instansi</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-regular fa-building text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" name="unit_kerja" required maxlength="100" value="{{ old('unit_kerja') }}" placeholder="Diskominsa"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-3 py-2 sm:py-2.5 text-xs sm:text-sm text-[#071E3D] font-semibold placeholder-gray-400 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all duration-200">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1 ml-0.5">Jabatan</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-solid fa-briefcase text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="text" name="jabatan" required maxlength="100" value="{{ old('jabatan') }}" placeholder="Kepala Bidang"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-3 py-2 sm:py-2.5 text-xs sm:text-sm text-[#071E3D] font-semibold placeholder-gray-400 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all duration-200">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1 ml-0.5">Nomor HP</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-solid fa-mobile-screen text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="tel" name="no_hp" required maxlength="15" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-3 py-2 sm:py-2.5 text-xs sm:text-sm text-[#071E3D] font-semibold placeholder-gray-400 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all duration-200">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1 ml-0.5">Email (@acehbaratkab.go.id)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-regular fa-envelope text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="email" name="email" required maxlength="255" value="{{ old('email') }}" placeholder="nama@acehbaratkab.go.id"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-3 py-2 sm:py-2.5 text-xs sm:text-sm text-[#071E3D] font-semibold placeholder-gray-400 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all duration-200">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1 ml-0.5">Kata Sandi</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-solid fa-lock text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="password" id="password" name="password" required maxlength="128" placeholder="Minimal 8 karakter"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-10 py-2 sm:py-2.5 text-xs sm:text-sm text-[#071E3D] font-semibold placeholder-gray-400 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all duration-200">
                                    <button type="button" onclick="togglePassword('password', 'eye_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-cyan-600 p-1 focus:outline-none transition-colors">
                                        <i id="eye_password" class="fa-regular fa-eye-slash text-xs sm:text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1 ml-0.5">Konfirmasi Sandi</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-solid fa-shield-check text-xs sm:text-sm"></i>
                                    </div>
                                    <input type="password" id="password_conf" name="password_confirmation" required maxlength="128" placeholder="Ulangi kata sandi"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-10 py-2 sm:py-2.5 text-xs sm:text-sm text-[#071E3D] font-semibold placeholder-gray-400 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all duration-200">
                                    <button type="button" onclick="togglePassword('password_conf', 'eye_conf')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-cyan-600 p-1 focus:outline-none transition-colors">
                                        <i id="eye_conf" class="fa-regular fa-eye-slash text-xs sm:text-sm"></i>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <button type="submit" class="w-full mt-2 sm:mt-2.5 bg-[#071E3D] hover:bg-[#1F4287] active:scale-[0.99] text-white font-extrabold rounded-xl py-2.5 sm:py-3 text-xs sm:text-sm tracking-wide transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 group">
                            Daftar Akun Sekarang
                            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>

                <div class="mt-2 shrink-0 text-center">
                    <p class="text-xs sm:text-sm text-gray-500 font-medium">
                        Sudah memiliki akun? 
                        <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="text-cyan-600 hover:text-[#071E3D] font-extrabold underline decoration-2 underline-offset-4 transition-colors ml-0.5">
                            Masuk Disini
                        </a>
                    </p>

                    <div class="mt-2 pt-2 border-t border-gray-100">
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-[#071E3D] transition-colors">
                            <i class="fa-solid fa-arrow-left-long"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isPassword = input.type === 'password';
            
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye-slash', !isPassword);
            icon.classList.toggle('fa-eye', isPassword);
        }
    </script>
</body>
</html>