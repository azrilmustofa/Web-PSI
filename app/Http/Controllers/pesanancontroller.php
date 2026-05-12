<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\pesanan;
use App\Models\detail_pesanan;

use Illuminate\Support\Facades\Auth;

class pesanancontroller extends Controller
{
    // ===============================
    // TAMBAH CEPAT (ICON CROSS)
    // ===============================
    public function quickAdd($id)
    {
        $jumlahPesan = 1;

        $barang = barang::findOrFail($id);

        if ($jumlahPesan > $barang->stok) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $pesanan = pesanan::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 0],
            [
                'tanggal' => now(),
                'jumlah_harga' => 0,
                'kode' => 'ORD-' . time()
            ]
        );

        // cek detail
        $detail = detail_pesanan::where('pesanan_id', $pesanan->id)
            ->where('barang_id', $barang->id)
            ->first();

        if ($detail) {
            $detail->jumlah += $jumlahPesan;
            $detail->jumlah_harga += $barang->harga * $jumlahPesan;
            $detail->save();
        } else {
            detail_pesanan::create([
                'pesanan_id' => $pesanan->id,
                'barang_id' => $barang->id,
                'jumlah' => $jumlahPesan,
                'jumlah_harga' => $barang->harga * $jumlahPesan
            ]);
        }

        // hitung ulang total
        $pesanan->jumlah_harga = detail_pesanan::where('pesanan_id', $pesanan->id)
            ->sum('jumlah_harga');
        $pesanan->save();

        return redirect()->route('customer.checkout')
            ->with('success', 'Barang berhasil masuk keranjang');
    }


    public function pesan(Request $request, $id)
    {
        $request->validate([
            'jumlah_pesan' => 'required|integer|min:1'
        ]);

        $barang = barang::findOrFail($id);

        if ($request->jumlah_pesan > $barang->stok) {
            return back()->with('error', 'Stok tidak cukup');
        }

        $pesanan = pesanan::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 0],
            [
                'tanggal' => now(),
                'jumlah_harga' => 0,
                'kode' => 'ORD-' . time()
            ]
        );

        $detail = detail_pesanan::where('pesanan_id', $pesanan->id)
            ->where('barang_id', $barang->id)
            ->first();

        if ($detail) {
            $detail->jumlah += $request->jumlah_pesan;
            $detail->jumlah_harga += $barang->harga * $request->jumlah_pesan;
            $detail->save();
        } else {
            detail_pesanan::create([
                'pesanan_id' => $pesanan->id,
                'barang_id' => $barang->id,
                'jumlah' => $request->jumlah_pesan,
                'jumlah_harga' => $barang->harga * $request->jumlah_pesan
            ]);
        }

        $pesanan->jumlah_harga = detail_pesanan::where('pesanan_id', $pesanan->id)
            ->sum('jumlah_harga');
        $pesanan->save();

        return redirect()->route('customer.checkout')
            ->with('success', 'Berhasil masuk keranjang');
    }

    public function tambah($id)
    {
        $item = detail_pesanan::findOrFail($id);

        $item->jumlah += 1;
        $item->jumlah_harga = $item->jumlah * $item->barang->harga;
        $item->save();

        // update total keranjang
        $keranjang = $item->pesanan;
        $keranjang->jumlah_harga = $keranjang->detail->sum('jumlah_harga');
        $keranjang->save();

        return back()->with('success', 'Jumlah ditambah');
    }

    public function kurang($id)
    {
        $item = detail_pesanan::findOrFail($id);

        if ($item->jumlah > 1) {
            $item->jumlah -= 1;
            $item->jumlah_harga = $item->jumlah * $item->barang->harga;
            $item->save();
        } else {
            // kalau jumlah 1, langsung hapus
            $item->delete();
        }

        // update total keranjang
        $keranjang = $item->pesanan;
        $keranjang->jumlah_harga = $keranjang->detail->sum('jumlah_harga');
        $keranjang->save();

        return back()->with('success', 'Jumlah dikurangi');
    }



   
    public function checkout()
    {
        $data = pesanan::with('detail.barang')
            ->where('user_id', Auth::id())
            ->where('status', 0)
            ->first();

        return view('customer.chart', compact('data'));
    }

    // ===============================
    // HAPUS ITEM KERANJANG
    // ===============================
    public function hapus($id)
    {
        $detail = detail_pesanan::findOrFail($id);
        $pesanan = pesanan::findOrFail($detail->pesanan_id);

        // kurangi total harga pesanan
        $pesanan->jumlah_harga -= $detail->jumlah_harga;

        if ($pesanan->jumlah_harga < 0) {
            $pesanan->jumlah_harga = 0;
        }

        $pesanan->save();

        // hapus item
        $detail->delete();

        return redirect()->route('customer.checkout')
            ->with('success', 'Barang berhasil dihapus dari keranjang');
    }


    public function bayar()
    {
        $pesanan = pesanan::with('detail.barang')
            ->where('user_id', Auth::id())
            ->where('status', 0)
            ->first();

        if (!$pesanan) {
            return redirect()->route('customer.checkout')
                ->with('error', 'Tidak ada pesanan untuk dibayar');
        }

        // 🔽 Kurangi stok barang
        foreach ($pesanan->detail as $item) {
            $barang = $item->barang;

            if ($barang->stok < $item->jumlah) {
                return redirect()->route('customer.checkout')
                    ->with('error', 'Stok barang tidak mencukupi');
            }

            $barang->stok -= $item->jumlah;
            $barang->save();
        }

        // 🔽 Update status pesanan → BERHASIL
        $pesanan->status = 1;
        $pesanan->save();

        return redirect()->route('customer.checkout')
            ->with('success', 'Pembayaran berhasil! Pesanan sedang diproses.');
    }

}
