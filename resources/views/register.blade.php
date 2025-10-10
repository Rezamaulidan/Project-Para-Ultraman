@extends('layouts.main')

@section('container')
<style>
    /* Custom style untuk background gradient */
    body, html {
        height: 100%;
    }

    .gradient-background {
        background: linear-gradient(135deg, #007bff, #0056b3);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .register-card {
        max-width: 400px;
        width: 100%;
    }
</style>

<div class="gradient-background">
    <div class="card shadow-lg border-0 rounded-4 register-card">
        <div class="card-body p-4 p-md-5">
            <div class="text-center">

                <img src="register.svg"
                     class="img-fluid mb-4"
                     alt="Register Illustration"
                     style="max-height: 200px;">

                <h3 class="mb-4 fw-bold">Daftar</h3>

                <div class="d-grid gap-3">
                    <a href="#" class="btn btn-primary btn-lg rounded-pill">Kamar</a>
                    <a href="#" class="btn btn-primary btn-lg rounded-pill">Staf</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
