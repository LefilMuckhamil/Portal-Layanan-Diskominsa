<!DOCTYPE html>
<html lang="id" class="scroll-smooth scroll-pt-24">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Layanan Diskominsa Aceh Barat</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%230F172A'/><path d='M30 30H70V38H30V30Z' fill='%23D97706'/><path d='M30 46H70V70C70 72.2091 68.2091 74 66 74H34C31.7909 74 30 72.2091 30 70V46Z' fill='none' stroke='%23F59E0B' stroke-width='4'/><path d='M42 56H58' stroke='%23F59E0B' stroke-width='4' stroke-linecap='round'/><circle cx='50' cy='64' r='2.5' fill='%23F59E0B'/></svg>">
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