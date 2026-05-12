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
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pesanan->detail as $item)
            <tr>
                <td>{{ $item->barang->nama_barang }}</td>
                <td>Rp {{ number_format($item->barang->harga, 0, ',', '.') }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <form action="{{ route('checkout.kurang', $item->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-warning btn-sm">-</button>
                        </form>
                        <span>{{ $item->jumlah }}</span>
                        <form action="{{ route('checkout.tambah', $item->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-sm">+</button>
                        </form>
                    </div>
                </td>
                <td>Rp {{ number_format($item->jumlah_harga, 0, ',', '.') }}</td>
                <td>
                    <form action="{{ route('checkout.hapus', $item->id) }}" method="POST"
                          onsubmit="return confirm('Hapus barang dari keranjang?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h5 class="text-end">
        Total: <strong>Rp {{ number_format($pesanan->jumlah_harga, 0, ',', '.') }}</strong>
    </h5>

    {{-- GANTI: dari form submit → buka modal --}}
    <div class="text-end mt-3">
        <button type="button" class="btn btn-success"
                data-bs-toggle="modal" data-bs-target="#checkoutModal">
            Bayar Sekarang
        </button>
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