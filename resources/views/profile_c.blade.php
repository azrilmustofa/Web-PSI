@extends('layouts.master')
@section('content')

<div class="container py-5">
    <div class="row">
        {{-- Sidebar Profil --}}
        <div class="col-lg-4 mb-4">
            <div class="card card-profile shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="d-inline-flex justify-content-center align-items-center mb-3 shadow-sm" 
                         style="width: 85px; height: 85px; background-color: var(--secondary-color); border-radius: 50%; color: white; font-size: 34px; font-weight: bold; border: 4px solid #fff;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted small mb-4">{{ Auth::user()->email }}</p>
                    
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                        <button class="nav-link active mb-2 py-3 text-start px-4" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab">
                            <i class="bi bi-person-circle me-3"></i>Profil Saya
                        </button>
                        <button class="nav-link mb-2 py-3 text-start px-4" id="pills-history-tab" data-bs-toggle="pill" data-bs-target="#pills-history" type="button" role="tab">
                            <i class="bi bi-clock-history me-3"></i>Riwayat Pesanan
                        </button>
                        <button class="nav-link mb-2 py-3 text-start px-4" id="pills-password-tab" data-bs-toggle="pill" data-bs-target="#pills-password" type="button" role="tab">
                            <i class="bi bi-shield-lock me-3"></i>Keamanan Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Konten Tab --}}
        <div class="col-lg-8">
            <div class="tab-content card shadow-sm border-0 p-4" id="v-pills-tabContent" style="border-radius: 15px; min-height: 450px;">
                
                {{-- Alert Sukses --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 10px; background-color: #d1e7dd; color: #0f5132;">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Tab Profil --}}
                <div class="tab-pane fade show active" id="pills-profile" role="tabpanel">
                    <h4 class="fw-bold mb-1 text-dark">Detail Akun</h4>
                    <p class="text-muted small mb-4">Kelola informasi data diri Anda.</p>
                    <hr class="mb-4 opacity-50">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-uppercase text-muted">Nama Lengkap</label>
                            <input type="text" class="form-control custom-input bg-light" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-uppercase text-muted">Alamat Email</label>
                            <input type="email" class="form-control custom-input bg-light" value="{{ Auth::user()->email }}" readonly>
                        </div>
                    </div>
                </div>

                {{-- Tab Riwayat --}}
                <div class="tab-pane fade" id="pills-history" role="tabpanel">
                    <h4 class="fw-bold mb-1">Daftar Transaksi</h4>
                    <p class="text-muted small mb-4">Riwayat pesanan yang pernah Anda lakukan.</p>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th class="border-0">No. Pesanan</th>
                                    <th class="border-0">Tanggal</th>
                                    <th class="border-0">Total Tagihan</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat_pesanan as $pesanan)
                                <tr>
                                    <td class="fw-bold text-dark">#DWJ-{{ $pesanan->id }}</td>
                                    <td class="text-muted small">{{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d M Y') }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($pesanan->jumlah_harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if($pesanan->status == 1)
                                            <span class="badge badge-status bg-warning text-dark">Pending</span>
                                        @elseif($pesanan->status == 2)
                                            <span class="badge badge-status bg-info text-white">Diproses</span>
                                        @elseif($pesanan->status == 3)
                                            <span class="badge badge-status bg-primary">Dikirim</span>
                                        @elseif($pesanan->status == 4)
                                            <span class="badge badge-status bg-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-dark px-3 btn-detail" 
                                                data-id="{{ $pesanan->id }}" style="border-radius: 8px;">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" width="80" class="mb-3 opacity-50">
                                        <p class="text-muted mb-0">Belum ada transaksi apapun.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab Password --}}
                <div class="tab-pane fade" id="pills-password" role="tabpanel">
                    <h4 class="fw-bold mb-1">Ganti Password</h4>
                    <p class="text-muted small mb-4">Pastikan password baru Anda kuat dan sulit ditebak.</p>
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password Lama</label>
                            <input type="password" name="current_password" class="form-control custom-input @error('current_password') is-invalid @enderror" placeholder="••••••••" required>
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password Baru</label>
                            <input type="password" name="new_password" class="form-control custom-input @error('new_password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                            @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control custom-input" placeholder="Ulangi password" required>
                        </div>
                        <button type="submit" class="btn btn-dwj w-100">Update Keamanan Akun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail Pesanan --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header border-0 bg-light px-4 py-3">
                <h5 class="modal-title fw-bold" style="color: var(--primary-color);">
                    <i class="bi bi-receipt me-2"></i>Rincian Transaksi <span id="display-id" class="text-muted"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="loading-spinner" class="text-center py-5">
                    <div class="spinner-grow text-warning" role="status"></div>
                    <p class="text-muted small mt-2">Menyusun detail produk...</p>
                </div>
                <div id="detail-content" class="d-none">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-muted">
                                <th>Produk</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="table-detail-body">
                            {{-- Data via AJAX --}}
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light" style="border-radius: 10px;">
                        <span class="fw-bold text-dark text-uppercase">Total Pembayaran</span>
                        <span class="fs-5 fw-bold text-success" id="total-bayar"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    /* Global Forest Green Theme */
    :root {
        --primary-color: #3b5d50;
        --secondary-color: #f9bf29;
        --light-bg: #f8f9fa;
    }

    .container { font-family: 'Poppins', sans-serif; }

    /* Card & Nav Styling */
    .card-profile {
        border-radius: 15px;
        transition: transform 0.3s ease;
    }

    .nav-pills .nav-link {
        color: #495057;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .nav-pills .nav-link.active {
        background-color: var(--primary-color) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(59, 93, 80, 0.2);
    }

    .nav-pills .nav-link:hover:not(.active) {
        background-color: #e9ecef;
        color: var(--primary-color);
    }

    /* Input Styling */
    .custom-input {
        border-radius: 10px !important;
        padding: 12px 15px !important;
        border: 1px solid #ced4da !important;
    }

    .custom-input:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(59, 93, 80, 0.1) !important;
    }

    /* Badge Customization */
    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.75rem;
    }

    /* Button Styling */
    .btn-dwj {
        background-color: var(--primary-color);
        color: white;
        border-radius: 10px;
        padding: 10px 25px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-dwj:hover {
        background-color: #2d463d;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 20px;
        border: none;
    }

    .table thead th {
        background-color: var(--light-bg);
        color: var(--primary-color);
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
</style>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.btn-detail').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const modalElement = document.getElementById('modalDetail');
            const modal = new bootstrap.Modal(modalElement);
            
            document.getElementById('display-id').innerText = '#DWJ-' + id;
            document.getElementById('loading-spinner').classList.remove('d-none');
            document.getElementById('detail-content').classList.add('d-none');
            modal.show();

            fetch(`/profile/detail/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    let rows = '';
                    data.details.forEach(item => {
                        rows += `
                            <tr>
                                <td class="py-3">
                                    <div class="fw-bold text-dark">${item.nama_barang}</div>
                                    <small class="text-muted">Rp ${(Math.floor(item.jumlah_harga / item.jumlah)).toLocaleString('id-ID')}</small>
                                </td>
                                <td class="text-center py-3">${item.jumlah} pcs</td>
                                <td class="text-end py-3 fw-bold text-dark">Rp ${item.jumlah_harga.toLocaleString('id-ID')}</td>
                            </tr>
                        `;
                    });

                    document.getElementById('table-detail-body').innerHTML = rows;
                    document.getElementById('total-bayar').innerText = 'Rp ' + data.total;
                    
                    document.getElementById('loading-spinner').classList.add('d-none');
                    document.getElementById('detail-content').classList.remove('d-none');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data.');
                    modal.hide();
                });
        });
    });
</script>
@endpush