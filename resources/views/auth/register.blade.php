<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Portal Layanan Diskominsa') }} - Daftar Akun</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Google & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: #f4f7f6; 
        }
        
        /* Custom Scrollbar untuk area form jika layar kekecilan */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        
        /* Menghilangkan background kuning bawaan browser saat autofill */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #f9fafb inset !important;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 selection:bg-cyan-300 selection:text-[#071E3D]">

    <!-- KONTENER KARTU UTAMA -->
    <div class="bg-white w-full max-w-[1150px] rounded-[2.5rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] p-3 sm:p-4 md:p-5 flex flex-col md:flex-row gap-6 h-auto md:h-[750px]">

        <!-- SISI KIRI: Banner dengan Background Foto Instansi -->
        <div class="hidden md:flex w-full md:w-5/12 rounded-4xl p-8 lg:p-12 flex-col relative overflow-hidden group">
            
            <!-- Foto Background -->
            <img src="{{ asset('image/diskominsa.jpeg') }}" alt="Gedung Diskominsa" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
            
            <!-- Overlay Gradient Biru Gelap -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#071E3D]/95 via-[#071E3D]/85 to-[#1F4287]/90 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-[#071E3D]/50"></div>

            <!-- Ornamen Cahaya -->
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-cyan-400 rounded-full mix-blend-screen filter blur-[80px] opacity-20 pointer-events-none"></div>

            <!-- Konten Teks di Atas Foto -->
            <div class="relative z-10 flex flex-col h-full justify-center pb-10">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-white leading-[1.15] mb-2 tracking-tight">
                    Mulai<br>
                    digitalisasi<br>
                    instansi Anda.
                </h1>
                
                <svg class="w-48 h-3 text-cyan-400 mb-6 drop-shadow-md" viewBox="0 0 200 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 6.5C48.5 2.5 108.5 1.5 198 6.5" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                </svg>

                <p class="text-white/90 text-sm lg:text-[15px] max-w-[300px] leading-relaxed font-light mb-8">
                    Daftarkan akun Anda untuk mendapatkan akses penuh ke seluruh layanan digital G2G Kabupaten Aceh Barat.
                </p>

                <!-- Box Info di Kiri -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-400/20 text-cyan-400 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-shield-halved text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-sm mb-1">Verifikasi Keamanan</h4>
                        <p class="text-white/70 text-[12px] leading-relaxed">Seluruh pendaftaran akun baru akan melalui proses verifikasi oleh Admin untuk memastikan validitas data ASN.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SISI KANAN: Form Registrasi ASN -->
        <div class="w-full md:w-7/12 flex flex-col justify-start px-4 sm:px-8 py-8 lg:py-10 bg-white rounded-4xl overflow-y-auto">
            
            <div class="w-full max-w-[550px] mx-auto">
                
                <!-- Logo & Header -->
                <div class="flex flex-col items-center mb-10">
                    <div class="flex items-center justify-center w-full mb-4 md:mb-6">
                        <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo" class="h-16 md:h-20 w-auto object-contain drop-shadow-sm">
                    </div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-[#071E3D] mb-1.5 tracking-tight text-center">Pendaftaran Akun ASN</h2>
                    <p class="text-[13px] text-gray-500 font-medium text-center">Lengkapi formulir di bawah ini dengan data diri dan instansi yang valid</p>
                </div>

                <!-- Notifikasi Error Global -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 text-[13px] rounded-2xl font-medium shadow-sm">
                        <div class="flex items-center gap-2 mb-2 font-bold">
                            <i class="fa-solid fa-circle-exclamation"></i> Terdapat kesalahan pengisian:
                        </div>
                        <ul class="list-disc list-inside space-y-1 ml-1 text-red-500">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- ================== FORM PENDAFTARAN UTAMA ================== -->
                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf
                    <!-- Hidden Role -->
                    <input type="hidden" name="role" value="asn">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-[12px] font-bold text-gray-600 mb-1.5 ml-1">Nama Lengkap (beserta gelar)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-regular fa-user text-[13px]"></i>
                                </div>
                                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Cth: Budi Santoso, S.Kom"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3.5 text-[13px] text-[#071E3D] font-bold placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-50 outline-none transition-all">
                            </div>
                        </div>

                        <!-- NIP -->
                        <div>
                            <label class="block text-[12px] font-bold text-gray-600 mb-1.5 ml-1">NIP</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-regular fa-id-badge text-[13px]"></i>
                                </div>
                                <input type="text" name="nip" required value="{{ old('nip') }}" placeholder="18 Digit NIP"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3.5 text-[13px] text-[#071E3D] font-bold placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-50 outline-none transition-all">
                            </div>
                        </div>

                        <!-- Instansi / Unit Kerja -->
                        <div>
                            <label class="block text-[12px] font-bold text-gray-600 mb-1.5 ml-1">Asal Instansi / SKPK</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-regular fa-building text-[13px]"></i>
                                </div>
                                <input type="text" name="unit_kerja" required value="{{ old('unit_kerja') }}" placeholder="Cth: Diskominsa"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3.5 text-[13px] text-[#071E3D] font-bold placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-50 outline-none transition-all">
                            </div>
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label class="block text-[12px] font-bold text-gray-600 mb-1.5 ml-1">Jabatan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-briefcase text-[13px]"></i>
                                </div>
                                <input type="text" name="jabatan" required value="{{ old('jabatan') }}" placeholder="Cth: Kepala Bidang"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3.5 text-[13px] text-[#071E3D] font-bold placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-50 outline-none transition-all">
                            </div>
                        </div>

                        <!-- Nomor HP -->
                        <div>
                            <label class="block text-[12px] font-bold text-gray-600 mb-1.5 ml-1">Nomor HP / WhatsApp</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-mobile-screen text-[13px]"></i>
                                </div>
                                <input type="text" name="phone" required value="{{ old('phone') }}" placeholder="Cth: 08xxxxxxxxx"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3.5 text-[13px] text-[#071E3D] font-bold placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-50 outline-none transition-all">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-[12px] font-bold text-gray-600 mb-1.5 ml-1">Email (@acehbarat.go.id)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-regular fa-envelope text-[13px]"></i>
                                </div>
                                <input type="email" name="email" required value="{{ old('email') }}" placeholder="nama@acehbaratkab.go.id"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3.5 text-[13px] text-[#071E3D] font-bold placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-50 outline-none transition-all">
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-[12px] font-bold text-gray-600 mb-1.5 ml-1">Kata Sandi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-lock text-[13px]"></i>
                                </div>
                                <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-10 py-3.5 text-[13px] text-[#071E3D] font-bold placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-50 outline-none transition-all">
                                <button type="button" onclick="togglePassword('password', 'eye_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-cyan-600 focus:outline-none p-1 transition-colors">
                                    <i id="eye_password" class="fa-regular fa-eye-slash text-[13px]"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-[12px] font-bold text-gray-600 mb-1.5 ml-1">Konfirmasi Sandi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-shield-check text-[13px]"></i>
                                </div>
                                <input type="password" id="password_conf" name="password_confirmation" required placeholder="Ulangi kata sandi"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-10 py-3.5 text-[13px] text-[#071E3D] font-bold placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-50 outline-none transition-all">
                                <button type="button" onclick="togglePassword('password_conf', 'eye_conf')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-cyan-600 focus:outline-none p-1 transition-colors">
                                    <i id="eye_conf" class="fa-regular fa-eye-slash text-[13px]"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="w-full mt-6 bg-[#071E3D] hover:bg-[#1F4287] text-white font-extrabold rounded-xl py-4 text-[14px] tracking-wide transition-all duration-300 shadow-[0_8px_20px_rgba(7,30,61,0.15)] hover:shadow-[0_8px_25px_rgba(7,30,61,0.25)] transform hover:-translate-y-0.5 flex items-center justify-center gap-2 group">
                        Daftar Akun Sekarang
                        <i class="fa-solid fa-arrow-right text-[12px] group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                <!-- Link Kembali ke Login -->
                <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col items-center gap-3">
                    <p class="text-[13px] text-gray-500 font-medium">
                        Sudah memiliki akun? 
                        <a href="{{ route('login') }}" class="text-cyan-600 hover:text-[#071E3D] font-extrabold underline decoration-2 underline-offset-4 transition-colors ml-1">
                            Masuk Disini
                        </a>
                    </p>
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[12px] font-bold text-gray-400 hover:text-[#071E3D] transition-colors mt-2">
                        <i class="fa-solid fa-arrow-left-long"></i> Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>

    </div>

    <!-- SCRIPT INTERAKTIF (Hanya untuk Tampil/Sembunyi Password) -->
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>