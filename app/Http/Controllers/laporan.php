<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pesanan;
use App\Models\CustomOrder; 

class laporan extends Controller
{
    public function produk()
    {
        return view('produk.index');
    }

    public function kategori()
    {
        return view('kategori.index');
    }

    public function index()
    {
        $pesanan_biasa = pesanan::with('user')
            ->where('status', '>=', 1)
            ->get()
            ->map(function ($item) {
                $item->jenis_pesanan = 'Reguler';
                return $item;
            });

        $custom_order = CustomOrder::with('user')
            ->whereIn('status', [2, 3, 4])
            ->where('estimasi_harga', '>', 0)
            ->get()
            ->map(function ($item) {
                $item->jenis_pesanan = 'Custom Order';
                return $item;
            });

        $data = $pesanan_biasa->concat($custom_order)->sortByDesc('tanggal');

        return view('barang.laporan', compact('data'));
    }
}