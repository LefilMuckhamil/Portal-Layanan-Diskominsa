@extends('layouts.user')

@section('title', 'Pengajuan Web Desa')

@section('content')
    <!-- KONTENER UTAMA DENGAN GRADASI & SHADOW -->
    <div class="relative overflow-hidden bg-gradient-to-br from-white via-[#f4f8fc] to-[#e0f0ff] rounded-[2.5rem] shadow-[0_15px_40px_-15px_rgba(7,30,61,0.15)] border border-white/60 p-8 md:p-12">
        
        <!-- Ornamen Background (Cahaya Blur) -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-gradient-to-br from-cyan-300/20 to-blue-400/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-gradient-to-tr from-cyan-300/20 to-transparent rounded-full blur-2xl pointer-events-none"></div>

        <!-- Header Form -->
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-5 mb-10 pb-8 border-b border-blue-900/10">
            <!-- Ikon Header Bergradasi -->
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-[#1F4287] text-white flex items-center justify-center text-3xl shadow-lg shadow-cyan-500/30 shrink-0 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                <i class="fa-solid fa-globe"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-[#071E3D] tracking-tight mb-1">Form Pengajuan Website</h2>
                <p class="text-[14px] text-gray-600 font-medium">Lengkapi data desa atau pesantren untuk pembuatan website resmi instansi Anda.</p>
            </div>
        </div>

        <!-- Form Input -->
        <form action="#" method="POST" class="relative z-10 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                
                <!-- Nama Instansi / Desa -->
                <div class="md:col-span-2 group">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Nama Desa atau Pesantren</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-cyan-500 transition-colors">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <input type="text" placeholder="Contoh: Desa Panggong" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/10 outline-none transition-all shadow-sm">
                    </div>
                </div>

                <!-- Nama Keuchik / Pimpinan -->
                <div class="group">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Nama Kepala Desa / Pimpinan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-cyan-500 transition-colors">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <input type="text" placeholder="Masukkan nama lengkap" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/10 outline-none transition-all shadow-sm">
                    </div>
                </div>

                <!-- Usulan Domain -->
                <div class="group">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Usulan Nama Domain</label>
                    <div class="flex shadow-sm rounded-2xl">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-cyan-500 transition-colors z-20">
                                <i class="fa-solid fa-link"></i>
                            </div>
                            <input type="text" placeholder="namadesa" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 border-r-0 rounded-l-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/10 outline-none transition-all z-10">
                        </div>
                        <span class="bg-gray-100/80 backdrop-blur-sm border border-blue-100 text-gray-500 font-bold text-[14px] px-5 py-3.5 rounded-r-2xl flex items-center border-l-0">.desa.id</span>
                    </div>
                </div>

                <!-- Upload SK (Desain Drag & Drop) -->
                <div class="md:col-span-2">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Upload SK Kepala Desa / Pimpinan (PDF)</label>
                    <div class="relative flex justify-center px-6 pt-6 pb-7 border-2 border-blue-200 border-dashed rounded-2xl hover:border-cyan-400 hover:bg-cyan-50/50 transition-colors bg-white/50 group">
                        <div class="space-y-2 text-center">
                            <!-- Ikon Upload -->
                            <div class="w-12 h-12 mx-auto bg-blue-50 text-blue-500 rounded-full flex items-center justify-center group-hover:bg-cyan-100 group-hover:text-cyan-600 transition-colors mb-3">
                                <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                            </div>
                            <div class="flex flex-col sm:flex-row items-center text-sm text-gray-600 justify-center gap-1">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-bold text-cyan-600 hover:text-cyan-500 focus-within:outline-none px-1">
                                    <span>Klik untuk memilih file</span>
                                    <input id="file-upload" name="file-upload" type="file" class="sr-only" accept=".pdf">
                                </label>
                                <p>atau drag and drop di sini</p>
                            </div>
                            <p class="text-xs text-gray-400 font-medium mt-1">Hanya format PDF. Maksimal 2MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-6 mt-8 border-t border-blue-900/10 flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-[#071E3D] to-[#1F4287] hover:from-[#1F4287] hover:to-[#278EA5] text-white px-10 py-4 rounded-2xl font-bold text-[15px] transition-all duration-300 shadow-[0_8px_20px_rgba(7,30,61,0.2)] hover:shadow-[0_8px_25px_rgba(7,30,61,0.3)] hover:-translate-y-0.5 w-full sm:w-auto flex items-center justify-center gap-3">
                    Kirim Pengajuan <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </form>

    </div>
@endsection