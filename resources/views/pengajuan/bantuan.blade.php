@extends('layouts.user')
@section('title', 'Pusat Bantuan')
@section('content')
    <div class="relative overflow-hidden bg-gradient-to-br from-white via-[#f4f8fc] to-[#e0f0ff] rounded-[2.5rem] shadow-[0_15px_40px_-15px_rgba(7,30,61,0.15)] border border-white/60 p-8 md:p-12">
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-5 mb-10 pb-8 border-b border-blue-900/10">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600 text-white flex items-center justify-center text-3xl shadow-lg shrink-0 transform -rotate-3">
                <i class="fa-solid fa-headset"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-[#071E3D]">Pusat Bantuan & Kendala</h2>
                <p class="text-[14px] text-gray-600 font-medium">Laporkan kendala terkait layanan, reset password, atau error sistem.</p>
            </div>
        </div>

        <form action="#" method="POST" enctype="multipart/form-data" class="relative z-10 space-y-6">
            @csrf
            <input type="hidden" name="jenis_layanan" value="bantuan">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                <div class="md:col-span-2">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Kategori Kendala</label>
                    <select name="data_pengajuan[kategori]" required class="w-full bg-white/80 border border-blue-100 rounded-2xl px-5 py-3.5 text-[14px] outline-none focus:border-cyan-400 text-gray-700">
                        <option value="Reset Password">Reset Password / Lupa Sandi</option>
                        <option value="Error Aplikasi">Aplikasi Error / Tidak Bisa Diakses</option>
                        <option value="Kendala Jaringan">Kendala Jaringan Intra</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Deskripsi Detail Kendala</label>
                    <textarea name="data_pengajuan[deskripsi]" required rows="4" placeholder="Jelaskan kendala yang Anda alami secara detail..." class="w-full bg-white/80 border border-blue-100 rounded-2xl px-5 py-3.5 text-[14px] outline-none focus:border-cyan-400 resize-none"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[13px] font-bold text-[#071E3D] mb-2 ml-1">Lampiran / Screenshot (Bila Ada - JPG/PNG/PDF)</label>
                    <input type="file" name="file_pendukung" accept=".jpg,.png,.jpeg,.pdf" class="w-full bg-white/50 border border-blue-100 rounded-2xl px-4 py-3 text-[13px] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[12px] file:font-bold file:bg-[#071E3D] file:text-white cursor-pointer">
                </div>
            </div>

            <div class="pt-6 mt-8 border-t border-blue-900/10 flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-rose-500 to-rose-700 text-white px-10 py-4 rounded-2xl font-bold text-[15px] shadow-lg hover:-translate-y-0.5 transition-all w-full sm:w-auto">Kirim Tiket Bantuan <i class="fa-solid fa-life-ring ml-2"></i></button>
            </div>
        </form>
    </div>
@endsection