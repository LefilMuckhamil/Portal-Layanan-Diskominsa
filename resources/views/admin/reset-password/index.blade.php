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
                    <td class="p-3.5 text-center">
                        @if($req->status == 'pending')
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $req->phone);
                            $waPhone = str_starts_with($cleanPhone, '0') ? '62'.substr($cleanPhone, 1) : (str_starts_with($cleanPhone, '8') ? '62'.$cleanPhone : $cleanPhone);
                        @endphp
                        <button type="button"
                            onclick="bukaModalReset('{{ $req->id }}', '{{ e($req->email_or_nip) }}', '{{ e($req->phone) }}', '{{ e($waPhone) }}')"
                            class="bg-green-600 hover:bg-green-700 active:scale-95 text-white font-extrabold px-3.5 py-2 rounded-xl text-xs flex items-center gap-2 shadow-sm transition-all mx-auto cursor-pointer">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            Reset & Kirim WA
                        </button>
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

<div id="modal-reset-password" class="fixed inset-0 z-[150] hidden items-center justify-center">
    <div class="absolute inset-0 bg-[#071E3D]/60 backdrop-blur-sm transition-opacity" onclick="tutupModalReset()"></div>
    <div class="relative bg-white rounded-2xl p-6 shadow-2xl max-w-md w-full mx-4 z-10 animate-fade-in-down">
        <div class="flex flex-col items-center text-center">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-4">
                <i class="fa-brands fa-whatsapp"></i>
            </div>
            <h3 class="text-[16px] font-extrabold text-[#071E3D] mb-1">Konfirmasi Reset Password</h3>
            <p class="text-[13px] text-gray-500 font-medium mb-4 leading-relaxed">
                Sandi baru akan dikirimkan via WhatsApp ke nomor tujuan.
            </p>

            <div class="w-full bg-gray-50 border border-gray-100 rounded-xl p-4 text-left space-y-3 mb-5">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Email / NIP</p>
                    <p id="modal-email" class="text-[13px] font-bold text-[#071E3D]">-</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nomor WhatsApp Tujuan</p>
                    <p id="modal-wa-display" class="text-[13px] font-bold text-emerald-700">-</p>
                </div>
            </div>

            <form id="form-reset-password" method="POST" data-no-ajax class="w-full">
                @csrf
                <div class="flex gap-3 w-full">
                    <button type="button" onclick="tutupModalReset()" class="flex-1 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-[13px] transition-colors cursor-pointer">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white font-bold text-[13px] transition-colors flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-brands fa-whatsapp text-sm"></i> Ya, Reset & Buka WA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function bukaModalReset(id, email, phoneDisplay, waPhone) {
        document.getElementById('modal-email').textContent = email;
        document.getElementById('modal-wa-display').textContent = phoneDisplay;

        var form = document.getElementById('form-reset-password');
        form.action = '{{ url("/admin/reset-password-requests") }}/' + id;

        var modal = document.getElementById('modal-reset-password');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function tutupModalReset() {
        var modal = document.getElementById('modal-reset-password');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            var modal = document.getElementById('modal-reset-password');
            if (!modal.classList.contains('hidden')) {
                tutupModalReset();
            }
        }
    });
</script>
@endsection
