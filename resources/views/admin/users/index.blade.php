@extends('layouts.admin')

@section('header_title', 'Manajemen Akun')
@section('header_subtitle', 'Kelola akun ASN, hak akses, dan data pengguna portal.')

@section('content')

<div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-[#071E3D]">Daftar Akun Pengguna</h3>
            <p class="text-[12px] text-gray-400 font-medium mt-1">Kelola data akun ASN beserta hak aksesnya.</p>
        </div>

        <div class="flex gap-3 items-center">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-3">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email / NIP..." class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none focus:border-cyan-400 focus:bg-white w-56 transition-all">
                </div>
            </form>

            <button type="button" onclick="bukaModalCreate()" class="px-4 py-2 bg-[#071E3D] hover:bg-[#1F4287] text-white text-[12px] font-bold rounded-lg transition-colors shadow-sm flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-plus"></i> Tambah Akun
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                <tr>
                    <th class="py-3.5 px-6">Pegawai ASN</th>
                    <th class="py-3.5 px-6">Kontak</th>
                    <th class="py-3.5 px-6">Instansi & Jabatan</th>
                    <th class="py-3.5 px-6">Role</th>
                    <th class="py-3.5 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($users as $item)
                    @php
                        $rawWa = preg_replace('/[^0-9]/', '', $item->no_hp ?? '');
                        if (str_starts_with($rawWa, '0')) {
                            $cleanWa = '62' . substr($rawWa, 1);
                        } elseif (!str_starts_with($rawWa, '62') && !empty($rawWa)) {
                            $cleanWa = '62' . $rawWa;
                        } else {
                            $cleanWa = $rawWa;
                        }
                    @endphp
                    <tr class="hover:bg-cyan-50/20 transition-colors duration-200">
                        {{-- 1. Nama di atas, NIP di bawah --}}
                        <td class="py-4 px-6">
                            <p class="text-[13.5px] font-extrabold text-[#071E3D] whitespace-nowrap">{{ $item->name }}</p>
                            <p class="text-[11.5px] text-gray-500 font-medium mt-0.5 font-mono">
                                NIP: {{ $item->nip ?? '-' }}
                            </p>
                        </td>

                        {{-- 2. Email di atas, No HP di bawah --}}
                        <td class="py-4 px-6">
                            <p class="text-[12.5px] font-bold text-gray-700">{{ $item->email }}</p>
                            @if(!empty($item->no_hp))
                                <p class="text-[11.5px] text-emerald-600 font-semibold mt-0.5 inline-flex items-center gap-1">
                                    <i class="fa-brands fa-whatsapp text-xs"></i>
                                    @if(!empty($cleanWa))
                                        <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="hover:underline">{{ $item->no_hp }}</a>
                                    @else
                                        {{ $item->no_hp }}
                                    @endif
                                </p>
                            @else
                                <p class="text-[11px] text-gray-400 font-medium mt-0.5">-</p>
                            @endif
                        </td>

                        {{-- 3. Instansi di atas, Jabatan di bawah --}}
                        <td class="py-4 px-6">
                            <p class="text-[13px] font-bold text-[#071E3D]">{{ $item->unit_kerja ?? '-' }}</p>
                            <p class="text-[11.5px] text-gray-500 font-medium mt-0.5">{{ $item->jabatan ?? '-' }}</p>
                        </td>

                        {{-- 4. Role --}}
                        <td class="py-4 px-6">
                            @if($item->role === 'admin')
                                <span class="px-3 py-1.5 rounded-lg border text-[10px] font-extrabold uppercase tracking-wider bg-violet-50 text-violet-600 border-violet-100">
                                    Admin
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-lg border text-[10px] font-extrabold uppercase tracking-wider bg-cyan-50 text-cyan-600 border-cyan-100">
                                    User
                                </span>
                            @endif
                        </td>

                        {{-- 5. Aksi --}}
                        <td class="py-4 px-6 text-center space-x-1 whitespace-nowrap">
                            <button type="button" onclick="bukaModalEdit('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-cyan-50 hover:text-cyan-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm cursor-pointer" title="Edit Akun">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <form id="form-delete-{{ $item->id }}" action="{{ route('admin.users.destroy', $item->id) }}" method="POST" class="inline-block" data-no-ajax>
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="bukaModalDelete('{{ $item->id }}', {{ Js::from($item->name) }})" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-rose-50 hover:text-rose-600 border border-gray-200 transition-colors inline-flex items-center justify-center shadow-sm cursor-pointer" title="Hapus Akun">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- Modal Edit Akun --}}
                    <div id="modal-edit-{{ $item->id }}" class="fixed inset-0 z-[150] hidden items-center justify-center">
                        <div class="absolute inset-0 bg-[#101828]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalEdit('{{ $item->id }}')"></div>
                        <div class="relative bg-white rounded-[1.5rem] w-full max-w-3xl mx-4 shadow-2xl overflow-hidden max-h-[95vh] flex flex-col animate-fade-in-down">
                            <div class="flex items-center justify-between gap-4 px-6 md:px-8 py-4 border-b border-[#E4E7EC] bg-gradient-to-r from-[#F8FAFC] to-[#F1F5F9] shrink-0">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-[#16324F] text-cyan-400 flex items-center justify-center text-lg shadow-md shadow-[#16324F]/20">
                                        <i class="fa-solid fa-user-pen"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold tracking-[0.16em] text-cyan-700 uppercase mb-0.5">Admin Panel</p>
                                        <h2 class="text-[17px] font-extrabold text-[#101828] leading-tight">Edit Akun</h2>
                                    </div>
                                </div>
                                <button type="button" onclick="tutupModalEdit('{{ $item->id }}')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm cursor-pointer">
                                    <i class="fa-solid fa-xmark text-lg"></i>
                                </button>
                            </div>

                            <div class="px-6 md:px-8 py-5 overflow-y-auto custom-scrollbar">
                                <form method="POST" action="{{ route('admin.users.update', $item->id) }}" onsubmit="disableSubmitButton(this)" data-no-ajax>
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3.5">
                                        <div class="col-span-1 md:col-span-2">
                                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                            <input type="text" name="name" value="{{ old('name', $item->name) }}" required class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Email Resmi <span class="text-rose-500">*</span></label>
                                            <input type="email" name="email" value="{{ old('email', $item->email) }}" required class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">NIP</label>
                                            <input type="text" inputmode="numeric" name="nip" value="{{ old('nip', $item->nip) }}" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Unit Kerja / Instansi</label>
                                            <input type="text" name="unit_kerja" value="{{ old('unit_kerja', $item->unit_kerja) }}" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Jabatan</label>
                                            <input type="text" name="jabatan" value="{{ old('jabatan', $item->jabatan) }}" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">No. HP / WhatsApp <span class="text-rose-500">*</span></label>
                                            <div class="bg-white border border-slate-300 rounded-xl flex items-center px-3 shadow-sm focus-within:border-cyan-500 transition-all">
                                                <i class="fa-brands fa-whatsapp text-emerald-500 text-[14px] mr-2"></i>
                                                <input type="tel" inputmode="numeric" name="no_hp" value="{{ old('no_hp', $item->no_hp) }}" required placeholder="08xxxxxxxxxx" class="flex-1 min-w-0 bg-transparent outline-none py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Role <span class="text-rose-500">*</span></label>
                                            <div class="bg-white border border-slate-300 rounded-xl flex items-center px-3 relative shadow-sm focus-within:border-cyan-500 transition-all">
                                                <select name="role" required class="flex-1 min-w-0 bg-transparent outline-none py-2 text-[12.5px] text-[#101828] font-bold appearance-none cursor-pointer">
                                                    <option value="user" @selected($item->role === 'user')>User</option>
                                                    <option value="admin" @selected($item->role === 'admin')>Admin</option>
                                                </select>
                                                <i class="fa-solid fa-chevron-down text-xs text-[#667085] pointer-events-none ml-2"></i>
                                            </div>
                                        </div>
                                        <div class="col-span-1 md:col-span-2">
                                            <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Password Baru</label>
                                            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-[#E4E7EC]">
                                        <button type="button" onclick="tutupModalEdit('{{ $item->id }}')" class="px-5 py-2.5 rounded-xl text-[12.5px] font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors cursor-pointer">Batal</button>
                                        <button type="submit" class="inline-flex items-center gap-2 bg-[#16324F] hover:bg-[#0F2438] active:scale-95 text-white px-6 py-2.5 rounded-xl font-bold text-[13px] transition-all shadow-md shadow-[#16324F]/20 hover:shadow-lg cursor-pointer">
                                            Simpan Perubahan <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Delete Akun --}}
                    <div id="modal-delete-{{ $item->id }}" class="fixed inset-0 z-[100] hidden items-center justify-center">
                        <div class="absolute inset-0 bg-[#071E3D]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalDelete('{{ $item->id }}')"></div>
                        <div class="relative bg-white rounded-[2rem] p-8 max-w-sm w-full mx-4 shadow-2xl text-center">
                            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-5 border-4 border-white shadow-md">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <h3 class="text-xl font-extrabold text-[#071E3D] mb-2">Hapus Akun?</h3>
                            <p class="text-[13px] text-gray-500 mb-6 leading-relaxed">
                                Tindakan ini tidak dapat dibatalkan. Yakin ingin menghapus akun <b class="text-[#071E3D]" id="nama-{{ $item->id }}">{{ $item->name }}</b> secara permanen?
                            </p>
                            <div class="flex gap-3">
                                <button type="button" onclick="tutupModalDelete('{{ $item->id }}')" class="flex-1 py-3 rounded-xl font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 transition-colors">Batal</button>
                                <button type="button" onclick="document.getElementById('form-delete-{{ $item->id }}').submit()" class="flex-1 py-3 rounded-xl font-bold text-white bg-rose-500 hover:bg-rose-600 transition-colors shadow-lg shadow-rose-500/20">Ya, Hapus!</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-user-slash text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="font-bold text-[14px] text-gray-600">Belum ada data akun</h3>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
</div>

{{-- Modal Tambah Akun (Create) --}}
<div id="modal-create" class="fixed inset-0 z-[150] hidden items-center justify-center">
    <div class="absolute inset-0 bg-[#101828]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalCreate()"></div>
    <div class="relative bg-white rounded-[1.5rem] w-full max-w-3xl mx-4 shadow-2xl overflow-hidden max-h-[95vh] flex flex-col animate-fade-in-down">
        <div class="flex items-center justify-between gap-4 px-6 md:px-8 py-4 border-b border-[#E4E7EC] bg-gradient-to-r from-[#F8FAFC] to-[#F1F5F9] shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#16324F] text-cyan-400 flex items-center justify-center text-lg shadow-md shadow-[#16324F]/20">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold tracking-[0.16em] text-cyan-700 uppercase mb-0.5">Admin Panel</p>
                    <h2 class="text-[17px] font-extrabold text-[#101828] leading-tight">Tambah Akun</h2>
                </div>
            </div>
            <button type="button" onclick="tutupModalCreate()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="px-6 md:px-8 py-5 overflow-y-auto custom-scrollbar">
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

            <form method="POST" action="{{ route('admin.users.store') }}" onsubmit="disableSubmitButton(this)" data-no-ajax>
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3.5">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama lengkap..." class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Email Resmi <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@acehbaratkab.go.id" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-[11.5px] font-bold text-[#344054] mb-1">NIP</label>
                        <input type="text" inputmode="numeric" name="nip" value="{{ old('nip') }}" placeholder="18 digit" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Unit Kerja / Instansi</label>
                        <input type="text" name="unit_kerja" value="{{ old('unit_kerja') }}" placeholder="Dinas / Instansi" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}" placeholder="Jabatan / Fungsional" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-[11.5px] font-bold text-[#344054] mb-1">No. HP / WhatsApp <span class="text-rose-500">*</span></label>
                        <div class="bg-white border border-slate-300 rounded-xl flex items-center px-3 shadow-sm focus-within:border-cyan-500 transition-all">
                            <i class="fa-brands fa-whatsapp text-emerald-500 text-[14px] mr-2"></i>
                            <input type="tel" inputmode="numeric" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx" class="flex-1 min-w-0 bg-transparent outline-none py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Role <span class="text-rose-500">*</span></label>
                        <div class="bg-white border border-slate-300 rounded-xl flex items-center px-3 relative shadow-sm focus-within:border-cyan-500 transition-all">
                            <select name="role" required class="flex-1 min-w-0 bg-transparent outline-none py-2 text-[12.5px] text-[#101828] font-bold appearance-none cursor-pointer">
                                <option value="user" @selected(old('role') === 'user')>User</option>
                                <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                            </select>
                            <i class="fa-solid fa-chevron-down text-xs text-[#667085] pointer-events-none ml-2"></i>
                        </div>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-[12.5px] text-[#101828] font-medium placeholder:text-[#98A2B3] outline-none focus:border-cyan-500 shadow-sm transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-[#E4E7EC]">
                    <button type="button" onclick="tutupModalCreate()" class="px-5 py-2.5 rounded-xl text-[12.5px] font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors cursor-pointer">Batal</button>
                    <button type="submit" class="inline-flex items-center gap-2 bg-[#16324F] hover:bg-[#0F2438] active:scale-95 text-white px-6 py-2.5 rounded-xl font-bold text-[13px] transition-all shadow-md shadow-[#16324F]/20 hover:shadow-lg cursor-pointer">
                        Simpan Akun <i class="fa-solid fa-paper-plane text-[11px]"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function bukaModalCreate() {
        document.getElementById('modal-create').classList.remove('hidden');
        document.getElementById('modal-create').classList.add('flex');
    }
    function tutupModalCreate() {
        document.getElementById('modal-create').classList.add('hidden');
        document.getElementById('modal-create').classList.remove('flex');
    }

    function bukaModalEdit(id) {
        document.getElementById('modal-edit-' + id).classList.remove('hidden');
        document.getElementById('modal-edit-' + id).classList.add('flex');
    }
    function tutupModalEdit(id) {
        document.getElementById('modal-edit-' + id).classList.add('hidden');
        document.getElementById('modal-edit-' + id).classList.remove('flex');
    }

    function bukaModalDelete(id, nama) {
        document.getElementById('nama-' + id).innerText = nama;
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

@endsection