{{--
    MODAL ADMIN UNIFORM (Sumber: prototype Website)
    Parameter yang dibutuhkan dari file pemanggil (di dalam @foreach $pengajuans):
      $warna    => kunci aksen warna: indigo|violet|teal|sky|blue|cyan|rose
      $icon     => ikon FontAwesome header (mis. 'fa-route')
      $judul    => judul modal (mis. 'Kelola Pengajuan Subdomain')
      $subjudul => sub-judul modal (optional, ada default)
      $details  => array kartu detail layanan: [['label' => '...', 'value' => '...'], ...]
      $item     => instance Pengajuan
      $cleanWa  => nomor WhatsApp terkini
--}}
@php
    $aksen = match ($warna ?? 'indigo') {
        'violet' => [
            'ico'        => 'text-violet-400',
            'label'      => 'text-violet-700',
            'chip'       => 'text-violet-800 bg-violet-50 border-violet-200',
            'dot'        => 'bg-violet-500',
            'nilai'      => 'text-violet-700',
            'file'       => 'text-violet-600',
            'download'   => 'hover:border-violet-500 hover:bg-violet-50/30',
            'hasil'      => 'hover:border-violet-500 hover:bg-violet-50/40',
            'hasilIcon'  => 'group-hover:bg-violet-600 group-hover:border-violet-600',
            'hasilText'  => 'group-hover:text-violet-900',
            'lihat'      => 'text-violet-700 bg-violet-100 hover:bg-violet-200',
            'browse'     => 'group-hover:border-violet-300',
        ],
        'teal' => [
            'ico'        => 'text-teal-400',
            'label'      => 'text-teal-700',
            'chip'       => 'text-teal-800 bg-teal-50 border-teal-200',
            'dot'        => 'bg-teal-500',
            'nilai'      => 'text-teal-700',
            'file'       => 'text-teal-600',
            'download'   => 'hover:border-teal-500 hover:bg-teal-50/30',
            'hasil'      => 'hover:border-teal-500 hover:bg-teal-50/40',
            'hasilIcon'  => 'group-hover:bg-teal-600 group-hover:border-teal-600',
            'hasilText'  => 'group-hover:text-teal-900',
            'lihat'      => 'text-teal-700 bg-teal-100 hover:bg-teal-200',
            'browse'     => 'group-hover:border-teal-300',
        ],
        'sky' => [
            'ico'        => 'text-sky-400',
            'label'      => 'text-sky-700',
            'chip'       => 'text-sky-800 bg-sky-50 border-sky-200',
            'dot'        => 'bg-sky-500',
            'nilai'      => 'text-sky-700',
            'file'       => 'text-sky-600',
            'download'   => 'hover:border-sky-500 hover:bg-sky-50/30',
            'hasil'      => 'hover:border-sky-500 hover:bg-sky-50/40',
            'hasilIcon'  => 'group-hover:bg-sky-600 group-hover:border-sky-600',
            'hasilText'  => 'group-hover:text-sky-900',
            'lihat'      => 'text-sky-700 bg-sky-100 hover:bg-sky-200',
            'browse'     => 'group-hover:border-sky-300',
        ],
        'blue' => [
            'ico'        => 'text-blue-400',
            'label'      => 'text-blue-700',
            'chip'       => 'text-blue-800 bg-blue-50 border-blue-200',
            'dot'        => 'bg-blue-500',
            'nilai'      => 'text-blue-700',
            'file'       => 'text-blue-600',
            'download'   => 'hover:border-blue-500 hover:bg-blue-50/30',
            'hasil'      => 'hover:border-blue-500 hover:bg-blue-50/40',
            'hasilIcon'  => 'group-hover:bg-blue-600 group-hover:border-blue-600',
            'hasilText'  => 'group-hover:text-blue-900',
            'lihat'      => 'text-blue-700 bg-blue-100 hover:bg-blue-200',
            'browse'     => 'group-hover:border-blue-300',
        ],
        'cyan' => [
            'ico'        => 'text-cyan-400',
            'label'      => 'text-cyan-700',
            'chip'       => 'text-cyan-800 bg-cyan-50 border-cyan-200',
            'dot'        => 'bg-cyan-500',
            'nilai'      => 'text-cyan-700',
            'file'       => 'text-cyan-600',
            'download'   => 'hover:border-cyan-500 hover:bg-cyan-50/30',
            'hasil'      => 'hover:border-cyan-500 hover:bg-cyan-50/40',
            'hasilIcon'  => 'group-hover:bg-cyan-600 group-hover:border-cyan-600',
            'hasilText'  => 'group-hover:text-cyan-900',
            'lihat'      => 'text-cyan-700 bg-cyan-100 hover:bg-cyan-200',
            'browse'     => 'group-hover:border-cyan-300',
        ],
        'rose' => [
            'ico'        => 'text-rose-400',
            'label'      => 'text-rose-700',
            'chip'       => 'text-rose-800 bg-rose-50 border-rose-200',
            'dot'        => 'bg-rose-500',
            'nilai'      => 'text-rose-700',
            'file'       => 'text-rose-600',
            'download'   => 'hover:border-rose-500 hover:bg-rose-50/30',
            'hasil'      => 'hover:border-rose-500 hover:bg-rose-50/40',
            'hasilIcon'  => 'group-hover:bg-rose-600 group-hover:border-rose-600',
            'hasilText'  => 'group-hover:text-rose-900',
            'lihat'      => 'text-rose-700 bg-rose-100 hover:bg-rose-200',
            'browse'     => 'group-hover:border-rose-300',
        ],
        default => [
            'ico'        => 'text-indigo-400',
            'label'      => 'text-indigo-700',
            'chip'       => 'text-indigo-800 bg-indigo-50 border-indigo-200',
            'dot'        => 'bg-indigo-500',
            'nilai'      => 'text-indigo-700',
            'file'       => 'text-indigo-600',
            'download'   => 'hover:border-indigo-500 hover:bg-indigo-50/30',
            'hasil'      => 'hover:border-indigo-500 hover:bg-indigo-50/40',
            'hasilIcon'  => 'group-hover:bg-indigo-600 group-hover:border-indigo-600',
            'hasilText'  => 'group-hover:text-indigo-900',
            'lihat'      => 'text-indigo-700 bg-indigo-100 hover:bg-indigo-200',
            'browse'     => 'group-hover:border-indigo-300',
        ],
    };
