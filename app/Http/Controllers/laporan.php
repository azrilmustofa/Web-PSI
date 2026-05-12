<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pesanan;
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
        $data = pesanan::with('user')
            ->where('status', 1)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('barang.laporan', compact('data'));
    }

}

