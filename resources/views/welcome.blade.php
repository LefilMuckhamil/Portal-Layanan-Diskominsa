<!DOCTYPE html>
<html lang="id" class="scroll-smooth scroll-pt-24">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Layanan Diskominsa Aceh Barat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { 'primary-dark': '#071E3D', 'primary-light': '#1F4287', } } } }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-15px); } 100% { transform: translateY(0px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased overflow-x-hidden">

    @include('partials.navbar')
    @include('partials.beranda')
    @include('partials.tentang')
    @include('partials.layanan')
    @include('partials.alur')
    @include('partials.tracking')
    @include('partials.hero')
    @include('partials.bantuan')
    @include('partials.footer')

</body>
</html>