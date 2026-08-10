<!-- Tabel Daftar Permohonan -->
<div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col mt-6">
    
    <!-- Notifikasi -->
    @if(session('sukses'))
        <div class="bg-green-50 text-green-600 px-6 py-3 border-b border-green-100 text-[13px] font-bold flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('sukses') }}
        </div>
    @endif

    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-[#071E3D]">Daftar Ajuan Website</h3>
            <p class="text-[12px] text-gray-400 font-medium mt-1">Kelola data dan perbarui status progres pembuatan website.</p>
        </div>
        
        <!-- FORM PENCARIAN, FILTER & TOMBOL CREATE -->
        <div class="flex gap-3 items-center">
            <form method="GET" action="{{ route('admin.website.index') }}" class="flex gap-3">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Tiket/Pemohon..." class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none focus:border-cyan-400 focus:bg-white w-48 transition-all">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-filter absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <select name="status" onchange="this.form.submit()" class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                        @foreach(['Pending' => 'Pending', 'Verifikasi Doc' => 'Verifikasi Dokumen', 'Proses Development' => 'Proses Development', 'Selesai' => 'Selesai', 'Ditolak' => 'Ditolak'] as $val => $label)
                            <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <button type="button" onclick="bukaModalCreate()" class="px-4 py-2 bg-[#071E3D] hover:bg-[#1F4287] text-white text-[12px] font-bold rounded-lg transition-colors shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Ajuan
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[900px]">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                <tr>
                    <th class="py-3 px-6">Id Permohonan</th>
                    <th class="py-3 px-6">Nama Pemohon</th>
                    <th class="py-3 px-6">Layanan</th>
                    <th class="py-3 px-6">Tanggal</th>
                    <th class="py-3 px-6">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                
                @forelse ($pengajuans as $item)
                @php
                    $dataForm = is_array($item->data_pengajuan) ? $item->data_pengajuan : json_decode($item->data_pengajuan ?? '[]', true);
                @endphp
                <tr class="hover:bg-cyan-50/20 transition-colors duration-200">
                    
                    <!-- 1. ID Tiket -->
                    <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D]">
                        {{ $item->nomor_tiket }}
                    </td>
                    
                    <!-- 2. Nama Pemohon & NIP (Tanpa Ikon Bulat) -->
                    <td class="py-4 px-6">
                        <p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['nama'] ?? $item->user->name ?? 'Pemohon' }}</p>
                        <p class="text-[11px] text-gray-500 font-medium mt-0.5">NIP: {{ $dataForm['nip'] ?? $item->user->nip ?? '-' }}</p>
                    </td>
                    
                    <!-- 3. Layanan & Nama Domain -->
                    <td class="py-4 px-6">
                        <p class="text-[13px] font-bold text-[#071E3D] capitalize">
                            {{ str_replace('_', ' ', $item->jenis_layanan) }}
                        </p>
                        @if(!empty($dataForm['domain']))
                            <p class="text-[11px] text-blue-500 font-medium mt-0.5">
                                {{ $dataForm['domain'] }}
                            </p>
                        @endif
                    </td>
                    
                    <!-- 4. Tanggal Masuk -->
                    <td class="py-4 px-6 text-[12px] text-gray-500 font-bold">
                        {{ $item->created_at->format('d M Y') }}
                    </td>
                    
                    <!-- 5. Status -->
                    <td class="py-4 px-6">
                        @php
                            $badgeColor = match($item->status) {
                                'Pending', 'Menunggu Validasi' => 'bg-amber-50 text-amber-600 border-amber-100',
                                'Verifikasi Doc', 'Proses Development' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'Ditolak' => 'bg-rose-50 text-rose-600 border-rose-100',
                                default   => 'bg-gray-50 text-gray-600 border-gray-100'
                            };
                        @endphp
                        <span class="px-3 py-1.5 rounded-lg border text-[10px] font-extrabold uppercase tracking-wider {{ $badgeColor }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    
                    <!-- 6. Aksi -->
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

                <!-- ======================================================= -->
                <!-- MODAL 3: KONFIRMASI HAPUS PERMANEN -->
                <!-- ======================================================= -->
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

                <!-- ======================================================= -->
                <!-- MODAL 1: INFO DETAIL PEMOHON & BERKAS AWAL -->
                <!-- ======================================================= -->
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
                                <i class="fa-solid fa-desktop"></i>
                            </div>
                            <h3 class="text-xl font-extrabold text-[#071E3D]">{{ $dataForm['nama'] ?? $item->user->name ?? '-' }}</h3>
                            <p class="text-[12px] text-gray-500 font-medium bg-gray-100 px-3 py-1 rounded-full mt-2">NIP: {{ $dataForm['nip'] ?? $item->user->nip ?? 'Tidak ada' }}</p>
                        </div>
                        
                        <div class="space-y-4 bg-gray-50 p-5 rounded-2xl border border-gray-100">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Asal Instansi / SKPK</p>
                                <p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['instansi'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nama Kepala Dinas / Pimpinan</p>
                                <p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['nama_pimpinan'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nomor HP / WhatsApp</p>
                                <p class="text-[13px] font-bold text-[#071E3D]"><i class="fa-brands fa-whatsapp text-green-500 mr-1"></i> {{ $dataForm['no_hp'] ?? '-' }}</p>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-xl border border-blue-100">
                                <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider mb-0.5">Usulan Nama Domain</p>
                                <p class="text-[14px] font-black text-blue-700">{{ $dataForm['domain'] ?? '-' }}<span class="text-blue-400">.go.id</span></p>
                            </div>
                        </div>

                        <div class="mt-6">
                            @if(!empty($item->file_pendukung))
                                <a href="{{ asset('storage/' . $item->file_pendukung) }}" target="_blank" class="w-full block text-center bg-[#071E3D] hover:bg-[#1F4287] text-white py-3 rounded-xl font-bold text-[13px] transition-all shadow-md">
                                    <i class="fa-solid fa-file-pdf mr-2"></i> Download Surat Permohonan
                                </a>
                            @else
                                <div class="text-center text-[12px] text-gray-400 italic bg-gray-100 py-3 rounded-xl border border-dashed border-gray-200">
                                    Tidak ada dokumen yang dilampirkan pemohon.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ======================================================= -->
                <!-- MODAL 2: KELOLA STATUS, PESAN & UPLOAD BERKAS HASIL -->
                <!-- ======================================================= -->
                <div id="modal-{{ $item->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center">
                    <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalAdmin('{{ $item->id }}')"></div>
                    <div class="relative bg-white rounded-[2rem] p-8 max-w-xl w-full mx-4 shadow-2xl overflow-y-auto max-h-[95vh] custom-scrollbar">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-extrabold text-[#071E3D]">Kelola Permohonan</h3>
                                <p class="text-[12px] text-gray-500">Update progres, serahkan berkas hasil, dan balas pesan.</p>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('admin.pengajuan.update', $item->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="bg-gray-50/50 border border-gray-100 p-5 rounded-2xl mb-5">
                                <h4 class="text-[13px] font-extrabold text-[#071E3D] mb-4 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fa-solid fa-bars-progress text-cyan-500"></i> 1. Update Timeline & Berkas
                                </h4>
                                
                                <div class="space-y-4">
                                    <!-- Update Status -->
                                    <div>
                                        <label class="block text-[12px] font-bold text-[#071E3D] mb-2">Pilih Status Baru</label>
                                        <select name="status" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[13px] text-[#071E3D] font-bold focus:border-cyan-400 outline-none transition-colors">
                                            @foreach(['Pending' => 'PENDING', 'Verifikasi Doc' => 'VERIFIKASI DOC', 'Proses Development' => 'PROSES DEVELOPMENT', 'Selesai' => 'SELESAI', 'Ditolak' => 'DITOLAK'] as $val => $label)
                                                <option value="{{ $val }}" @selected($item->status == $val)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- FITUR UPLOAD BERKAS HASIL SEPERTI DESAIN TTE -->
                                    <div class="mb-4">
                                        <label class="block text-[12px] font-bold text-[#071E3D] mb-2">Upload Dokumen Hasil / Akses Web (Jika Selesai)</label>
                                        <input type="file" name="file_hasil" accept=".pdf" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-[12px] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-blue-600 cursor-pointer">
                                        @if(!empty($item->file_hasil))
                                            <p class="text-[10px] text-green-600 mt-2 font-bold"><i class="fa-solid fa-check mr-1"></i> File hasil sudah pernah diupload.</p>
                                        @endif
                                    </div>

                                    <!-- Catatan -->
                                    <div>
                                        <label class="block text-[12px] font-bold text-[#071E3D] mb-2">Catatan Buat E-Tracking (Opsional)</label>
                                        <textarea name="catatan" rows="2" placeholder="Tuliskan catatan progres yang akan muncul di E-Tracking pemohon..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[13px] outline-none focus:border-cyan-400 resize-none"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50/30 border border-blue-100 p-5 rounded-2xl mb-8">
                                <h4 class="text-[13px] font-extrabold text-[#071E3D] mb-4 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fa-regular fa-comments text-blue-500"></i> 2. Riwayat Diskusi / Chat
                                </h4>
                                
                                <div class="bg-white border border-blue-100 rounded-xl p-4 mb-4 h-48 overflow-y-auto space-y-3 custom-scrollbar">
                                    @if(!empty($item->pesan) && is_array($item->pesan))
                                        @foreach($item->pesan as $chat)
                                            <div class="flex flex-col {{ $chat['role'] === 'admin' ? 'items-end' : 'items-start' }}">
                                                <div class="max-w-[85%] p-3 rounded-2xl text-[12px] {{ $chat['role'] === 'admin' ? 'bg-[#071E3D] text-white rounded-br-none' : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                                                    <p class="font-bold text-[10px] opacity-80 mb-0.5">{{ $chat['pengirim'] }}</p>
                                                    <p>{{ $chat['isi'] }}</p>
                                                </div>
                                                <span class="text-[9px] text-gray-400 mt-1">{{ $chat['waktu'] }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="flex items-center justify-center h-full">
                                            <p class="text-[12px] text-gray-400 italic">Belum ada obrolan.</p>
                                        </div>
                                    @endif
                                </div>
                                @if($chatAktif ?? true)
                                    <div>
                                        <textarea name="pesan" rows="2" placeholder="Ketik pesan balasan atau pertanyaan kepada pemohon di sini..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[13px] outline-none focus:border-blue-400 resize-none"></textarea>
                                    </div>
                                @else
                                    <div class="bg-rose-50 border border-rose-100 p-3 rounded-xl text-center">
                                        <p class="text-[12px] font-bold text-rose-500"><i class="fa-solid fa-lock mr-1"></i> Fitur obrolan sedang dinonaktifkan sementara oleh Admin.</p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex gap-3">
                                <button type="button" onclick="tutupModalAdmin('{{ $item->id }}')" class="flex-1 py-3.5 rounded-xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-colors">Batal</button>
                                <button type="submit" class="flex-1 py-3.5 rounded-xl font-bold text-white bg-[#071E3D] hover:bg-[#1F4287] transition-colors shadow-lg shadow-blue-900/20">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-folder-open text-2xl text-gray-300"></i>
                        </div>
                        <h3 class="font-bold text-[14px] text-gray-600">Belum ada data permohonan</h3>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>