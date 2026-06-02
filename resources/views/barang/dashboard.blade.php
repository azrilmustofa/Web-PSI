@extends('layouts.master_admin')
@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Analisis & Statistik Penjualan</h1>
            <p class="text-muted mb-0">Overview performa bisnis toko dan custom order.</p>
        </div>
        <div class="text-muted">
            <i class="fas fa-calendar-alt-md"></i> Hari ini: <strong>{{ date('d M Y') }}</strong>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 border-0" style="border-left: 4px solid #4e73df !important; background: #fff;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" style="font-size: 0.8rem;">Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300" style="color: #dddfeb; font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 border-0" style="border-left: 4px solid #1cc88a !important; background: #fff;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1" style="font-size: 0.8rem;">Total Transaksi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPesanan }} Pesanan</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300" style="color: #dddfeb; font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 border-0" style="border-left: 4px solid #36b9cc !important; background: #fff;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1" style="font-size: 0.8rem;">Katalog Barang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProduk }} Item</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300" style="color: #dddfeb; font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 border-0" style="border-left: 4px solid #f6c23e !important; background: #fff;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1" style="font-size: 0.8rem;">Registered Customer</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPelanggan }} User</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300" style="color: #dddfeb; font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Tren Pendapatan Bulanan (Tahun Ini)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="position: relative; height:320px;">
                        <canvas id="lineSalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kategori Produk Terlaris</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4" style="position: relative; height:240px;">
                        <canvas id="pieKategoriChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small text-muted">
                        Analisis klasifikasi dominasi penjualan barang.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Perbandingan Stok vs Penjualan Produk</h6>
                </div>
                <div class="card-body">
                    <div style="position: relative; height:300px;">
                        <canvas id="barStokChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Analisis Fluktuasi Rentang Nominal Pembelian (7 Hari Terakhir)</h6>
                </div>
                <div class="card-body">
                    <div style="position: relative; height:300px;">
                        <canvas id="candleSimChart"></canvas>
                    </div>
                    <div class="mt-2 small text-muted text-center">
                        <span class="badge" style="background-color: rgba(255, 99, 132, 0.7)">&nbsp;</span> Batas Terendah (Low) &nbsp;|&nbsp; 
                        <span class="badge" style="background-color: rgba(54, 162, 235, 0.7)">&nbsp;</span> Batas Tertinggi (High) Transaksi
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Terbaru</h6>
                    <span class="badge bg-primary text-white p-2">Realtime Update</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Kode Order</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Total Harga</th>
                                    <th class="pe-3 text-center">Status Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pesananTerbaru as $p)
                                <tr>
                                    <td class="ps-3 font-weight-bold text-secondary">#{{ $p->kode }}</td>
                                    <td>{{ $p->nama_user }}</td>
                                    <td>{{ date('d-m-Y H:i', strtotime($p->tanggal)) }}</td>
                                    <td><span class="text-uppercase font-weight-bold small text-muted">{{ $p->metode_pembayaran ?? 'Midtrans' }}</span></td>
                                    <td>Rp {{ number_format($p->jumlah_harga, 0, ',', '.') }}</td>
                                    <td class="pe-3 text-center">
                                        @if($p->payment_status == 'settlement' || $p->payment_status == 'success' || $p->payment_status == 'paid')
                                            <span class="badge bg-success p-2 text-white rounded w-75">Sukses</span>
                                        @elseif($p->payment_status == 'pending')
                                            <span class="badge bg-warning p-2 text-dark rounded w-75">Pending</span>
                                        @else
                                            <span class="badge bg-danger p-2 text-white rounded w-75">{{ $p->payment_status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data transaksi masuk.</td>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ----------------------------------------------------
    // 1. CONFIG GRAPH LINE: TREN PENJUALAN BULANAN
    // ----------------------------------------------------
    const ctxLine = document.getElementById('lineSalesChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($salesData),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                tension: 0.3,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#4e73df',
                pointBorderColor: '#fff',
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); }
                    }
                }
            }
        }
    });

    // ----------------------------------------------------
    // 2. CONFIG GRAPH PIE: KATEGORI PRODUK TERLARIS
    // ----------------------------------------------------
    const ctxPie = document.getElementById('pieKategoriChart').getContext('2d');
    const kategoriLabels = @json($kategoriTerlaris->pluck('nama_kategori'));
    const kategoriData = @json($kategoriTerlaris->pluck('total_terjual'));

    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: kategoriLabels.length ? kategoriLabels : ['Belum Ada Data'],
            datasets: [{
                data: kategoriData.length ? kategoriData : [1],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12 } }
            },
            cutout: '70%'
        }
    });

    // ----------------------------------------------------
    // 3. CONFIG GRAPH BAR: STOK VS TERJUAL
    // ----------------------------------------------------
    const ctxBar = document.getElementById('barStokChart').getContext('2d');
    const barangLabels = @json($performaBarang->pluck('nama_barang'));
    const barangStok = @json($performaBarang->pluck('stok'));
    const barangTerjual = @json($performaBarang->pluck('terjual'));

    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: barangLabels,
            datasets: [
                {
                    label: 'Stok Tersedia',
                    data: barangStok,
                    backgroundColor: '#f6c23e',
                },
                {
                    label: 'Item Terjual',
                    data: barangTerjual,
                    backgroundColor: '#1cc88a',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // ----------------------------------------------------
    // 4. CONFIG GRAPH BAR SIMULATION (CANDLESTICK)
    // ----------------------------------------------------
    // Catatan: Native candlestick membutuhkan library eksternal moment & chartjs-financial. 
    // Untuk keandalan maksimal tanpa error modul, kita buat Floating Bar Chart handal 
    // yang merepresentasikan range fluktuasi harga (Min-Max/Low-High) transaksi harian.
    const ctxCandle = document.getElementById('candleSimChart').getContext('2d');
    const candleRaw = @json($candlestickData);
    
    const candleLabels = candleRaw.map(item => item.date);
    // Floating bar format: [Low, High]
    const floatingBars = candleRaw.map(item => [parseFloat(item.low), parseFloat(item.high)]);

    new Chart(ctxCandle, {
        type: 'bar',
        data: {
            labels: candleLabels.length ? candleLabels : ['No Data'],
            datasets: [{
                label: 'Rentang Nilai Transaksi Terendah - Tertinggi',
                data: floatingBars.length ? floatingBars : [[0,0]],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 4,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); }
                    }
                }
            }
        }
    });
</script>
@endsection