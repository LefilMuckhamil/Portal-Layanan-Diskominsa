@extends('layouts.admin')

@section('header_title', 'Dashboard Portal Layanan Diskominsa Aceh Barat')
@section('header_subtitle', 'Pantau seluruh statistik dan pengajuan layanan secara real-time.')

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
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">Pusat Bantuan</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-extrabold text-[#071E3D] leading-none">{{ $countBantuan ?? 0 }}</h3>
                    <span class="text-[10px] font-bold text-rose-500 mb-1">Ajuan</span>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50">
            <h3 class="text-[14px] font-extrabold text-[#071E3D] mb-4"><i class="fa-solid fa-chart-pie text-cyan-500 mr-2"></i>Komposisi Status</h3>
            <div class="relative w-full flex items-center justify-center" style="height: 240px;">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50">
            <h3 class="text-[14px] font-extrabold text-[#071E3D] mb-4"><i class="fa-solid fa-chart-bar text-cyan-500 mr-2"></i>Volume per Layanan</h3>
            <div class="relative w-full" style="height: 240px;">
                <canvas id="chartLayanan"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-[14px] font-extrabold text-[#071E3D]"><i class="fa-solid fa-calendar-days text-cyan-500 mr-2"></i>Kalender</h3>
                <button type="button" id="btnTampilkanSemua" onclick="tampilkanSemua()" class="text-[11px] font-bold text-cyan-600 hover:text-cyan-800 transition-colors {{ $tanggal ? '' : 'hidden' }}">
                    <i class="fa-solid fa-rotate-left mr-1"></i>Tampilkan Semua
                </button>
            </div>
            <div id="calendarWidget" class="select-none"></div>
        </div>
    </div>

    @include('admin.dashboard.partials.table')

    <script>
        let chartBar, chartDoughnut;
        let activeTanggal = @json($tanggal);
        const chartDataUrl = '{{ url("/admin/dashboard/chart-data") }}';
        const exportBaseUrl = '{{ route("admin.pengajuan.export") }}';

        function updateExportLink(tgl) {
            const btn = document.getElementById('btnExport');
            if (!btn) return;
            btn.href = tgl ? exportBaseUrl + '?start_date=' + tgl + '&end_date=' + tgl : exportBaseUrl;
        }

        function fetchChartData(tgl) {
            const url = tgl ? chartDataUrl + '?tanggal=' + tgl : chartDataUrl;
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status !== 'success') return;
                updateCharts(data.chartData);
                updateExportLink(data.tanggal);
                updateTampilkanSemua(data.tanggal);
            })
            .catch(function () {});
        }

        function updateCharts(cd) {
            if (chartBar) {
                chartBar.data.datasets[0].data = cd.volume;
                chartBar.update();
            }
            if (chartDoughnut) {
                chartDoughnut.data.datasets[0].data = Object.values(cd.status);
                chartDoughnut.update();
            }
        }

        function updateTampilkanSemua(tgl) {
            const btn = document.getElementById('btnTampilkanSemua');
            if (btn) {
                tgl ? btn.classList.remove('hidden') : btn.classList.add('hidden');
            }
        }

        function tampilkanSemua() {
            activeTanggal = null;
            fetchChartData(null);
            document.querySelectorAll('#calendarWidget a[data-day]').forEach(function (el) {
                el.classList.remove('bg-cyan-500', 'text-white', 'shadow-md');
                if (el.dataset.isToday === 'true') {
                    el.classList.add('bg-cyan-50', 'text-cyan-600');
                } else {
                    el.classList.add('text-gray-600');
                }
            });
        }

        function highlightDay(dateStr) {
            document.querySelectorAll('#calendarWidget a[data-day]').forEach(function (el) {
                el.classList.remove('bg-cyan-500', 'text-white', 'shadow-md');
                if (el.dataset.day === dateStr) {
                    el.classList.add('bg-cyan-500', 'text-white', 'shadow-md');
                } else {
                    if (el.dataset.isToday === 'true') {
                        el.classList.add('bg-cyan-50', 'text-cyan-600');
                    } else {
                        el.classList.add('text-gray-600');
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const initChartData = @json($chartData);

            const barCtx = document.getElementById('chartLayanan');
            if (barCtx) {
                chartBar = new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: initChartData.layanan,
                        datasets: [{
                            label: 'Jumlah Pengajuan',
                            data: initChartData.volume,
                            backgroundColor: ['#818cf8', '#22d3ee', '#34d399', '#38bdf8', '#fb7185'],
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, font: { size: 11, family: 'Outfit', weight: '600' } },
                                grid: { color: '#f1f5f9' }
                            },
                            x: {
                                ticks: { font: { size: 10, family: 'Outfit', weight: '600' } },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            const doughnutCtx = document.getElementById('chartStatus');
            if (doughnutCtx) {
                const statusData = initChartData.status;
                const hasData = Object.values(statusData).some(function (v) { return v > 0; });
                chartDoughnut = new Chart(doughnutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(statusData),
                        datasets: [{
                            data: Object.values(statusData),
                            backgroundColor: ['#fbbf24', '#3b82f6', '#10b981', '#f43f5e'],
                            borderWidth: 0,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: hasData ? '65%' : '100%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 12,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: { size: 10, family: 'Outfit', weight: '600' }
                                }
                            }
                        }
                    }
                });
            }

            // Calendar Widget
            const calEl = document.getElementById('calendarWidget');
            if (calEl) {
                const baseParams = @json($calendarParams);
                let viewDate = activeTanggal ? new Date(activeTanggal + 'T00:00:00') : new Date();

                function renderCalendar() {
                    const year = viewDate.getFullYear();
                    const month = viewDate.getMonth();
                    const firstDay = new Date(year, month, 1).getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    const dayNames = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];

                    let html = '<div class="flex items-center justify-between mb-3">';
                    html += '<button id="calPrev" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 text-[11px] transition-colors cursor-pointer"><i class="fa-solid fa-chevron-left"></i></button>';
                    html += '<span class="text-[13px] font-extrabold text-[#071E3D]">' + monthNames[month] + ' ' + year + '</span>';
                    html += '<button id="calNext" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 text-[11px] transition-colors cursor-pointer"><i class="fa-solid fa-chevron-right"></i></button>';
                    html += '</div>';

                    html += '<div class="grid grid-cols-7 gap-0 mb-1">';
                    dayNames.forEach(function (d) {
                        html += '<div class="text-center text-[10px] font-bold text-gray-400 py-1">' + d + '</div>';
                    });
                    html += '</div>';

                    html += '<div class="grid grid-cols-7 gap-0">';
                    for (let i = 0; i < firstDay; i++) {
                        html += '<div></div>';
                    }
                    const todayStr = new Date().toISOString().slice(0, 10);
                    for (let day = 1; day <= daysInMonth; day++) {
                        const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
                        const isToday = todayStr === dateStr;
                        const isActive = activeTanggal === dateStr;
                        let cls = 'w-full aspect-square flex items-center justify-center text-[12px] font-bold rounded-lg transition-all cursor-pointer ';
                        if (isActive) {
                            cls += 'bg-cyan-500 text-white shadow-md';
                        } else if (isToday) {
                            cls += 'bg-cyan-50 text-cyan-600 hover:bg-cyan-100';
                        } else {
                            cls += 'text-gray-600 hover:bg-gray-100';
                        }
                        html += '<a href="#" data-day="' + dateStr + '" data-is-today="' + isToday + '" class="' + cls + '">' + day + '</a>';
                    }
                    html += '</div>';

                    calEl.innerHTML = html;

                    document.getElementById('calPrev').addEventListener('click', function (e) {
                        e.preventDefault();
                        viewDate.setMonth(viewDate.getMonth() - 1);
                        renderCalendar();
                    });
                    document.getElementById('calNext').addEventListener('click', function (e) {
                        e.preventDefault();
                        viewDate.setMonth(viewDate.getMonth() + 1);
                        renderCalendar();
                    });

                    calEl.querySelectorAll('a[data-day]').forEach(function (link) {
                        link.addEventListener('click', function (e) {
                            e.preventDefault();
                            const tgl = this.dataset.day;
                            activeTanggal = tgl;
                            highlightDay(tgl);
                            fetchChartData(tgl);
                        });
                    });
                }

                renderCalendar();
            }
        });
    </script>
@endsection