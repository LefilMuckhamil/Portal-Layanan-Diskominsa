<!DOCTYPE html>
<html lang="id" class="scroll-smooth scroll-pt-24">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Layanan Diskominsa Aceh Barat</title>
    <link rel="icon" type="image/png" href="{{ asset('image/icon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: { 'primary-dark': '#040914', 'card-dark': '#0B1528', 'accent-cyan': '#22d3ee' }
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes textGlowAnimated {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .animate-text-glow {
            background-size: 200% auto;
            animation: textGlowAnimated 6s linear infinite;
        }
        .magnet-text {
            transition: transform 0.2s cubic-bezier(0.25, 1, 0.5, 1);
            display: inline-block;
        }
    </style>
</head>
<body class="bg-[#040914] text-white font-sans antialiased overflow-x-hidden selection:bg-cyan-400 selection:text-[#040914]">

    @include('partials.navbar')
    @include('partials.beranda')
    @include('partials.tentang')
    @include('partials.layanan')
    @include('partials.alur')
    @include('partials.tracking')
    @include('partials.bantuan')
    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.magnet-text').forEach(el => {
                el.addEventListener('mousemove', e => {
                    const r = el.getBoundingClientRect();
                    el.style.transform = `translate(${(e.clientX - r.left - r.width / 2) * 0.12}px, ${(e.clientY - r.top - r.height / 2) * 0.12}px)`;
                });
                el.addEventListener('mouseleave', () => el.style.transform = 'translate(0px, 0px)');
            });
        });
    </script>
</body>
</html>