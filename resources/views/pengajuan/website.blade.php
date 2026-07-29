@extends('layouts.user')

@section('title', 'Pengajuan Web Desa')

@section('content')
    <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50">
        
        <!-- Header Form -->
        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-100">
            <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-500 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-globe"></i>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-[#071E3D]">Form Pengajuan Website</h2>
                <p class="text-[13px] text-gray-500 font-medium mt-1">Lengkapi data desa atau pesantren untuk pembuatan website resmi.</p>
            </div>
        </div>

        <!-- Form Input -->
        <form action="#" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Instansi / Desa -->
                <div class="md:col-span-2">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2">Nama Desa / Pesantren</label>
                    <input type="text" placeholder="Contoh: Desa Panggong" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] text-gray-700 font-medium focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                </div>

                <!-- Nama Keuchik / Pimpinan -->
                <div>
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2">Nama Kepala Desa / Pimpinan</label>
                    <input type="text" placeholder="Masukkan nama lengkap" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] text-gray-700 font-medium focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all">
                </div>

                <!-- Usulan Domain -->
                <div>
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2">Usulan Nama Domain</label>
                    <div class="flex">
                        <input type="text" placeholder="namadesa" class="w-full bg-gray-50 border border-gray-200 border-r-0 rounded-l-xl px-4 py-3 text-[13px] text-gray-700 font-medium focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-50 outline-none transition-all z-10">
                        <span class="bg-gray-100 border border-gray-200 text-gray-500 font-bold text-[13px] px-4 py-3 rounded-r-xl flex items-center">.desa.id</span>
                    </div>
                </div>

                <!-- Upload SK -->
                <div class="md:col-span-2">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2">Upload SK Kepala Desa / Pimpinan (PDF)</label>
                    <input type="file" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-[13px] text-gray-700 font-medium file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[12px] file:font-bold file:bg-[#071E3D] file:text-white hover:file:bg-[#1F4287] transition-all cursor-pointer">
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4 mt-6 border-t border-gray-100">
                <button type="submit" class="bg-[#071E3D] hover:bg-[#1F4287] text-white px-8 py-3.5 rounded-xl font-bold text-[14px] transition-colors shadow-lg hover:shadow-xl w-full sm:w-auto flex items-center justify-center gap-2">
                    Kirim Pengajuan <i class="fa-solid fa-paper-plane text-[12px]"></i>
                </button>
            </div>
        </form>

    </div>
@endsection