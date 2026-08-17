<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akses Ditolak - Diskominsa</title>
    <link rel="icon" type="image/png" href="{{ asset('image/icon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#F1F5F9] min-h-screen flex items-center justify-center" style="font-family: 'Outfit', sans-serif;">
    <div class="text-center px-6 max-w-lg">
        <p class="text-8xl font-black text-[#071E3D] tracking-tight">403</p>
        <div class="mt-3 flex items-center justify-center">
            <i class="fa-solid fa-lock text-3xl text-rose-500"></i>
        </div>
        <p class="mt-4 text-xl font-extrabold text-slate-800">Akses Ditolak</p>
        <p class="mt-2 text-sm text-slate-600 font-medium">Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika Anda merasa ini sebuah kesalahan.</p>
        <a href="{{ url('/') }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-[#071E3D] hover:bg-[#1F4287] text-white font-bold rounded-xl shadow-lg transition-colors">
            <i class="fa-solid fa-house"></i> Kembali ke Beranda
        </a>
    </div>
</body>
</html>
