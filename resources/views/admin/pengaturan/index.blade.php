@extends('layouts.admin')

@section('header_title', 'Pengaturan Sistem')
@section('header_subtitle', 'Kelola konfigurasi dan fitur global Portal Layanan Diskominsa.')

@section('content')
    
    {{-- Panggil file pengaturan yang ada di dalam folder partials --}}
    @include('admin.pengaturan.partials.pengaturan')

@endsection