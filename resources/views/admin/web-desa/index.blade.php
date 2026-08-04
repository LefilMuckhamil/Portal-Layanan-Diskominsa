@extends('layouts.admin')

@section('header_title', 'Manajemen Web Desa & Pesantren')
@section('header_subtitle', 'Kelola permohonan, verifikasi berkas, dan pantau progres pembuatan website.')

@section('content')
    <!-- Deretan Kartu Statistik Khusus Web -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Kartu 1 -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
            </div>
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Total Permohonan</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">45</h3>
                <span class="text-[12px] font-bold text-indigo-500 mb-1">Unit Web</span>
            </div>
        </div>

        <!-- Kartu 2 -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-file-circle-exclamation"></i>
                </div>
            </div>
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Menunggu Verifikasi</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">8</h3>
                <span class="text-[12px] font-bold text-amber-500 mb-1">Permohonan</span>
            </div>
        </div>

        <!-- Kartu 3 -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-code"></i>
                </div>
            </div>
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Proses Development</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">12</h3>
                <span class="text-[12px] font-bold text-blue-500 mb-1">Sedang dikerjakan</span>
            </div>
        </div>

        <!-- Kartu 4 -->
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
            </div>
            <p class="text-[12px] font-bold text-gray-400 uppercase tracking-wide">Selesai / Online</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">25</h3>
                <span class="text-[12px] font-bold text-green-500 mb-1">Web Aktif</span>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Permohonan Web Desa -->
    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col mt-6">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-[#071E3D]">Daftar Ajuan Website</h3>
                <p class="text-[12px] text-gray-400 font-medium mt-1">Kelola data dan perbarui status progres pembuatan website.</p>
            </div>
            
            <div class="flex gap-3">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <input type="text" placeholder="Cari permohonan..." class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none focus:border-cyan-400 focus:bg-white w-48 transition-all">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-filter absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <select class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="verifikasi">Verifikasi Dokumen</option>
                        <option value="proses">Proses Development</option>
                        <option value="selesai">Selesai / Online</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead class="bg-gray-50/50 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                    <tr>
                        <th class="py-3 px-6">ID Ajuan</th>
                        <th class="py-3 px-6">Layanan / Instansi</th>
                        <th class="py-3 px-6">Tgl Masuk</th>
                        <th class="py-3 px-6">Status Terkini</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    
                    @forelse ($pengajuans as $item)
                    <tr class="hover:bg-cyan-50/20 transition-colors duration-200">
                        <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D]">
                            #REQ-{{ strtoupper(substr($item->id, -5)) }}
                        </td>
                        <td class="py-4 px-6 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-layer-group text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[13px] font-bold text-[#071E3D] capitalize">
                                    {{ str_replace('_', ' ', $item->jenis_layanan) }}
                                </p>
                                <p class="text-[11px] text-gray-500 font-medium">
                                    {{ $item->user->name ?? 'Pemohon' }}
                                </p>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-[12px] text-gray-500 font-bold">
                            {{ $item->created_at->format('d M Y') }}
                        </td>
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
                        <td class="py-4 px-6 text-center space-x-1">
                            <!-- Tombol ini sekarang memanggil ID spesifik modalnya -->
                            <button type="button" onclick="bukaModalAdmin('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-cyan-50 hover:text-cyan-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm" title="Kelola & Balas Pesan">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- ======================================================= -->
                    <!-- MODAL KHUSUS UNTUK SETIAP ITEM (Di dalam perulangan) -->
                    <!-- ======================================================= -->
                    <div id="modal-{{ $item->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center">
                        <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalAdmin('{{ $item->id }}')"></div>
                        
                        <div class="relative bg-white rounded-[2rem] p-8 max-w-xl w-full mx-4 shadow-2xl overflow-y-auto max-h-[95vh] custom-scrollbar">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                                <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-xl flex items-center justify-center text-xl">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-extrabold text-[#071E3D]">Kelola Permohonan</h3>
                                    <p class="text-[12px] text-gray-500">Update progres dan balas pesan pemohon.</p>
                                </div>
                            </div>
                            
                            <form method="POST" action="{{ route('admin.pengajuan.update', $item->id) }}">
                                @csrf
                                @method('PUT')
                                
                                <!-- BAGIAN 1: STATUS & TIMELINE -->
                                <div class="bg-gray-50/50 border border-gray-100 p-5 rounded-2xl mb-5">
                                    <h4 class="text-[13px] font-extrabold text-[#071E3D] mb-4 uppercase tracking-wider flex items-center gap-2">
                                        <i class="fa-solid fa-bars-progress text-cyan-500"></i> 1. Update Timeline Progres
                                    </h4>
                                    
                                    <div class="mb-4">
                                        <label class="block text-[12px] font-bold text-gray-600 mb-2">Ubah Status</label>
                                        <select name="status" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[13px] text-[#071E3D] font-bold focus:border-cyan-400 outline-none transition-colors">
                                            <option value="Pending" {{ $item->status == 'Pending' ? 'selected' : '' }}>PENDING</option>
                                            <option value="Verifikasi Doc" {{ $item->status == 'Verifikasi Doc' ? 'selected' : '' }}>VERIFIKASI DOC</option>
                                            <option value="Proses Development" {{ $item->status == 'Proses Development' ? 'selected' : '' }}>PROSES DEVELOPMENT</option>
                                            <option value="Ditolak" {{ $item->status == 'Ditolak' ? 'selected' : '' }}>DITOLAK</option>
                                            <option value="Selesai" {{ $item->status == 'Selesai' ? 'selected' : '' }}>SELESAI / ONLINE</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-[12px] font-bold text-gray-600 mb-2">Catatan Progres (Opsional)</label>
                                        <textarea name="catatan" rows="2" placeholder="Tuliskan catatan progres yang akan muncul di E-Tracking pemohon..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[13px] outline-none focus:border-cyan-400 resize-none"></textarea>
                                    </div>
                                </div>

                                <!-- BAGIAN 2: RIWAYAT CHAT & BALAS PESAN -->
                                <div class="bg-blue-50/30 border border-blue-100 p-5 rounded-2xl mb-8">
                                    <h4 class="text-[13px] font-extrabold text-[#071E3D] mb-4 uppercase tracking-wider flex items-center gap-2">
                                        <i class="fa-regular fa-comments text-blue-500"></i> 2. Riwayat Diskusi / Chat
                                    </h4>
                                    
                                    <!-- Kotak Menampilkan Riwayat Pesan -->
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

                                    <!-- Kolom Balas Pesan -->
                                    <div>
                                        <textarea name="pesan" rows="2" placeholder="Ketik pesan balasan atau pertanyaan kepada pemohon di sini..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[13px] outline-none focus:border-blue-400 resize-none"></textarea>
                                    </div>
                                </div>

                                <!-- TOMBOL AKSI -->
                                <div class="flex gap-3">
                                    <button type="button" onclick="tutupModalAdmin('{{ $item->id }}')" class="flex-1 py-3.5 rounded-xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-colors">Batal</button>
                                    <button type="submit" class="flex-1 py-3.5 rounded-xl font-bold text-white bg-[#071E3D] hover:bg-[#1F4287] transition-colors shadow-lg shadow-blue-900/20">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Akhir Modal -->
                    
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-folder-open text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="font-bold text-[14px] text-gray-600">Belum ada permohonan</h3>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/30 text-[12px] font-medium text-gray-500">
            <p>Menampilkan riwayat terbaru</p>
        </div>
    </div>

    <!-- SCRIPT PENGGERAK MODAL DINAMIS -->
    <script>
        function bukaModalAdmin(id) {
            const modal = document.getElementById('modal-' + id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupModalAdmin(id) {
            const modal = document.getElementById('modal-' + id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
    
    <!-- CSS untuk Scrollbar -->
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
@endsection