@extends('layouts.admin')

@section('header_title', 'Manajemen Cloud Government')
@section('header_subtitle', 'Kelola pembuatan akun dan alokasi kapasitas penyimpanan (storage) untuk ASN dan Instansi.')

@section('content')
    <!-- Memanggil Partial Statistik -->
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
            document.getElementById('modal-' + id).classList.add('hidden');
            document.getElementById('modal-' + id).classList.remove('flex');
        }

        function bukaModalInfo(id) {
            document.getElementById('modal-info-' + id).classList.remove('hidden');
            document.getElementById('modal-info-' + id).classList.add('flex');
        }
        function tutupModalInfo(id) {
            document.getElementById('modal-info-' + id).classList.add('hidden');
            document.getElementById('modal-info-' + id).classList.remove('flex');
        }
        
        function bukaModalDelete(id) {
            document.getElementById('modal-delete-' + id).classList.remove('hidden');
            document.getElementById('modal-delete-' + id).classList.add('flex');
        }
        function tutupModalDelete(id) {
            document.getElementById('modal-delete-' + id).classList.add('hidden');
            document.getElementById('modal-delete-' + id).classList.remove('flex');
        }
    </script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

@endsection