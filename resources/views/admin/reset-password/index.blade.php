@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-[#071E3D]">Pengajuan Reset Password</h2>
            <p class="text-xs text-gray-500 font-medium">Daftar pengajuan pemulihan akun ASN yang membutuhkan verifikasi.</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-b border-gray-100 font-bold">
                    <th class="p-3.5">Tanggal</th>
                    <th class="p-3.5">Email / NIP Pemohon</th>
                    <th class="p-3.5">No. WhatsApp</th>
                    <th class="p-3.5">Status</th>
                    <th class="p-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-3.5 text-gray-500 font-medium">
                        {{ \Carbon\Carbon::parse($req->created_at)->format('d M Y, H:i') }}
                    </td>
                    <td class="p-3.5 font-bold text-[#071E3D]">
                        {{ $req->email_or_nip }}
                    </td>
                    <td class="p-3.5 font-medium text-gray-600">
                        {{ $req->phone }}
                    </td>
                    <td class="p-3.5">
                        @if($req->status == 'pending')
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-600 border border-amber-100 font-bold rounded-lg text-[11px]">
                                Menunggu Verifikasi
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-green-50 text-green-600 border border-green-100 font-bold rounded-lg text-[11px]">
                                Selesai
                            </span>
                        @endif
                    </td>
                    <td class="p-3.5 flex justify-center">
                        @if($req->status == 'pending')
                        <form action="{{ route('admin.reset-password.process', $req->id) }}" method="POST" target="_blank" onsubmit="this.querySelector('button').disabled = true;">
                            @csrf
                            <button type="submit" class="bg-green-600 hover:bg-green-700 active:scale-95 text-white font-extrabold px-3.5 py-2 rounded-xl text-xs flex items-center gap-2 shadow-sm transition-all">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                Reset & Kirim WA
                            </button>
                        </form>
                        @else
                        <span class="text-[11px] text-gray-400 font-bold">Sudah Diproses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-400 font-medium">
                        Belum ada pengajuan reset password saat ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection