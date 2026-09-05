@extends('layouts.admin')

@section('header_title', 'Manajemen Cloud Government')
@section('header_subtitle', 'Kelola pembuatan akun dan alokasi kapasitas penyimpanan (storage) untuk ASN dan Instansi.')

@section('content')
   
    @include('admin.cloud.partials.statistics')

    <!-- Memanggil Partial Tabel -->
    @include('admin.cloud.partials.table')

    <script>
        function bukaModalCreate() {
            document.getElementById('modal-create').classList.remove('hidden');
            document.getElementById('modal-create').classList.add('flex');
        }
        function tutupModalCreate() {
            document.getElementById('modal-create').classList.add('hidden');
            document.getElementById('modal-create').classList.remove('flex');
        }

        function bukaModalAdmin(id) {
            document.getElementById('modal-' + id).classList.remove('hidden');
            document.getElementById('modal-' + id).classList.add('flex');
        }
        function tutupModalAdmin(id) {
            if (window.stopChatPolling) window.stopChatPolling();
            document.getElementById('modal-' + id).classList.add('hidden');
            document.getElementById('modal-' + id).classList.remove('flex');
        }

        function bukaModalDelete(id) {
            document.getElementById('modal-delete-' + id).classList.remove('hidden');
            document.getElementById('modal-delete-' + id).classList.add('flex');
        }
        function tutupModalDelete(id) {
            document.getElementById('modal-delete-' + id).classList.add('hidden');
            document.getElementById('modal-delete-' + id).classList.remove('flex');
        }

        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                bukaModalCreate();
            });
        @endif

        function konfirmasiTolak(form) {
            const status = form.querySelector('select[name="status"]');
            if (status && status.value === 'Ditolak' && !confirm('Apakah Anda yakin menolak pengajuan ini? Status pengajuan akan menjadi Ditolak.')) {
                return false;
            }
            disableSubmitButton(form);
            return true;
        }

        function disableSubmitButton(form) {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                btn.innerHTML = 'Memproses... <i class="fa-solid fa-spinner fa-spin ml-2"></i>';
            }
        }
    </script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
@endsection
