@extends('layouts.master')
@section('content')

<div class="container py-5">

    <div class="row">

        {{-- SIDEBAR --}}
        <div class="col-lg-4 mb-4">

            <div class="card card-profile shadow-sm border-0">

                <div class="card-body text-center p-4">

                    <div class="d-inline-flex justify-content-center align-items-center mb-3 shadow-sm"
                         style="
                            width: 85px;
                            height: 85px;
                            background-color: var(--secondary-color);
                            border-radius: 50%;
                            color: white;
                            font-size: 34px;
                            font-weight: bold;
                            border: 4px solid #fff;
                         ">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <h5 class="fw-bold mb-1">
                        {{ Auth::user()->name }}
                    </h5>

                    <p class="text-muted small mb-4">
                        {{ Auth::user()->email }}
                    </p>

                    <div class="nav flex-column nav-pills">

                        <button class="nav-link active mb-2 py-3 text-start px-4"
                                data-bs-toggle="pill"
                                data-bs-target="#pills-profile">

                            <i class="bi bi-person-circle me-2"></i>
                            Profil Saya

                        </button>

                        <button class="nav-link mb-2 py-3 text-start px-4"
                                data-bs-toggle="pill"
                                data-bs-target="#pills-history">

                            <i class="bi bi-clock-history me-2"></i>
                            Riwayat Pesanan

                        </button>

                        <button class="nav-link mb-2 py-3 text-start px-4"
                                data-bs-toggle="pill"
                                data-bs-target="#pills-custom">

                            <i class="bi bi-tools me-2"></i>
                            Custom Order

                        </button>

                        <button class="nav-link mb-2 py-3 text-start px-4"
                                data-bs-toggle="pill"
                                data-bs-target="#pills-password">

                            <i class="bi bi-shield-lock me-2"></i>
                            Keamanan Akun

                        </button>

                    </div>

                </div>

            </div>

        </div>

        {{-- CONTENT --}}
        <div class="col-lg-8">

            <div class="tab-content card shadow-sm border-0 p-4"
                 style="border-radius: 15px; min-height: 500px;">

                {{-- ALERT --}}
                @if(session('success'))

                <div class="alert alert-success border-0 shadow-sm">

                    {{ session('success') }}

                </div>

                @endif

                {{-- PROFILE --}}
                <div class="tab-pane fade show active"
                     id="pills-profile">

                    <h4 class="fw-bold mb-1">
                        Detail Akun
                    </h4>

                    <p class="text-muted small mb-4">
                        Kelola informasi data diri Anda.
                    </p>

                    <hr>

                    <div class="mb-3">

                        <label class="form-label fw-bold small">
                            Nama Lengkap
                        </label>

                        <input type="text"
                               class="form-control custom-input bg-light"
                               value="{{ Auth::user()->name }}"
                               readonly>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-bold small">
                            Email
                        </label>

                        <input type="email"
                               class="form-control custom-input bg-light"
                               value="{{ Auth::user()->email }}"
                               readonly>

                    </div>

                </div>

                {{-- RIWAYAT PESANAN --}}
                <div class="tab-pane fade"
                     id="pills-history">

                    <h4 class="fw-bold mb-1">
                        Riwayat Pesanan
                    </h4>

                    <p class="text-muted small mb-4">
                        Riwayat transaksi produk Anda.
                    </p>

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>

                                <tr>

                                    <th>No Pesanan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($riwayat_pesanan as $pesanan)

                                <tr>

                                    <td class="fw-bold">
                                        #DWJ-{{ $pesanan->id }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d M Y') }}
                                    </td>

                                    <td class="fw-bold text-success">

                                        Rp {{ number_format($pesanan->jumlah_harga,0,',','.') }}

                                    </td>

                                    <td>

                                        @if($pesanan->status == 1)

                                        <span class="badge badge-status"
                                              style="background-color:#f9bf29;color:black;">

                                            Pending

                                        </span>

                                        @elseif($pesanan->status == 2)

                                        <span class="badge badge-status"
                                              style="background-color:#3b5d50;color:white;">

                                            Diproses

                                        </span>

                                        @elseif($pesanan->status == 3)

                                        <span class="badge badge-status"
                                              style="background-color:#6c757d;color:white;">

                                            Dikirim

                                        </span>

                                        @elseif($pesanan->status == 4)

                                        <span class="badge badge-status"
                                              style="background-color:#198754;color:white;">

                                            Selesai

                                        </span>

                                        @endif

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td colspan="4"
                                        class="text-center text-muted py-5">

                                        Belum ada transaksi

                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- CUSTOM ORDER --}}
                <div class="tab-pane fade"
                     id="pills-custom">

                    <h4 class="fw-bold mb-1">
                        Riwayat Custom Order
                    </h4>

                    <p class="text-muted small mb-4">
                        Daftar pesanan custom furniture Anda.
                    </p>

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>

                                <tr>

                                    <th>Furniture</th>
                                    <th>Kayu</th>
                                    <th>Ukuran</th>
                                    <th>Harga</th>
                                    <th>Status</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($custom_orders as $custom)

                                <tr>

                                    <td>

                                        <div class="fw-bold">
                                            {{ $custom->jenis_furniture }}
                                        </div>

                                        @if($custom->gambar)

                                        <img src="{{ asset('storage/' . $custom->gambar) }}"
                                             width="80"
                                             class="rounded mt-2 border">

                                        @endif

                                    </td>

                                    <td>
                                        {{ $custom->jenis_kayu }}
                                    </td>

                                    <td>
                                        {{ $custom->ukuran }}
                                    </td>

                                    <td class="fw-bold text-success">

                                        @if($custom->estimasi_harga)

                                            Rp {{ number_format($custom->estimasi_harga,0,',','.') }}

                                        @else

                                            <span class="text-muted">
                                                Menunggu estimasi
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        @if($custom->status == 'pending')

                                        <span class="badge badge-status"
                                              style="background-color:#f9bf29;color:black;">

                                            Pending

                                        </span>

                                        @elseif($custom->status == 'diproses')

                                        <span class="badge badge-status"
                                              style="background-color:#3b5d50;color:white;">

                                            Diproses

                                        </span>

                                        @elseif($custom->status == 'selesai')

                                        <span class="badge badge-status"
                                              style="background-color:#198754;color:white;">

                                            Selesai

                                        </span>

                                        @endif

                                    </td>
                                    <td>

                                    @if($custom->estimasi_harga)

                                        <div class="d-flex flex-column gap-2">

                                            <div class="fw-bold text-success">
                                                Rp {{ number_format($custom->estimasi_harga,0,',','.') }}
                                            </div>

                                            {{-- BUTTON BAYAR --}}
                                            @if($custom->status != 'selesai')

                                            <a href="{{ route('payment.show', $custom->id) }}"
                                            class="btn btn-dwj btn-sm">

                                                <i class="bi bi-credit-card me-1"></i>
                                                Bayar Sekarang

                                            </a>

                                            @else

                                            <span class="badge bg-success">
                                                Sudah Dibayar
                                            </span>

                                            @endif

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            Menunggu estimasi
                                        </span>

                                    @endif

                                </td>

                                </tr>

                                @empty

                                <tr>

                                    <td colspan="5"
                                        class="text-center text-muted py-5">

                                        Belum ada custom order

                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- PASSWORD --}}
                <div class="tab-pane fade"
                     id="pills-password">

                    <h4 class="fw-bold mb-1">
                        Ganti Password
                    </h4>

                    <p class="text-muted small mb-4">
                        Pastikan password baru aman.
                    </p>

                    <form action="{{ route('password.update') }}"
                          method="POST">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">

                            <label class="form-label fw-bold small">
                                Password Lama
                            </label>

                            <input type="password"
                                   name="current_password"
                                   class="form-control custom-input"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-bold small">
                                Password Baru
                            </label>

                            <input type="password"
                                   name="new_password"
                                   class="form-control custom-input"
                                   required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-bold small">
                                Konfirmasi Password
                            </label>

                            <input type="password"
                                   name="new_password_confirmation"
                                   class="form-control custom-input"
                                   required>

                        </div>

                        <button type="submit"
                                class="btn btn-dwj w-100">

                            Update Password

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<style>

:root{
    --primary-color:#3b5d50;
    --secondary-color:#f9bf29;
    --light-bg:#f8f9fa;
}

.container{
    font-family:'Poppins',sans-serif;
}

.card-profile{
    border-radius:15px;
}

.nav-pills .nav-link{
    color:#495057;
    font-weight:500;
    border-radius:10px;
}

.nav-pills .nav-link.active{
    background-color:var(--primary-color)!important;
    color:white!important;
}

.nav-pills .nav-link:hover{
    background:#f1f1f1;
}

.custom-input{
    border-radius:10px!important;
    padding:12px 15px!important;
}

.badge-status{
    padding:6px 12px;
    border-radius:8px;
    font-size:12px;
}

.btn-dwj{
    background-color:var(--primary-color);
    color:white;
    border:none;
    border-radius:10px;
    padding:10px 20px;
    font-weight:600;
}

.btn-dwj:hover{
    background-color:#2d463d;
    color:white;
}

.table thead th{
    background-color:var(--light-bg);
    color:var(--primary-color);
}

</style>

@endsection