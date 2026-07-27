@extends('layouts.admin')

@section('header_title', 'Manajemen Layanan TTE')
@section('header_subtitle', 'Kelola permohonan dan penerbitan Tanda Tangan Elektronik (TTE) pejabat instansi.')

@section('content')
    <!-- Memanggil Partial Statistik -->
    @include('admin.tte.partials.statistics')

    <!-- Memanggil Partial Tabel -->
    @include('admin.tte.partials.table')
@endsection