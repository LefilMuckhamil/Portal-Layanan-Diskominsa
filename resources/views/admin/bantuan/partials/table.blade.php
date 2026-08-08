<div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col mt-6">
    @if(session('sukses'))
        <div class="bg-green-50 text-green-600 px-6 py-3 border-b border-green-100 text-[13px] font-bold flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('sukses') }}
        </div>
    @endif

    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-[#071E3D]">Daftar Tiket Bantuan</h3>
            <p class="text-[12px] text-gray-400 font-medium mt-1">Respon kendala pengguna dan lakukan tindakan perbaikan.</p>
        </div>
        <div class="flex gap-3 items-center">
            <form method="GET" action="{{ route('admin.bantuan.index') }}" class="flex gap-3">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / Email..." class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none focus:border-cyan-400 w-48 transition-all">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-filter absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <select name="status" onchange="this.form.submit()" class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Sedang Ditangani</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
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
                    <th class="py-3 px-6">ID Tiket</th>
                    <th class="py-3 px-6">Data Pelapor</th>
                    <th class="py-3 px-6">Jenis Kendala</th>
                    <th class="py-3 px-6">Tgl Masuk</th>
                    <th class="py-3 px-6 text-center">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($pengajuans as $item)
                @php 
                    $dataForm = is_string($item->data_pengajuan) ? json_decode($item->data_pengajuan, true) : ($item->data_pengajuan ?? []);
                @endphp
                <tr class="hover:bg-cyan-50/20 transition-colors duration-200">
                    <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D]">
                        #TKT-{{ strtoupper(substr($item->id, -5)) }}
                    </td>
                    <td class="py-4 px-6 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-cyan-50 text-cyan-500 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-headset text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-[#071E3D] capitalize">{{ $dataForm['nama_pelapor'] ?? $item->user->name ?? '-' }}</p>
                            <p class="text-[11px] text-gray-500 font-medium">{{ $dataForm['email'] ?? $item->user->email ?? '-' }}</p>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1.5 rounded-lg border text-[11px] font-bold bg-rose-50 text-rose-600 border-rose-100">
                            <i class="fa-solid fa-key mr-1"></i> {{ $dataForm['kendala'] ?? 'Reset Sandi / OTP' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-[12px] text-gray-500 font-bold">
                        {{ $item->created_at->format('d M Y') }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        @php
                            $badgeColor = match($item->status) {
                                'Pending', 'Menunggu' => 'bg-amber-50 text-amber-600 border-amber-100',
                                'Proses', 'Sedang Ditangani' => 'bg-blue-50 text-blue-600 border-blue-100',
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
                        <button type="button" onclick="bukaModalInfo('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-cyan-50 hover:text-cyan-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm" title="Detail Kendala">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                        <button type="button" onclick="bukaModalAdmin('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm" title="Kelola Tiket & Chat">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </button>
                        <form id="form-delete-{{ $item->id }}" action="{{ route('admin.pengajuan.destroy', $item->id) }}" method="POST" class="inline-block">
                            @csrf @method('DELETE')
                            <button type="button" onclick="bukaModalDelete('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-rose-50 hover:text-rose-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm" title="Hapus Permanen">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- MODAL: DELETE -->
                <div id="modal-delete-{{ $item->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center">
                    <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalDelete('{{ $item->id }}')"></div>
                    <div class="relative bg-white rounded-[2rem] p-8 max-w-sm w-full mx-4 shadow-2xl text-center">
                        <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-5 border-4 border-white shadow-md"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <h3 class="text-xl font-extrabold text-[#071E3D] mb-2">Hapus Tiket?</h3>
                        <p class="text-[13px] text-gray-500 mb-6">Yakin menghapus tiket <b class="text-[#071E3D]">#TKT-{{ strtoupper(substr($item->id, -5)) }}</b>?</p>
                        <div class="flex gap-3">
                            <button type="button" onclick="tutupModalDelete('{{ $item->id }}')" class="flex-1 py-3 rounded-xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200">Batal</button>
                            <button type="button" onclick="document.getElementById('form-delete-{{ $item->id }}').submit()" class="flex-1 py-3 rounded-xl font-bold text-white bg-rose-500 hover:bg-rose-600">Ya, Hapus!</button>
                        </div>
                    </div>
                </div>

                
                <div id="modal-info-{{ $item->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center">
                    <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalInfo('{{ $item->id }}')"></div>
                    <div class="relative bg-white rounded-[2rem] p-8 max-w-md w-full mx-4 shadow-2xl text-left">
                        <div class="absolute top-6 right-6"><button onclick="tutupModalInfo('{{ $item->id }}')" class="text-gray-400 hover:text-rose-500"><i class="fa-solid fa-xmark text-xl"></i></button></div>
                        <div class="flex items-center gap-4 mb-6 border-b border-gray-100 pb-4">
                            <div class="w-12 h-12 bg-cyan-50 text-cyan-500 rounded-2xl flex items-center justify-center text-xl"><i class="fa-solid fa-headset"></i></div>
                            <div><h3 class="text-lg font-extrabold text-[#071E3D]">Detail Kendala</h3><p class="text-[12px] text-gray-500">Tiket #TKT-{{ strtoupper(substr($item->id, -5)) }}</p></div>
                        </div>
                        <div class="space-y-4 bg-gray-50 p-5 rounded-2xl border border-gray-100">
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nama Pelapor</p><p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['nama_pelapor'] ?? '-' }}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Email / Kontak</p><p class="text-[13px] font-bold text-[#071E3D]">{{ $dataForm['email'] ?? '-' }}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Keterangan Kendala</p><p class="text-[13px] font-medium text-gray-700 leading-relaxed">{{ $dataForm['pesan_kendala'] ?? 'Tidak ada catatan.' }}</p></div>
                        </div>
                    </div>
                </div>

                <!-- MODAL: ADMIN UPDATE & CHAT -->
                <div id="modal-{{ $item->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center">
                    <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalAdmin('{{ $item->id }}')"></div>
                    <div class="relative bg-white rounded-[2rem] p-8 max-w-xl w-full mx-4 shadow-2xl overflow-y-auto max-h-[95vh] custom-scrollbar text-left">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-pen-to-square"></i></div>
                            <div><h3 class="text-xl font-extrabold text-[#071E3D]">Kelola Tiket Bantuan</h3><p class="text-[12px] text-gray-500">Update status dan balas pesan pengguna.</p></div>
                        </div>
                        <form method="POST" action="{{ route('admin.pengajuan.update', $item->id) }}">
                            @csrf @method('PUT')
                            <div class="bg-gray-50/50 border border-gray-100 p-5 rounded-2xl mb-5">
                                <h4 class="text-[13px] font-extrabold text-[#071E3D] mb-4 uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-bars-progress text-blue-500"></i> Update Status</h4>
                                <div class="mb-4">
                                    <label class="block text-[12px] font-bold text-gray-600 mb-2">Status Tiket</label>
                                    <select name="status" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[13px] font-bold text-[#071E3D] outline-none">
                                        <option value="Pending" {{ $item->status == 'Pending' ? 'selected' : '' }}>MENUNGGU</option>
                                        <option value="Proses" {{ $item->status == 'Proses' ? 'selected' : '' }}>SEDANG DITANGANI</option>
                                        <option value="Selesai" {{ $item->status == 'Selesai' ? 'selected' : '' }}>SELESAI</option>
                                        <option value="Ditolak" {{ $item->status == 'Ditolak' ? 'selected' : '' }}>DITOLAK</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[12px] font-bold text-gray-600 mb-2">Catatan Perbaikan</label>
                                    <textarea name="catatan" rows="2" placeholder="Tuliskan catatan perbaikan..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[13px] outline-none resize-none"></textarea>
                                </div>
                            </div>
                            <div class="bg-blue-50/30 border border-blue-100 p-5 rounded-2xl mb-8">
                                <h4 class="text-[13px] font-extrabold text-[#071E3D] mb-4 uppercase tracking-wider flex items-center gap-2"><i class="fa-regular fa-comments text-blue-500"></i> Riwayat Diskusi</h4>
                                <div class="bg-white border border-blue-100 rounded-xl p-4 mb-4 h-48 overflow-y-auto space-y-3 custom-scrollbar">
                                    @if(!empty($item->pesan) && is_array($item->pesan))
                                        @foreach($item->pesan as $chat)
                                            <div class="flex flex-col {{ $chat['role'] === 'admin' ? 'items-end' : 'items-start' }}">
                                                <div class="max-w-[85%] p-3 rounded-2xl text-[12px] {{ $chat['role'] === 'admin' ? 'bg-[#071E3D] text-white rounded-br-none' : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                                                    <p class="font-bold text-[10px] opacity-80 mb-0.5">{{ $chat['pengirim'] }}</p><p>{{ $chat['isi'] }}</p>
                                                </div>
                                                <span class="text-[9px] text-gray-400 mt-1">{{ $chat['waktu'] }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="flex items-center justify-center h-full"><p class="text-[12px] text-gray-400 italic">Belum ada obrolan.</p></div>
                                    @endif
                                </div>
                                @if($chatAktif ?? true)
                                    <textarea name="pesan" rows="2" placeholder="Ketik balasan untuk pengguna..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[13px] outline-none resize-none"></textarea>
                                @else
                                    <div class="bg-rose-50 border border-rose-100 p-3 rounded-xl text-center"><p class="text-[12px] font-bold text-rose-500"><i class="fa-solid fa-lock mr-1"></i> Fitur obrolan dinonaktifkan sementara.</p></div>
                                @endif
                            </div>
                            <div class="flex gap-3">
                                <button type="button" onclick="tutupModalAdmin('{{ $item->id }}')" class="flex-1 py-3.5 rounded-xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200">Batal</button>
                                <button type="submit" class="flex-1 py-3.5 rounded-xl font-bold text-white bg-[#071E3D] hover:bg-[#1F4287]">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr><td colspan="6" class="py-12 text-center"><div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3"><i class="fa-solid fa-folder-open text-2xl text-gray-300"></i></div><h3 class="font-bold text-[14px] text-gray-600">Belum ada tiket bantuan</h3></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL CREATE -->
<div id="modal-create" class="fixed inset-0 z-[100] hidden items-center justify-center">
    <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm" onclick="tutupModalCreate()"></div>
    <div class="relative bg-white rounded-[2rem] p-8 max-w-lg w-full mx-4 shadow-2xl text-left">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
            <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-plus"></i></div>
            <div><h3 class="text-xl font-extrabold text-[#071E3D]">Buat Tiket Bantuan</h3><p class="text-[12px] text-gray-500">Input tiket reset password atau OTP secara manual.</p></div>
        </div>
        <form method="POST" action="{{ route('admin.bantuan.store') }}" onsubmit="disableSubmitButton(this)">
            @csrf
            <div class="mb-6">
                <label class="block text-[12px] font-bold text-gray-600 mb-2">Pilih Nama Pegawai</label>
                <select name="user_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-[13px] font-bold text-[#071E3D] outline-none">
                    <option value="">-- Pilih Akun Terdaftar --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="tutupModalCreate()" class="flex-1 py-3.5 rounded-xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200">Batal</button>
                <button type="submit" class="flex-1 py-3.5 rounded-xl font-bold text-white bg-cyan-600 hover:bg-cyan-700">Buat Tiket</button>
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