@extends('layouts.admin')

@section('header_title', 'Manajemen Layanan Bantuan')
@section('header_subtitle', 'Kelola tiket bantuan, permohonan reset password email, dan kendala sistem lainnya.')

@section('content')
    @include('admin.bantuan.partials.statistics')

    @include('admin.bantuan.partials.table')
@endsection