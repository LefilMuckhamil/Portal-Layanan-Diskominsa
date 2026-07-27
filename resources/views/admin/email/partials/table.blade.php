<div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden flex flex-col">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-[#071E3D]">Daftar Ajuan Email</h3>
            <p class="text-[12px] text-gray-400 font-medium mt-1">Kelola persetujuan dan pembuatan akun email resmi.</p>
        </div>
        
        <div class="flex gap-3">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                <input type="text" placeholder="Cari NIP / Instansi..." class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none focus:border-cyan-400 focus:bg-white w-48 transition-all">
            </div>
            <div class="relative">
                <i class="fa-solid fa-filter absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-[11px]"></i>
                <select class="pl-8 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-lg text-[12px] font-bold text-gray-600 outline-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="proses">Diproses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[900px]">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                <tr>
                    <th class="py-3 px-6">ID Ajuan</th>
                    <th class="py-3 px-6">Data Pegawai / Instansi</th>
                    <th class="py-3 px-6">Jenis Akun</th>
                    <th class="py-3 px-6">Tgl Masuk</th>
                    <th class="py-3 px-6">Status (Edit)</th>
                    <th class="py-3 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                
                {{-- PERSIAPAN BE: Looping Data --}}
                {{-- @forelse ($data_email as $item) --}}
                
                <!-- Row 1: Email ASN -->
                <tr class="hover:bg-cyan-50/20 transition-colors duration-200">
                    <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D]">#EML-001</td>
                    <td class="py-4 px-6 flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=F3F4F6&color=374151" class="w-9 h-9 rounded-full object-cover">
                        <div>
                            <p class="text-[13px] font-bold text-[#071E3D]">Budi Santoso, S.Kom</p>
                            <p class="text-[11px] text-gray-500 font-medium">NIP: 198002123...</p>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide bg-indigo-50 text-indigo-600 rounded-md">
                            Akun ASN
                        </span>
                    </td>
                    <td class="py-4 px-6 text-[12px] text-gray-500 font-bold">22 Jul 2026</td>
                    <td class="py-4 px-6">
                        <div class="relative inline-block">
                            <select name="status" onchange="/* this.form.submit() */" class="appearance-none bg-amber-50 text-amber-600 border border-amber-100 hover:bg-amber-100 font-extrabold text-[10px] uppercase tracking-wider py-1.5 pl-3 pr-7 rounded-lg cursor-pointer outline-none transition-all">
                                <option value="pending" selected>Menunggu</option>
                                <option value="proses">Diproses</option>
                                <option value="selesai">Selesai / Aktif</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-2.5 top-1/2 transform -translate-y-1/2 text-[10px] text-amber-600 pointer-events-none"></i>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-right space-x-1">
                        <button class="p-2 text-gray-400 hover:text-green-500 transition-colors bg-white hover:bg-green-50 rounded-lg shadow-sm border border-gray-100" title="Cek SK & KTP"><i class="fa-solid fa-file-lines text-[13px]"></i></button>
                        <button class="p-2 text-gray-400 hover:text-cyan-500 transition-colors bg-white hover:bg-cyan-50 rounded-lg shadow-sm border border-gray-100" title="Kirim Info ke User"><i class="fa-solid fa-paper-plane text-[13px]"></i></button>
                    </td>
                </tr>

                <!-- Row 2: Email Instansi -->
                <tr class="hover:bg-cyan-50/20 transition-colors duration-200">
                    <td class="py-4 px-6 text-[13px] font-extrabold text-[#071E3D]">#EML-002</td>
                    <td class="py-4 px-6 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center font-bold"><i class="fa-solid fa-building text-sm"></i></div>
                        <div>
                            <p class="text-[13px] font-bold text-[#071E3D]">Dinas Kesehatan</p>
                            <p class="text-[11px] text-gray-500 font-medium">Instansi Kabupaten</p>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide bg-emerald-50 text-emerald-600 rounded-md">
                            Akun Instansi
                        </span>
                    </td>
                    <td class="py-4 px-6 text-[12px] text-gray-500 font-bold">20 Jul 2026</td>
                    <td class="py-4 px-6">
                        <div class="relative inline-block">
                            <select name="status" onchange="/* this.form.submit() */" class="appearance-none bg-green-50 text-green-600 border border-green-100 hover:bg-green-100 font-extrabold text-[10px] uppercase tracking-wider py-1.5 pl-3 pr-7 rounded-lg cursor-pointer outline-none transition-all">
                                <option value="pending">Menunggu</option>
                                <option value="proses">Diproses</option>
                                <option value="selesai" selected>Selesai / Aktif</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-2.5 top-1/2 transform -translate-y-1/2 text-[10px] text-green-600 pointer-events-none"></i>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-right space-x-1">
                        <button class="p-2 text-gray-400 hover:text-green-500 transition-colors bg-white hover:bg-green-50 rounded-lg shadow-sm border border-gray-100" title="Cek Surat Permohonan"><i class="fa-solid fa-file-lines text-[13px]"></i></button>
                        <button class="p-2 text-gray-400 hover:text-cyan-500 transition-colors bg-white hover:bg-cyan-50 rounded-lg shadow-sm border border-gray-100" title="Kirim Info ke User"><i class="fa-solid fa-paper-plane text-[13px]"></i></button>
                    </td>
                </tr>

                {{-- @empty --}}
                {{-- @endforelse --}}

            </tbody>
        </table>
    </div>
</div>