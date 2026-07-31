@extends('layouts.user')

@section('title', 'Riwayat Pengajuan Saya')

@section('content')
    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-[0_15px_40px_-15px_rgba(7,30,61,0.08)] border border-gray-100">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-100">
            <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-500 flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-[#071E3D]">Riwayat Pengajuan</h2>
                <p class="text-[13px] text-gray-500 font-medium mt-1">Pantau status seluruh pengajuan layanan digital Anda di sini.</p>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="overflow-x-auto pb-4">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/70 text-[#071E3D] text-[13px] border-b border-gray-100">
                        <th class="py-4 px-5 font-bold rounded-tl-xl">ID Permohonan</th>
                        <th class="py-4 px-5 font-bold">Data Pemohon</th>
                        <th class="py-4 px-5 font-bold">Jenis Layanan</th>
                        <th class="py-4 px-5 font-bold">Tanggal Pengajuan</th>
                        <th class="py-4 px-5 font-bold">Status Layanan</th>
                        <th class="py-4 px-5 font-bold text-center rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-[13px] font-medium divide-y divide-gray-50">
                    
                    @forelse ($pengajuans as $item)
                        <tr class="hover:bg-cyan-50/30 transition-colors group">
                            
                            <!-- ID Permohonan -->
                            <td class="py-4 px-5">
                                <span class="font-mono text-[12px] font-bold text-cyan-600 bg-cyan-50 px-2.5 py-1 rounded-md border border-cyan-100">
                                    #REQ-{{ strtoupper(substr($item->id, -5)) }}
                                </span>
                            </td>

                            <!-- Data Pemohon -->
                            <td class="py-4 px-5">
                                <p class="font-bold text-[#071E3D]">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                            </td>

                            <!-- Jenis Layanan -->
                            <td class="py-4 px-5">
                                <div class="font-bold text-gray-700 capitalize flex items-center gap-2">
                                    @if($item->jenis_layanan == 'website') <i class="fa-solid fa-globe text-cyan-500"></i>
                                    @elseif($item->jenis_layanan == 'email') <i class="fa-solid fa-envelope text-cyan-500"></i>
                                    @elseif($item->jenis_layanan == 'tte') <i class="fa-solid fa-pen-nib text-cyan-500"></i>
                                    @elseif($item->jenis_layanan == 'cloud') <i class="fa-solid fa-cloud text-cyan-500"></i>
                                    @else <i class="fa-solid fa-headset text-rose-500"></i>
                                    @endif
                                    {{ str_replace('_', ' ', $item->jenis_layanan) }}
                                </div>
                            </td>

                            <!-- Tanggal Pengajuan -->
                            <td class="py-4 px-5">
                                <span class="flex items-center gap-2">
                                    <i class="fa-regular fa-calendar text-gray-400"></i>
                                    {{ $item->created_at->translatedFormat('d F Y') }}
                                </span>
                            </td>

                            <!-- Status Layanan -->
                            <td class="py-4 px-5">
                                @php
                                    $badgeColor = match($item->status) {
                                        'Menunggu Validasi' => 'bg-amber-50 text-amber-600 border-amber-200',
                                        'Diproses' => 'bg-blue-50 text-blue-600 border-blue-200',
                                        'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                        'Ditolak' => 'bg-rose-50 text-rose-600 border-rose-200',
                                        default => 'bg-gray-50 text-gray-600 border-gray-200'
                                    };
                                @endphp
                                <span class="px-3 py-1.5 rounded-lg border text-[11px] font-bold {{ $badgeColor }} flex items-center inline-flex gap-1.5 w-max">
                                    @if($item->status == 'Menunggu Validasi') <i class="fa-solid fa-clock-rotate-left"></i>
                                    @elseif($item->status == 'Diproses') <i class="fa-solid fa-spinner fa-spin"></i>
                                    @elseif($item->status == 'Selesai') <i class="fa-solid fa-check-circle"></i>
                                    @else <i class="fa-solid fa-xmark-circle"></i>
                                    @endif
                                    {{ $item->status }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-5 text-center">
                                <button class="bg-gray-50 hover:bg-cyan-500 text-gray-400 hover:text-white border border-gray-200 hover:border-cyan-500 p-2.5 rounded-xl transition-all shadow-sm cursor-pointer group-hover:shadow-md" title="Lihat Detail Form">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <!-- Jika Data Masih Kosong -->
                        <tr>
                            <td colspan="6" class="py-16 text-center text-gray-400">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-regular fa-folder-open text-3xl opacity-50"></i>
                                </div>
                                <p class="font-bold text-[14px] text-[#071E3D]">Belum ada riwayat pengajuan</p>
                                <p class="text-[13px] mt-1">Anda belum pernah mengajukan layanan apapun.</p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>
@endsection