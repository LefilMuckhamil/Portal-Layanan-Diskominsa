<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Portal Layanan Diskominsa') }} - Lupa Kata Sandi</title>
    
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
        
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #f9fafb inset !important;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 selection:bg-cyan-300 selection:text-[#071E3D] relative overflow-hidden">

    <!-- Ornamen Background -->
    <div class="absolute top-0 left-0 w-full h-80 bg-[#071E3D] -skew-y-6 origin-top-left -z-10 rounded-br-[100px] opacity-90"></div>
    <div class="absolute -top-20 right-10 w-64 h-64 bg-cyan-400 rounded-full mix-blend-screen filter blur-[100px] opacity-30 -z-10"></div>

    <!-- KONTENER KARTU TENGAH (Dilebarkan kesamping sedikit: max-w-[540px] & padding lebih efisien) -->
    <div class="bg-white w-full max-w-[540px] rounded-[2rem] shadow-[0_20px_50px_-15px_rgba(0,0,0,0.12)] p-6 sm:p-8 relative z-10 border border-gray-50">
        
        <!-- Logo & Header (Dibuat lebih ringkas agar tombol bawah tidak terhalang) -->
        <div class="flex items-center gap-4 mb-6 pb-5 border-b border-gray-100">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo" class="h-10 w-auto object-contain">
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-[#071E3D] tracking-tight">Permohonan Reset Sandi</h2>
                <p class="text-[12px] text-gray-500 font-medium">Admin akan memverifikasi dan mengirimkan sandi baru via WhatsApp.</p>
            </div>
        </div>

        <!-- Notifikasi Status -->
        @if (session('status'))
            <div class="mb-5 p-3.5 bg-green-50 border border-green-100 text-green-600 text-[13px] rounded-xl font-bold flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-xl flex-shrink-0"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Form Pengajuan -->
        <form action="#" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Input Email -->
                <div class="sm:col-span-2">
                    <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Email Terdaftar</label>
                    <div class="relative">
                        <input type="text" name="email" required value="{{ old('email') }}" placeholder="nama@email.com"
                            class="w-full bg-gray-50 border border-gray-100 rounded-[12px] pl-10 pr-4 py-3 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        <div class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fa-regular fa-envelope text-[14px]"></i>
                        </div>
                    </div>
                </div>

                <!-- Input Nomor WhatsApp -->
                <div class="sm:col-span-2">
                    <label class="block text-[12px] font-bold text-[#071E3D] mb-1.5 ml-1">Nomor WhatsApp Aktif</label>
                    <div class="relative">
                        <input type="tel" name="phone" required value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                            class="w-full bg-gray-50 border border-gray-100 rounded-[12px] pl-10 pr-4 py-3 text-[13px] text-[#071E3D] font-medium placeholder-gray-400 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                        <div class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-green-600">
                            <i class="fa-brands fa-whatsapp text-[16px]"></i>
                        </div>
                    </div>
                    <span class="text-[11px] text-gray-400 mt-1 ml-1 block">Password baru akan dikirimkan ke nomor WhatsApp ini setelah diverifikasi.</span>
                </div>
            </div>

            <!-- Tombol Kirim Permohonan -->
            <button type="submit" class="w-full mt-2 flex items-center justify-center gap-2 bg-[#071E3D] hover:bg-[#1F4287] text-white font-bold rounded-[12px] py-3.5 text-[14px] transition-all duration-300 shadow-[0_6px_15px_rgba(7,30,61,0.2)] hover:shadow-[0_8px_20px_rgba(7,30,61,0.3)] transform hover:-translate-y-0.5">
                Kirim Permohonan ke Admin <i class="fa-solid fa-paper-plane text-[11px] ml-1"></i>
            </button>
        </form>

        <!-- Link Kembali (Aman dari terhalang karena posisi card lebih proporsional) -->
        <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between text-[13px]">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 font-bold text-gray-500 hover:text-[#278EA5] transition-colors">
                <i class="fa-solid fa-arrow-left-long"></i> Kembali ke Masuk
            </a>
            <span class="text-gray-400 text-[11px]">Butuh bantuan? Hubungi Diskominsa</span>
        </div>

    </div>

</body>
</html>