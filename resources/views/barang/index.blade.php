@extends('layouts.master_admin')
@section('title', 'Produk')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: #1c1b1b;">Tabel Barang</h2>
        <button class="btn shadow-sm text-white" data-bs-toggle="modal" data-bs-target="#modalTambahBarang" style="background-color: #3d5a4a;">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="tabel-barang">
                    <!-- Header Tabel dengan Hijau Dwijaya Mebel -->
                    <thead style="background-color: #1c1b1b; color: white;">
                        <tr>
                            <th class="text-center" style="width:5%">No.</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th> <!-- Tambahan Kolom Kategori -->
                            <th>Harga</th>
                            <th>Bahan</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Gambar</th>
                            <th class="text-center" style="width:15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataBarang as $data)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $data->nama_barang }}</td>
                            <td>
                                <!-- Menampilkan Nama Kategori -->
                                <span class="badge bg-light text-dark border">
                                    {{ $data->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td class="fw-bold text-success">Rp {{ number_format($data->harga, 0, ',', '.') }}</td>
                            <td>{{ $data->bahan }}</td>
                            <td class="text-center">
                                @if($data->stok <= 5)
                                    <span class="text-danger fw-bold">{{ $data->stok }} (Hampir Habis)</span>
                                @else
                                    {{ $data->stok }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($data->gambar)
                                    <img src="{{ asset('storage/barang/'.$data->gambar) }}" class="rounded shadow-sm border" width="60" height="60" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Tombol Ubah Kuning/Emas -->
                                    <button class="btn btn-warning btn-sm fw-bold text-white shadow-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditBarang{{ $data->id }}">
                                        Ubah
                                    </button>
                                    <!-- Tombol Hapus Merah -->
                                    <button class="btn btn-danger btn-sm fw-bold shadow-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalHapusBarang{{ $data->id }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        @include('barang.update', ['data' => $data, 'kategori' => $kategori])
                        @include('barang.delete', ['data' => $data])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('barang.create')

@stop

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tabel-barang').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "search": "Cari Produk:",
                "lengthMenu": "_MENU_ data per halaman",
                "zeroRecords": "Barang tidak ditemukan",
                "info": "Halaman _PAGE_ dari _PAGES_",
                "paginate": {
                    "next": "Next",
                    "previous": "Prev"
                }
            }
        });
    });
</script>
@endpush