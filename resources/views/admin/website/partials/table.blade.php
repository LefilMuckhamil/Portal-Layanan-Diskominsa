<div data-ajax-table class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col mt-6">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-[#071E3D]">Daftar Pengajuan Website</h3>
            <p class="text-[12px] text-gray-400 font-medium mt-1">Kelola data dan perbarui status progres pembuatan website.</p>
        </div>
        
        <div class="flex gap-3 items-center">
            <form method="GET" action="{{ route('admin.website.index') }}" class="flex gap-3">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Tiket #WEB-..." class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none focus:border-cyan-400 focus:bg-white w-48 transition-all">
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
                        $dataForm = is_array($item->data_pengajuan) ? $item->data_pengajuan : json_decode($item->data_pengajuan ?? '[]', true);
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
                                {{ str_replace('_', ' ', $item->jenis_layanan) }}
                            </span>
                            @if(!empty($dataForm['nama_website'] ?? $dataForm['domain'] ?? null))
                                <p class="text-[11px] text-indigo-600 font-medium mt-0.5">
                                    {{ $dataForm['nama_website'] ?? $dataForm['domain'] }}
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
                            <h3 class="font-bold text-[14px] text-gray-600">Belum ada data pengajuan website</h3>
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

    {{-- KUMPULAN MODAL UPDATE & DETAIL (3 KOLOM SEIMBANG) --}}
    @foreach ($pengajuans as $item)
        @php
            $dataForm = is_array($item->data_pengajuan) ? $item->data_pengajuan : json_decode($item->data_pengajuan ?? '[]', true);
            $rawWa = preg_replace('/[^0-9]/', '', $dataForm['no_hp'] ?? '');
            if (str_starts_with($rawWa, '0')) {
                $cleanWa = '62' . substr($rawWa, 1);
            } elseif (!str_starts_with($rawWa, '62') && !empty($rawWa)) {
                $cleanWa = '62' . $rawWa;
            } else {
                $cleanWa = $rawWa;
            }
        @endphp
        
        <div id="modal-{{ $item->id }}" class="fixed inset-0 z-[150] hidden items-center justify-center">
            <div class="absolute inset-0 bg-[#101828]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalAdmin('{{ $item->id }}')"></div>
            <div class="relative bg-white rounded-[1.75rem] w-full max-w-6xl mx-4 shadow-2xl overflow-hidden max-h-[92vh] flex flex-col animate-fade-in-down">
                
                {{-- Header Modal --}}
                <div class="flex items-center justify-between gap-4 px-6 md:px-8 py-4 border-b border-[#E4E7EC] bg-gradient-to-r from-[#F8FAFC] to-[#F1F5F9] shrink-0">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-[#16324F] text-indigo-400 flex items-center justify-center text-lg shadow-md shadow-[#16324F]/20">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.16em] text-indigo-700 uppercase mb-0.5">Admin Panel</p>
                            <h2 class="text-[17px] font-extrabold text-[#101828] leading-tight">Kelola Pengajuan Website</h2>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 text-[10.5px] font-bold text-indigo-800 bg-indigo-50 border border-indigo-200 rounded-full px-3 py-1 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> {{ $item->nomor_tiket }}
                        </span>
                        <button type="button" onclick="tutupModalAdmin('{{ $item->id }}')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm cursor-pointer">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                </div>

                {{-- Body Modal --}}
                <div class="px-6 md:px-8 py-5 overflow-y-auto custom-scrollbar flex-1 flex flex-col">
                    <form method="POST" action="{{ route('admin.pengajuan.update', $item->id) }}" enctype="multipart/form-data" data-no-ajax onsubmit="return konfirmasiTolak(this)" class="flex-1 flex flex-col justify-between">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="mb-4 rounded-xl border-2 border-[#FDA29B] bg-[#FEF3F2] p-3 text-[#B42318] shrink-0">
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
                                                 
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch flex-1">
                            
                            {{-- KOLOM 1: INFORMASI PEMOHON --}}
                            <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 flex flex-col justify-between h-full">
                                <div>
                                    <div class="flex items-center gap-2.5 mb-3.5">
                                        <div class="w-7 h-7 rounded-full bg-[#16324F] text-white text-[11px] font-black flex items-center justify-center shadow-sm">1</div>
                                        <h3 class="text-[13.5px] font-extrabold text-[#101828]">Informasi Pemohon</h3>
                                    </div>

                                    <div class="bg-white border border-slate-200 rounded-xl p-3.5 space-y-2.5 shadow-sm">
                                        <div>
                                            <p class="text-[9.5px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nama Pemohon</p>
                                            <p class="text-[13px] font-extrabold text-[#071E3D]">{{ $dataForm['nama'] ?? $item->user->name ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9.5px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">NIP</p>
                                            <p class="text-[12px] font-bold text-[#071E3D]">{{ $dataForm['nip'] ?? $item->user->nip ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9.5px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Instansi</p>
                                            <p class="text-[12px] font-bold text-[#071E3D]">{{ $dataForm['instansi'] ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9.5px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nama Pimpinan</p>
                                            <p class="text-[12px] font-bold text-[#071E3D]">{{ $dataForm['nama_pimpinan'] ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9.5px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">No. HP / WhatsApp</p>
                                            @if(!empty($cleanWa))
                                                <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="text-[12px] font-bold text-emerald-600 hover:underline inline-flex items-center gap-1.5"><i class="fa-brands fa-whatsapp text-sm"></i> {{ $dataForm['no_hp'] ?? '-' }}</a>
                                            @else
                                                <span class="text-[12px] font-bold text-gray-600">{{ $dataForm['no_hp'] ?? '-' }}</span>
                                            @endif
                                        </div>
                                        <div class="bg-indigo-50 p-2.5 rounded-lg border border-indigo-100">
                                            <p class="text-[9px] font-bold text-indigo-600 uppercase tracking-wider mb-0.5">Nama Domain / Website</p>
                                            <p class="text-[12.5px] font-black text-indigo-800 break-all">{{ $dataForm['nama_website'] ?? $dataForm['domain'] ?? '-' }}</p>
                                        </div>

                                        @php
                                            $filePemohon = $item->file_pendukung 
                                                ?? ($dataForm['surat_permohonan'] ?? ($dataForm['file'] ?? ($dataForm['berkas'] ?? ($dataForm['dokumen'] ?? ($dataForm['file_persyaratan'] ?? null)))));
                                        @endphp

                                        @if(!empty($filePemohon))
                                            <div class="pt-2.5 border-t border-slate-100">
                                                <p class="text-[9.5px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Berkas / Surat Pemohon</p>
                                                <a href="{{ route('dokumen.unduh', ['pengajuan' => $item->id, 'jenis' => 'pendukung']) }}" target="_blank" class="flex items-center justify-between p-2.5 rounded-xl border border-indigo-200 bg-indigo-50/50 hover:bg-indigo-100/70 transition-all text-indigo-900 group shadow-sm">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs shrink-0 shadow-sm">
                                                            <i class="fa-solid fa-file-pdf"></i>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="text-[11.5px] font-bold truncate">Surat Permohonan</p>
                                                            <p class="text-[9px] text-indigo-600 font-medium">Klik untuk melihat / unduh file</p>
                                                        </div>
                                                    </div>
                                                    <i class="fa-solid fa-download text-xs text-indigo-500 group-hover:text-indigo-800 transition-colors mr-1"></i>
                                                </a>
                                            </div>
                                        @else
                                            <div class="pt-2 border-t border-slate-100">
                                                <p class="text-[9.5px] font-bold text-gray-400 uppercase tracking-wider mb-1">Berkas / Surat Pemohon</p>
                                                <div class="p-2 rounded-lg border border-dashed border-gray-200 bg-gray-50/70 text-center">
                                                    <p class="text-[10px] font-medium text-gray-400 italic">Tidak ada berkas diunggah oleh pemohon</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM 2: STATUS & HASIL --}}
                            <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 flex flex-col justify-between h-full">
                                <div>
                                    <div class="flex items-center justify-between mb-3.5">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-full bg-[#16324F] text-white text-[11px] font-black flex items-center justify-center shadow-sm">2</div>
                                            <h3 class="text-[13.5px] font-extrabold text-[#101828]">Status & Hasil</h3>
                                        </div>
                                        <span class="text-[9px] bg-sky-100 text-sky-800 border border-sky-200 px-2 py-0.5 rounded-md font-bold">Wajib Diupdate</span>
                                    </div>

                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Pilih Status Baru <span class="text-rose-500">*</span></label>
                                            <div class="bg-white border border-slate-200 rounded-xl flex items-center px-3 relative shadow-sm">
                                                <i class="fa-solid fa-bars-progress text-indigo-600 text-[13px] mr-2"></i>
                                                <select name="status" required onchange="toggleHasilUpload(this, {{ $item->id }})" class="flex-1 min-w-0 bg-transparent outline-none py-2 text-[12px] text-[#101828] font-bold appearance-none cursor-pointer">
                                                    @foreach(['Pending' => 'PENDING', 'Proses' => 'PROSES', 'Selesai' => 'SELESAI', 'Ditolak' => 'DITOLAK'] as $val => $label)
                                                        <option value="{{ $val }}" @selected($item->status == $val)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                <i class="fa-solid fa-chevron-down text-xs text-[#667085] pointer-events-none ml-2"></i>
                                            </div>
                                        </div>

                                        <div id="hasil-upload-{{ $item->id }}" class="{{ $item->status === 'Selesai' ? '' : 'hidden' }}">
                                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Upload Dokumen Hasil / Akses Web (PDF)</label>
                                            <label for="admin-hasil-upload-{{ $item->id }}" class="group flex items-center justify-between gap-2 rounded-xl border-2 border-dashed border-[#DCE1E8] bg-white hover:border-indigo-500 hover:bg-indigo-50/40 transition-all px-3 py-2 cursor-pointer shadow-sm">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <div class="w-7 h-7 shrink-0 rounded-lg bg-slate-100 group-hover:bg-indigo-500 group-hover:text-white flex items-center justify-center text-[#667085] transition-colors shadow-sm">
                                                        <i class="fa-solid fa-file-pdf text-[13px]"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p id="admin-hasil-name-{{ $item->id }}" class="text-[11.5px] text-[#101828] font-bold group-hover:text-indigo-900 truncate">
                                                            @if(!empty($dataForm['file_hasil']))
                                                                File Hasil Tersedia
                                                                <a href="{{ route('dokumen.unduh', ['pengajuan' => $item->id, 'jenis' => 'hasil']) }}" target="_blank" class="inline-flex items-center gap-1 ml-1.5 text-sky-700 bg-sky-100 hover:bg-sky-200 px-2 py-0.5 rounded-md text-[10.5px] font-bold transition-colors">
                                                                    <i class="fa-solid fa-eye text-[9px]"></i> Lihat File Hasil
                                                                </a>
                                                            @else
                                                                Pilih Berkas PDF Hasil
                                                            @endif
                                                        </p>
                                                        <p class="text-[10px] text-[#667085] truncate">Dokumen hasil pembuatan</p>
                                                    </div>
                                                </div>
                                                <div class="px-2.5 py-1 bg-slate-50 border border-gray-200 rounded-lg text-[10.5px] font-bold text-gray-600 group-hover:border-indigo-300 shrink-0">Browse</div>
                                                <input id="admin-hasil-upload-{{ $item->id }}" name="file_hasil" type="file" class="sr-only" accept=".pdf" onchange="if(this.files && this.files[0]){document.getElementById('admin-hasil-name-{{ $item->id }}').innerText = this.files[0].name; document.getElementById('admin-hasil-name-{{ $item->id }}').classList.add('text-emerald-700')}">
                                            </label>
                                        </div>

                                        <div>
                                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Catatan E-Tracking</label>
                                            <textarea name="catatan" placeholder="Tuliskan catatan progres yang akan muncul di E-Tracking pemohon..." class="w-full bg-white border border-slate-200 rounded-xl p-3 text-[12px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-indigo-400 resize-none h-28 shadow-sm">{{ old('catatan') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM 3: DISKUSI & BALAS PESAN --}}
                            <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 flex flex-col justify-between h-full">
                                <div>
                                    <div class="flex items-center gap-2.5 mb-3.5">
                                        <div class="w-7 h-7 rounded-full bg-[#16324F] text-white text-[11px] font-black flex items-center justify-center shadow-sm">3</div>
                                        <h3 class="text-[13.5px] font-extrabold text-[#101828]">Diskusi & Balas Pesan</h3>
                                    </div>

                                    <div id="chat-box-{{ $item->id }}" class="bg-white border border-slate-200 rounded-xl p-3 h-56 overflow-y-auto space-y-2.5 custom-scrollbar shadow-sm">
                                        @if(!empty($item->pesan) && is_array($item->pesan))
                                            @foreach($item->pesan as $chat)
                                                <div class="flex flex-col {{ ($chat['role'] ?? '') === 'admin' ? 'items-end' : 'items-start' }}">
                                                    <div class="max-w-[85%] px-3 py-1.5 rounded-xl text-[11.5px] {{ ($chat['role'] ?? '') === 'admin' ? 'bg-[#16324F] text-white rounded-br-none' : 'bg-slate-100 border border-slate-200 text-[#101828] rounded-bl-none' }}">
                                                        <p class="font-bold text-[9.5px] opacity-80 mb-0.5">{{ $chat['pengirim'] ?? 'Pengguna' }}</p>
                                                        <p class="leading-relaxed">{{ $chat['isi'] ?? '-' }}</p>
                                                    </div>
                                                    <span class="text-[9px] text-[#667085] mt-0.5">{{ $chat['waktu'] ?? '' }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="flex flex-col items-center justify-center h-full text-slate-400 py-6">
                                                <i class="fa-regular fa-comments text-2xl mb-1 opacity-40"></i>
                                                <p class="text-[11.5px] italic">Belum ada obrolan dengan pemohon.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3">
                                    @if($chatAktif ?? true)
                                        <div class="bg-white border border-slate-200 rounded-xl flex items-center px-3 shadow-sm">
                                            <i class="fa-regular fa-paper-plane text-indigo-600 text-[13px] mr-2"></i>
                                            <input type="text" name="pesan" value="{{ old('pesan') }}" placeholder="Ketik pesan balasan atau pertanyaan..." class="flex-1 min-w-0 bg-transparent outline-none py-2 text-[12px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                                        </div>
                                    @else
                                        <div class="bg-rose-50 border border-rose-100 p-2 rounded-xl text-center shadow-sm">
                                            <p class="text-[11px] font-bold text-rose-500"><i class="fa-solid fa-lock mr-1"></i> Fitur obrolan dinonaktifkan.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>

                        {{-- Footer Action Buttons --}}
                        <div class="flex items-center justify-end gap-3 mt-5 pt-3.5 border-t border-[#E4E7EC] shrink-0">
                            <button type="button" onclick="tutupModalAdmin('{{ $item->id }}')" class="px-5 py-2.5 rounded-xl text-[12.5px] font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" class="inline-flex items-center gap-2 bg-[#16324F] hover:bg-[#0F2438] active:scale-95 text-white px-6 py-2.5 rounded-xl font-bold text-[13px] transition-all shadow-md shadow-[#16324F]/20 hover:shadow-lg cursor-pointer">
                                Simpan Perubahan <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                            </button>
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

    {{-- MODAL TAMBAH PENGAJUAN (CREATE TANPA DOKUMEN PERSYARATAN) --}}
    <div id="modal-create" class="fixed inset-0 z-[150] hidden items-center justify-center">
        <div class="absolute inset-0 bg-[#101828]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalCreate()"></div>
        <div class="relative bg-white rounded-[1.5rem] w-full max-w-3xl mx-4 shadow-2xl overflow-hidden max-h-[95vh] flex flex-col animate-fade-in-down">
            <div class="flex items-center justify-between gap-4 px-6 md:px-8 py-4 border-b border-[#E4E7EC] bg-gradient-to-r from-[#F8FAFC] to-[#F1F5F9] shrink-0">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-[#16324F] text-indigo-400 flex items-center justify-center text-lg shadow-md shadow-[#16324F]/20">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-[0.16em] text-indigo-700 uppercase mb-0.5">Admin Panel</p>
                        <h2 class="text-[17px] font-extrabold text-[#101828] leading-tight">Pengajuan Website</h2>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline-flex items-center gap-1.5 text-[10.5px] font-bold text-indigo-800 bg-indigo-50 border border-indigo-200 rounded-full px-3 py-1 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Layanan Website
                    </span>
                    <button type="button" onclick="tutupModalCreate()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
            </div>

            <div class="px-6 md:px-8 py-5 overflow-y-auto custom-scrollbar">
                <form method="POST" action="{{ route('admin.pengajuan.storeWebsite') }}" enctype="multipart/form-data" data-no-ajax onsubmit="return konfirmasiTolak(this)">
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

                    {{-- Step 1: Identitas Pemohon & Instansi --}}
                    <div class="relative pl-10 mb-5">
                        <div class="absolute left-0 top-0 w-7 h-7 rounded-full bg-[#16324F] text-white text-[11.5px] font-black flex items-center justify-center ring-4 ring-slate-100 shadow-sm">1</div>
                        <div class="flex items-center gap-2 mb-3">
                            <h3 class="text-[14px] font-extrabold text-[#101828]">Identitas Pemohon & Instansi</h3>
                            <span class="text-[9px] bg-sky-50 text-sky-700 border border-sky-200 px-2 py-0.5 rounded font-bold">Wajib Diisi</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3.5">
                            @include('admin.partials.select-asn', ['prefix' => 'website'])
                            <div>
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Nama Pemohon <span class="text-rose-500">*</span></label>
                                <input type="text" name="data_pengajuan[nama]" value="{{ old('data_pengajuan.nama') }}" required placeholder="Nama lengkap..." class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-indigo-500 shadow-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">NIP Pemohon</label>
                                <input type="text" inputmode="numeric" name="data_pengajuan[nip]" id="nip-field-website" value="{{ old('data_pengajuan.nip') }}" maxlength="17" placeholder="Masukkan NIP (opsional, maksimal 17 digit)..." class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-indigo-500 shadow-sm transition-all">
                                <input type="hidden" name="data_pengajuan[perketat_nip]" id="nip-ketat-val-website" value="0">
                                <label class="inline-flex items-center gap-1.5 mt-1.5 text-[10.5px] font-semibold text-[#475467] cursor-pointer select-none">
                                    <input type="checkbox" id="nip-ketat-website" class="accent-indigo-600 w-3 h-3 rounded" onchange="toggleNipKetat(this, 'website')">
                                    Perketat NIP (18 digit wajib)
                                </label>
                            </div>
                            <div>
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Instansi / Unit Kerja <span class="text-rose-500">*</span></label>
                                <input type="text" name="data_pengajuan[instansi]" value="{{ old('data_pengajuan.instansi') }}" required placeholder="Contoh: Dinas Kesehatan" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-indigo-500 shadow-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Jabatan Operator <span class="text-rose-500">*</span></label>
                                <input type="text" name="data_pengajuan[jabatan]" value="{{ old('data_pengajuan.jabatan') }}" required placeholder="Contoh: Pranata Komputer" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-indigo-500 shadow-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Email Dinas <span class="text-rose-500">*</span></label>
                                <input type="email" name="data_pengajuan[email_dinas]" value="{{ old('data_pengajuan.email_dinas') }}" required placeholder="nama@acehbaratkab.go.id" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-indigo-500 shadow-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Email Alternatif (Google) <span class="text-rose-500">*</span></label>
                                <input type="email" name="data_pengajuan[email_google]" value="{{ old('data_pengajuan.email_google') }}" required placeholder="nama@gmail.com" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-indigo-500 shadow-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Nomor HP / WhatsApp <span class="text-rose-500">*</span></label>
                                <input type="tel" inputmode="numeric" name="data_pengajuan[no_hp]" value="{{ old('data_pengajuan.no_hp') }}" required placeholder="08xxxxxxxxxx" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-indigo-500 shadow-sm transition-all">
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Nama Pimpinan <span class="text-rose-500">*</span></label>
                                <input type="text" name="data_pengajuan[nama_pimpinan]" value="{{ old('data_pengajuan.nama_pimpinan') }}" required placeholder="Masukkan nama pimpinan instansi..." class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-indigo-500 shadow-sm transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Detail Website --}}
                    <div class="relative pl-10 mb-3">
                        <div class="absolute left-0 top-0 w-7 h-7 rounded-full bg-[#16324F] text-white text-[11.5px] font-black flex items-center justify-center ring-4 ring-slate-100 shadow-sm">2</div>
                        <h3 class="text-[14px] font-extrabold text-[#101828] mb-3">Detail Website</h3>
                        <input type="hidden" name="jenis_layanan" value="Pembuatan Website">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Nama Website Usulan <span class="text-rose-500">*</span></label>
                                <input type="text" name="data_pengajuan[nama_website]" value="{{ old('data_pengajuan.nama_website') }}" required placeholder="Contoh: Website Resmi Dinas Kesehatan" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-indigo-500 shadow-sm transition-all">
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Upload Surat / Berkas Permohonan (PDF) <span class="text-rose-500">*</span></label>
                                <label for="admin-website-upload" class="group flex items-center justify-between gap-3 rounded-xl border-2 border-dashed border-[#DCE1E8] bg-white hover:border-indigo-500 hover:bg-indigo-50/40 transition-all px-4 py-2.5 cursor-pointer shadow-sm">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 shrink-0 rounded-lg bg-slate-100 group-hover:bg-indigo-500 group-hover:text-white flex items-center justify-center text-[#667085] transition-colors shadow-sm">
                                            <i class="fa-solid fa-file-pdf text-[14px]"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p id="admin-website-file-name" class="text-[12.5px] text-[#101828] font-bold group-hover:text-indigo-900 truncate">Klik untuk memilih berkas</p>
                                            <p class="text-[10.5px] text-[#667085] font-medium mt-0.5 truncate">Format PDF &middot; Maksimal 5MB</p>
                                        </div>
                                    </div>
                                    <div class="px-3 py-1.5 bg-slate-50 border border-gray-200 rounded-lg text-[11px] font-bold text-gray-600 group-hover:border-indigo-300 shrink-0">Browse</div>
                                    <input id="admin-website-upload" name="file_persyaratan" type="file" class="sr-only" accept=".pdf" required onchange="if(this.files && this.files[0]){document.getElementById('admin-website-file-name').innerText = this.files[0].name; document.getElementById('admin-website-file-name').classList.add('text-emerald-700', 'font-bold')}">
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