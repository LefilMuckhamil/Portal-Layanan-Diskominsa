@extends('layouts.admin')

@section('header_title', 'Manajemen Layanan Bantuan')
@section('header_subtitle', 'Kelola tiket bantuan, permohonan reset password email, dan kendala sistem lainnya.')

@section('content')
    <!-- Memanggil Partial Statistik -->
    @include('admin.bantuan.partials.statistics')

    <!-- Memanggil Partial Tabel -->
    @include('admin.bantuan.partials.table')
@endsection