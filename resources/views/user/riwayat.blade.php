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
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-[#071E3D] text-[13px] border-b border-gray-100">
                        <th class="py-4 px-5 font-bold rounded-tl-xl">Layanan</th>
                        <th class="py-4 px-5 font-bold">Tanggal Pengajuan</th>
                        <th class="py-4 px-5 font-bold">Status</th>
                        <th class="py-4 px-5 font-bold text-center rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-[13px] font-medium divide-y divide-gray-50">
                    
                    @forelse ($pengajuans as $item)
                        <tr class="hover:bg-cyan-50/30 transition-colors">
                            <td class="py-4 px-5">
                                <div class="font-bold text-[#071E3D] capitalize">
                                    {{ str_replace('_', ' ', $item->jenis_layanan) }}
                                </div>
                            </td>
                            <td class="py-4 px-5">
                                {{ $item->created_at->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-4 px-5">
                                <!-- Pewarnaan Badge Status Otomatis -->
                                @php
                                    $badgeColor = match($item->status) {
                                        'Menunggu Validasi' => 'bg-amber-100 text-amber-600 border-amber-200',
                                        'Diproses' => 'bg-blue-100 text-blue-600 border-blue-200',
                                        'Selesai' => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                                        'Ditolak' => 'bg-rose-100 text-rose-600 border-rose-200',
                                        default => 'bg-gray-100 text-gray-600 border-gray-200'
                                    };
                                @endphp
                                <span class="px-3 py-1.5 rounded-lg border text-[11px] font-bold {{ $badgeColor }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center">
                                <button class="bg-gray-100 hover:bg-cyan-100 text-gray-500 hover:text-cyan-600 p-2 rounded-lg transition-colors cursor-pointer" title="Lihat Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <!-- Jika Data Masih Kosong -->
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-400">
                                <i class="fa-regular fa-folder-open text-4xl mb-3 block opacity-50"></i>
                                Belum ada riwayat pengajuan layanan.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>
@endsection