@extends('layouts.user')

@section('title', 'Pusat Bantuan')

@section('content')
    <!-- KONTENER UTAMA DENGAN GRADASI & SHADOW -->
    <div class="relative overflow-hidden bg-gradient-to-br from-white via-[#f4f8fc] to-[#e0f0ff] rounded-[2.5rem] shadow-[0_15px_40px_-15px_rgba(7,30,61,0.15)] border border-white/60 p-8 md:p-12">
        
        <!-- Ornamen Background (Cahaya Blur) -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-gradient-to-br from-rose-300/20 to-rose-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-gradient-to-tr from-rose-300/20 to-transparent rounded-full blur-2xl pointer-events-none"></div>

        <!-- Header Form -->
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-5 mb-10 pb-8 border-b border-rose-900/10">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600 text-white flex items-center justify-center text-3xl shadow-lg shadow-rose-500/30 shrink-0 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                <i class="fa-solid fa-headset"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-[#071E3D] tracking-tight mb-1">Pusat Bantuan</h2>
                <p class="text-[14px] text-gray-600 font-medium">Laporkan kendala terkait layanan, permohonan reset password</p>
            </div>
        </div>

        <!-- Form Input -->
      <form action="{{ route('user.pengajuan.store', 'bantuan') }}" method="POST" enctype="multipart/form-data" class="relative z-10 space-y-6" onsubmit="disableSubmitButton(this)">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-7">

                <!-- Kategori Kendala -->
                <div class="group md:col-span-2">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Kategori Kendala</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-rose-500 transition-colors z-10">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <select name="kategori" required class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-rose-400 focus:ring-4 focus:ring-rose-400/10 outline-none transition-all shadow-sm appearance-none cursor-pointer">
                            <option value="Reset Password" selected>Reset Password</option>
                            <!-- Kategori lain bisa ditambahkan via Database nantinya -->
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Nama Lengkap -->
                <div class="group">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Nama Lengkap Pemohon</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-rose-500 transition-colors">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input type="text" name="nama" required placeholder="Masukkan nama lengkap" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-rose-400 focus:ring-4 focus:ring-rose-400/10 outline-none transition-all shadow-sm">
                    </div>
                </div>

                <!-- NIP -->
                <div class="group">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">NIP (18 Digit)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-rose-500 transition-colors">
                            <i class="fa-solid fa-id-badge"></i>
                        </div>
                       <input type="text" name="nip" required placeholder="Masukkan NIP" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-rose-400 focus:ring-4 focus:ring-rose-400/10 outline-none transition-all shadow-sm">
                    </div>
                </div>

                <!-- Email Bermasalah -->
                <div class="group md:col-span-2">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Email Resmi yang Ingin Direset</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-rose-500 transition-colors">
                            <i class="fa-solid fa-envelope-open-text"></i>
                        </div>
                        <input type="email" name="email" required placeholder="email@acehbaratkab.go.id" class="w-full bg-white/80 backdrop-blur-sm border border-blue-100 rounded-2xl pl-11 pr-4 py-3.5 text-[14px] text-gray-700 font-medium focus:bg-white focus:border-rose-400 focus:ring-4 focus:ring-rose-400/10 outline-none transition-all shadow-sm">
                    </div>
                    <p class="text-[11px] text-gray-500 mt-2 ml-1"><i class="fa-solid fa-circle-info mr-1"></i> Pastikan alamat email yang dimasukkan sudah benar.</p>
                </div>

                <!-- Upload Surat (Desain Drag & Drop) -->
                <div class="md:col-span-2">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Upload Surat Permohonan Reset Password (PDF)</label>
                    <div class="relative flex justify-center px-6 pt-6 pb-7 border-2 border-rose-200 border-dashed rounded-2xl hover:border-rose-400 hover:bg-rose-50/50 transition-colors bg-white/50 group">
                        <div class="space-y-2 text-center">
                            <!-- Ikon Upload -->
                            <div class="w-12 h-12 mx-auto bg-rose-50 text-rose-500 rounded-full flex items-center justify-center group-hover:bg-rose-100 group-hover:text-rose-600 transition-colors mb-3">
                                <i class="fa-solid fa-file-pdf text-xl"></i>
                            </div>
                            <div class="flex flex-col sm:flex-row items-center text-sm text-gray-600 justify-center gap-1">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-bold text-rose-600 hover:text-rose-500 focus-within:outline-none px-1">
                                    <span>Klik untuk memilih file</span>
                                    <input id="file-upload" name="file_surat" type="file" class="sr-only" accept=".pdf" required onchange="document.getElementById('file-name').innerText = this.files[0].name">
                                </label>
                                <p>atau drag and drop di sini</p>
                            </div>
                            <p id="file-name" class="text-xs text-gray-400 font-medium mt-1">Hanya format PDF. Maksimal 2MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-6 mt-8 border-t border-rose-900/10 flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-rose-500 to-rose-700 hover:from-rose-600 hover:to-rose-800 text-white px-10 py-4 rounded-2xl font-bold text-[15px] transition-all duration-300 shadow-[0_8px_20px_rgba(225,29,72,0.2)] hover:shadow-[0_8px_25px_rgba(225,29,72,0.3)] hover:-translate-y-0.5 w-full sm:w-auto flex items-center justify-center gap-3">
                    Kirim Tiket Bantuan <i class="fa-solid fa-paper-plane"></i>
                </button>
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