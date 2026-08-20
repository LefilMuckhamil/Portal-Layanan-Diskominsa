@php
    $prefix = $prefix ?? 'default';
    $listAsn = $users ?? \App\Models\User::where('role', '!=', 'admin')->get();
@endphp

<div class="col-span-1 md:col-span-2">
    <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Pilih ASN Pemohon <span class="text-rose-500">*</span></label>
    <div class="bg-white border border-slate-300 rounded-xl flex items-center px-3 relative shadow-sm focus-within:border-indigo-500 transition-all">
        <i class="fa-solid fa-user-check text-indigo-600 text-[13px] mr-2"></i>
        <input
            type="text"
            id="asn-picker-{{ $prefix }}"
            list="list-asn-{{ $prefix }}"
            autocomplete="off"
            placeholder="Ketik nama atau NIP untuk mencari ASN..."
            oninput="handleAsnSelected(this, '{{ $prefix }}')"
            class="flex-1 min-w-0 bg-transparent outline-none py-2 text-[12.5px] text-[#101828] font-bold"
        >
        <input type="hidden" name="user_id" id="user_id_hidden_{{ $prefix }}" value="{{ old('user_id') }}">
    </div>
    <datalist id="list-asn-{{ $prefix }}">
        @forelse($listAsn as $asn)
            <option
                value="{{ $asn->name }} - {{ $asn->nip ?? '' }}"
                data-id="{{ $asn->id }}"
                data-name="{{ $asn->name }}"
                data-nip="{{ $asn->nip ?? '' }}"
                data-instansi="{{ $asn->unit_kerja ?? '' }}"
                data-hp="{{ $asn->no_hp ?? '' }}"
                data-email="{{ $asn->email ?? '' }}"
            >{{ $asn->name }} - {{ $asn->nip ?? $asn->unit_kerja ?? 'ASN' }}</option>
        @empty
            <option value="" disabled>Belum ada data ASN</option>
        @endforelse
    </datalist>
</div>
