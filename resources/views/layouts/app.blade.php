<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Layanan Diskominsa Aceh Barat</title>
    <link rel="icon" type="image/png" href="{{ asset('image/icon.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-dark': '#071E3D',
                        'primary-light': '#1F4287',
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
    </style>
</head>
<body class="bg-white text-gray-800 font-sans antialiased flex flex-col min-h-screen">
    <nav class="bg-white border-b-2 border-primary-dark sticky top-0 z-50 transition-all duration-300 hover:shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-black text-primary-dark tracking-tighter">
                DISKOMINSA<span class="text-primary-light">.</span>
            </h1>
            
            <div class="hidden md:flex items-center space-x-8 font-semibold text-primary-dark">
                <a href="#layanan" class="hover:text-primary-light transition-colors duration-300">Layanan</a>
                <a href="#tracking" class="hover:text-primary-light transition-colors duration-300">E-Tracking</a>
    
                <a href="#" class="bg-primary-dark text-white hover:bg-primary-light px-6 py-2 rounded-none border-2 border-primary-dark hover:border-primary-light transition-all duration-300 transform hover:-translate-y-1">
                    Login Sistem
                </a>
            </div>
        </div>
    </nav>

    <main class="grow">
        @yield('content')
    </main>

    <footer class="bg-primary-dark text-white border-t-4 border-primary-light mt-auto">
        <div class="container mx-auto px-6 py-8 text-center md:text-left flex flex-col md:flex-row justify-between items-center">
            <p class="font-bold text-lg mb-2 md:mb-0">Diskominsa Aceh Barat</p>
            <p class="text-sm opacity-80">&copy; {{ date('Y') }} Sistem Portal Layanan G2G. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>