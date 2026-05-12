@extends('layouts.master')
@section('title','Check Out')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ url('home') }}" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="col-md-12 mt-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Check Out
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3><i class="fa fa-shopping-cart"></i> Check Out</h3>
                    @if(!empty($pesanan))
                        <p class="text-end">
                            Tanggal Pesan : {{ $pesanan->tanggal }}
                        </p>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Total Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach($pesanan_details as $pesanan_detail)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        <img src="{{ asset('storage/'.$pesanan_detail->barang->gambar) }}"
                                             width="100" alt="{{ $pesanan_detail->barang->nama_barang }}">
                                    </td>
                                    <td>{{ $pesanan_detail->barang->nama_barang }}</td>
                                    <td>{{ $pesanan_detail->jumlah }} kain</td>
                                    <td class="text-end">
                                        Rp {{ number_format($pesanan_detail->barang->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        Rp {{ number_format($pesanan_detail->jumlah_harga, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <form action="{{ url('check-out/'.$pesanan_detail->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Anda yakin akan menghapus data ?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" class="text-end">
                                        <strong>Total Harga :</strong>
                                    </td>
                                    <td class="text-end">
                                        <strong>
                                            Rp {{ number_format($pesanan->jumlah_harga, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                    <td>
                                        {{-- ✅ Ganti link checkout langsung → buka modal --}}
                                        <button type="button" class="btn btn-success" onclick="showCheckoutModal()">
                                            <i class="fa fa-shopping-cart"></i> Bayar Sekarang
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL PEMBAYARAN ===== --}}
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">

            {{-- Header --}}
            <div class="modal-header" style="background: #3b5d50; border: none;">
                <h5 class="modal-title text-white fw-bold" id="checkoutModalLabel">
                    <i class="fa fa-shopping-cart me-2"></i> Konfirmasi Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">

                {{-- Ringkasan Barang --}}
                <h6 class="fw-bold mb-3" style="color: #3b5d50;">
                    <i class="fa fa-list me-1"></i> Ringkasan Pesanan
                </h6>
                <div class="table-responsive mb-3">
                    <table class="table table-borderless" style="font-size: 0.9rem;">
                        <thead style="background: #f0f7f4;">
                            <tr>
                                <th>Barang</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan_details as $pesanan_detail)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ asset('storage/'.$pesanan_detail->barang->gambar) }}"
                                             width="50" height="50"
                                             style="object-fit: cover; border-radius: 8px;"
                                             alt="{{ $pesanan_detail->barang->nama_barang }}">
                                        <span class="fw-semibold">{{ $pesanan_detail->barang->nama_barang }}</span>
                                    </div>
                                </td>
                                <td class="text-center align-middle">{{ $pesanan_detail->jumlah }}x</td>
                                <td class="text-end align-middle">
                                    Rp {{ number_format($pesanan_detail->jumlah_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="border-top: 2px solid #dee2e6;">
                                <td colspan="2" class="text-end fw-bold">Total Pembayaran :</td>
                                <td class="text-end fw-bold" style="color: #c0392b;">
                                    Rp {{ number_format($pesanan->jumlah_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <hr style="border-color: #e0d6cc;">

                {{-- Form Alamat & Pembayaran --}}
                <form id="form-checkout" action="{{ url('konfirmasi-check-out') }}" method="POST">
                    @csrf

                    {{-- Alamat Pengiriman --}}
                    <h6 class="fw-bold mb-3" style="color: #3b5d50;">
                        <i class="fa fa-map-marker me-1"></i> Alamat Pengiriman
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Penerima</label>
                        <input type="text" name="nama_penerima" class="form-control"
                               placeholder="Masukkan nama penerima" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="text" name="no_telepon" class="form-control"
                               placeholder="Contoh: 08123456789" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3"
                                  placeholder="Jalan, RT/RW, Kelurahan, Kecamatan..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kota</label>
                            <input type="text" name="kota" class="form-control"
                                   placeholder="Nama kota" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kode Pos</label>
                            <input type="text" name="kode_pos" class="form-control"
                                   placeholder="Contoh: 12345" required>
                        </div>
                    </div>

                    <hr style="border-color: #e0d6cc;">

                    {{-- Metode Pembayaran --}}
                    <h6 class="fw-bold mb-3" style="color: #3b5d50;">
                        <i class="fa fa-credit-card me-1"></i> Metode Pembayaran
                    </h6>

                    <div class="row g-3 mb-3">

                        <div class="col-6 col-md-4">
                            <input type="radio" class="btn-check" name="metode_pembayaran"
                                   id="pay_transfer" value="Transfer Bank" required>
                            <label class="btn btn-outline-secondary w-100 py-3 metode-label" for="pay_transfer">
                                <i class="fa fa-university d-block fs-4 mb-1"></i>
                                Transfer Bank
                            </label>
                        </div>

                        <div class="col-6 col-md-4">
                            <input type="radio" class="btn-check" name="metode_pembayaran"
                                   id="pay_cod" value="COD">
                            <label class="btn btn-outline-secondary w-100 py-3 metode-label" for="pay_cod">
                                <i class="fa fa-money d-block fs-4 mb-1"></i>
                                COD
                            </label>
                        </div>

                        <div class="col-6 col-md-4">
                            <input type="radio" class="btn-check" name="metode_pembayaran"
                                   id="pay_ewallet" value="E-Wallet">
                            <label class="btn btn-outline-secondary w-100 py-3 metode-label" for="pay_ewallet">
                                <i class="fa fa-mobile d-block fs-4 mb-1"></i>
                                E-Wallet
                            </label>
                        </div>

                    </div>

                    {{-- Info rekening muncul jika pilih Transfer Bank --}}
                    <div id="info-transfer" class="alert alert-info d-none" style="font-size: 0.88rem;">
                        <strong>Info Transfer Bank:</strong><br>
                        BCA — 1234567890 — a.n Dwijaya Mebel<br>
                        Mandiri — 0987654321 — a.n Dwijaya Mebel
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan (opsional)</label>
                        <textarea name="catatan" class="form-control" rows="2"
                                  placeholder="Catatan tambahan untuk penjual..."></textarea>
                    </div>

                </form>

            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="border-top: 1px solid #e0d6cc;">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <span class="text-muted" style="font-size: 0.88rem;">Total Pembayaran</span><br>
                        <strong style="color: #c0392b; font-size: 1.1rem;">
                            Rp {{ number_format($pesanan->jumlah_harga, 0, ',', '.') }}
                        </strong>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" form="form-checkout" class="btn btn-success px-4">
                            <i class="fa fa-check me-1"></i> Konfirmasi Pembayaran
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Tombol metode pembayaran */
    .metode-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #555;
        border-color: #ccc;
        transition: all 0.2s;
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
    function showCheckoutModal() {
        const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
        modal.show();
    }

    // Tampilkan info rekening jika pilih Transfer Bank
    document.querySelectorAll('input[name="metode_pembayaran"]').forEach(function(el) {
        el.addEventListener('change', function() {
            const info = document.getElementById('info-transfer');
            info.classList.toggle('d-none', this.value !== 'Transfer Bank');
        });
    });
</script>
@endpush