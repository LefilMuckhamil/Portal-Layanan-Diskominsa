@extends('layouts.user')

@section('title', 'Pengajuan Cloud Gov')

@section('content')

    <style>
        .dk-input, label, button, a, div {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dk-input {
            border: 1.5px solid #DCE1E8;
            border-radius: 12px;
            background: #FFFFFF;
        }

        .dk-input:focus-within, .dk-input:focus {
            outline: none;
            border-color: #0284C7 !important;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.12) !important;
        }

        .dk-rail::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 36px;
            bottom: -28px;
            width: 2px;
            background: #E2E8F0;
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-12px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

    <div class="bg-white border border-[#E4E7EC] rounded-2xl shadow-[0_2px_8px_rgba(16,24,40,0.04)] overflow-hidden animate-fade-in-down">

        <div class="flex items-center justify-between gap-4 px-7 md:px-10 py-6 border-b border-[#E4E7EC] bg-gradient-to-r from-[#F8FAFC] to-[#F1F5F9]">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-[#16324F] text-cyan-400 flex items-center justify-center text-lg shadow-md shadow-[#16324F]/20">
                    <i class="fa-solid fa-cloud"></i>
                </div>
                <div>
                    <p class="text-[10.5px] font-bold tracking-[0.16em] text-cyan-700 uppercase mb-0.5">Diskominsa &middot; Layanan Digital</p>
                    <h2 class="text-[19px] font-extrabold text-[#101828] leading-tight">Pengajuan Cloud Government</h2>
                </div>
            </div>
            <span class="hidden sm:inline-flex items-center gap-1.5 text-[11.5px] font-bold text-[#16324F] bg-white rounded-xl px-3.5 py-1.5 shrink-0 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span> Penyimpanan Cloud
            </span>
        </div>

        <div class="px-7 md:px-10 py-9">

            @if ($errors->any())
                <div class="mb-8 rounded-xl border-2 border-[#FDA29B] bg-[#FEF3F2] p-4 text-[#B42318] animate-fade-in-down">
                    <div class="flex items-center text-[13.5px] font-bold mb-1.5">
                        <i class="fa-solid fa-circle-exclamation mr-2 text-lg"></i> Pengajuan Gagal Diproses:
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-[12.5px] text-[#912018] font-medium pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pengajuan.cloud.store') }}" method="POST" enctype="multipart/form-data" onsubmit="disableSubmitButton(this)">
                @csrf

                <div class="relative dk-rail pl-11">
                    <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-[#16324F] text-white text-[12.5px] font-black flex items-center justify-center ring-4 ring-slate-100 shadow-sm">1</div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <h3 class="text-[15px] font-extrabold text-[#101828]">Data Pemohon</h3>
                    </div>
                    <p class="text-[12.5px] text-[#667085] font-medium mb-6">Informasi pegawai yang bertanggung jawab atas akun cloud.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-10">
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Nama</label>
                            <input type="text" name="data_pengajuan[nama]" value="{{ old('data_pengajuan.nama') }}" required placeholder="Masukkan nama lengkap" class="dk-input w-full px-3.5 py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">NIP</label>
                            <input type="text" name="data_pengajuan[nip]" value="{{ old('data_pengajuan.nip') }}" required placeholder="Masukkan NIP" class="dk-input w-full px-3.5 py-2.5 text-[13px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Instansi</label>
                            <input type="text" name="data_pengajuan[instansi]" value="{{ old('data_pengajuan.instansi') }}" required placeholder="Dinas Komunikasi dan Informatika" class="dk-input w-full px-3.5 py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                    </div>
                </div>

                <div class="relative pl-11">
                    <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-[#16324F] text-white text-[12.5px] font-black flex items-center justify-center ring-4 ring-slate-100 shadow-sm">2</div>
                    <h3 class="text-[15px] font-extrabold text-[#101828] mb-0.5">Spesifikasi Cloud &amp; Dokumen</h3>
                    <p class="text-[12.5px] text-[#667085] font-medium mb-6">Konfigurasi kapasitas penyimpanan cloud dan berkas pengajuan.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Email Resmi (Untuk Aktivasi)</label>
                            <div class="dk-input flex items-center px-3.5">
                                <i class="fa-solid fa-envelope text-sky-600 text-[14px] mr-2.5"></i>
                                <input type="email" name="data_pengajuan[email]" value="{{ old('data_pengajuan.email') }}" required placeholder="email@acehbaratkab.go.id" class="flex-1 min-w-0 bg-transparent outline-none py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Kapasitas Penyimpanan</label>
                            <div class="dk-input flex items-center px-3.5 relative">
                                <i class="fa-solid fa-server text-cyan-700 text-[14px] mr-2.5"></i>
                                <select name="data_pengajuan[kapasitas]" required class="flex-1 min-w-0 bg-transparent outline-none py-2.5 text-[13.5px] text-[#101828] font-bold appearance-none cursor-pointer">
                                    <option value="" disabled {{ old('data_pengajuan.kapasitas') ? '' : 'selected' }}>Pilih Kapasitas</option>
                                    <option value="10GB" {{ old('data_pengajuan.kapasitas') == '10GB' ? 'selected' : '' }}>10 GB (Standar)</option>
                                    <option value="50GB" {{ old('data_pengajuan.kapasitas') == '50GB' ? 'selected' : '' }}>50 GB (Menengah)</option>
                                    <option value="100GB" {{ old('data_pengajuan.kapasitas') == '100GB' ? 'selected' : '' }}>100 GB (Besar)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down text-xs text-[#667085] pointer-events-none ml-2"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Upload Surat Permohonan Akun Cloud (PDF)</label>
                        <label for="file-upload" class="group flex items-center gap-4 rounded-xl border-2 border-dashed border-[#DCE1E8] hover:border-sky-500 hover:bg-sky-50/40 transition-all px-5 py-4 cursor-pointer shadow-sm">
                            <div class="w-10 h-10 shrink-0 rounded-xl bg-slate-100 group-hover:bg-sky-500 group-hover:text-white flex items-center justify-center text-[#667085] transition-colors shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up text-[16px]"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[13.5px] text-[#101828] font-bold group-hover:text-sky-900">Klik untuk memilih berkas <span class="font-medium text-[#667085]">atau tarik file ke sini</span></p>
                                <p id="file-name" class="text-[11.5px] text-[#667085] font-medium mt-0.5">Format PDF &middot; Maksimal 5MB</p>
                            </div>
                            <input id="file-upload" name="file_pendukung" type="file" class="sr-only" accept=".pdf" required onchange="document.getElementById('file-name').innerText = 'File Terpilih: ' + this.files[0].name; document.getElementById('file-name').classList.add('text-emerald-700', 'font-bold')">
                        </label>
                        @error('file_pendukung')
                            <p class="text-[12px] font-semibold text-red-600 mt-2"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="text-[11.5px] text-amber-700 font-medium mt-1">Catatan: Jika terjadi kesalahan validasi pada formulir, silakan pilih kembali berkas PDF Anda.</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-[#E4E7EC]">
                    <button type="submit" class="inline-flex items-center gap-2.5 bg-[#16324F] hover:bg-[#0F2438] active:scale-95 text-white px-7 py-3 rounded-xl font-bold text-[14px] transition-all shadow-md shadow-[#16324F]/20 hover:shadow-lg">
                        Kirim Pengajuan Cloud <i class="fa-solid fa-paper-plane text-[12px]"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function disableSubmitButton(form) {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-60', 'cursor-not-allowed');
                btn.innerHTML = 'Memproses... <i class="fa-solid fa-spinner fa-spin ml-1.5"></i>';
            }
        }
    </script>
@endsection