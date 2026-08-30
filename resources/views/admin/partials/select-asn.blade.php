@php
    $prefix = $prefix ?? 'default';
    $listAsn = $users ?? \App\Models\User::where('role', '!=', 'admin')->get();
@endphp

<div class="col-span-1 md:col-span-2">
    <label class="block text-[11.5px] font-bold text-[#344054] mb-1">Pilih ASN Pemohon <span class="text-rose-500">*</span></label>
    <input
        type="text"
        id="asn-picker-{{ $prefix }}"
        list="list-asn-{{ $prefix }}"
        autocomplete="off"
        placeholder="Ketik nama atau NIP untuk mencari ASN..."
        oninput="handleAsnSelected(this, '{{ $prefix }}')"
        class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-[12.5px] text-[#101828] font-bold placeholder:text-slate-400 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all shadow-sm"
    >
    <input type="hidden" name="user_id" id="user_id_hidden_{{ $prefix }}" value="{{ old('user_id') }}">
    <datalist id="list-asn-{{ $prefix }}">
        @forelse($listAsn as $asn)
            <option
                value="{{ $asn->name }} - {{ $asn->nip ?? '' }}"
                data-id="{{ $asn->id }}"
                data-name="{{ $asn->name }}"
                data-nip="{{ $asn->nip ?? '' }}"
                data-instansi="{{ $asn->unit_kerja ?? '' }}"
                data-hp="{{ $asn->no_hp ?? '' }}"
                data-jabatan="{{ $asn->jabatan ?? '' }}"
                data-email="{{ $asn->email ?? '' }}"
            >{{ $asn->name }} - {{ $asn->nip ?? $asn->unit_kerja ?? 'ASN' }}</option>
        @empty
            <option value="" disabled>Belum ada data ASN</option>
        @endforelse
    </datalist>
</div>