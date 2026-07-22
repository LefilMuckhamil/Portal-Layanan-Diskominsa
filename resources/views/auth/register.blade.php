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
    <div class="bg-white w-full max-w-287 rounded-[2.5rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] p-3 sm:p-4 md:p-5 flex flex-col md:flex-row gap-6 h-auto md:h-187">

        <!-- SISI KIRI: Banner dengan Background Foto Instansi -->
        <div class="hidden md:flex w-full md:w-5/12 rounded-4xl p-8 lg:p-12 flex-col relative overflow-hidden group">
            
            <!-- Foto Background -->
            <img src="{{ asset('image/diskominsa.jpeg') }}" alt="Gedung Diskominsa" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
            
            <!-- Overlay Gradient Biru Gelap -->
            <div class="absolute inset-0 bg-linear-to-br from-[#071E3D]/95 via-[#071E3D]/85 to-[#1F4287]/90 mix-blend-multiply"></div>
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

                <p class="text-white/90 text-sm lg:text-[15px] max-w-75 leading-relaxed font-light mb-8">
                    Daftarkan akun Anda untuk mendapatkan akses penuh ke seluruh layanan digital G2G Kabupaten Aceh Barat.
                </p>

                <!-- Box Info di Kiri -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-400/20 text-cyan-400 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-shield-halved text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-sm mb-1">Verifikasi Keamanan</h4>
                        <p class="text-white/70 text-[12px] leading-relaxed">Seluruh pendaftaran akun baru akan melalui proses verifikasi oleh Admin untuk memastikan validitas data instansi & ASN.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SISI KANAN: Form Registrasi Dinamis -->
        <div class="w-full md:w-7/12 flex flex-col justify-start px-4 sm:px-8 py-8 lg:py-10 bg-white rounded-4xl overflow-y-auto">
            
            <div class="w-full max-w-137 mx-auto">
                
                <!-- Logo & Header -->
                <div class="flex flex-col items-center mb-8">
                    <div class="flex items-center justify-center w-full mb-4 md:mb-6">
                        <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo" class="h-16 md:h-20 w-auto object-contain drop-shadow-sm">
                    </div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-[#071E3D] mb-1.5 tracking-tight text-center">Pendaftaran Akun Baru</h2>
                    <p class="text-[13px] text-gray-500 font-medium text-center">Lengkapi formulir di bawah ini dengan data yang valid</p>
                </div>

                <!-- Tab Pemilihan Jenis Akun -->
                <div class="flex bg-gray-100 p-1.5 rounded-2xl mb-8">
                    <button type="button" id="tab-asn" onclick="setRegType('asn')" class="flex-1 py-3 text-[13px] font-bold rounded-xl bg-white text-[#071E3D] shadow-sm transition-all duration-300">Pendaftar ASN</button>
                    <button type="button" id="tab-instansi" onclick="setRegType('instansi')" class="flex-1 py-3 text-[13px] font-bold rounded-xl text-gray-400 hover:text-[#071E3D] transition-all duration-300">Pendaftar Instansi</button>
                </div>

                <!-- Notifikasi Error Global -->
                @if ($errors->any())
                    <div class="mb-6 p-3 bg-red-50 border border-red-100 text-red-500 text-[13px] rounded-xl font-medium">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- ================== FORM ASN ================== -->
                <form id="form-asn" action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="role" value="asn">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Nama Lengkap (beserta gelar)</label>
                            <input type="text" name="name" required value="{{ old('name') }}" placeholder="Cth: Budi Santoso, S.Kom"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        </div>
                        <!-- NIP -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">NIP</label>
                            <input type="text" name="nip" required value="{{ old('nip') }}" placeholder="18 Digit NIP"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        </div>
                        <!-- Email -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Email Resmi (Go.id)</label>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="nama@acehbarat.go.id"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        </div>
                        <!-- No HP -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Nomor HP / WhatsApp</label>
                            <input type="text" name="phone" required value="{{ old('phone') }}" placeholder="08xxxxxxxxx"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        </div>
                        <!-- Unit Kerja -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Unit Kerja / SKPD</label>
                            <input type="text" name="unit_kerja" required value="{{ old('unit_kerja') }}" placeholder="Cth: Diskominsa"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        </div>
                        <!-- Jabatan -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Jabatan</label>
                            <input type="text" name="jabatan" required value="{{ old('jabatan') }}" placeholder="Cth: Kepala Bidang"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        </div>
                        <!-- Password -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Kata Sandi</label>
                            <div class="relative">
                                <input type="password" id="asn_password" name="password" required placeholder="Minimal 8 karakter"
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl pl-4 pr-10 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                                <button type="button" onclick="togglePassword('asn_password', 'asn_eye')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#071E3D]">
                                    <i id="asn_eye" class="fa-regular fa-eye-slash text-[13px]"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Konfirmasi Sandi</label>
                            <div class="relative">
                                <input type="password" id="asn_password_conf" name="password_confirmation" required placeholder="Ulangi kata sandi"
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl pl-4 pr-10 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                                <button type="button" onclick="togglePassword('asn_password_conf', 'asn_eye_conf')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#071E3D]">
                                    <i id="asn_eye_conf" class="fa-regular fa-eye-slash text-[13px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-2 bg-[#071E3D] hover:bg-[#1F4287] text-white font-bold rounded-[14px] py-4 text-[14px] transition-all duration-300 shadow-[0_8px_20px_rgba(7,30,61,0.2)] hover:shadow-[0_8px_25px_rgba(7,30,61,0.3)] transform hover:-translate-y-0.5">
                        Kirim Pendaftaran ASN
                    </button>
                </form>

                <!-- ================== FORM INSTANSI ================== -->
                <form id="form-instansi" action="{{ route('register') }}" method="POST" class="space-y-4 hidden">
                    @csrf
                    <input type="hidden" name="role" value="instansi">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Instansi -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Nama Instansi / Desa</label>
                            <input type="text" name="instansi_name" required value="{{ old('instansi_name') }}" placeholder="Cth: Desa Panggong"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        </div>
                        <!-- Email Instansi -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Email Instansi</label>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="instansi@desa.go.id"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        </div>
                        <!-- Nama PIC -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Penanggung Jawab (PIC)</label>
                            <input type="text" name="pic_name" required value="{{ old('pic_name') }}" placeholder="Nama pengelola akun"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        </div>
                        <!-- Jabatan PIC -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Jabatan PIC</label>
                            <input type="text" name="pic_jabatan" required value="{{ old('pic_jabatan') }}" placeholder="Cth: Sekretaris Desa"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        </div>
                        <!-- No HP -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Nomor HP / WhatsApp</label>
                            <input type="text" name="phone" required value="{{ old('phone') }}" placeholder="08xxxxxxxxx"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        </div>
                        <!-- Alamat -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Alamat Instansi</label>
                            <input type="text" name="alamat" required value="{{ old('alamat') }}" placeholder="Alamat lengkap instansi"
                                class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        </div>
                        <!-- Password -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Kata Sandi</label>
                            <div class="relative">
                                <input type="password" id="ins_password" name="password" required placeholder="Minimal 8 karakter"
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl pl-4 pr-10 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                                <button type="button" onclick="togglePassword('ins_password', 'ins_eye')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#071E3D]">
                                    <i id="ins_eye" class="fa-regular fa-eye-slash text-[13px]"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Konfirmasi Sandi</label>
                            <div class="relative">
                                <input type="password" id="ins_password_conf" name="password_confirmation" required placeholder="Ulangi kata sandi"
                                    class="w-full bg-gray-50 border border-gray-100 rounded-xl pl-4 pr-10 py-3.5 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                                <button type="button" onclick="togglePassword('ins_password_conf', 'ins_eye_conf')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#071E3D]">
                                    <i id="ins_eye_conf" class="fa-regular fa-eye-slash text-[13px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-2 bg-[#071E3D] hover:bg-[#1F4287] text-white font-bold rounded-[14px] py-4 text-[14px] transition-all duration-300 shadow-[0_8px_20px_rgba(7,30,61,0.2)] hover:shadow-[0_8px_25px_rgba(7,30,61,0.3)] transform hover:-translate-y-0.5">
                        Kirim Pendaftaran Instansi
                    </button>
                </form>

                <!-- Link Kembali ke Login -->
                <p class="text-center text-[13px] text-gray-500 font-medium mt-8 pb-4">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="text-[#278EA5] hover:text-[#071E3D] font-bold underline decoration-2 underline-offset-4 transition-colors ml-1">
                        Masuk Disini
                    </a>
                </p>

            </div>
        </div>

    </div>

    <!-- SCRIPT INTERAKTIF -->
    <script>
        // Logika Toggle Mata Password Dinamis
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

        // Logika Pergantian Form Sesuai Tab
        function setRegType(type) {
            const tabAsn = document.getElementById('tab-asn');
            const tabInstansi = document.getElementById('tab-instansi');
            const formAsn = document.getElementById('form-asn');
            const formInstansi = document.getElementById('form-instansi');

            const activeClass = 'flex-1 py-3 text-[13px] font-bold rounded-[12px] bg-white text-[#071E3D] shadow-sm transition-all duration-300';
            const inactiveClass = 'flex-1 py-3 text-[13px] font-bold rounded-[12px] text-gray-400 hover:text-[#071E3D] transition-all duration-300';

            if (type === 'asn') {
                tabAsn.className = activeClass;
                tabInstansi.className = inactiveClass;
                formAsn.classList.remove('hidden');
                formInstansi.classList.add('hidden');
            } else {
                tabInstansi.className = activeClass;
                tabAsn.className = inactiveClass;
                formInstansi.classList.remove('hidden');
                formAsn.classList.add('hidden');
            }
        }
    </script>
</body>
</html>