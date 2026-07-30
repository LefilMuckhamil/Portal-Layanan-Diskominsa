@extends('layouts.user')
@section('title', 'Pengajuan Email Resmi')
@section('content')
    <div class="relative overflow-hidden bg-gradient-to-br from-white via-[#f4f8fc] to-[#e0f0ff] rounded-[2.5rem] shadow-[0_15px_40px_-15px_rgba(7,30,61,0.15)] border border-white/60 p-8 md:p-12">
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-5 mb-10 pb-8 border-b border-blue-900/10">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-[#1F4287] text-white flex items-center justify-center text-3xl shadow-lg shadow-cyan-500/30 shrink-0 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-[#071E3D] tracking-tight mb-1">Pengajuan Email Resmi</h2>
                <p class="text-[14px] text-gray-600 font-medium">Pembuatan akun email @acehbaratkab.go.id untuk ASN Pemerintah.</p>
            </div>
        </div>

        <form action="#" method="POST" enctype="multipart/form-data" class="relative z-10 space-y-6">
            @csrf
            <!-- Input disembunyikan untuk menandai jenis layanan -->
            <input type="hidden" name="jenis_layanan" value="email">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                <div class="group">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Nama Lengkap (Gelar)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fa-solid fa-user"></i></div>
                        <input type="text" name="data_pengajuan[nama_lengkap]" required class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/10 outline-none shadow-sm">
                    </div>
                </div>

                <div class="group">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">NIP (18 Digit)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fa-solid fa-id-card"></i></div>
                        <input type="number" name="data_pengajuan[nip]" required class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] focus:border-cyan-400 outline-none shadow-sm">
                    </div>
                </div>

                <div class="group">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Jabatan & Instansi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"><i class="fa-solid fa-briefcase"></i></div>
                        <input type="text" name="data_pengajuan[jabatan]" required placeholder="Cth: Kepala Bidang di Diskominsa" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] focus:border-cyan-400 outline-none shadow-sm">
                    </div>
                </div>

                <div class="group">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Usulan Alamat Email</label>
                    <div class="flex shadow-sm rounded-2xl">
                        <input type="text" name="data_pengajuan[usulan_email]" required placeholder="namasaya" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 border-r-0 rounded-l-2xl px-4 py-3.5 text-[14px] focus:border-cyan-400 z-10 outline-none">
                        <span class="bg-gray-100/80 border border-blue-100 text-gray-500 font-bold text-[13px] px-4 py-3.5 rounded-r-2xl border-l-0">@acehbaratkab.go.id</span>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Upload Surat Permohonan (PDF)</label>
                    <input type="file" name="file_pendukung" required accept=".pdf" class="w-full bg-white/50 border border-blue-100 rounded-2xl px-4 py-3 text-[13px] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[12px] file:font-bold file:bg-[#071E3D] file:text-white hover:file:bg-[#1F4287] cursor-pointer">
                </div>
            </div>

            <div class="pt-6 mt-8 border-t border-blue-900/10 flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-[#071E3D] to-[#1F4287] hover:from-[#1F4287] hover:to-[#278EA5] text-white px-10 py-4 rounded-2xl font-bold text-[15px] shadow-lg hover:-translate-y-0.5 transition-all w-full sm:w-auto">
                    Kirim Pengajuan <i class="fa-solid fa-paper-plane ml-2"></i>
                </button>
            </div>
        </form>
    </div>
@endsection