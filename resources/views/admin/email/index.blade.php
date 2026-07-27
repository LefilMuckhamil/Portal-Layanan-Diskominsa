@extends('layouts.admin')

@section('header_title', 'Manajemen Email Resmi')
@section('header_subtitle', 'Kelola pembuatan akun email ASN (@acehbarat.go.id) dan Instansi.')

@section('content')
    <!-- Memanggil Partial Statistik -->
    @include('admin.email.partials.statistics')

    <!-- Memanggil Partial Tabel -->
    @include('admin.email.partials.table')
@endsection