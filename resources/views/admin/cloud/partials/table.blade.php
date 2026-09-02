<div data-ajax-table class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col mt-6">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-[#071E3D]">Daftar Pengajuan Cloud Gov Storage</h3>
            <p class="text-[12px] text-gray-400 font-medium mt-1">Kelola pembuatan akun dan alokasi kapasitas penyimpanan (storage).</p>
        </div>
        
        <div class="flex gap-3 items-center">
            <form method="GET" action="{{ route('admin.cloud.index') }}" class="flex gap-3">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Tiket #CLD-..." class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none focus:border-cyan-400 focus:bg-white w-48 transition-all">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-filter absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <select name="status" onchange="this.form.submit()" class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none cursor-pointer">
                        <option value="">Status</option>
                        @foreach(['Pending' => 'Pending', 'Proses' => 'Proses', 'Selesai' => 'Selesai', 'Ditolak' => 'Ditolak'] as $val => $label)
                            <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <button type="button" onclick="bukaModalCreate()" class="px-4 py-2 bg-[#071E3D] hover:bg-[#1F4287] text-white text-[12px] font-bold rounded-lg transition-colors shadow-sm flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-plus"></i> Tambah Pengajuan
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[900px]">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                <tr>
                    <th class="py-3 px-6">Tiket</th>
                    <th class="py-3 px-6">Nama Pemohon</th>
                    <th class="py-3 px-6">Layanan</th>
                    <th class="py-3 px-6">Tanggal</th>
                    <th class="py-3 px-6">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="admin-tbody" class="divide-y divide-gray-50">
                @forelse ($pengajuans as $item)
                    @php
                        $dataForm = $item->dataForm();
                        $badgeColor = match($item->status) {
                            'Pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'Proses'  => 'bg-blue-50 text-blue-600 border-blue-100',
                            'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'Ditolak' => 'bg-rose-50 text-rose-600 border-rose-100',
                            default   => 'bg-gray-50 text-gray-600 border-gray-100'
                        };
                    @endphp
                    <tr class="hover:bg-cyan-50/20 transition-colors duration-200">
                        <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D] whitespace-nowrap font-mono">
                            <span class="inline-flex items-center gap-1.5">
                                {{ $item->nomor_tiket }}
                                <button type="button" onclick="event.stopPropagation(); copyTiket('{{ $item->nomor_tiket }}')" title="Salin Nomor Tiket" class="text-gray-400 hover:text-cyan-500 transition-colors cursor-pointer"><i class="fa-regular fa-copy text-[11px]"></i></button>
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-[13px] font-bold text-[#071E3D]">
                                {{ $dataForm['nama'] ?? $item->user->name ?? 'Pemohon' }}
                            </p>
                            <p class="text-[11px] text-gray-500 font-medium mt-0.5">
                                NIP: {{ $dataForm['nip'] ?? $item->user->nip ?? '-' }}
                            </p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-[13px] font-bold text-[#071E3D] capitalize">
                                {{ $item->layanan?->nama ?? 'Layanan IT' }}
                            </span>
                            @if(!empty($dataForm['email']))
                                <p class="text-[11px] text-cyan-700 font-bold mt-0.5">
                                    {{ $dataForm['email'] }}
                                </p>
                            @endif
                            @if(!empty($dataForm['kapasitas']))
                                <p class="text-[11px] text-gray-500 font-medium">
                                    Penyimpanan: {{ $dataForm['kapasitas'] }}
                                </p>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-[12px] text-gray-500 font-bold">
                            {{ $item->created_at->format('d M Y') }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1.5 rounded-lg border text-[10px] font-extrabold uppercase tracking-wider {{ $badgeColor }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center space-x-1 whitespace-nowrap">
                            <button type="button" onclick="bukaModalAdmin('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-cyan-50 hover:text-cyan-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm cursor-pointer" title="Kelola & Detail Pengajuan">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <form id="form-delete-{{ $item->id }}" action="{{ route('admin.pengajuan.destroy', $item->id) }}" method="POST" class="inline-block" data-no-ajax>
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="bukaModalDelete('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-rose-50 hover:text-rose-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm cursor-pointer" title="Hapus Permanen">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-folder-open text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="font-bold text-[14px] text-gray-600">Belum ada data pengajuan cloud</h3>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($pengajuans, 'links') && $pengajuans->hasPages())
        <div id="admin-pagination" class="admin-pagination px-6 py-4 border-t border-gray-100">
            {{ $pengajuans->links() }}
        </div>
    @else
        <div id="admin-pagination" class="admin-pagination"></div>
    @endif

    {{-- KUMPULAN MODAL UPDATE & DETAIL (STRIP PROFIL + 2 KOLOM WORKSPACE) --}}
    @foreach ($pengajuans as $item)
        @php
            $dataForm = $item->dataForm();
            $cleanWa = \App\Support\PhoneNumber::wa($dataForm['no_hp'] ?? '');
        @endphp
        
        <div id="modal-{{ $item->id }}" class="fixed inset-0 z-[150] hidden items-center justify-center">
            <div class="absolute inset-0 bg-[#101828]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalAdmin('{{ $item->id }}')"></div>
            <div class="relative bg-white rounded-3xl w-full max-w-6xl mx-4 my-auto shadow-2xl border border-slate-100 overflow-hidden max-h-[92vh] flex flex-col animate-fade-in-down">

                {{-- HEADER MODAL --}}
                <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-white shrink-0">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-11 h-11 rounded-xl bg-[#16324F] text-cyan-400 flex items-center justify-center text-lg shadow-md shadow-[#16324F]/20 shrink-0">
                            <i class="fa-solid fa-cloud"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold tracking-[0.16em] text-cyan-700 uppercase mb-0.5">Admin Panel</p>
                            <h2 class="text-[16px] font-extrabold text-[#101828] leading-tight truncate">Kelola Pengajuan Cloud Government</h2>
                            <p class="text-[11px] text-slate-500 font-medium mt-0.5">Perbarui progres, unggah berkas akses, dan balas diskusi pemohon.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="inline-flex items-center gap-1.5 text-[10.5px] font-bold text-cyan-800 bg-cyan-50 border border-cyan-200 rounded-full px-3 py-1.5 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span> {{ $item->nomor_tiket }}
                        </span>
                        <button type="button" onclick="tutupModalAdmin('{{ $item->id }}')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm cursor-pointer">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                </div>

                {{-- Body Modal --}}
                <div class="flex-1 min-h-0 overflow-y-auto custom-scrollbar">
                    <form method="POST" action="{{ route('admin.pengajuan.update', $item->id) }}" enctype="multipart/form-data" data-no-ajax onsubmit="return konfirmasiTolak(this)">
                        @csrf
                        @method('PUT')

                        <div class="p-6 md:p-8 space-y-6">

                        @if ($errors->any())
                            <div class="rounded-xl border-2 border-[#FDA29B] bg-[#FEF3F2] p-3 text-[#B42318]">
                                <div class="flex items-center text-[12px] font-bold mb-1">
                                    <i class="fa-solid fa-circle-exclamation mr-2"></i> Gagal memperbarui data:
                                </div>
                                <ul class="list-disc list-inside space-y-0.5 text-[11px] text-[#912018] font-medium pl-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- BAGIAN 1: STRIP PROFIL ASN & BERKAS --}}
                        <div class="p-5 bg-gradient-to-r from-slate-50 to-slate-100/70 border border-slate-200/80 rounded-2xl flex flex-wrap lg:flex-nowrap items-center justify-between gap-6 shadow-sm">
                            <div class="min-w-0">
                                <p class="text-base font-bold text-slate-800 truncate">{{ $dataForm['nama'] ?? $item->user->name ?? 'Pemohon' }}</p>
                                <p class="text-xs text-slate-500 font-medium mt-0.5 truncate">
                                    NIP: {{ $dataForm['nip'] ?? $item->user->nip ?? '-' }} &middot; {{ $dataForm['instansi'] ?? 'Instansi' }}
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Email</p>
                                    <p class="text-xs font-semibold text-slate-700 break-all max-w-[220px]">{{ $dataForm['email'] ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">No. HP</p>
                                    @if(!empty($dataForm['no_hp']))
                                        <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="text-xs font-semibold text-slate-700 hover:text-emerald-600 hover:underline inline-flex items-center gap-1.5">
                                            <i class="fa-brands fa-whatsapp text-[11px] text-slate-400"></i> {{ \App\Support\PhoneNumber::local($dataForm['no_hp'] ?? '') }}
                                        </a>
                                    @else
                                        <p class="text-xs font-semibold text-slate-700">-</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Kapasitas</p>
                                    <span class="px-3.5 py-1.5 bg-cyan-100 text-cyan-800 rounded-xl text-xs font-bold shadow-sm inline-block">{{ $dataForm['kapasitas'] ?? '-' }}</span>
                                </div>
                            </div>

                            @php
                                $filePemohon = $item->file_pendukung
                                    ;
                            @endphp
                            @if(!empty($filePemohon))
                                <a href="{{ route('dokumen.unduh', ['pengajuan' => $item->id, 'jenis' => 'pendukung']) }}" target="_blank" class="px-4 py-2.5 bg-white border border-slate-200 hover:border-blue-500 hover:bg-blue-50/30 rounded-xl text-xs font-semibold text-slate-700 flex items-center gap-2.5 shadow-sm transition-all shrink-0">
                                    <i class="fa-solid fa-file-pdf text-sm text-blue-600"></i> Surat Permohonan
                                    <i class="fa-solid fa-download text-xs text-slate-400"></i>
                                </a>
                            @else
                                <div class="px-4 py-2.5 bg-white border border-dashed border-slate-200 rounded-xl text-xs font-semibold text-slate-400 italic shadow-sm shrink-0">
                                    <i class="fa-solid fa-folder-open mr-1.5"></i> Tanpa berkas
                                </div>
                            @endif
                        </div>

                        {{-- BAGIAN 2: WORKSPACE 2 KOLOM SEIMBANG --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

                            {{-- A. KARTU UPDATE STATUS & PROGRES --}}
                            <div class="bg-slate-50/50 border border-slate-200/80 rounded-2xl p-6 shadow-sm flex flex-col justify-between h-full">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Update Status & Progres</p>

                                <div class="space-y-4 flex-1">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Pengajuan <span class="text-rose-500">*</span></label>
                                        <select name="status" required onchange="toggleHasilUpload(this, {{ $item->id }})" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 bg-white">
                                            @foreach(['Pending' => 'PENDING', 'Proses' => 'PROSES', 'Selesai' => 'SELESAI', 'Ditolak' => 'DITOLAK'] as $val => $label)
                                                <option value="{{ $val }}" @selected($item->status == $val)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan E-Tracking</label>
                                        <textarea name="catatan" rows="4" placeholder="Tuliskan catatan progres pengajuan..." class="w-full rounded-xl border border-slate-300 p-3.5 text-sm focus:ring-2 focus:ring-blue-500 bg-white placeholder:text-slate-400">{{ old('catatan') }}</textarea>
                                    </div>

                                    <div id="hasil-upload-{{ $item->id }}" class="{{ $item->status === 'Selesai' ? '' : 'hidden' }}">
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Berkas Akses / Akun (PDF)</label>
                                        <label for="admin-hasil-upload-{{ $item->id }}" class="group flex items-center justify-between gap-3 rounded-xl border-2 border-dashed border-slate-200 bg-white hover:border-blue-500 hover:bg-blue-50/40 transition-all px-3 py-2.5 cursor-pointer">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-8 h-8 shrink-0 rounded-lg bg-white group-hover:bg-blue-600 group-hover:text-white border border-slate-200 group-hover:border-blue-600 flex items-center justify-center text-slate-500 transition-colors shadow-sm">
                                                    <i class="fa-solid fa-cloud-arrow-up text-[13px]"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <p id="admin-hasil-name-{{ $item->id }}" class="text-[12px] text-[#101828] font-bold group-hover:text-blue-900 truncate">
                                                        @if($item->file_hasil)
                                                            File Hasil Tersedia
                                                            <a href="{{ route('dokumen.unduh', ['pengajuan' => $item->id, 'jenis' => 'hasil']) }}" target="_blank" class="inline-flex items-center gap-1 ml-1.5 text-blue-700 bg-blue-100 hover:bg-blue-200 px-2 py-0.5 rounded-md text-[10px] font-bold transition-colors">
                                                                <i class="fa-solid fa-eye text-[9px]"></i> Lihat
                                                            </a>
                                                        @else
                                                            Pilih Berkas PDF
                                                        @endif
                                                    </p>
                                                    <p class="text-[10px] text-slate-500">Dokumen informasi akses</p>
                                                </div>
                                            </div>
                                            <div class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[10.5px] font-bold text-gray-600 group-hover:border-blue-300 shrink-0">Browse</div>
                                            <input id="admin-hasil-upload-{{ $item->id }}" name="file_hasil" type="file" class="sr-only" accept=".pdf" onchange="if(this.files && this.files[0]){document.getElementById('admin-hasil-name-{{ $item->id }}').innerText = this.files[0].name; document.getElementById('admin-hasil-name-{{ $item->id }}').classList.add('text-emerald-700')}">
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-semibold rounded-xl text-sm shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-2 cursor-pointer mt-4">
                                    <i class="fa-solid fa-floppy-disk text-xs"></i> Simpan Perubahan
                                </button>
                            </div>

                            {{-- B. KARTU RUANG DISKUSI --}}
                            <div class="bg-slate-50/50 border border-slate-200/80 rounded-2xl p-6 shadow-sm flex flex-col justify-between h-full">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Ruang Diskusi Pemohon</p>

                                <div id="chat-box-{{ $item->id }}" class="flex-1 min-h-[200px] max-h-[240px] overflow-y-auto p-4 bg-white border border-slate-200 rounded-2xl my-2 space-y-2 text-sm custom-scrollbar">
                                    @if($item->messages->isNotEmpty())
                                        @foreach($item->messages as $chat)
                                            <div class="flex flex-col {{ $chat->role === 'admin' ? 'items-end' : 'items-start' }}">
                                                <div class="max-w-[85%] px-3 py-1.5 rounded-xl text-[11.5px] {{ $chat->role === 'admin' ? 'bg-[#16324F] text-white rounded-br-none' : 'bg-slate-100 border border-slate-200 text-[#101828] rounded-bl-none' }}">
                                                    <p class="font-bold text-[9.5px] opacity-80 mb-0.5">{{ $chat->pengirim }}</p>
                                                    <p class="leading-relaxed">{{ $chat->isi }}</p>
                                                </div>
                                                <span class="text-[9px] text-[#667085] mt-0.5">{{ $chat->waktu }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="flex flex-col items-center justify-center h-full text-slate-400">
                                            <i class="fa-regular fa-comments text-2xl mb-1 opacity-40"></i>
                                            <p class="text-sm italic">Belum ada obrolan dengan pemohon.</p>
                                        </div>
                                    @endif
                                </div>

                                @if($chatAktif ?? true)
                                    <div class="flex gap-2 mt-2">
                                        <input type="text" name="pesan" value="{{ old('pesan') }}" placeholder="Ketik pesan balasan..." class="flex-1 min-w-0 rounded-xl border border-slate-300 py-2.5 px-3.5 text-sm focus:ring-2 focus:ring-blue-500 bg-white placeholder:text-slate-400">
                                        <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm transition-all cursor-pointer shrink-0">
                                            <i class="fa-solid fa-paper-plane text-xs"></i> Kirim
                                        </button>
                                    </div>
                                @else
                                    <div class="bg-rose-50 border border-rose-100 p-2 rounded-xl text-center shadow-sm mt-2">
                                        <p class="text-[11px] font-bold text-rose-500"><i class="fa-solid fa-lock mr-1"></i> Fitur obrolan dinonaktifkan.</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL DELETE --}}
        <div id="modal-delete-{{ $item->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center">
            <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalDelete('{{ $item->id }}')"></div>
            <div class="relative bg-white rounded-[2rem] p-8 max-w-sm w-full mx-4 shadow-2xl text-center">
                <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-5 border-4 border-white shadow-md">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-xl font-extrabold text-[#071E3D] mb-2">Hapus Pengajuan?</h3>
                <p class="text-[13px] text-gray-500 mb-6 leading-relaxed">
                    Tindakan ini tidak dapat dibatalkan. Yakin ingin menghapus data pengajuan <b class="text-[#071E3D]">{{ $item->nomor_tiket }}</b> secara permanen?
                </p>
                <div class="flex gap-3">
                    <button type="button" onclick="tutupModalDelete('{{ $item->id }}')" class="flex-1 py-3 rounded-xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-colors">Batal</button>
                    <button type="button" onclick="document.getElementById('form-delete-{{ $item->id }}').submit()" class="flex-1 py-3 rounded-xl font-bold text-white bg-rose-500 hover:bg-rose-600 transition-colors shadow-lg shadow-rose-500/20">Ya, Hapus!</button>
                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL TAMBAH PENGAJUAN (CREATE DENGAN UPLOAD BERKAS PERSYARATAN) --}}
    <div id="modal-create" class="fixed inset-0 z-[150] hidden items-center justify-center">
        <div class="absolute inset-0 bg-[#101828]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalCreate()"></div>
        <div class="relative bg-white rounded-[1.5rem] w-full max-w-3xl mx-4 shadow-2xl overflow-hidden max-h-[95vh] flex flex-col animate-fade-in-down">
            <div class="flex items-center justify-between gap-4 px-6 md:px-8 py-4 border-b border-[#E4E7EC] bg-gradient-to-r from-[#F8FAFC] to-[#F1F5F9] shrink-0">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-[#16324F] text-cyan-400 flex items-center justify-center text-lg shadow-md shadow-[#16324F]/20">
                        <i class="fa-solid fa-cloud"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-[0.16em] text-cyan-700 uppercase mb-0.5">Admin Panel</p>
                        <h2 class="text-[17px] font-extrabold text-[#101828] leading-tight">Pengajuan Cloud Gov</h2>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline-flex items-center gap-1.5 text-[10.5px] font-bold text-cyan-800 bg-cyan-50 border border-cyan-200 rounded-full px-3 py-1 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span> Storage ASN
                    </span>
                    <button type="button" onclick="tutupModalCreate()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
            </div>

            <div class="px-6 md:px-8 py-5 overflow-y-auto custom-scrollbar">
                <form method="POST" action="{{ route('admin.pengajuan.storeCloud') }}" enctype="multipart/form-data" data-no-ajax onsubmit="return konfirmasiTolak(this)">
                    @csrf
                    @if ($errors->any())
                        <div class="mb-5 rounded-xl border-2 border-[#FDA29B] bg-[#FEF3F2] p-3.5 text-[#B42318]">
                            <div class="flex items-center text-[12.5px] font-bold mb-1">
                                <i class="fa-solid fa-circle-exclamation mr-2"></i> Gagal menyimpan data:
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 text-[11.5px] text-[#912018] font-medium pl-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Step 1: Data Penanggung Jawab --}}
                    <div class="relative pl-10 mb-5">
                        <div class="absolute left-0 top-0 w-7 h-7 rounded-full bg-[#16324F] text-white text-[11.5px] font-black flex items-center justify-center ring-4 ring-slate-100 shadow-sm">1</div>
                        <div class="flex items-center gap-2 mb-3">
                            <h3 class="text-[14px] font-extrabold text-[#101828]">Data Penanggung Jawab</h3>
                            <span class="text-[9px] bg-sky-50 text-sky-700 border border-sky-200 px-2 py-0.5 rounded font-bold">Admin Only</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3.5">
                            @include('admin.partials.select-asn', ['prefix' => 'cloud'])
                            <div>
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Nama Penanggung Jawab <span class="text-rose-500">*</span></label>
                                <input type="text" name="data_pengajuan[nama]" value="{{ old('data_pengajuan.nama') }}" required placeholder="Masukkan nama lengkap" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">NIP Pemohon</label>
                                <input type="text" inputmode="numeric" name="data_pengajuan[nip]" id="nip-field-cloud" value="{{ old('data_pengajuan.nip') }}" maxlength="18" placeholder="Masukkan NIP (opsional, maksimal 18 digit)..." class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                                <input type="hidden" name="data_pengajuan[perketat_nip]" id="nip-ketat-val-cloud" value="0">
                                <label class="inline-flex items-center gap-1.5 mt-1.5 text-[10.5px] font-semibold text-[#475467] cursor-pointer select-none">
                                    <input type="checkbox" id="nip-ketat-cloud" class="accent-cyan-600 w-3 h-3 rounded" onchange="toggleNipKetat(this, 'cloud')">
                                    Perketat NIP (18 digit wajib)
                                </label>
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Instansi / Unit Kerja <span class="text-rose-500">*</span></label>
                                <input type="text" name="data_pengajuan[instansi]" value="{{ old('data_pengajuan.instansi') }}" required placeholder="Contoh: Dinas Komunikasi dan Informatika" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Spesifikasi Cloud & Dokumen Persyaratan (Upload PDF) --}}
                    <div class="relative pl-10 mb-3">
                        <div class="absolute left-0 top-0 w-7 h-7 rounded-full bg-[#16324F] text-white text-[11.5px] font-black flex items-center justify-center ring-4 ring-slate-100 shadow-sm">2</div>
                        <h3 class="text-[14px] font-extrabold text-[#101828] mb-3">Spesifikasi Cloud & Dokumen Persyaratan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Email Resmi (Untuk Aktivasi) <span class="text-rose-500">*</span></label>
                                <input type="email" name="data_pengajuan[email]" value="{{ old('data_pengajuan.email') }}" required placeholder="email@acehbaratkab.go.id" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Nomor HP / WhatsApp <span class="text-rose-500">*</span></label>
                                <input type="tel" inputmode="numeric" name="data_pengajuan[no_hp]" value="{{ old('data_pengajuan.no_hp') }}" required maxlength="15" placeholder="081234567890" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Kapasitas Penyimpanan <span class="text-rose-500">*</span></label>
                                <select name="data_pengajuan[kapasitas]" required class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-bold appearance-none cursor-pointer outline-none focus:border-cyan-500 shadow-sm transition-all">
                                    <option value="" disabled selected>-- Pilih Kapasitas --</option>
                                    <option value="10GB" @selected(old('data_pengajuan.kapasitas') == '10GB')>10 GB (Standar)</option>
                                    <option value="50GB" @selected(old('data_pengajuan.kapasitas') == '50GB')>50 GB (Menengah)</option>
                                    <option value="100GB" @selected(old('data_pengajuan.kapasitas') == '100GB')>100 GB (Instansi)</option>
                                </select>
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Upload Surat Permohonan Akun Cloud (PDF) <span class="text-rose-500">*</span></label>
                                <label for="admin-cloud-upload" class="group flex items-center justify-between gap-3 rounded-xl border-2 border-dashed border-[#DCE1E8] bg-white hover:border-sky-500 hover:bg-sky-50/40 transition-all px-4 py-2.5 cursor-pointer shadow-sm">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 shrink-0 rounded-lg bg-slate-100 group-hover:bg-sky-500 group-hover:text-white flex items-center justify-center text-[#667085] transition-colors shadow-sm">
                                            <i class="fa-solid fa-cloud-arrow-up text-[14px]"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p id="admin-cloud-name" class="text-[12.5px] text-[#101828] font-bold group-hover:text-sky-900 truncate">Klik untuk memilih berkas</p>
                                            <p class="text-[10.5px] text-[#667085] font-medium mt-0.5 truncate">Format PDF &middot; Maksimal 5MB</p>
                                        </div>
                                    </div>
                                    <div class="px-3 py-1.5 bg-slate-50 border border-gray-200 rounded-lg text-[11px] font-bold text-gray-600 group-hover:border-sky-300 shrink-0">Browse</div>
                                    <input id="admin-cloud-upload" name="file_pendukung" type="file" class="sr-only" accept=".pdf" required onchange="if(this.files && this.files[0]){document.getElementById('admin-cloud-name').innerText = this.files[0].name; document.getElementById('admin-cloud-name').classList.add('text-emerald-700', 'font-bold')}">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-[#E4E7EC]">
                        <button type="button" onclick="tutupModalCreate()" class="px-5 py-2.5 rounded-xl text-[12.5px] font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors cursor-pointer">Batal</button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-[#16324F] hover:bg-[#0F2438] active:scale-95 text-white px-6 py-2.5 rounded-xl font-bold text-[13px] transition-all shadow-md shadow-[#16324F]/20 hover:shadow-lg cursor-pointer">
                            Simpan Pengajuan <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>