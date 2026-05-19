@extends('layouts.master_admin')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold">Laporan Semua Pesanan</h3>
            <p class="text-muted">Daftar semua pesanan reguler dan custom order yang telah diproses pembayarannya.</p>
        </div>
    </div>

    @if ($data->count() > 0)
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="py-3">No</th>
                        <th class="py-3">Jenis Pesanan</th>
                        <th class="py-3">Kode Pesanan</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Pembeli</th>
                        <th class="py-3">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($item->jenis_pesanan == 'Reguler')
                                <span> Reguler</span>
                            @else
                                <span> Custom Order</span>
                            @endif
                        </td>
                        <td class="fw-semibold text-secondary">{{ $item->kode }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y, H:i') }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td class="fw-bold text-danger">
                            Rp {{ number_format($item->jumlah_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
        <div class="alert alert-info text-center py-4" style="border-radius: 12px;">
            <i class="mb-2 fs-3 d-block">📥</i>
            Belum ada data pesanan yang memenuhi kriteria laporan.
        </div>
    @endif

</div>
@endsection