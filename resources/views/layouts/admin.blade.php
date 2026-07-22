<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Portal Layanan & E-Tracking') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Google & FontAwesome -->
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

    <!-- Panggil Sidebar khusus Admin -->
    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-full relative overflow-hidden">
        
        <!-- Panggil Navbar khusus Admin -->
        @include('admin.partials.navbar')

        <!-- Area Konten Dinamis -->
        <div class="flex-1 overflow-y-auto p-8 space-y-6">
            @yield('content')
        </div>

    </main>

</body>
</html>