@endphp

<div id="modal-{{ $item->id }}" class="fixed inset-0 z-[150] hidden items-center justify-center">
    <div class="absolute inset-0 bg-[#101828]/80 backdrop-blur-sm transition-opacity" onclick="tutupModalAdmin('{{ $item->id }}')"></div>
    <div class="relative bg-white rounded-3xl w-full max-w-7xl mx-4 my-auto shadow-2xl border border-slate-100 overflow-hidden max-h-[92vh] flex flex-col animate-fade-in-down">

        {{-- HEADER MODAL --}}
        <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-white shrink-0">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-11 h-11 rounded-xl bg-[#16324F] {{ $aksen['ico'] }} flex items-center justify-center text-lg shadow-md shadow-[#16324F]/20 shrink-0">
                    <i class="fa-solid {{ $icon }}"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-bold tracking-[0.16em] {{ $aksen['label'] }} uppercase mb-0.5">Admin Panel</p>
                    <h2 class="text-[16px] font-extrabold text-[#101828] leading-tight truncate">{{ $judul }}</h2>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $subjudul ?? 'Perbarui progres, unggah dokumen hasil, dan balas diskusi pemohon.' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <span class="inline-flex items-center gap-1.5 text-[10.5px] font-bold {{ $aksen['chip'] }} rounded-full px-3 py-1.5 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full {{ $aksen['dot'] }}"></span> {{ $item->nomor_tiket }}
                </span>
                <button type="button" onclick="tutupModalAdmin('{{ $item->id }}')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        {{-- Body Modal --}}
        <div class="flex-1 min-h-0 overflow-y-auto custom-scrollbar">
            <form method="POST" action="{{ route('admin.pengajuan.update', $item->id) }}" enctype="multipart/form-data" data-no-ajax onsubmit="return konfirmasiTolak(this)">
                @csrf
                @method('PUT')

                <div class="p-5 md:p-6 space-y-4">

                @if ($errors->any())
                    <div class="rounded-xl border-2 border-[#FDA29B] bg-[#FEF3F2] p-3 text-[#B42318]">
                        <div class="flex items-center text-[12px] font-bold mb-1">
                            <i class="fa-solid fa-circle-exclamation mr-2"></i> Gagal memperbarui data:
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-[#912018] font-medium pl-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- BAGIAN 1: KARTU RINGKASAN PEMOHON & BERKAS (SUMBER: $item->pemohon & relasi detail layanan) --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center bg-slate-50 p-4 rounded-xl border border-slate-200/80 mb-6">

                    {{-- KOLOM 1 (2/4): PROFIL ASN PEMOHON --}}
                    <div class="md:col-span-2 min-w-0">
                        <p class="text-[15px] font-extrabold text-[#101828]">{{ $item->pemohon?->nama ?? $item->user->name ?? 'Pemohon' }}</p>
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1.5 text-[11.5px] text-slate-600 font-medium">
                            <p class="inline-flex items-start gap-1.5 break-words">
                                <i class="fa-solid fa-id-card text-[10px] text-slate-400 mt-0.5 shrink-0"></i> NIP: {{ $item->pemohon?->nip ?? '-' }}
                            </p>
                            <p class="inline-flex items-start gap-1.5 break-words">
                                <i class="fa-solid fa-building text-[10px] text-slate-400 mt-0.5 shrink-0"></i> SKPK: {{ $item->pemohon?->instansi ?? 'Instansi' }}
                            </p>
                            <p class="inline-flex items-start gap-1.5 break-words">
                                @if(!empty($item->pemohon?->no_hp))
                                    <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="inline-flex items-start gap-1.5 hover:text-emerald-600 font-semibold transition-colors">
                                        <i class="fa-brands fa-whatsapp text-[11px] text-emerald-500 mt-0.5 shrink-0"></i> {{ \App\Support\PhoneNumber::local($item->pemohon?->no_hp ?? '') }}
                                    </a>
                                @else
                                    <i class="fa-solid fa-phone text-[10px] text-slate-400 mt-0.5 shrink-0"></i> -
                                @endif
                            </p>
                            <p class="inline-flex items-start gap-1.5 min-w-0">
                                <i class="fa-regular fa-envelope text-[10px] text-slate-400 mt-0.5 shrink-0"></i>
                                <span class="break-all">{{ $item->pemohon?->email_dinas ?? '-' }}</span>
                            </p>
                        </div>
                    </div>

                    {{-- KOLOM 2 (1/4): DETAIL LAYANAN (DATA BERBEDA PER LAYANAN) --}}
                    <div class="md:col-span-1 space-y-2 min-w-0">
                        @forelse ($details ?? [] as $index => $detail)
                            <div class="bg-white border border-slate-200/80 rounded-lg px-3 py-2">
                                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">{{ $detail['label'] }}</p>
                                <p class="text-[12px] {{ $index === 0 ? 'font-bold ' . $aksen['nilai'] : 'font-semibold text-slate-700' }} break-words leading-snug">{{ $detail['value'] }}</p>
                            </div>
                        @empty
                            <div class="bg-white border border-slate-200/80 rounded-lg px-3 py-2">
                                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Detail Layanan</p>
                                <p class="text-[12px] font-semibold text-slate-700">-</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- KOLOM 3 (1/4): DOWNLOAD SURAT PERMOHONAN --}}
                    <div class="md:col-span-1 flex items-stretch">
                        @if(!empty($item->file_pendukung))
                            <a href="{{ route('dokumen.unduh', ['pengajuan' => $item->id, 'jenis' => 'pendukung']) }}" target="_blank" class="w-full h-full min-h-[76px] flex flex-col items-center justify-center gap-1.5 px-4 py-3 bg-white border border-slate-200 {{ $aksen['download'] }} rounded-xl shadow-sm transition-all text-center">
                                <i class="fa-solid fa-file-pdf text-lg {{ $aksen['file'] }}"></i>
                                <span class="text-xs font-bold text-slate-700">Surat Permohonan</span>
                                <span class="text-[10px] text-slate-400 inline-flex items-center gap-1"><i class="fa-solid fa-download"></i> Unduh Berkas</span>
                            </a>
                        @else
                            <div class="w-full min-h-[76px] flex flex-col items-center justify-center gap-1 px-4 bg-white border border-dashed border-slate-200 rounded-xl text-slate-400 italic">
                                <i class="fa-solid fa-folder-open text-lg"></i>
                                <span class="text-xs font-semibold">Tanpa berkas</span>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- BAGIAN 2: WORKSPACE GRID 3 KOLOM --}}
                <div class="grid grid-cols-12 gap-4 items-stretch">

                    {{-- A. KOLOM 1: FORM UPDATE STATUS & PROGRES --}}
                    <div class="col-span-12 lg:col-span-4 bg-slate-50/50 border border-slate-200/80 rounded-2xl p-5 shadow-sm flex flex-col h-full">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Update Status & Progres</p>

                        <div class="space-y-3.5 flex-1">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Pengajuan <span class="text-rose-500">*</span></label>
                                <select name="status" required onchange="toggleHasilUpload(this, {{ $item->id }})" class="w-full rounded-xl border border-slate-300 py-2.5 px-3.5 text-sm font-medium focus:ring-2 focus:ring-blue-500 bg-white">
                                    @foreach(['Pending' => 'PENDING', 'Proses' => 'PROSES', 'Selesai' => 'SELESAI', 'Ditolak' => 'DITOLAK'] as $val => $label)
                                        <option value="{{ $val }}" @selected($item->status == $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan E-Tracking</label>
                                <textarea name="catatan" rows="4" placeholder="Catatan progres yang masuk langsung ke pengajuan_logs..." class="w-full rounded-xl border border-slate-300 p-3.5 text-sm focus:ring-2 focus:ring-blue-500 bg-white placeholder:text-slate-400">{{ old('catatan') }}</textarea>
                            </div>

                            <div id="hasil-upload-{{ $item->id }}" class="{{ $item->status === 'Selesai' ? '' : 'hidden' }}">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Upload File Hasil Verifikasi</label>
                                <label for="admin-hasil-upload-{{ $item->id }}" class="group flex items-center justify-between gap-3 rounded-xl border-2 border-dashed border-slate-200 bg-white {{ $aksen['hasil'] }} transition-all px-3 py-2.5 cursor-pointer">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 shrink-0 rounded-lg bg-white group-hover:text-white border border-slate-200 {{ $aksen['hasilIcon'] }} flex items-center justify-center text-slate-500 transition-colors shadow-sm">
                                            <i class="fa-solid fa-cloud-arrow-up text-[13px]"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p id="admin-hasil-name-{{ $item->id }}" class="text-[12px] text-[#101828] font-bold {{ $aksen['hasilText'] }} truncate">
                                                @if($item->file_hasil)
                                                    File Hasil Tersedia
                                                    <a href="{{ route('dokumen.unduh', ['pengajuan' => $item->id, 'jenis' => 'hasil']) }}" target="_blank" class="inline-flex items-center gap-1 ml-1.5 {{ $aksen['lihat'] }} px-2 py-0.5 rounded-md text-[10px] font-bold transition-colors">
                                                        <i class="fa-solid fa-eye text-[9px]"></i> Lihat File Hasil
                                                    </a>
                                                @else
                                                    Pilih Berkas PDF/PNG/JPG/JPEG Hasil
                                                @endif
                                            </p>
                                            <p class="text-[10px] text-slate-500">Dokumen hasil pembuatan</p>
                                        </div>
                                    </div>
                                    <div class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[10.5px] font-bold text-gray-600 {{ $aksen['browse'] }} shrink-0">Browse</div>
                                    <input id="admin-hasil-upload-{{ $item->id }}" name="file_hasil" type="file" class="sr-only" accept=".pdf,.png,.jpg,.jpeg" onchange="if(this.files && this.files[0]){document.getElementById('admin-hasil-name-{{ $item->id }}').innerText = this.files[0].name; document.getElementById('admin-hasil-name-{{ $item->id }}').classList.add('text-emerald-700')}">
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-semibold rounded-xl text-sm shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-2 cursor-pointer mt-4">
                            <i class="fa-solid fa-floppy-disk text-xs"></i> Simpan Perubahan
                        </button>
                    </div>

                    {{-- B. KOLOM 2: RUANG DISKUSI PEMOHON (SUMBER: $item->messages) --}}
                    <div class="col-span-12 lg:col-span-5 bg-slate-50/50 border border-slate-200/80 rounded-2xl p-5 shadow-sm flex flex-col h-full">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Ruang Diskusi Pemohon</p>

                        <div id="admin-chat-box-{{ $item->id }}" class="h-[350px] overflow-y-auto p-4 bg-white border border-slate-200 rounded-2xl space-y-2 text-sm custom-scrollbar">
                            @if($item->messages->isNotEmpty())
                                @foreach($item->messages->sortBy('created_at') as $chat)
                                    <div class="flex flex-col {{ $chat->role === 'admin' ? 'items-end' : 'items-start' }}">
                                        <div class="max-w-[85%] px-3 py-1.5 rounded-xl text-[11.5px] leading-relaxed {{ $chat->role === 'admin' ? 'bg-[#16324F] text-white rounded-br-none' : 'bg-slate-100 border border-slate-200 text-[#101828] rounded-bl-none' }}">
                                            <p class="font-bold text-[9.5px] opacity-80 mb-0.5">{{ $chat->pengirim }}</p>
                                            <p>{{ $chat->isi }}</p>
                                        </div>
                                        <span class="text-[9.5px] text-[#667085] mt-0.5">{{ $chat->waktu }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="chat-empty-state flex flex-col items-center justify-center h-full text-slate-400">
                                    <i class="fa-regular fa-comments text-2xl mb-1 opacity-40"></i>
                                    <p class="text-sm italic">Belum ada obrolan dengan pemohon.</p>
                                </div>
                            @endif
                        </div>

                        @if($chatAktif ?? true)
                            <div class="flex gap-2 mt-3">
                                <input type="text" name="pesan" id="pesan-input-{{ $item->id }}" value="{{ old('pesan') }}" placeholder="Ketik pesan balasan atau pertanyaan..." autocomplete="off" onkeydown="if(event.key === 'Enter'){event.preventDefault();kirimPesanWebsite('{{ $item->id }}', '{{ route('admin.pengajuan.pesan', $item->id) }}')}" class="flex-1 min-w-0 rounded-xl border border-slate-300 py-2.5 px-3.5 text-sm focus:ring-2 focus:ring-blue-500 bg-white placeholder:text-slate-400">
                                <button type="button" onclick="kirimPesanWebsite('{{ $item->id }}', '{{ route('admin.pengajuan.pesan', $item->id) }}')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm transition-all cursor-pointer shrink-0">
                                    <i class="fa-solid fa-paper-plane text-xs"></i> Kirim
                                </button>
                            </div>
                        @else
                            <div class="bg-rose-50 border border-rose-100 p-2 rounded-xl text-center shadow-sm mt-3">
                                <p class="text-[11px] font-bold text-rose-500"><i class="fa-solid fa-lock mr-1"></i> Fitur obrolan dinonaktifkan.</p>
                            </div>
                        @endif
                    </div>

                    {{-- C. KOLOM 3: PANEL E-TRACKING SINGKAT (SUMBER: $item->riwayatStatus / pengajuan_logs) --}}
                    <div class="col-span-12 lg:col-span-3 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex flex-col h-full">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2.5">Riwayat Progres</p>
                        <div class="h-[350px] overflow-y-auto bg-slate-50 border border-slate-100 rounded-xl p-3 space-y-3 custom-scrollbar">
                            @if($item->riwayatStatus->isNotEmpty())
                                @foreach($item->riwayatStatus->sortBy('id') as $log)
                                    @php
                                        $logBadge = match($log->status) {
                                            'Pending' => 'bg-amber-100 text-amber-700',
                                            'Proses'  => 'bg-blue-100 text-blue-700',
                                            'Selesai' => 'bg-emerald-100 text-emerald-700',
                                            'Ditolak' => 'bg-rose-100 text-rose-700',
                                            default   => 'bg-gray-200 text-gray-600',
                                        };
                                    @endphp
                                    <div class="relative pl-4">
                                        <div class="absolute left-0 top-1.5 w-2 h-2 rounded-full bg-slate-300"></div>
                                        <div class="flex flex-wrap items-center gap-1.5 mb-0.5">
                                            <span class="px-2 py-0.5 rounded-lg text-[9px] font-extrabold uppercase tracking-wider {{ $logBadge }}">{{ $log->status }}</span>
                                            <span class="text-[9.5px] text-[#667085] font-bold">{{ $log->created_at?->format('d M Y H:i') ?? '-' }}</span>
                                        </div>
                                        <p class="text-[10.5px] font-bold text-[#101828] truncate" title="{{ $log->admin?->name ?? 'Sistem' }}">
                                            <i class="fa-regular fa-user text-[9px] text-slate-400 mr-1"></i>{{ $log->admin?->name ?? 'Sistem' }}
                                        </p>
                                        @if($log->catatan_admin)
                                            <p class="text-[10.5px] text-slate-600 font-medium mt-1 bg-white border border-slate-200 rounded-lg p-2 leading-relaxed">"{{ $log->catatan_admin }}"</p>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-center text-slate-400">
                                    <i class="fa-solid fa-bars-progress text-xl mb-1 opacity-40"></i>
                                    <p class="text-[12px] italic">Belum ada riwayat progres.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
                </div>
            </form>
        </div>
    </div>
</div>

@once
<script>
    function kirimPesanWebsite(id, urlRoute) {
        var input = document.getElementById('pesan-input-' + id);
        var chatBox = document.getElementById('admin-chat-box-' + id);
        if (!input || !chatBox) return;

        var isi = (input.value || '').trim();
        if (!isi) return;

        var token = document.querySelector('meta[name="csrf-token"]')?.content
            || document.querySelector('input[name="_token"]')?.value || '';

        var btn = input.closest('div').querySelector('button[type="button"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i>';
        }

        fetch(urlRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ pesan: isi })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.status !== 'success' || !data.pesan) {
                alert(data.pesan || 'Gagal mengirim pesan.');
                return;
            }

            var emptyState = chatBox.querySelector('.chat-empty-state');
            if (emptyState) emptyState.remove();

            var msg = data.pesan;
            var wrapper = document.createElement('div');
            wrapper.className = 'flex flex-col items-end';

            var bubble = document.createElement('div');
            bubble.className = 'max-w-[85%] px-3 py-1.5 rounded-xl text-[11.5px] leading-relaxed bg-[#16324F] text-white rounded-br-none';

            var sender = document.createElement('p');
            sender.className = 'font-bold text-[9.5px] opacity-80 mb-0.5';
            sender.textContent = msg.pengirim || 'Admin Diskominsa';

            var text = document.createElement('p');
            text.textContent = msg.isi;

            bubble.appendChild(sender);
            bubble.appendChild(text);
            wrapper.appendChild(bubble);

            var waktu = document.createElement('span');
            waktu.className = 'text-[9.5px] text-[#667085] mt-0.5';
            waktu.textContent = msg.waktu || '';

            wrapper.appendChild(waktu);
            chatBox.appendChild(wrapper);

            input.value = '';
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(function () {
            alert('Gagal mengirim pesan. Periksa koneksi Anda.');
        })
        .finally(function () {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane text-xs"></i> Kirim';
            }
        });
    }
</script>
@endonce