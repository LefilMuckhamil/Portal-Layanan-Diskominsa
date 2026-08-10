@extends('layouts.admin')

@section('header_title', 'Manajemen Website')
@section('header_subtitle', 'Kelola permohonan, verifikasi berkas, dan pantau progres pembuatan website.')

@section('content')

    <!-- Memanggil bagian Statistik -->
    @include('admin.website.partials.statistics')

    <!-- Memanggil bagian Tabel dan Modal -->
    @include('admin.website.partials.table')

    

    
@endsection