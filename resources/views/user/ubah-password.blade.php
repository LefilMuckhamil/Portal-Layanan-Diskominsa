@extends('layouts.user')

@section('title', 'Ubah Kata Sandi')

@section('content')

    <div class="max-w-xl mx-auto animate-fade-in-down">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-[#16324F] text-cyan-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-[#16324F]/20 text-2xl">
                <i class="fa-solid fa-key"></i>
            </div>
            <h1 class="text-2xl font-black text-[#101828]">Ubah Kata Sandi</h1>
            <p class="text-[13px] text-[#667085] font-bold mt-1">Ganti kata sandi akun Anda secara mandiri demi keamanan.</p>
        </div>

        <div class="bg-white rounded-2xl p-7 sm:p-9 shadow-[0_2px_8px_rgba(16,24,40,0.04)] border border-[#E4E7EC]">
            @if ($errors->any())
                <div class="mb-6 rounded-xl border-2 border-[#FDA29B] bg-[#FEF3F2] p-4 text-[#B42318]">
                    <div class="flex items-center text-[12.5px] font-bold mb-1">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i> Gagal memperbarui kata sandi:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-[11.5px] text-[#912018] font-medium pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('user.password.update') }}" onsubmit="disableSubmitButton(this)">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label class="block text-[12px] font-bold text-[#344054] mb-1.5">Kata Sandi Saat Ini <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[12px]"></i>
                            <input type="password" name="current_password" required autocomplete="current-password" placeholder="Masukkan kata sandi lama..." class="dk-input w-full pl-10 pr-4 py-3 text-[13px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        @error('current_password')
                            <p class="text-[11.5px] text-rose-600 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[12px] font-bold text-[#344054] mb-1.5">Kata Sandi Baru <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[12px]"></i>
                            <input type="password" name="password" required minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter..." class="dk-input w-full pl-10 pr-4 py-3 text-[13px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        @error('password')
                            <p class="text-[11.5px] text-rose-600 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[12px] font-bold text-[#344054] mb-1.5">Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-shield-halved absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[12px]"></i>
                            <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi baru..." class="dk-input w-full pl-10 pr-4 py-3 text-[13px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8 pt-5 border-t border-[#E4E7EC]">
                    <a href="{{ route('user.riwayat') }}" class="px-5 py-3 rounded-xl text-[12.5px] font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Batal
                    </a>
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 bg-[#16324F] hover:bg-[#0F2438] active:scale-95 text-white px-6 py-3 rounded-xl font-bold text-[13px] transition-all shadow-md shadow-[#16324F]/20 hover:shadow-lg cursor-pointer">
                        Simpan Kata Sandi <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .dk-input, label, button, a, div {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .dk-input {
            border: 1.5px solid #DCE1E8;
            border-radius: 12px;
            background: #FFFFFF;
            outline: none;
        }
        .dk-input:focus {
            border-color: #0284C7 !important;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.12) !important;
        }
        @keyframes fadeInDown {
            0% { opacity: 0; transform: translateY(-12px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

    <script>
        function disableSubmitButton(form) {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                btn.innerHTML = 'Menyimpan... <i class="fa-solid fa-spinner fa-spin ml-2"></i>';
            }
        }
    </script>

@endsection
