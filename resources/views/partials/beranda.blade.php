<!-- Bagian Hero / Beranda -->
<section id="beranda" class="relative bg-[#071E3D] bg-gradient-to-br from-[#071E3D] to-[#1F4287] overflow-hidden pb-20 pt-10">
    <!-- Ornamen Background -->
    <div class="absolute top-10 left-10 w-96 h-96 bg-[#1F4287] rounded-full mix-blend-screen filter blur-[100px] opacity-40"></div>
    <div class="absolute bottom-10 right-20 w-80 h-80 bg-blue-400 rounded-full mix-blend-screen filter blur-[120px] opacity-20"></div>

    <div class="container mx-auto px-6 relative z-20 flex flex-col lg:flex-row items-center">
        
        <!-- Konten Kiri -->
        <div class="w-full lg:w-1/2 text-white pr-0 lg:pr-4 mt-10">
            <span class="bg-white/10 border border-white/20 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider mb-6 inline-block backdrop-blur-sm">Portal Layanan Terpadu</span>
            <h2 class="text-4xl lg:text-[55px] font-extrabold mb-6 leading-tight">
                Portal Layanan <br>
                <span class="text-cyan-400">DISKOMINSA</span><br>
                Aceh Barat
            </h2>
            <p class="mb-10 text-lg opacity-90 max-w-lg leading-relaxed">
                Solusi layanan digital terpadu satu pintu untuk seluruh ASN dan Instansi Pemerintah di lingkungan Kabupaten Aceh Barat.
            </p>
            
            <!-- Tombol Aksi -->
            <div class="flex flex-wrap gap-4 mb-12">
                <a href="#layanan" class="bg-cyan-400 hover:bg-cyan-300 text-[#071E3D] px-6 py-3 rounded-xl font-bold shadow-lg flex items-center gap-2 transition">
                    Ajukan Layanan
                </a>
                
                @auth
                    <!-- Tombol Cek Tracking (Sudah Login) -->
                    <a href="{{ route('user.riwayat') }}" class="border border-white/50 hover:border-white hover:bg-white/10 text-white px-6 py-3 rounded-xl font-semibold transition flex items-center gap-2">
                        Cek Tracking
                    </a>
                @else
                    <!-- Tombol Cek Tracking (Belum Login memicu Modal) -->
                    <button type="button" onclick="openAuthModal()" class="border border-white/50 hover:border-white hover:bg-white/10 text-white px-6 py-3 rounded-xl font-semibold transition flex items-center gap-2">
                        Cek Tracking
                    </button>
                @endauth
            </div>
        </div>
        
        <!-- Mockup Kanan -->
        <div class="w-full lg:w-1/2 mt-16 lg:mt-0 relative z-20 animate-float">
            <div class="relative max-w-[550px] mx-auto w-full">
                <div class="bg-black p-2 md:p-3 rounded-t-3xl border-4 border-gray-800 shadow-2xl relative z-10 mx-4 md:mx-0">
                    <div class="absolute top-1.5 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 bg-gray-600 rounded-full"></div>
                    <div class="bg-white rounded-lg overflow-hidden h-48 md:h-[280px] relative w-full flex">
                        <div class="w-1/4 bg-[#071E3D] h-full p-2 border-r border-gray-200 hidden md:block">
                            <div class="w-6 h-6 bg-white/20 rounded-full mb-4"></div>
                            <div class="w-full h-2 bg-white/20 rounded mb-2"></div>
                            <div class="w-3/4 h-2 bg-white/20 rounded mb-2"></div>
                        </div>
                        <div class="flex-1 bg-gray-50 p-4">
                            <div class="w-32 h-4 bg-gray-200 rounded mb-4"></div>
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <div class="bg-white p-2 rounded shadow border border-gray-100 text-center"><div class="text-xs text-gray-400">Total</div><div class="font-bold text-[#1F4287]">120</div></div>
                                <div class="bg-white p-2 rounded shadow border border-gray-100 text-center"><div class="text-xs text-gray-400">Proses</div><div class="font-bold text-cyan-500">56</div></div>
                                <div class="bg-white p-2 rounded shadow border border-gray-100 text-center"><div class="text-xs text-gray-400">Selesai</div><div class="font-bold text-green-500">98%</div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative z-0">
                    <div class="bg-gray-300 h-3 w-full rounded-t-sm rounded-b-xl shadow-lg relative z-20"></div>
                    <div class="bg-gray-400 h-1.5 w-[90%] mx-auto rounded-b-xl shadow-xl relative z-10"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Peringatan Login -->
<div id="authModal" class="fixed inset-0 z-[100] hidden items-center justify-center">
    <!-- Latar belakang gelap (klik area gelap untuk tutup) -->
    <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="closeAuthModal()"></div>
    
    <!-- Kotak Modal -->
    <div class="relative bg-white rounded-[2rem] p-8 max-w-md w-full mx-4 shadow-2xl transform scale-95 transition-all">
        
        <!-- LOGO DISKOMINSA -->
        <div class="flex items-center justify-center mx-auto mb-6">
            <img src="{{ asset('image/kominsa_biru.png') }}" alt="Logo Diskominsa" class="h-12 w-auto object-contain drop-shadow-sm">
        </div>
        
        <h3 class="text-2xl font-extrabold text-center text-[#071E3D] mb-2">Akses Dibatasi</h3>
        <p class="text-center text-gray-500 text-[14px] mb-8 leading-relaxed">
            Maaf, Anda harus Login terlebih dahulu untuk dapat menggunakan fitur E-Tracking.
        </p>
        
        <div class="flex gap-3">
            <button type="button" onclick="closeAuthModal()" class="flex-1 py-3.5 rounded-xl font-bold text-gray-500 bg-gray-50 hover:bg-gray-100 transition-colors">
                Batal
            </button>
            <a href="{{ route('login') }}" class="flex-1 py-3.5 rounded-xl font-bold text-white bg-cyan-500 hover:bg-cyan-600 text-center transition-colors shadow-lg">
                Masuk Sekarang
            </a>
        </div>
    </div>
</div>

<script>
    function openAuthModal() {
        const modal = document.getElementById('authModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeAuthModal() {
        const modal = document.getElementById('authModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>