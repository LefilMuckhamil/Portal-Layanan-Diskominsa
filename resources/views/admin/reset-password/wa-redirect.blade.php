<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Membuka WhatsApp</title>
    <link rel="icon" type="image/png" href="{{ asset('image/icon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-sm w-full text-center">
        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mx-auto mb-4">
            <i class="fa-brands fa-whatsapp"></i>
        </div>
        <h2 class="text-lg font-extrabold text-[#071E3D] mb-2">Reset Password Berhasil</h2>
        <p class="text-sm text-gray-500 font-medium mb-6">Kata sandi baru telah dikirimkan. Anda akan diarahkan ke WhatsApp secara otomatis.</p>

        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-6 py-3 rounded-xl transition-colors shadow-md">
            <i class="fa-brands fa-whatsapp text-lg"></i> Buka WhatsApp Sekarang
        </a>

        <div class="mt-5 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.reset-password.index') }}" class="text-xs font-bold text-gray-400 hover:text-[#071E3D] transition-colors">
                <i class="fa-solid fa-arrow-left-long mr-1"></i> Kembali ke Daftar Pengajuan
            </a>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            var link = document.querySelector('a[target="_blank"]');
            if (link) { link.click(); }
        });
    </script>
</body>
</html>
