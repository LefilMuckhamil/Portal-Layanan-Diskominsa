@if($chatAktif)
    <div>
        <textarea name="pesan" rows="2" placeholder="Balas atau kirim info ke pemohon..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[13px] outline-none focus:border-blue-400 resize-none"></textarea>
    </div>
@else
    <div class="bg-rose-50 border border-rose-100 p-3 rounded-xl text-center">
        <p class="text-[12px] font-bold text-rose-500"><i class="fa-solid fa-lock mr-1"></i> Fitur obrolan sedang dinonaktifkan sementara.</p>
    </div>
@endif