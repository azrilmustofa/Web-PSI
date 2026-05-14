@extends('layouts.master')
@section('title','Keranjang')
@section('content')
<div class="container mt-4">

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if ($pesanan && $pesanan->detail && $pesanan->detail->count() > 0)
    {{-- AREA TABEL - FIX TEKS KEPENYET --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem; line-height: 1.6;">
                    <thead class="bg-light">
                        <tr style="border-bottom: 2px solid #eee;">
                            <th class="ps-4 py-3 text-uppercase fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Produk</th>
                            <th class="py-3 text-uppercase fw-bold text-muted text-center" style="font-size: 0.75rem;">Harga</th>
                            <th class="py-3 text-uppercase fw-bold text-muted text-center" style="font-size: 0.75rem; width: 120px;">Jumlah</th>
                            <th class="py-3 text-uppercase fw-bold text-muted text-center" style="font-size: 0.75rem;">Subtotal</th>
                            <th class="pe-4 py-3 text-uppercase fw-bold text-muted text-end" style="font-size: 0.75rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pesanan->detail as $item)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/barang/'.$item->barang->gambar) }}" 
                                         width="60" height="60" class="rounded shadow-sm" 
                                         style="object-fit: cover;">
                                    <div style="line-height: 1.4;">
                                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">{{ $item->barang->nama_barang }}</h6>
                                        <small class="text-muted" style="font-size: 0.75rem;">Dwijaya Mebel Original</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center text-secondary fw-medium">
                                Rp {{ number_format($item->barang->harga, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <form action="{{ route('checkout.kurang', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-qty-mini">-</button>
                                    </form>
                                    <span class="fw-bold" style="min-width: 20px;">{{ $item->jumlah }}</span>
                                    <form action="{{ route('checkout.tambah', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-qty-mini">+</button>
                                    </form>
                                </div>
                            </td>
                            <td class="text-center fw-bold" style="color: #c0392b; font-size: 0.95rem;">
                                Rp {{ number_format($item->jumlah_harga, 0, ',', '.') }}
                            </td>
                            <td class="pe-4 text-end">
                                <form action="{{ route('checkout.hapus', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus barang?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-dark btn-sm px-3 py-1" style="border-radius: 20px; font-size: 0.75rem; font-weight: 500;">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- FOOTER - FIX TOMBOL KOTAK --}}
    <div class="row align-items-center mb-5 px-2">
        <div class="col-md-6">
             <a href="{{ url('/shop') }}" class="btn btn-dark px-4 py-2" style="border-radius: 30px; font-size: 0.85rem; font-weight: 500;">
                &larr; Kembali Belanja
            </a>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-inline-block text-end me-4 align-middle" style="line-height: 1.2;">
                <small class="text-muted d-block mb-1" style="font-size: 0.75rem;">Total Pembayaran:</small>
                <strong class="h4 fw-bold mb-0" style="color: #c0392b;">Rp {{ number_format($pesanan->jumlah_harga, 0, ',', '.') }}</strong>
            </div>
            <button type="button" class="btn btn-success px-4 py-2 fw-bold shadow-sm"
                    style="border-radius: 30px; background-color: #3b5d50; border: none; font-size: 0.9rem; letter-spacing: 0.5px;"
                    data-bs-toggle="modal" data-bs-target="#checkoutModal">
                BAYAR SEKARANG
            </button>
        </div>
    </div>
@else
    <p class="text-center">Keranjang masih kosong.</p>
@endif

</div>


{{-- ================= MODAL ================= --}}
@if ($pesanan && $pesanan->detail && $pesanan->detail->count() > 0)
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">

            <div class="modal-header" style="background: #3b5d50; border: none;">
                <h5 class="modal-title text-white fw-bold" id="checkoutModalLabel">
                    <i class=""></i> Konfirmasi Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0">

                    {{-- Kiri: Ringkasan --}}
                    <div class="col-md-5 d-flex flex-column"
                         style="background: #f8f3ef; padding: 28px; border-right: 1px solid #e0d6cc;">
                        <h6 class="fw-bold mb-3" style="color: #3b5d50;">
                            <i class=""></i> Ringkasan Pesanan
                        </h6>
                        <div class="flex-grow-1">
                            @foreach ($pesanan->detail as $item)
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3"
                                 style="border-bottom: 1px dashed #d8cfc8;">
                                <img src="{{ asset('storage/'.$item->barang->gambar) }}"
                                     width="60" height="60" class="rounded"
                                     style="object-fit: cover; flex-shrink: 0;">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold" style="font-size: 0.88rem; color: #3b2a1a;">
                                        {{ $item->barang->nama_barang }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.82rem;">
                                        {{ $item->jumlah }}x &nbsp;·&nbsp;
                                        Rp {{ number_format($item->barang->harga, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="fw-bold" style="font-size: 0.88rem; color: #c0392b; flex-shrink: 0;">
                                    Rp {{ number_format($item->jumlah_harga, 0, ',', '.') }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-3"
                             style="border-top: 2px solid #c8bfb5;">
                            <span class="fw-bold" style="color: #3b2a1a;">Total Pembayaran</span>
                            <span class="fw-bold fs-5" style="color: #c0392b;">
                                Rp {{ number_format($pesanan->jumlah_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Kanan: Form --}}
                    <div class="col-md-7 p-4">
                        <form id="form-checkout" action="{{ route('checkout.bayar') }}" method="POST">
                            @csrf

                            <h6 class="fw-bold mb-3" style="color: #3b5d50;">
                                <i class=""></i> Alamat Pengiriman
                            </h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Nama Penerima</label>
                                <input type="text" name="nama_penerima" class="form-control form-control-sm" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Nomor Telepon</label>
                                <input type="text" name="no_telepon" class="form-control form-control-sm" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Alamat Lengkap</label>
                                <textarea name="alamat" rows="2" class="form-control form-control-sm" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-7 mb-3">
                                    <label class="form-label fw-semibold" style="font-size:0.88rem;">Kota</label>
                                    <input type="text" name="kota" class="form-control form-control-sm" required>
                                </div>
                                <div class="col-5 mb-3">
                                    <label class="form-label fw-semibold" style="font-size:0.88rem;">Kode Pos</label>
                                    <input type="text" name="kode_pos" class="form-control form-control-sm" required>
                                </div>
                            </div>

                            <hr style="border-color: #e0d6cc;">

                            <h6 class="fw-bold mb-3" style="color: #3b5d50;">
                                <i class="me-1"></i> Metode Pembayaran
                            </h6>

                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="metode_pembayaran"
                                           id="pay_transfer" value="Transfer Bank" required>
                                    <label class="btn btn-outline-secondary w-100 py-3 metode-label" for="pay_transfer">
                                        <i class="mb-1"></i>
                                        Transfer Bank
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="metode_pembayaran"
                                           id="pay_cod" value="COD">
                                    <label class="btn btn-outline-secondary w-100 py-3 metode-label" for="pay_cod">
                                        <i class="mb-1"></i>
                                        COD
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="metode_pembayaran"
                                           id="pay_ewallet" value="E-Wallet">
                                    <label class="btn btn-outline-secondary w-100 py-3 metode-label" for="pay_ewallet">
                                        <i class="mb-1"></i>
                                        E-Wallet
                                    </label>
                                </div>
                            </div>

                            <div id="info-transfer" class="alert alert-info d-none py-2" style="font-size:0.85rem;">
                                <strong>Rekening Transfer:</strong><br>
                                BCA – 1234567890 – a.n Dwijaya Mebel<br>
                                Mandiri – 0987654321 – a.n Dwijaya Mebel
                            </div>

                            <div class="mb-2">
                                <label class="form-label fw-semibold" style="font-size:0.88rem;">Catatan (opsional)</label>
                                <textarea name="catatan" rows="2" class="form-control form-control-sm"></textarea>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="modal-footer" style="background: #f8f3ef; border-top: 1px solid #e0d6cc;">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <small class="text-muted">Total Pembayaran</small><br>
                        <strong class="fs-5" style="color: #c0392b;">
                            Rp {{ number_format($pesanan->jumlah_harga, 0, ',', '.') }}
                        </strong>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" form="form-checkout" class="btn btn-success btn-sm px-4">
                            <i class=""></i> Konfirmasi Pembayaran
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
.metode-label {
    font-size: 0.80rem;
    font-weight: 600;
    transition: 0.2s;
}
.btn-check:checked + .metode-label {
    background: #3b5d50;
    border-color: #3b5d50;
    color: #fff;
}
.metode-label:hover {
    border-color: #3b5d50;
    color: #3b5d50;
}
    /* Spasi teks supaya tidak kepenyet */
    .table td, .table th {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }

    .btn-qty-mini {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        border: 1px solid #ddd;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: 0.2s;
        cursor: pointer;
    }

    .btn-qty-mini:hover {
        background: #3b5d50;
        color: white;
        border-color: #3b5d50;
    }
    
    /* Efek hover tombol utama */
    .btn-success:hover {
        background-color: #2e493f !important;
        transform: translateY(-1px);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[name="metode_pembayaran"]').forEach(function(item) {
        item.addEventListener('change', function() {
            const infoTransfer = document.getElementById('info-transfer');
            if (this.value === 'Transfer Bank') {
                infoTransfer.classList.remove('d-none');
            } else {
                infoTransfer.classList.add('d-none');
            }
        });
    });
});
</script>
@endpush