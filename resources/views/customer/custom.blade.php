@extends('layouts.master')

@section('content')

<div class="container py-5">

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success shadow-sm border-0 rounded-4">

            {{ session('success') }}

        </div>

    @endif

    <div class="row">

        {{-- FORM CUSTOM --}}
        <div class="col-lg-5 mb-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h3 class="fw-bold mb-2">
                        Custom Furniture
                    </h3>

                    <p class="text-muted mb-4">
                        Request furniture sesuai kebutuhan Anda
                    </p>

                    <form action="{{ route('custom-orders.store') }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf

                        {{-- JENIS FURNITURE --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Jenis Furniture
                            </label>

                            <input type="text"
                                   name="jenis_furniture"
                                   class="form-control rounded-3"
                                   placeholder="Contoh: Meja Belajar"
                                   required>

                        </div>

                        {{-- JENIS KAYU --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Jenis Kayu
                            </label>

                            <input type="text"
                                   name="jenis_kayu"
                                   class="form-control rounded-3"
                                   placeholder="Contoh: Jati"
                                   required>

                        </div>

                        {{-- UKURAN --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Ukuran
                            </label>

                            <input type="text"
                                   name="ukuran"
                                   class="form-control rounded-3"
                                   placeholder="Contoh: 120x60x75 cm"
                                   required>

                        </div>

                        {{-- CATATAN --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Catatan Tambahan
                            </label>

                            <textarea name="catatan"
                                      rows="4"
                                      class="form-control rounded-3"
                                      placeholder="Tambahkan detail custom furniture"></textarea>

                        </div>

                        {{-- GAMBAR --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Upload Referensi Gambar
                            </label>

                            <input type="file"
                                   name="gambar"
                                   class="form-control rounded-3">

                        </div>

                        {{-- BUTTON --}}
                        <button type="submit"
                                class="btn btn-success w-100 rounded-3 py-2 fw-semibold">

                            Kirim Custom Order

                        </button>

                    </form>

                </div>

            </div>

        </div>

        {{-- RIWAYAT CUSTOM --}}
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h3 class="fw-bold mb-2">
                        Riwayat Custom Order
                    </h3>

                    <p class="text-muted mb-4">
                        Pantau status dan harga custom furniture Anda
                    </p>

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>

                                <tr>

                                    <th>No</th>
                                    <th>Furniture</th>
                                    <th>Gambar</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($custom_orders as $item)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $item->jenis_furniture }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $item->jenis_kayu }}
                                        </small>

                                    </td>

                                    {{-- GAMBAR --}}
                                    <td>

                                        @if($item->gambar)

                                            <img src="{{ asset('storage/' . $item->gambar) }}"
                                                 width="80"
                                                 class="rounded shadow-sm">

                                        @else

                                            <span class="text-muted">
                                                Tidak ada
                                            </span>

                                        @endif

                                    </td>

                                    {{-- HARGA --}}
                                    <td>

                                        @if($item->estimasi_harga)

                                            <span class="fw-bold text-success">

                                                Rp {{ number_format($item->estimasi_harga,0,',','.') }}

                                            </span>

                                        @else

                                            <span class="text-muted">

                                                Menunggu Harga

                                            </span>

                                        @endif

                                    </td>

                                    {{-- STATUS --}}
                                    <td>

                                        @if($item->status == 'pending')

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                        @elseif($item->status == 'diproses')

                                            <span class="badge bg-info">
                                                Diproses
                                            </span>

                                        @elseif($item->status == 'selesai')

                                            <span class="badge bg-success">
                                                Selesai
                                            </span>

                                        @endif

                                    </td>

                                    {{-- MIDTRANS --}}
                                    <td>

                                        @if($item->estimasi_harga && $item->status != 'selesai')

                                            <a href="{{ route('custom.payment', $item->id) }}"
                                               class="btn btn-success btn-sm rounded-pill">

                                                Bayar

                                            </a>

                                        @else

                                            <button class="btn btn-secondary btn-sm rounded-pill"
                                                    disabled>

                                                Menunggu

                                            </button>

                                        @endif

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td colspan="6"
                                        class="text-center text-muted py-4">

                                        Belum ada custom order

                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection