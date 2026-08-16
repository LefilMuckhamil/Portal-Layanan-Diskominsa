<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Portal Layanan & E-Tracking') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('image/icon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#f4f7f6] text-gray-800 h-screen flex overflow-hidden selection:bg-cyan-300 selection:text-[#071E3D]">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-full relative overflow-hidden">
    
        @include('admin.partials.navbar')

        <div class="flex-1 overflow-y-auto p-8 space-y-6">
            @if(session('sukses'))
            <div id="flash-sukses" class="flex items-center justify-between gap-3 p-4 rounded-2xl border-2 border-emerald-200 bg-emerald-50 text-emerald-800 shadow-sm">
                <p class="text-[13px] font-bold flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> {{ session('sukses') }}
                </p>
                <button type="button" onclick="document.getElementById('flash-sukses').remove()" class="text-emerald-500 hover:text-emerald-800 transition-colors cursor-pointer" aria-label="Tutup notifikasi">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div id="flash-error" class="flex items-center justify-between gap-3 p-4 rounded-2xl border-2 border-rose-200 bg-rose-50 text-rose-800 shadow-sm">
                <p class="text-[13px] font-bold flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </p>
                <button type="button" onclick="document.getElementById('flash-error').remove()" class="text-rose-500 hover:text-rose-800 transition-colors cursor-pointer" aria-label="Tutup notifikasi">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @yield('content')
        </div>

    </main>

</body>
</html>