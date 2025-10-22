@extends('profil_pemilik')
@section('title', 'Informasi Data Kamar - SIMK')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/style_kamar_pemilik.css') }}">
@endsection

@section('content')
<div id="main-content">

    @include('menu_kamar')
    @include('input_data_kamar')
    @include('edit_data_kamar')
    @include('info_data_kamar')

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/manajemen_kamar_pemilik.js') }}"></script>
@endsection