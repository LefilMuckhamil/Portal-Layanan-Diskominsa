<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Portal Layanan & E-Tracking') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('image/icon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#f4f7f6] text-gray-800 h-screen flex overflow-hidden selection:bg-cyan-300 selection:text-[#071E3D]">

    <div id="sidebarBackdrop" onclick="closeSidebar()" class="fixed inset-0 bg-[#071E3D]/60 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity duration-300 opacity-0 pointer-events-none"></div>

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-full relative overflow-hidden">
    
        @include('admin.partials.navbar')

        <div class="flex-1 overflow-y-auto p-8 space-y-6">
            @if(session('sukses'))
            <div id="flash-sukses" class="flex items-center justify-between gap-3 p-4 rounded-2xl border-2 border-emerald-200 bg-emerald-50 text-emerald-800 shadow-sm">
                <p class="text-[13px] font-bold flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> {{ session('sukses') }}
                </p>
                <button type="button" onclick="document.getElementById('flash-sukses').remove()" class="text-emerald-500 hover:text-emerald-800 transition-colors cursor-pointer" aria-label="Tutup notifikasi">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div id="flash-error" class="flex items-center justify-between gap-3 p-4 rounded-2xl border-2 border-rose-200 bg-rose-50 text-rose-800 shadow-sm">
                <p class="text-[13px] font-bold flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </p>
                <button type="button" onclick="document.getElementById('flash-error').remove()" class="text-rose-500 hover:text-rose-800 transition-colors cursor-pointer" aria-label="Tutup notifikasi">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @yield('content')
        </div>

    </main>

    <div id="modal-logout" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div onclick="tutupModalLogout()" class="absolute inset-0 bg-[#071E3D]/60 backdrop-blur-sm transition-opacity"></div>
        <div class="relative bg-white rounded-2xl p-6 shadow-2xl w-full max-w-sm mx-4 z-10">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </div>
                <h3 class="text-[16px] font-extrabold text-[#071E3D] mb-1">Konfirmasi Keluar</h3>
                <p class="text-[13px] text-gray-500 font-medium mb-6">Apakah Anda yakin ingin mengakhiri sesi admin saat ini?</p>
                <div class="flex gap-3 w-full">
                    <button onclick="tutupModalLogout()" class="flex-1 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-[13px] transition-colors cursor-pointer">Batal</button>
                    <button onclick="document.getElementById('form-logout').submit()" class="flex-1 py-2.5 rounded-xl bg-rose-500 hover:bg-rose-600 text-white font-bold text-[13px] transition-colors cursor-pointer">Ya, Keluar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            backdrop.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
            backdrop.classList.add('opacity-100');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            backdrop.classList.remove('opacity-100');
            setTimeout(() => { backdrop.classList.add('hidden'); }, 300);
            document.body.style.overflow = '';
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });

        function copyTiket(tiket) {
            navigator.clipboard.writeText(tiket).then(() => {
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-6 right-6 z-50 px-4 py-2.5 bg-[#071E3D] text-white text-[12px] font-bold rounded-xl shadow-lg transition-opacity';
                toast.innerHTML = '<i class="fa-solid fa-check mr-1.5 text-emerald-400"></i>Nomor tiket disalin';
                document.body.appendChild(toast);
                setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 1500);
            });
        }

        function bukaModalLogout() {
            const m = document.getElementById('modal-logout');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
        function tutupModalLogout() {
            const m = document.getElementById('modal-logout');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const m = document.getElementById('modal-logout');
                if (!m.classList.contains('hidden')) { tutupModalLogout(); }
            }
        });

        (function () {
            let pollTimer = null;

            function getChatUrl(id) {
                return '{{ url("/admin/pengajuan") }}/' + id + '/chat';
            }

            function escapeHtml(str) {
                const d = document.createElement('div');
                d.textContent = str;
                return d.innerHTML;
            }

            function pollVisibleModals() {
                document.querySelectorAll('[id^="modal-"]:not(#modal-logout):not(#modal-create)').forEach(function (modal) {
                    if (modal.classList.contains('hidden')) return;
                    const id = modal.id.replace('modal-', '');
                    const chatBox = modal.querySelector('.bg-slate-50.border.border-slate-200\\/80');
                    if (!chatBox) return;
                    const currentCount = chatBox.querySelectorAll('.flex.flex-col').length;

                    fetch(getChatUrl(id), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (data.status !== 'success') return;
                        const pesan = data.pesan || [];
                        if (pesan.length <= currentCount) return;

                        const newMessages = pesan.slice(currentCount);
                        newMessages.forEach(function (msg) {
                            const isUser = msg.role === 'user';
                            const wrapper = document.createElement('div');
                            wrapper.className = 'flex flex-col ' + (isUser ? 'items-start' : 'items-end');

                            const bubble = document.createElement('div');
                            bubble.className = 'max-w-[85%] px-3 py-1.5 rounded-xl text-[11.5px] ' +
                                (isUser ? 'bg-white border border-slate-200 text-[#101828] rounded-bl-none shadow-sm' : 'bg-[#16324F] text-white rounded-br-none');

                            const sender = document.createElement('p');
                            sender.className = 'font-bold text-[9.5px] opacity-80 mb-0.5';
                            sender.textContent = msg.pengirim || '';

                            const isi = document.createElement('p');
                            isi.className = 'leading-relaxed';
                            isi.textContent = msg.isi || '';

                            bubble.append(sender, isi);

                            const waktu = document.createElement('span');
                            waktu.className = 'text-[9px] text-[#667085] mt-0.5';
                            waktu.textContent = msg.waktu || '';

                            wrapper.append(bubble, waktu);
                            chatBox.appendChild(wrapper);
                        });

                        chatBox.scrollTop = chatBox.scrollHeight;
                    })
                    .catch(function () {});
                });
            }

            pollTimer = setInterval(pollVisibleModals, 6000);
        })();

        (function () {
            var _ajaxAbort = null;
            var _debounceTimer = null;

            function buildUrl(form, page) {
                var url = new URL(form.action, window.location.origin);
                var fd = new FormData(form);
                var entries = fd.entries();
                var pair = entries.next();
                while (!pair.done) {
                    if (pair.value[1]) {
                        url.searchParams.set(pair.value[0], pair.value[1]);
                    }
                    pair = entries.next();
                }
                if (page) {
                    url.searchParams.set('page', page);
                }
                return url.toString();
            }

            function fetchTable(form, page) {
                if (_ajaxAbort) {
                    _ajaxAbort.abort();
                }
                _ajaxAbort = new AbortController();

                var card = form.closest('[data-ajax-table]');
                if (!card) return;

                var tbody = card.querySelector('#admin-tbody');
                if (tbody) {
                    tbody.style.opacity = '0.5';
                    tbody.style.transition = 'opacity 0.15s ease';
                }

                var url = buildUrl(form, page);

                fetch(url, {
                    signal: _ajaxAbort.signal,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json, text/html, */*'
                    }
                })
                .then(function (r) {
                    if (!r.ok) throw new Error(r.statusText);
                    return r.json();
                })
                .then(function (data) {
                    if (!data.html) return;
                    var doc = new DOMParser().parseFromString(data.html, 'text/html');
                    var newCard = doc.querySelector('[data-ajax-table]');
                    if (newCard) {
                        card.outerHTML = newCard.outerHTML;
                    }
                })
                .catch(function (e) {
                    if (e.name !== 'AbortError') {
                        if (tbody) {
                            tbody.style.opacity = '1';
                        }
                    }
                });
            }

            document.addEventListener('input', function (e) {
                if (!e.target.matches('[data-ajax-table] input[name="search"]')) return;
                clearTimeout(_debounceTimer);
                var input = e.target;
                _debounceTimer = setTimeout(function () {
                    var form = input.closest('form');
                    if (form) fetchTable(form);
                }, 400);
            }, { passive: true });

            document.addEventListener('submit', function (e) {
                if (!e.target.closest('[data-ajax-table]')) return;
                e.preventDefault();
                e.stopPropagation();
                fetchTable(e.target);
            }, true);

            document.addEventListener('click', function (e) {
                var link = e.target.closest('[data-ajax-table] #admin-pagination a');
                if (!link) return;
                e.preventDefault();
                e.stopPropagation();
                var href = link.getAttribute('href');
                if (!href) return;
                var urlObj = new URL(href, window.location.origin);
                var page = urlObj.searchParams.get('page');
                var form = document.querySelector('[data-ajax-table] form');
                if (form) fetchTable(form, page);
            }, true);
        })();
    </script>
</body>
</html>