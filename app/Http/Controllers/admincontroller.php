<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class admincontroller extends Controller
{
    // =========================
    // DASHBOARD STATISTIK
    // =========================
    public function dashboard()
    {
        // 1. Ringkasan Pendapatan & Pesanan (Total Cards)
        $totalPendapatan = DB::table('pesanan')->where('payment_status', 'settlement')->orWhere('payment_status', 'success')->sum('jumlah_harga');
        $totalPesanan = DB::table('pesanan')->count();
        $totalProduk = DB::table('barang')->count();
        $totalPelanggan = DB::table('users')->where('role', 'customer')->count();

        // 2. Data Grafik Line: Tren Penjualan Bulanan (Tahun Ini)
        $monthlySales = DB::table('pesanan')
            ->select(DB::raw('MONTH(tanggal) as bulan'), DB::raw('SUM(jumlah_harga) as total'))
            ->whereYear('tanggal', date('Y'))
            ->whereIn('payment_status', ['settlement', 'success', 'paid'])
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->all();

        $salesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $salesData[] = $monthlySales[$i] ?? 0;
        }

        // 3. Data Grafik Pie: Distribusi Kategori Produk Terlaris
        $kategoriTerlaris = DB::table('detail_pesanan')
            ->join('barang', 'detail_pesanan.barang_id', '=', 'barang.id')
            ->join('kategoris', 'barang.kategori_id', '=', 'kategoris.id')
            ->select('kategoris.nama_kategori', DB::raw('SUM(detail_pesanan.jumlah) as total_terjual'))
            ->groupBy('kategoris.nama_kategori')
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();

        // 4. Data Grafik Bar: Performa Stok vs Penjualan Barang
        $performaBarang = DB::table('barang')
            ->leftJoin('detail_pesanan', 'barang.id', '=', 'detail_pesanan.barang_id')
            ->select('barang.nama_barang', 'barang.stok', DB::raw('IFNULL(SUM(detail_pesanan.jumlah), 0) as terjual'))
            ->groupBy('barang.id', 'barang.nama_barang', 'barang.stok')
            ->orderBy('terjual', 'desc')
            ->take(7)
            ->get();

        // 5. Data Grafik Candlestick/OHLC (Simulasi fluktuasi harga pesanan harian: Open, High, Low, Close)
        // Membaca pergerakan nilai transaksi harian dalam 7 hari terakhir
        $candlestickData = DB::table('pesanan')
            ->select(
                DB::raw('DATE(tanggal) as date'),
                DB::raw('MIN(jumlah_harga) as low'),
                DB::raw('MAX(jumlah_harga) as high'),
                DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(jumlah_harga ORDER BY tanggal ASC), ",", 1) as open'),
                DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(jumlah_harga ORDER BY tanggal DESC), ",", 1) as close')
            )
            ->where('tanggal', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->get();

        // 6. Pesanan Terbaru untuk Tabel Aktivitas
        $pesananTerbaru = DB::table('pesanan')
            ->join('users', 'pesanan.user_id', '=', 'users.id')
            ->select('pesanan.*', 'users.name as nama_user')
            ->orderBy('pesanan.created_at', 'desc')
            ->take(5)
            ->get();

        return view('barang.dashboard', compact(
            'totalPendapatan', 'totalPesanan', 'totalProduk', 'totalPelanggan',
            'salesData', 'kategoriTerlaris', 'performaBarang', 'candlestickData', 'pesananTerbaru'
        ));
    }

    // =========================
    // DATA PENGGUNA (Existing)
    // =========================
    public function index()
    {
        $users = User::latest()->get();
        return view('barang.datpen', compact('users'));
    }

    // ... Method store, update, destroy tetap sama ...
}