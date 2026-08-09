<div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col mt-6">
    
    @if(session('sukses'))
        <div class="bg-green-50 text-green-600 px-6 py-3 border-b border-green-100 text-[13px] font-bold flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('sukses') }}
        </div>
    @endif

    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-[#071E3D]">Daftar Ajuan TTE</h3>
            <p class="text-[12px] text-gray-400 font-medium mt-1">Pantau status penerbitan sertifikat elektronik BSSN.</p>
        </div>
        
        <div class="flex gap-3 items-center">
            <form method="GET" action="{{ route('admin.tte.index') }}" class="flex gap-3">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP / Nama..." class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none focus:border-cyan-400 focus:bg-white w-48 transition-all">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-filter absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <select name="status" onchange="this.form.submit()" class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Verifikasi Doc" {{ request('status') == 'Verifikasi Doc' ? 'selected' : '' }}>Verifikasi Dokumen</option>
                        <option value="Proses BSSN" {{ request('status') == 'Proses BSSN' ? 'selected' : '' }}>Proses BSSN</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai / Terbit</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
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
                    <th class="py-3 px-6">ID Ajuan</th>
                    <th class="py-3 px-6">Data Pejabat</th>
                    <th class="py-3 px-6">Jabatan / Instansi</th>
                    <th class="py-3 px-6">Tgl Masuk</th>
                    <th class="py-3 px-6">Status Terkini</th>
                    <th class="py-3 px-6 text-center">Aksi (CRUD)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                
                @forelse ($pengajuans as $item)
                @php 
                    $dataForm = is_string($item->data_pengajuan) ? json_decode($item->data_pengajuan, true) : ($item->data_pengajuan ?? []);
                @endphp
                <tr class="hover:bg-cyan-50/20 transition-colors duration-200">
                    <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D]">
                        #TTE-{{ strtoupper(substr($item->id, -5)) }}
                    </td>
                    <td class="py-4 px-6 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-signature text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-[#071E3D] capitalize">
                                {{ $dataForm['nama_lengkap'] ?? $item->user->name }}
                            </p>
                            <p class="text-[11px] text-gray-500 font-medium">
                                NIP: {{ $dataForm['nip'] ?? $item->user->nip ?? '-' }}
                            </p>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-[12px] font-bold text-gray-700 capitalize">{{ $dataForm['jabatan'] ?? '-' }}</p>
                        <p class="text-[11px] text-gray-500 capitalize">{{ $dataForm['instansi'] ?? '-' }}</p>
                    </td>
                    <td class="py-4 px-6 text-[12px] text-gray-500 font-bold">
                        {{ $item->created_at->format('d M Y') }}
                    </td>
                    <td class="py-4 px-6">
                        @php
                            $badgeColor = match($item->status) {
                                'Pending', 'Menunggu Validasi' => 'bg-amber-50 text-amber-600 border-amber-100',
                                'Verifikasi Doc', 'Proses BSSN' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'Ditolak' => 'bg-rose-50 text-rose-600 border-rose-100',
                                default   => 'bg-gray-50 text-gray-600 border-gray-100'
                            };
                        @endphp
                        <span class="px-3 py-1.5 rounded-lg border text-[10px] font-extrabold uppercase tracking-wider {{ $badgeColor }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center space-x-1 whitespace-nowrap">
                        <!-- info detail user -->
                        <button type="button" onclick="bukaModalInfo('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm" title="Lihat Profil & File">
                            <i class="fa-regular fa-address-card text-xs"></i>
                        </button>
                        
                        <!-- edit & chat & upload file -->
                        <button type="button" onclick="bukaModalAdmin('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm" title="Kelola Progres & Upload">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </button>

                        <!-- hapus -->
                        <form id="form-delete-{{ $item->id }}" action="{{ route('admin.pengajuan.destroy', $item->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="bukaModalDelete('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-rose-50 hover:text-rose-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm" title="Hapus Permanen">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- modal buat hapus data -->
                <div id="modal-delete-{{ $item->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center">
                    <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalDelete('{{ $item->id }}')"></div>
                    <div class="relative bg-white rounded-[2rem] p-8 max-w-sm w-full mx-4 shadow-2xl text-center">
                        <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-5 border-4 border-white shadow-md">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h3 class="text-xl font-extrabold text-[#071E3D] mb-2">Hapus Permohonan?</h3>
                        <p class="text-[13px] text-gray-500 mb-6 leading-relaxed">Yakin mau hapus pengajuan <b class="text-[#071E3D]">#TTE-{{ strtoupper(substr($item->id, -5)) }}</b> secara permanen?</p>
                        <div class="flex gap-3">
                            <button type="button" onclick="tutupModalDelete('{{ $item->id }}')" class="flex-1 py-3 rounded-xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-colors">Batal</button>
                            <button type="button" onclick="document.getElementById('form-delete-{{ $item->id }}').submit()" class="flex-1 py-3 rounded-xl font-bold text-white bg-rose-500 hover:bg-rose-600 transition-colors">Ya, Hapus!</button>
                        </div>
                    </div>
                </div>

                <!-- modal liat info pemohon & DOWNLOAD DOKUMEN -->
                <div id="modal-info-{{ $item->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center">
                    <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalInfo('{{ $item->id }}')"></div>
                    <div class="relative bg-white rounded-[2rem] p-8 max-w-md w-full mx-4 shadow-2xl overflow-y-auto max-h-[90vh] custom-scrollbar">
                        <div class="absolute top-6 right-6">
                            <button onclick="tutupModalInfo('{{ $item->id }}')" class="text-gray-400 hover:text-rose-500 transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
                        </div>
                        <div class="flex flex-col items-center text-center mb-6">
                            <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center text-3xl mb-4 border-4 border-white shadow-md"><i class="fa-regular fa-user"></i></div>
                            <h3 class="text-xl font-extrabold text-[#071E3D]">{{ $dataForm['nama_lengkap'] ?? '-' }}</h3>
                            <p class="text-[12px] text-gray-500 font-medium bg-gray-100 px-3 py-1 rounded-full mt-2">NIP: {{ $dataForm['nip'] ?? '-' }}</p>
                        </div>
                        <div class="space-y-4 bg-gray-50 p-5 rounded-2xl border border-gray-100">
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">NIK (KTP)</p><p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['nik'] ?? '-' }}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Instansi</p><p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['instansi'] ?? '-' }}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Jabatan Pejabat</p><p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['jabatan'] ?? '-' }}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nomor HP / WhatsApp</p><p class="text-[13px] font-bold text-[#071E3D]"><i class="fa-brands fa-whatsapp text-green-500 mr-1"></i> {{ $dataForm['nomor_hp'] ?? '-' }}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Email Aktif</p><p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['email'] ?? '-' }}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Alamat</p><p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['alamat'] ?? '-' }}</p></div>
                        </div>

                        <!-- TOMBOL DOWNLOAD DOKUMEN DARI USER -->
                        @if(!empty($item->file_pendukung))
                        <div class="mt-6">
                            <a href="{{ asset('storage/' . $item->file_pendukung) }}" target="_blank" class="w-full block text-center bg-[#071E3D] hover:bg-[#1F4287] text-white py-3 rounded-xl font-bold text-[13px] transition-all shadow-md">
                                <i class="fa-solid fa-file-pdf mr-2"></i> Download Dokumen Pengajuan
                            </a>
                        </div>
                        @else
                        <div class="mt-6 text-center text-[12px] text-gray-400 italic bg-gray-100 py-3 rounded-xl border border-dashed border-gray-200">
                            Tidak ada dokumen yang dilampirkan.
                        </div>
                        @endif
                    </div>
                </div>

                <!-- modal buat edit status & UPLOAD DOKUMEN TTE -->
                <div id="modal-{{ $item->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center">
                    <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalAdmin('{{ $item->id }}')"></div>
                    <div class="relative bg-white rounded-[2rem] p-8 max-w-xl w-full mx-4 shadow-2xl overflow-y-auto max-h-[95vh] custom-scrollbar">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-pen-to-square"></i></div>
                            <div>
                                <h3 class="text-xl font-extrabold text-[#071E3D]">Kelola Permohonan TTE</h3>
                                <p class="text-[12px] text-gray-500">Update status, upload dokumen jadi, dan chat.</p>
                            </div>
                        </div>
                        
                        <!-- Perhatikan tag form di bawah ini sudah lengkap dengan enctype dan onsubmit -->
                        <form method="POST" action="{{ route('admin.pengajuan.update', $item->id) }}" enctype="multipart/form-data" onsubmit="disableSubmitButton(this)">
                            @csrf
                            @method('PUT')
                            <div class="bg-gray-50/50 border border-gray-100 p-5 rounded-2xl mb-5">
                                <h4 class="text-[13px] font-extrabold text-[#071E3D] mb-4 uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-bars-progress text-blue-500"></i> Update Timeline & File</h4>
                                
                                <div class="mb-4">
                                    <label class="block text-[12px] font-bold text-gray-600 mb-2">Pilih Status Baru</label>
                                    <select name="status" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[13px] text-[#071E3D] font-bold focus:border-blue-400 outline-none transition-colors">
                                        <option value="Pending" {{ $item->status == 'Pending' ? 'selected' : '' }}>PENDING</option>
                                        <option value="Verifikasi Doc" {{ $item->status == 'Verifikasi Doc' ? 'selected' : '' }}>VERIFIKASI DOKUMEN</option>
                                        <option value="Proses BSSN" {{ $item->status == 'Proses BSSN' ? 'selected' : '' }}>PROSES BSSN</option>
                                        <option value="Ditolak" {{ $item->status == 'Ditolak' ? 'selected' : '' }}>DITOLAK</option>
                                        <option value="Selesai" {{ $item->status == 'Selesai' ? 'selected' : '' }}>SELESAI / TERBIT</option>
                                    </select>
                                </div>

                                <!-- INPUT UPLOAD DOKUMEN HASIL -->
                                <div class="mb-4">
                                    <label class="block text-[12px] font-bold text-gray-600 mb-2">Upload Dokumen Hasil TTE (Jika Selesai)</label>
                                    <input type="file" name="file_hasil" accept=".pdf" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-[12px] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-blue-600 cursor-pointer">
                                    @if(isset($dataForm['file_hasil']))
                                        <p class="text-[10px] text-green-600 mt-2 font-bold"><i class="fa-solid fa-check mr-1"></i> File hasil sudah pernah diupload.</p>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-[12px] font-bold text-gray-600 mb-2">Catatan Buat E-Tracking (Opsional)</label>
                                    <textarea name="catatan" rows="2" placeholder="Tulis update ke user di sini..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[13px] outline-none focus:border-blue-400 resize-none"></textarea>
                                </div>
                            </div>
                            <div class="bg-blue-50/30 border border-blue-100 p-5 rounded-2xl mb-8">
                                <h4 class="text-[13px] font-extrabold text-[#071E3D] mb-4 uppercase tracking-wider flex items-center gap-2"><i class="fa-regular fa-comments text-blue-500"></i> 2. Obrolan</h4>
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
                                        <div class="flex items-center justify-center h-full"><p class="text-[12px] text-gray-400 italic">Belum ada obrolan.</p></div>
                                    @endif
                                </div>
                                  @if($chatAktif)
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
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3"><i class="fa-solid fa-folder-open text-2xl text-gray-300"></i></div>
                        <h3 class="font-bold text-[14px] text-gray-600">Belum ada pengajuan TTE</h3>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

<!-- modal nambah data manual -->
<div id="modal-create" class="fixed inset-0 z-[100] hidden items-center justify-center">
    <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalCreate()"></div>
    <div class="relative bg-white rounded-[2rem] p-8 max-w-lg w-full mx-4 shadow-2xl">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-plus"></i></div>
            <div>
                <h3 class="text-xl font-extrabold text-[#071E3D]">Tambah Permohonan TTE</h3>
                <p class="text-[12px] text-gray-500">Input ajuan TTE manual (hanya membuat tiket).</p>
            </div>
        </div>
        
        <form method="POST" action="{{ route('admin.pengajuan.storeTte') }}" onsubmit="disableSubmitButton(this)">
            @csrf
            <div class="mb-6">
                <label class="block text-[12px] font-bold text-gray-600 mb-2">Pilih Nama Pejabat (ASN)</label>
                <select name="user_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-[13px] text-[#071E3D] font-bold focus:border-emerald-400 outline-none transition-colors">
                    <option value="">-- Pilih Pejabat Terdaftar --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="tutupModalCreate()" class="flex-1 py-3.5 rounded-xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3.5 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-900/20 transition-all">Buat Permohonan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function disableSubmitButton(form) {
        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');
        btn.innerHTML = 'Memproses... <i class="fa-solid fa-spinner fa-spin ml-2"></i>';
    }
</script>