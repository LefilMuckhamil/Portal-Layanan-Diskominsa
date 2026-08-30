@extends('layouts.user')

@section('title', 'Pengajuan Hosting & Server')

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
                <div class="w-11 h-11 rounded-xl bg-[#16324F] text-indigo-400 flex items-center justify-center text-lg shadow-md shadow-[#16324F]/20">
                    <i class="fa-solid fa-server"></i>
                </div>
                <div>
                    <p class="text-[10.5px] font-bold tracking-[0.16em] text-indigo-700 uppercase mb-0.5">Diskominsa &middot; Layanan Digital</p>
                    <h2 class="text-[19px] font-extrabold text-[#101828] leading-tight">Pengajuan Hosting &amp; Server</h2>
                </div>
            </div>
            <span class="hidden sm:inline-flex items-center gap-1.5 text-[11.5px] font-bold text-[#16324F] bg-white rounded-xl px-3.5 py-1.5 shrink-0 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>Hosting
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

            <form action="{{ route('pengajuan.hosting.store') }}" method="POST" enctype="multipart/form-data" onsubmit="disableSubmitButton(this)">
                @csrf

                <div class="relative dk-rail pl-11">
                    <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-[#16324F] text-white text-[12.5px] font-black flex items-center justify-center ring-4 ring-slate-100 shadow-sm">1</div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <h3 class="text-[15px] font-extrabold text-[#101828]">Data Operator</h3>
                    </div>
                    <p class="text-[12.5px] text-[#667085] font-medium mb-6">Informasi operator/pegawai yang mengajukan layanan.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Nama Lengkap</label>
                            <input type="text" name="data_pengajuan[nama]" value="{{ old('data_pengajuan.nama', auth()->user()->name ?? '') }}" required placeholder="Masukkan Nama Lengkap" class="dk-input w-full px-3.5 py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">NIP</label>
                            <input type="text" inputmode="numeric" maxlength="18" name="data_pengajuan[nip]" value="{{ old('data_pengajuan.nip', auth()->user()->nip ?? '') }}" required placeholder="Masukkan NIP" class="dk-input w-full px-3.5 py-2.5 text-[13px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Email Dinas</label>
                            <input type="email" name="data_pengajuan[email_dinas]" value="{{ old('data_pengajuan.email_dinas', auth()->user()->email ?? '') }}" required placeholder="nama@acehbaratkab.go.id" class="dk-input w-full px-3.5 py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Email Alternatif</label>
                            <input type="email" name="data_pengajuan[email_google]" value="{{ old('data_pengajuan.email_google') }}" required placeholder="nama@gmail.com" class="dk-input w-full px-3.5 py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Nomor HP / WhatsApp</label>
                            <input type="tel" inputmode="numeric" name="data_pengajuan[no_hp]" value="{{ old('data_pengajuan.no_hp', auth()->user()->no_hp ?? '') }}" required maxlength="15" placeholder="081234567890" class="dk-input w-full px-3.5 py-2.5 text-[13px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                    </div>
                </div>

                <div class="relative pl-11">
                    <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-[#16324F] text-white text-[12.5px] font-black flex items-center justify-center ring-4 ring-slate-100 shadow-sm">2</div>
                    <h3 class="text-[15px] font-extrabold text-[#101828] mb-0.5">Data Instansi &amp; Kebutuhan Hosting</h3>
                    <p class="text-[12.5px] text-[#667085] font-medium mb-6">Detail instansi, jabatan, dan spesifikasi web server / database / storage yang diinginkan.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Instansi / Unit Kerja</label>
                            <input type="text" name="data_pengajuan[instansi]" value="{{ old('data_pengajuan.instansi', auth()->user()->unit_kerja ?? auth()->user()->instansi ?? '') }}" required placeholder="Contoh: Dinas Kesehatan" class="dk-input w-full px-3.5 py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Jabatan Operator</label>
                            <input type="text" name="data_pengajuan[jabatan]" value="{{ old('data_pengajuan.jabatan', auth()->user()->jabatan ?? '') }}" required placeholder="Contoh: Pranata Komputer / Pengelola IT" class="dk-input w-full px-3.5 py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Nama Aplikasi / Sistem</label>
                            <input type="text" name="data_pengajuan[nama_aplikasi]" value="{{ old('data_pengajuan.nama_aplikasi') }}" required placeholder="Contoh: SIAP - Sistem Informasi App" class="dk-input w-full px-3.5 py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Bahasa Pemrograman</label>
                            <input type="text" name="data_pengajuan[runtime]" value="{{ old('data_pengajuan.runtime') }}" required placeholder="Contoh: PHP 8.2 / Node.js / Python / HTML" class="dk-input w-full px-3.5 py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Database</label>
                            <input type="text" name="data_pengajuan[database_type]" value="{{ old('data_pengajuan.database_type') }}" required placeholder="Contoh: MySQL / PostgreSQL / Tidak butuh" class="dk-input w-full px-3.5 py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div>
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Kebutuhan Storage</label>
                            <input type="text" name="data_pengajuan[storage_quota]" value="{{ old('data_pengajuan.storage_quota') }}" required placeholder="Contoh: 5 GB / 10 GB" class="dk-input w-full px-3.5 py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Domain Terkait <span class="text-[11px] font-semibold text-slate-400">(opsional)</span></label>
                            <input type="text" name="data_pengajuan[domain_terkait]" value="{{ old('data_pengajuan.domain_terkait') }}" placeholder="Contoh: dinkes.acehbaratkab.go.id" class="dk-input w-full px-3.5 py-2.5 text-[13.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                    </div>

                    <div class="mb-4">
                        <a href="{{ asset('docs/template-surat-permohonan-hosting.docx') }}" class="group flex items-center justify-between gap-4 rounded-xl border-2 border-indigo-100 bg-gradient-to-r from-indigo-50 to-violet-50 hover:from-indigo-100 hover:to-violet-100 transition-all px-5 py-4 shadow-sm">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-11 h-11 shrink-0 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-lg shadow-md shadow-indigo-600/25">
                                    <i class="fa-solid fa-file-word"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[13.5px] text-[#101828] font-extrabold">Unduh Template Surat Permohonan <span class="text-indigo-700">(.docx)</span></p>
                                    <p class="text-[11.5px] text-[#667085] font-medium mt-0.5">Format surat resmi siap pakai &middot; lengkapi lalu unggah dalam bentuk PDF</p>
                                </div>
                            </div>
                            <span class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-indigo-200 text-indigo-700 rounded-xl text-[12.5px] font-bold group-hover:border-indigo-400 transition-colors shadow-sm">
                                <i class="fa-solid fa-download"></i> Unduh
                            </span>
                        </a>
                    </div>

                    <div class="mb-2">
                        <label class="block text-[12.5px] font-bold text-[#344054] mb-1.5">Upload Surat Permohonan Resmi (PDF)</label>
                        <label for="hosting-file-upload" class="group flex items-center gap-4 rounded-xl border-2 border-dashed border-[#DCE1E8] hover:border-sky-500 hover:bg-sky-50/40 transition-all px-5 py-4 cursor-pointer shadow-sm">
                            <div class="w-10 h-10 shrink-0 rounded-xl bg-slate-100 group-hover:bg-sky-500 group-hover:text-white flex items-center justify-center text-[#667085] transition-colors shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up text-[16px]"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[13.5px] text-[#101828] font-bold group-hover:text-sky-900">Klik untuk memilih berkas <span class="font-medium text-[#667085]">atau tarik file ke sini</span></p>
                                <p id="hosting-file-name" class="text-[11.5px] text-[#667085] font-medium mt-0.5">Surat Permohonan &middot; Format PDF &middot; Maksimal 5MB</p>
                            </div>
                            <input id="hosting-file-upload" name="file_pendukung" type="file" class="sr-only" accept=".pdf" required onchange="if (this.files && this.files[0]) { document.getElementById('hosting-file-name').innerText = 'File Terpilih: ' + this.files[0].name; document.getElementById('hosting-file-name').classList.add('text-emerald-700', 'font-bold'); }">
                        </label>
                        @error('file_pendukung')
                            <p class="text-[12px] font-semibold text-red-600 mt-2"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="text-[11.5px] text-amber-700 font-medium mt-1">Catatan: Jika terjadi kesalahan validasi pada formulir, silakan pilih kembali berkas PDF Anda.</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-[#E4E7EC]">
                    <button type="submit" class="inline-flex items-center gap-2.5 bg-[#16324F] hover:bg-[#0F2438] active:scale-95 text-white px-7 py-3 rounded-xl font-bold text-[14px] transition-all shadow-md shadow-[#16324F]/20 hover:shadow-lg">
                        Kirim Pengajuan <i class="fa-solid fa-paper-plane text-[12px]"></i>
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