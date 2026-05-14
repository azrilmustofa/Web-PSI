@extends('layouts.master_admin') 

@section('content')
<style>
    /* Latar belakang dengan dekorasi agar tidak kosong */
    .profile-page-wrapper {
        min-height: 85vh;
        background-color: #f4f7f6;
        background-image: radial-gradient(#3b5d50 0.5px, transparent 0.5px);
        background-size: 20px 20px; /* Efek titik-titik halus */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }

    /* Elemen dekoratif tambahan */
    .bg-circle {
        position: absolute;
        background: rgba(59, 93, 80, 0.03);
        border-radius: 50%;
        z-index: 0;
    }

    .profile-card {
        background: #ffffff;
        border-radius: 24px;
        z-index: 1;
        width: 100%;
        max-width: 850px; /* Lebarkan sedikit untuk layout 2 kolom */
        display: flex;
        flex-wrap: wrap;
        overflow: hidden;
    }

    /* Bagian Kiri: Visual */
    .profile-side-visual {
        flex: 1;
        background: linear-gradient(135deg, #3b5d50 0%, #2d463d 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
        color: white;
        min-width: 300px;
    }

    .avatar-massive {
        width: 120px;
        height: 120px;
        background: #f9bf29;
        border: 6px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 50px;
        font-weight: 800;
        margin-bottom: 20px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    /* Bagian Kanan: Detail */
    .profile-side-content {
        flex: 1.5;
        padding: 50px;
        background: white;
        min-width: 350px;
    }

    .info-group {
        margin-bottom: 25px;
    }

    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #94a3b8;
        font-weight: 700;
        display: block;
        margin-bottom: 8px;
    }

    .info-value {
        display: flex;
        align-items: center;
        padding: 15px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        font-weight: 600;
    }

    .btn-action {
        background: #3b5d50;
        color: white;
        border: none;
        padding: 15px;
        border-radius: 12px;
        font-weight: 700;
        width: 100%;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        text-align: center;
    }

    .btn-action:hover {
        background: #f9bf29;
        color: #3b5d50;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(249, 191, 41, 0.2);
    }
</style>

<div class="profile-page-wrapper">
    <!-- Dekorasi Latar Belakang -->
    <div class="bg-circle" style="width: 400px; height: 400px; top: -100px; right: -100px;"></div>
    <div class="bg-circle" style="width: 300px; height: 300px; bottom: -50px; left: -50px;"></div>

    <div class="card profile-card shadow-lg border-0">
        <div class="row g-0 w-100">
            <!-- Sisi Kiri (Visual Identity) -->
            <div class="col-md-5 profile-side-visual">
                <div class="avatar-massive text-white">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <h3 class="fw-bold mb-1">{{ Auth::user()->name }}</h3>
                <p class="text-white-50 mb-4">Administrator System</p>
                
                <div class="w-100 border-top border-white-50 pt-4 opacity-75">
                    <div class="d-flex justify-content-between align-items-center small mb-2">
                        <span>Status Akun</span>
                        <span class="badge bg-success" style="padding: 5px 10px;">Aktif</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span>Role</span>
                        <span>Administrator</span>
                    </div>
                </div>
            </div>

            <!-- Sisi Kanan (Data Detail) -->
            <div class="col-md-7 profile-side-content">
                <h4 class="fw-bold mb-4" style="color: #3b5d50;">Detail Informasi</h4>
                
                <div class="info-group">
                    <label class="info-label">Nama Lengkap Admin</label>
                    <div class="info-value">
                        <i class="bi bi-person-circle me-3 text-muted"></i>
                        {{ Auth::user()->name }}
                    </div>
                </div>

                <div class="info-group">
                    <label class="info-label">Alamat Email Resmi</label>
                    <div class="info-value">
                        <i class="bi bi-envelope-fill me-3 text-muted"></i>
                        {{ Auth::user()->email }}
                    </div>
                </div>

                <div class="mt-5">
                    <a href="{{ route('barang.index') }}" class="btn-action">
                        <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Dashboard
                    </a>
                </div>

                <div class="text-center mt-4">
                    <small class="text-muted" style="font-size: 10px;">Dwijaya Mebel &bull; Security Verified</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection