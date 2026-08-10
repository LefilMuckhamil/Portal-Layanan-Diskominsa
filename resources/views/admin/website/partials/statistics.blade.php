<!-- Konfigurasi Data Statistik -->
@php
    $statistik = [
        ['title' => 'Total Permohonan', 'count' => $total ?? 0, 'label' => 'Unit Web', 'icon' => 'fa-laptop-code', 'theme' => 'indigo'],
        ['title' => 'Menunggu Verif', 'count' => $pending ?? 0, 'label' => 'Permohonan', 'icon' => 'fa-file-circle-exclamation', 'theme' => 'amber'],
        ['title' => 'Proses Development', 'count' => $proses ?? 0, 'label' => 'Dikerjakan', 'icon' => 'fa-code', 'theme' => 'blue'],
        ['title' => 'Selesai', 'count' => $selesai ?? 0, 'label' => 'Web Aktif', 'icon' => 'fa-check-circle', 'theme' => 'green'],
        ['title' => 'Ditolak', 'count' => $ditolak ?? 0, 'label' => 'Dibatalkan', 'icon' => 'fa-circle-xmark', 'theme' => 'rose'],
    ];

    $colors = [
        'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-500'],
        'amber'  => ['bg' => 'bg-amber-50', 'text' => 'text-amber-500'],
        'blue'   => ['bg' => 'bg-blue-50', 'text' => 'text-blue-500'],
        'green'  => ['bg' => 'bg-green-50', 'text' => 'text-green-500'],
        'rose'   => ['bg' => 'bg-rose-50', 'text' => 'text-rose-500'],
    ];
@endphp

<!-- Deretan Kartu Statistik Dinamis -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-6">
    @foreach ($statistik as $stat)
        @php $c = $colors[$stat['theme']]; @endphp
        <div class="bg-white rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-3">
                <div class="w-11 h-11 rounded-2xl {{ $c['bg'] }} {{ $c['text'] }} flex items-center justify-center text-lg">
                    <i class="fa-solid {{ $stat['icon'] }}"></i>
                </div>
            </div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">{{ $stat['title'] }}</p>
            <div class="flex items-end gap-2 mt-1">
                <h3 class="text-3xl font-extrabold text-[#071E3D]">{{ $stat['count'] }}</h3>
                <span class="text-[10px] font-bold {{ $c['text'] }} mb-1">{{ $stat['label'] }}</span>
            </div>
        </div>
    @endforeach
</div>