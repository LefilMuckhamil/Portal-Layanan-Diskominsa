<div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col mt-6">
    @if(session('sukses'))
        <div class="bg-green-50 text-green-600 px-6 py-3 border-b border-green-100 text-[13px] font-bold flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('sukses') }}
        </div>
    @endif

    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-[#071E3D]">Daftar Permohonan TTE</h3>
            <p class="text-[12px] text-gray-400 font-medium mt-1">Kelola data dan perbarui status progres permohonan TTE.</p>
        </div>
        
        <div class="flex gap-3 items-center">
            <form method="GET" action="{{ route('admin.tte.index') }}" class="flex gap-3">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Tiket #TTE-..." class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none focus:border-cyan-400 focus:bg-white w-48 transition-all">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-filter absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <select name="status" onchange="this.form.submit()" class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                        @foreach(['Pending' => 'Pending', 'Proses' => 'Proses', 'Selesai' => 'Selesai', 'Ditolak' => 'Ditolak'] as $val => $label)
                            <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <button type="button" onclick="bukaModalCreate()" class="px-4 py-2 bg-[#071E3D] hover:bg-[#1F4287] text-white text-[12px] font-bold rounded-lg transition-colors shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Permohonan
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[900px]">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                <tr>
                    <th class="py-3 px-6">Tiket</th>
                    <th class="py-3 px-6">Nama Pemohon</th>
                    <th class="py-3 px-6">Instansi</th>
                    <th class="py-3 px-6">Tanggal</th>
                    <th class="py-3 px-6">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($pengajuans as $item)
                    @php
                        $dataForm = is_array($item->data_pengajuan) ? $item->data_pengajuan : json_decode($item->data_pengajuan ?? '[]', true);
                        $badgeColor = match($item->status) {
                            'Pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'Proses' => 'bg-blue-50 text-blue-600 border-blue-100',
                            'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'Ditolak' => 'bg-rose-50 text-rose-600 border-rose-100',
                            default   => 'bg-gray-50 text-gray-600 border-gray-100'
                        };
                    @endphp
                    <tr class="hover:bg-cyan-50/20 transition-colors duration-200">
                        <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D]">
                            {{ $item->nomor_tiket }}
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-[13px] font-bold text-[#071E3D]">
                                {{ $dataForm['nama'] ?? $dataForm['nama_lengkap'] ?? $item->user->name ?? 'Pemohon' }}
                            </p>
                            <p class="text-[11px] text-gray-500 font-medium mt-0.5">
                                NIP: {{ $dataForm['nip'] ?? $item->user->nip ?? '-' }}
                            </p>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-[13px] font-bold text-[#071E3D] capitalize">
                                {{ $dataForm['instansi'] ?? '-' }}
                            </p>
                            @if(!empty($dataForm['no_hp']))
                                <p class="text-[11px] text-gray-500 font-medium mt-0.5">
                                    <i class="fa-brands fa-whatsapp text-green-500 mr-1"></i>{{ $dataForm['no_hp'] }}
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
                            <button type="button" onclick="bukaModalInfo('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm" title="Lihat Detail Ajuan">
                                <i class="fa-regular fa-id-card text-xs"></i>
                            </button>
                            <button type="button" onclick="bukaModalAdmin('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-cyan-50 hover:text-cyan-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm" title="Kelola Progres & Pesan">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <form id="form-delete-{{ $item->id }}" action="{{ route('admin.pengajuan.destroy', $item->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="bukaModalDelete('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-rose-50 hover:text-rose-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm" title="Hapus Permanen">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <div id="modal-{{ $item->id }}" class="fixed inset-0 z-[150] hidden items-center justify-center">
                        <div class="absolute inset-0 bg-[#101828]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalAdmin('{{ $item->id }}')"></div>
                        <div class="relative bg-white rounded-[1.5rem] w-full max-w-3xl mx-4 shadow-2xl overflow-hidden max-h-[95vh] flex flex-col animate-fade-in-down">
                            <div class="flex items-center justify-between gap-4 px-6 md:px-8 py-4 border-b border-[#E4E7EC] bg-gradient-to-r from-[#F8FAFC] to-[#F1F5F9] shrink-0">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-[#16324F] text-blue-400 flex items-center justify-center text-lg shadow-md shadow-[#16324F]/20">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold tracking-[0.16em] text-blue-700 uppercase mb-0.5">Admin Panel</p>
                                        <h2 class="text-[17px] font-extrabold text-[#101828] leading-tight">Kelola Permohonan TTE</h2>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1.5 text-[10.5px] font-bold text-blue-800 bg-blue-50 border border-blue-200 rounded-full px-3 py-1 shrink-0">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> {{ $item->nomor_tiket }}
                                    </span>
                                    <button type="button" onclick="tutupModalAdmin('{{ $item->id }}')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm cursor-pointer">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="px-6 md:px-8 py-5 overflow-y-auto custom-scrollbar flex-1 flex flex-col">
                                <form method="POST" action="{{ route('admin.pengajuan.update', $item->id) }}" enctype="multipart/form-data" onsubmit="disableSubmitButton(this)" class="flex-1 flex flex-col justify-between">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch flex-1">
                                        <div class="relative dk-rail-modal pl-10 flex flex-col h-full">
                                            <div class="absolute left-0 top-0 w-7 h-7 rounded-full bg-[#16324F] text-white text-[11.5px] font-black flex items-center justify-center ring-4 ring-slate-100 shadow-sm">1</div>
                                            <div class="flex items-center gap-2 mb-3 shrink-0">
                                                <h3 class="text-[14px] font-extrabold text-[#101828]">Status & Hasil</h3>
                                                <span class="text-[9px] bg-sky-50 text-sky-700 border border-sky-200 px-2 py-0.5 rounded font-bold">Wajib Diupdate</span>
                                            </div>
                                            
                                            <div class="space-y-3 flex-1 flex flex-col justify-between">
                                                <div>
                                                    <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Status Pengajuan <span class="text-rose-500">*</span></label>
                                                    <div class="dk-input-modal flex items-center px-3 relative">
                                                        <i class="fa-solid fa-bars-progress text-blue-600 text-[13px] mr-2"></i>
                                                        <select name="status" required class="flex-1 min-w-0 bg-transparent outline-none py-2 text-[12.5px] text-[#101828] font-bold appearance-none cursor-pointer">
                                                            @foreach(['Pending' => 'PENDING', 'Proses' => 'PROSES', 'Selesai' => 'SELESAI', 'Ditolak' => 'DITOLAK'] as $val => $label)
                                                                <option value="{{ $val }}" @selected($item->status == $val)>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i class="fa-solid fa-chevron-down text-xs text-[#667085] pointer-events-none ml-2"></i>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Upload Berkas Hasil TTE (PDF)</label>
                                                    <label for="admin-hasil-upload-{{ $item->id }}" class="group flex items-center justify-between gap-2 rounded-xl border-2 border-dashed border-[#DCE1E8] hover:border-blue-500 hover:bg-blue-50/40 transition-all px-3 py-1.5 cursor-pointer shadow-sm">
                                                        <div class="flex items-center gap-2 min-w-0">
                                                            <div class="w-7 h-7 shrink-0 rounded-lg bg-slate-100 group-hover:bg-blue-500 group-hover:text-white flex items-center justify-center text-[#667085] transition-colors shadow-sm">
                                                                <i class="fa-solid fa-file-pdf text-[13px]"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <p id="admin-hasil-name-{{ $item->id }}" class="text-[11.5px] text-[#101828] font-bold group-hover:text-blue-900 truncate">
                                                                    {{ isset($dataForm['file_hasil']) ? 'File Hasil Tersedia' : 'Pilih Berkas PDF Hasil' }}
                                                                </p>
                                                                <p class="text-[10px] text-[#667085] truncate">Dokumen berttd elektronik</p>
                                                            </div>
                                                        </div>
                                                        <div class="px-2.5 py-1 bg-white border border-gray-200 rounded-lg text-[10.5px] font-bold text-gray-600 group-hover:border-blue-300 shrink-0">Browse</div>
                                                        <input id="admin-hasil-upload-{{ $item->id }}" name="file_hasil" type="file" class="sr-only" accept=".pdf" onchange="document.getElementById('admin-hasil-name-{{ $item->id }}').innerText = this.files[0].name; document.getElementById('admin-hasil-name-{{ $item->id }}').classList.add('text-emerald-700')">
                                                    </label>
                                                </div>

                                                <div class="flex-1 flex flex-col">
                                                    <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Catatan E-Tracking</label>
                                                    <textarea name="catatan" placeholder="Tuliskan catatan progres pemohon..." class="dk-input-modal w-full p-3 text-[12px] text-[#101828] font-medium placeholder:text-[#98A2B3] resize-none flex-1 min-h-[90px]">{{ old('catatan') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="relative pl-10 flex flex-col h-full">
                                            <div class="absolute left-0 top-0 w-7 h-7 rounded-full bg-[#16324F] text-white text-[11.5px] font-black flex items-center justify-center ring-4 ring-slate-100 shadow-sm">2</div>
                                            <div class="flex items-center gap-2 mb-3 shrink-0">
                                                <h3 class="text-[14px] font-extrabold text-[#101828]">Diskusi & Balas Pesan</h3>
                                            </div>
                                            
                                            <div class="space-y-3 flex-1 flex flex-col justify-between">
                                                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3 flex-1 min-h-[175px] max-h-[195px] overflow-y-auto space-y-2.5 custom-scrollbar">
                                                    @if(!empty($item->pesan) && is_array($item->pesan))
                                                        @foreach($item->pesan as $chat)
                                                            <div class="flex flex-col {{ $chat['role'] === 'admin' ? 'items-end' : 'items-start' }}">
                                                                <div class="max-w-[85%] px-3 py-1.5 rounded-xl text-[11.5px] {{ $chat['role'] === 'admin' ? 'bg-[#16324F] text-white rounded-br-none' : 'bg-white border border-slate-200 text-[#101828] rounded-bl-none shadow-sm' }}">
                                                                    <p class="font-bold text-[9.5px] opacity-80 mb-0.5">{{ $chat['pengirim'] }}</p>
                                                                    <p class="leading-relaxed">{{ $chat['isi'] }}</p>
                                                                </div>
                                                                <span class="text-[9px] text-[#667085] mt-0.5">{{ $chat['waktu'] }}</span>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="flex flex-col items-center justify-center h-full text-slate-400 py-6">
                                                            <i class="fa-regular fa-comments text-2xl mb-1 opacity-40"></i>
                                                            <p class="text-[11.5px] italic">Belum ada obrolan dengan pemohon.</p>
                                                        </div>
                                                    @endif
                                                </div>

                                                @if($chatAktif ?? true)
                                                    <div class="dk-input-modal flex items-center px-3 shrink-0">
                                                        <i class="fa-regular fa-paper-plane text-blue-600 text-[13px] mr-2"></i>
                                                        <input type="text" name="pesan" value="{{ old('pesan') }}" placeholder="Ketik pesan balasan..." class="flex-1 min-w-0 bg-transparent outline-none py-2 text-[12px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                                                    </div>
                                                @else
                                                    <div class="bg-rose-50 border border-rose-100 p-2 rounded-xl text-center shrink-0">
                                                        <p class="text-[11px] font-bold text-rose-500"><i class="fa-solid fa-lock mr-1"></i> Fitur obrolan dinonaktifkan.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-[#E4E7EC] shrink-0">
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

                    <div id="modal-delete-{{ $item->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center">
                        <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalDelete('{{ $item->id }}')"></div>
                        <div class="relative bg-white rounded-[2rem] p-8 max-w-sm w-full mx-4 shadow-2xl text-center">
                            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-5 border-4 border-white shadow-md">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <h3 class="text-xl font-extrabold text-[#071E3D] mb-2">Hapus Permohonan?</h3>
                            <p class="text-[13px] text-gray-500 mb-6 leading-relaxed">
                                Tindakan ini tidak dapat dibatalkan. Yakin ingin menghapus data permohonan <b class="text-[#071E3D]">{{ $item->nomor_tiket }}</b> secara permanen?
                            </p>
                            <div class="flex gap-3">
                                <button type="button" onclick="tutupModalDelete('{{ $item->id }}')" class="flex-1 py-3 rounded-xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-colors">Batal</button>
                                <button type="button" onclick="document.getElementById('form-delete-{{ $item->id }}').submit()" class="flex-1 py-3 rounded-xl font-bold text-white bg-rose-500 hover:bg-rose-600 transition-colors shadow-lg shadow-rose-500/20">Ya, Hapus!</button>
                            </div>
                        </div>
                    </div>

                    <div id="modal-info-{{ $item->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center">
                        <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalInfo('{{ $item->id }}')"></div>
                        <div class="relative bg-white rounded-[2rem] p-8 max-w-md w-full mx-4 shadow-2xl overflow-y-auto max-h-[90vh] custom-scrollbar">
                            <div class="absolute top-6 right-6">
                                <button onclick="tutupModalInfo('{{ $item->id }}')" class="text-gray-400 hover:text-rose-500 transition-colors">
                                    <i class="fa-solid fa-xmark text-xl"></i>
                                </button>
                            </div>
                            <div class="flex flex-col items-center text-center mb-6">
                                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-3xl mb-4 border-4 border-white shadow-md">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </div>
                                <h3 class="text-xl font-extrabold text-[#071E3D]">{{ $dataForm['nama'] ?? $dataForm['nama_lengkap'] ?? $item->user->name ?? '-' }}</h3>
                                <p class="text-[12px] text-gray-500 font-medium bg-gray-100 px-3 py-1 rounded-full mt-2">NIP: {{ $dataForm['nip'] ?? $item->user->nip ?? 'Tidak ada' }}</p>
                            </div>
                            <div class="space-y-4 bg-gray-50 p-5 rounded-2xl border border-gray-100">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Instansi</p>
                                    <p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['instansi'] ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nomor HP</p>
                                    <p class="text-[13px] font-bold text-[#071E3D]"><i class="fa-brands fa-whatsapp text-green-500 mr-1"></i> {{ $dataForm['no_hp'] ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Email Aktif</p>
                                    <p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['email'] ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Alamat Domisili</p>
                                    <p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['alamat'] ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="mt-6">
                                @if(!empty($item->file_pendukung))
                                    <a href="{{ route('dokumen.unduh', ['pengajuan' => $item->id, 'jenis' => 'pendukung']) }}" target="_blank" class="w-full block text-center bg-[#071E3D] hover:bg-[#1F4287] text-white py-3 rounded-xl font-bold text-[13px] transition-all shadow-md">
                                        <i class="fa-solid fa-file-pdf mr-2"></i> Download Dokumen Persyaratan
                                    </a>
                                @else
                                    <div class="text-center text-[12px] text-gray-400 italic bg-gray-100 py-3 rounded-xl border border-dashed border-gray-200">
                                        Tidak ada dokumen yang dilampirkan pemohon.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-folder-open text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="font-bold text-[14px] text-gray-600">Belum ada data permohonan TTE</h3>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal-create" class="fixed inset-0 z-[150] hidden items-center justify-center">
    <div class="absolute inset-0 bg-[#101828]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalCreate()"></div>
    <div class="relative bg-white rounded-[1.5rem] w-full max-w-3xl mx-4 shadow-2xl overflow-hidden max-h-[95vh] flex flex-col animate-fade-in-down">
        <div class="flex items-center justify-between gap-4 px-6 md:px-8 py-4 border-b border-[#E4E7EC] bg-gradient-to-r from-[#F8FAFC] to-[#F1F5F9] shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#16324F] text-blue-400 flex items-center justify-center text-lg shadow-md shadow-[#16324F]/20">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold tracking-[0.16em] text-blue-700 uppercase mb-0.5">Admin Panel</p>
                    <h2 class="text-[17px] font-extrabold text-[#101828] leading-tight">Pengajuan Sertifikat TTE</h2>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline-flex items-center gap-1.5 text-[10.5px] font-bold text-blue-800 bg-blue-50 border border-blue-200 rounded-full px-3 py-1 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Layanan TTE
                </span>
                <button type="button" onclick="tutupModalCreate()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <div class="px-6 md:px-8 py-5 overflow-y-auto custom-scrollbar">
            <form method="POST" action="{{ route('admin.pengajuan.storeTte') }}" enctype="multipart/form-data" onsubmit="disableSubmitButton(this)">
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

                <div class="relative dk-rail-modal pl-10 mb-4">
                    <div class="absolute left-0 top-0 w-7 h-7 rounded-full bg-[#16324F] text-white text-[11.5px] font-black flex items-center justify-center ring-4 ring-slate-100 shadow-sm">1</div>
                    <div class="flex items-center gap-2 mb-3">
                        <h3 class="text-[14px] font-extrabold text-[#101828]">Identitas Pemohon</h3>
                        <span class="text-[9px] bg-sky-50 text-sky-700 border border-sky-200 px-2 py-0.5 rounded font-bold">Wajib Sesuai KTP</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3 mb-2">
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Pilih ASN Pemohon <span class="text-rose-500">*</span></label>
                            <div class="dk-input-modal flex items-center px-3 relative">
                                <i class="fa-solid fa-user text-blue-600 text-[13px] mr-2"></i>
                                <select name="user_id" required class="flex-1 min-w-0 bg-transparent outline-none py-2 text-[12.5px] text-[#101828] font-bold appearance-none cursor-pointer">
                                    <option value="">Silakan Pilih ASN Terdaftar</option>
                                    @php
                                        $listUsers = $users ?? \App\Models\User::where('role', '!=', 'admin')->get();
                                    @endphp
                                    @forelse($listUsers as $user)
                                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} - {{ $user->nip ?? $user->unit_kerja ?? 'ASN' }}</option>
                                    @empty
                                        <option value="" disabled>Belum ada data ASN</option>
                                    @endforelse
                                </select>
                                <i class="fa-solid fa-chevron-down text-xs text-[#667085] pointer-events-none ml-2"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Nama Lengkap<span class="text-rose-500">*</span></label>
                            <input type="text" name="data_pengajuan[nama]" value="{{ old('data_pengajuan.nama') }}" required placeholder="Contoh: Budi Santoso, S.Kom" class="dk-input-modal w-full px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div>
                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">NIP <span class="text-rose-500">*</span></label>
                            <input type="number" name="data_pengajuan[nip]" value="{{ old('data_pengajuan.nip') }}" required placeholder="Masukkan NIP" class="dk-input-modal w-full px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Instansi<span class="text-rose-500">*</span></label>
                            <input type="text" name="data_pengajuan[instansi]" value="{{ old('data_pengajuan.instansi') }}" required placeholder="Dinas Kesehatan" class="dk-input-modal w-full px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                    </div>
                </div>

                <div class="relative pl-10 mb-2">
                    <div class="absolute left-0 top-0 w-7 h-7 rounded-full bg-[#16324F] text-white text-[11.5px] font-black flex items-center justify-center ring-4 ring-slate-100 shadow-sm">2</div>
                    <h3 class="text-[14px] font-extrabold text-[#101828] mb-3">Kontak & Dokumen Persyaratan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Nomor HP <span class="text-rose-500">*</span></label>
                            <div class="dk-input-modal flex items-center px-3">
                                <i class="fa-brands fa-whatsapp text-emerald-600 text-[13px] mr-2"></i>
                                <input type="number" name="data_pengajuan[no_hp]" value="{{ old('data_pengajuan.no_hp') }}" required placeholder="0812xxxxxxxx" class="flex-1 min-w-0 bg-transparent outline-none py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Email Aktif <span class="text-rose-500">*</span></label>
                            <div class="dk-input-modal flex items-center px-3">
                                <i class="fa-solid fa-envelope text-blue-600 text-[13px] mr-2"></i>
                                <input type="email" name="data_pengajuan[email]" value="{{ old('data_pengajuan.email') }}" required placeholder="email@acehbaratkab.go.id" class="flex-1 min-w-0 bg-transparent outline-none py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                            </div>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Alamat Domisili <span class="text-rose-500">*</span></label>
                            <input type="text" name="data_pengajuan[alamat]" value="{{ old('data_pengajuan.alamat') }}" required placeholder="Masukkan alamat domisili lengkap..." class="dk-input-modal w-full px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Upload Dokumen Persyaratan (PDF) <span class="text-rose-500">*</span></label>
                            <label for="admin-tte-upload" class="group flex items-center justify-between gap-3 rounded-xl border-2 border-dashed border-[#DCE1E8] hover:border-blue-500 hover:bg-blue-50/40 transition-all px-4 py-2 cursor-pointer shadow-sm">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 shrink-0 rounded-lg bg-slate-100 group-hover:bg-blue-500 group-hover:text-white flex items-center justify-center text-[#667085] transition-colors shadow-sm">
                                        <i class="fa-solid fa-file-pdf text-[14px]"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[12.5px] text-[#101828] font-bold group-hover:text-blue-900 truncate">Klik untuk memilih berkas</p>
                                        <p id="admin-tte-name" class="text-[10.5px] text-[#667085] font-medium mt-0.5 truncate">Surat Rekomendasi/KTP (Maks 2MB)</p>
                                    </div>
                                </div>
                                <div class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-[11px] font-bold text-gray-600 group-hover:border-blue-300 shrink-0">Browse</div>
                                <input id="admin-tte-upload" name="file_pendukung" type="file" class="sr-only" accept=".pdf" required onchange="document.getElementById('admin-tte-name').innerText = this.files[0].name; document.getElementById('admin-tte-name').classList.add('text-emerald-700', 'font-bold')">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-[#E4E7EC]">
                    <button type="button" onclick="tutupModalCreate()" class="px-5 py-2.5 rounded-xl text-[12.5px] font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">Batal</button>
                    <button type="submit" class="inline-flex items-center gap-2 bg-[#16324F] hover:bg-[#0F2438] active:scale-95 text-white px-6 py-2.5 rounded-xl font-bold text-[13px] transition-all shadow-md shadow-[#16324F]/20 hover:shadow-lg">
                        Simpan Permohonan <i class="fa-solid fa-paper-plane text-[11px]"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .dk-input-modal, label, button, a, div { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .dk-input-modal { border: 1.5px solid #DCE1E8; border-radius: 10px; background: #FFFFFF; }
    .dk-input-modal:focus-within, .dk-input-modal:focus { outline: none; border-color: #0284C7 !important; box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.12) !important; }
    .dk-rail-modal::before { content: ''; position: absolute; left: 14px; top: 32px; bottom: -10px; width: 2px; background: #E2E8F0; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<script>
    function bukaModalCreate() {
        document.getElementById('modal-create').classList.remove('hidden');
        document.getElementById('modal-create').classList.add('flex');
    }
    function tutupModalCreate() {
        document.getElementById('modal-create').classList.add('hidden');
        document.getElementById('modal-create').classList.remove('flex');
    }

    function bukaModalAdmin(id) {
        document.getElementById('modal-' + id).classList.remove('hidden');
        document.getElementById('modal-' + id).classList.add('flex');
    }
    function tutupModalAdmin(id) {
        document.getElementById('modal-' + id).classList.add('hidden');
        document.getElementById('modal-' + id).classList.remove('flex');
    }

    function bukaModalInfo(id) {
        document.getElementById('modal-info-' + id).classList.remove('hidden');
        document.getElementById('modal-info-' + id).classList.add('flex');
    }
    function tutupModalInfo(id) {
        document.getElementById('modal-info-' + id).classList.add('hidden');
        document.getElementById('modal-info-' + id).classList.remove('flex');
    }
    
    function bukaModalDelete(id) {
        document.getElementById('modal-delete-' + id).classList.remove('hidden');
        document.getElementById('modal-delete-' + id).classList.add('flex');
    }
    function tutupModalDelete(id) {
        document.getElementById('modal-delete-' + id).classList.add('hidden');
        document.getElementById('modal-delete-' + id).classList.remove('flex');
    }

    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            bukaModalCreate();
        });
    @endif

    function disableSubmitButton(form) {
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btn.innerHTML = 'Memproses... <i class="fa-solid fa-spinner fa-spin ml-2"></i>';
        }
    }
</script>