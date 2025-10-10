@extends('layouts.main')

@section('container')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-10 text-center">

            {{-- Logo Besar --}}
            <div class="mb-4">
                <img src="{{ asset('img/logo-simk.png') }}" alt="Logo SIMK" style="width: 180px; height: auto;">
            </div>

            {{-- Judul --}}
            <h1 class="display-4 fw-bold mb-3">
                Sistem Informasi Manajemen Kos
            </h1>

            {{-- Deskripsi --}}
            <p class="lead text-muted fw-bold">
                SIMK adalah aplikasi web yang dirancang untuk membantu pemilik kos, penyewa, dan staf dalam mengelola kegiatan operasional kos secara efisien. Sistem ini memudahkan pengelolaan data kamar, informasi penyewa, pembayaran, pencatatan administrasi, dan memudahkan staf dalam bekerja.
            </p>

        </div>
    </div>
</div>
@endsection

