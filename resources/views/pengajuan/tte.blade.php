@extends('layouts.user')

@section('title', 'Pengajuan TTE')

@section('content')
    <!-- KONTENER UTAMA DENGAN GRADASI & SHADOW -->
    <div class="relative overflow-hidden bg-gradient-to-br from-white via-[#f4f8fc] to-[#e0f0ff] rounded-[2.5rem] shadow-[0_15px_40px_-15px_rgba(7,30,61,0.15)] border border-white/60 p-8 md:p-12">
        
        <!-- Ornamen Background (Cahaya Blur) -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-gradient-to-br from-cyan-300/20 to-blue-400/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-gradient-to-tr from-cyan-300/20 to-transparent rounded-full blur-2xl pointer-events-none"></div>

        <!-- Header Form -->
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-5 mb-10 pb-8 border-b border-blue-900/10">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-[#1F4287] text-white flex items-center justify-center text-3xl shadow-lg shadow-cyan-500/30 shrink-0 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                <i class="fa-solid fa-pen-nib"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-[#071E3D] tracking-tight mb-1">Layanan TTE</h2>
                <p class="text-[14px] text-gray-600 font-medium">Fasilitasi penerbitan Tanda Tangan Elektronik tersertifikasi BSrE.</p>
            </div>
        </div>

        <!-- Form Input -->
        <!-- Action disesuaikan ke route store dengan fitur anti double-submit -->
                <form action="{{ route('user.pengajuan.store', 'tte') }}" method="POST" enctype="multipart/form-data" class="relative z-10 space-y-6" onsubmit="disableSubmitButton(this)">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                    
                    <!-- Nama Lengkap -->
                    <div class="group">
                        <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Nama Lengkap (beserta Gelar)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-cyan-500 transition-colors">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <!-- UBAH: name="data_pengajuan[nama]" -->
                            <input type="text" name="data_pengajuan[nama]" required placeholder="Contoh: Budi Santoso, S.Kom" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/10 outline-none transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- NIP -->
                    <div class="group">
                        <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">NIP (18 Digit)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-cyan-500 transition-colors">
                                <i class="fa-solid fa-id-badge"></i>
                            </div>
                            <!-- UBAH: name="data_pengajuan[nip]" -->
                            <input type="number" name="data_pengajuan[nip]" required placeholder="Masukkan NIP" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/10 outline-none transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Instansi / SKPK -->
                    <div class="group">
                        <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Instansi / Unit Kerja</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-cyan-500 transition-colors">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <!-- UBAH: name="data_pengajuan[instansi]" -->
                            <input type="text" name="data_pengajuan[instansi]" required placeholder="Contoh: Dinas Komunikasi dan Informatika" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/10 outline-none transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Nomor HP / WA -->
                    <div class="group">
                        <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Nomor HP / WhatsApp</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-cyan-500 transition-colors">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <!-- UBAH: name="data_pengajuan[no_hp]" -->
                            <input type="number" name="data_pengajuan[no_hp]" required placeholder="Contoh: 081234567890" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/10 outline-none transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Email Aktif -->
                    <div class="group">
                        <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Email Aktif</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-cyan-500 transition-colors">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <!-- UBAH: name="data_pengajuan[email]" -->
                            <input type="email" name="data_pengajuan[email]" required placeholder="email@acehbaratkab.go.id" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/10 outline-none transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Alamat Domisili -->
                    <div class="group">
                        <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Alamat Domisili</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-cyan-500 transition-colors">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </div>
                            <!-- UBAH: name="data_pengajuan[alamat]" -->
                            <input type="text" name="data_pengajuan[alamat]" required placeholder="Masukkan alamat lengkap" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/10 outline-none transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Upload Surat (Desain Drag & Drop Mewah) -->
                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Upload Dokumen Persyaratan TTE (PDF)</label>
                        <div class="relative flex justify-center px-6 pt-6 pb-7 border-2 border-blue-200 border-dashed rounded-2xl hover:border-cyan-400 hover:bg-cyan-50/50 transition-colors bg-white/50 group">
                            <div class="space-y-2 text-center">
                                <!-- Ikon Upload -->
                                <div class="w-12 h-12 mx-auto bg-blue-50 text-blue-500 rounded-full flex items-center justify-center group-hover:bg-cyan-100 group-hover:text-cyan-600 transition-colors mb-3">
                                    <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                                </div>
                                <div class="flex flex-col sm:flex-row items-center text-sm text-gray-600 justify-center gap-1">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-bold text-cyan-600 hover:text-cyan-500 focus-within:outline-none px-1">
                                        <span>Klik untuk memilih file</span>
                                        <!-- UBAH: name="file_pendukung" -->
                                        <input id="file-upload" name="file_pendukung" type="file" class="sr-only" accept=".pdf" required onchange="document.getElementById('file-name').innerText = this.files[0].name">
                                    </label>
                                    <p>atau drag and drop di sini</p>
                                </div>
                                <p id="file-name" class="text-xs text-gray-400 font-medium mt-1">Hanya format PDF (Surat Rekomendasi/KTP). Maksimal 2MB.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Submit (Sesuaikan jika terpotong di kodemu) -->
                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-[#071E3D] hover:bg-[#1F4287] text-white px-8 py-3.5 rounded-xl font-bold transition-all shadow-lg shadow-blue-900/20 text-[14px]">
                        Kirim Pengajuan <i class="fa-solid fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>
    </div>
        </form>
    </div>

    <!-- Script Anti Double Submit -->
    <script>
        function disableSubmitButton(form) {
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btn.innerHTML = 'Memproses... <i class="fa-solid fa-spinner fa-spin ml-2"></i>';
        }
    </script>
@endsection