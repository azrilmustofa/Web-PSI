@extends('layouts.master_admin') @section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg border-0" style="width: 100%; max-width: 500px; border-radius: 15px;">
        <div class="card-body p-5 text-center">
            
            <div class="d-inline-flex justify-content-center align-items-center shadow-sm mb-4" 
                 style="width: 100px; height: 100px; background-color: #f9bf29; border-radius: 50%; border: 4px solid #fff;">
                <span class="fw-bold text-white" style="font-size: 40px;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
            </div>

            <h3 class="fw-bold mb-1" style="color: #2f2f2f;">Profil Pengguna</h3>
            <p class="text-muted mb-4">Informasi akun kasir Dwijaya Mebel</p>
            
            <hr class="mb-4" style="opacity: 0.1;">

            <div class="text-start bg-light p-4" style="border-radius: 10px;">
                <div class="mb-3">
                    <label class="small fw-bold text-uppercase tracking-wider text-muted mb-1" style="font-size: 11px;">Nama Lengkap</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person me-2 text-primary"></i>
                        <span class="fs-5 fw-semibold" style="color: #3b5d50;">{{ Auth::user()->name }}</span>
                    </div>
                </div>

                <div class="mb-0">
                    <label class="small fw-bold text-uppercase tracking-wider text-muted mb-1" style="font-size: 11px;">Alamat Email</label>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope me-2 text-primary"></i>
                        <span class="fs-6">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-grid">
                <a href="{{ route('barang.index') }}" class="btn btn-primary py-2 fw-bold" style="background-color: #3b5d50; border: none; border-radius: 8px;">
                    Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>
</div>
@endsection