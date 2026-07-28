@extends('layouts.admin')

@section('header_title', 'Manajemen Cloud Government')
@section('header_subtitle', 'Kelola pembuatan akun dan alokasi kapasitas penyimpanan (storage) untuk ASN dan Instansi.')

@section('content')
    <!-- Memanggil Partial Statistik -->
    @include('admin.cloud.partials.statistics')

    <!-- Memanggil Partial Tabel -->
    @include('admin.cloud.partials.table')
@endsection