<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sesi Berakhir - Diskominsa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F1F5F9] min-h-screen flex items-center justify-center" style="font-family: 'Outfit', sans-serif;">
    <div class="text-center px-6 max-w-lg">
        <p class="text-8xl font-black text-[#071E3D] tracking-tight">419</p>
        <div class="mt-3 flex items-center justify-center">
            <i class="fa-solid fa-clock-rotate-left text-3xl text-amber-500"></i>
        </div>
        <p class="mt-4 text-xl font-extrabold text-slate-800">Sesi Berakhir</p>
        <p class="mt-2 text-sm text-slate-600 font-medium">Sesi Anda telah berakhir atau halaman terlalu lama terbuka. Silakan muat ulang halaman untuk melanjutkan aktivitas.</p>
        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <button onclick="location.reload()" class="inline-flex items-center gap-2 px-6 py-3 bg-[#071E3D] hover:bg-[#1F4287] text-white font-bold rounded-xl shadow-lg transition-colors">
                <i class="fa-solid fa-rotate-right"></i> Muat Ulang Halaman
            </button>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold rounded-xl transition-colors">
                <i class="fa-solid fa-house"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
