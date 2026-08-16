@extends('layouts.admin')

@section('header_title', 'Dashboard Portal Layanan Diskominsa Aceh Barat')
@section('header_subtitle', 'Pantau seluruh statistik dan permohonan layanan secara real-time.')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-6">
        
        <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
            <div class="flex justify-between items-start mb-3">
                <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Website</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-extrabold text-[#071E3D] leading-none">{{ $countWeb ?? 0 }}</h3>
                    <span class="text-[10px] font-bold text-indigo-500 mb-1">Ajuan</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
            <div class="flex justify-between items-start mb-3">
                <div class="w-11 h-11 rounded-2xl bg-cyan-50 text-cyan-500 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-cyan-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-envelope-circle-check"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Email Resmi</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-extrabold text-[#071E3D] leading-none">{{ $countEmail ?? 0 }}</h3>
                    <span class="text-[10px] font-bold text-cyan-500 mb-1">Ajuan</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
            <div class="flex justify-between items-start mb-3">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Layanan TTE</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-extrabold text-[#071E3D] leading-none">{{ $countTTE ?? 0 }}</h3>
                    <span class="text-[10px] font-bold text-emerald-500 mb-1">Ajuan</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
            <div class="flex justify-between items-start mb-3">
                <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-sky-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Cloud Gov</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-extrabold text-[#071E3D] leading-none">{{ $countCloud ?? 0 }}</h3>
                    <span class="text-[10px] font-bold text-sky-500 mb-1">Ajuan</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
            <div class="flex justify-between items-start mb-3">
                <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-unlock-keyhole"></i>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Bantuan / OTP</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-extrabold text-[#071E3D] leading-none">{{ $countBantuan ?? 0 }}</h3>
                    <span class="text-[10px] font-bold text-rose-500 mb-1">Ajuan</span>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col mt-6">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-[#071E3D]">Permohonan Terbaru Terpadu</h3>
                <p class="text-[12px] text-gray-400 font-medium mt-1">Daftar semua permohonan masuk dari berbagai layanan.</p>
            </div>
            
            <div class="flex gap-3 items-center">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex gap-3">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nomor Tiket..." class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none focus:border-cyan-400 focus:bg-white w-48 transition-all">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-filter absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                        <select name="status" onchange="this.form.submit()" class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead class="bg-gray-50/50 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                    <tr>
                        <th class="py-3 px-6">Tiket</th>
                        <th class="py-3 px-6">Nama Pemohon</th>
                        <th class="py-3 px-6">Layanan</th>
                        <th class="py-3 px-6">Tanggal</th>
                        <th class="py-3 px-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    
                    @forelse ($pengajuans as $item)
                        @php
                            $dataForm = is_array($item->data_pengajuan) ? $item->data_pengajuan : json_decode($item->data_pengajuan ?? '[]', true);
                        @endphp
                        <tr class="hover:bg-cyan-50/10 transition-colors duration-200">
                            
                            <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D]">
                                {{ $item->nomor_tiket }}
                            </td>
                            
                            <td class="py-4 px-6">
                                <p class="text-[13px] font-bold text-[#071E3D]">
                                    {{ $dataForm['nama'] ?? $item->user->name ?? 'Pemohon' }}
                                </p>
                                <p class="text-[11px] text-gray-500 font-medium mt-0.5">
                                    {{ $dataForm['instansi'] ?? $item->user->unit_kerja ?? 'Instansi' }}
                                </p>
                            </td>
                            
                            <td class="py-4 px-6">
                                <span class="text-[12px] font-bold text-gray-700 capitalize">
                                    {{ str_replace('_', ' ', $item->jenis_layanan) }}
                                </span>
                            </td>
                            
                            <td class="py-4 px-6 text-[12px] text-gray-500 font-bold">
                                {{ $item->created_at->format('d M Y') }}
                            </td>
                            
                            <td class="py-4 px-6 text-center">
                                @php
                                    $badgeColor = match($item->status) {
                                        'Pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'Proses' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'Ditolak' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        default   => 'bg-gray-50 text-gray-600 border-gray-100'
                                    };
                                @endphp
                                <span class="px-3 py-1.5 rounded-lg border text-[10px] font-extrabold uppercase tracking-wider {{ $badgeColor }}">
                                    {{ $item->status }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-regular fa-folder-open text-2xl text-gray-300"></i>
                                </div>
                                <h3 class="font-bold text-[14px] text-gray-600">Belum ada permohonan</h3>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        @if(method_exists($pengajuans, 'links'))
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $pengajuans->links() }}
            </div>
        @endif

    </div>
@endsection