<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\pesanan; // Menggunakan model pesanan (huruf kecil sesuai project kamu)
use App\Models\detail_pesanan;
use Illuminate\Support\Facades\Auth;

class pesanancontroller extends Controller
{
// 1. Menampilkan halaman pesanan reguler ke kasir
public function pesanan()
{
    // Mengambil pesanan yang bukan lagi di dalam keranjang (status 1, 2, 3, 4)
    $data_pesanan = pesanan::where('status', '>', 0)
        ->orderBy('updated_at', 'desc')
        ->get();
    
    // Mengarahkan ke file blade pesanan reguler
    return view('kasir.pesanan', compact('data_pesanan'));
}

// 2. Memproses update status pesanan dari select dropdown kasir
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|integer|in:1,2,3,4',
    ]);

    $pesanan = pesanan::findOrFail($id);
    $pesanan->status = $request->status;
    $pesanan->save();

    return redirect()->back()->with('success', 'Status pesanan #' . $pesanan->kode . ' berhasil diperbarui!');
}
    public function quickAdd(Request $request, $id)
    {
        $jumlahPesan = max(1, (int) $request->input('quantity', 1));
        $barang = barang::findOrFail($id);

        if ($jumlahPesan > $barang->stok) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $pesanan = pesanan::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 0],
            [
                'tanggal'      => now(),
                'jumlah_harga' => 0,
                'kode'         => 'ORD-' . time()
            ]
        );

        $detail = detail_pesanan::where('pesanan_id', $pesanan->id)
            ->where('barang_id', $barang->id)
            ->first();

        if ($detail) {
            $detail->jumlah       += $jumlahPesan;
            $detail->jumlah_harga += $barang->harga * $jumlahPesan;
            $detail->save();
        } else {
            detail_pesanan::create([
                'pesanan_id'   => $pesanan->id,
                'barang_id'    => $barang->id,
                'jumlah'       => $jumlahPesan,
                'jumlah_harga' => $barang->harga * $jumlahPesan
            ]);
        }

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
            $item->delete();
        }

        $keranjang = $item->pesanan;
        $keranjang->jumlah_harga = $keranjang->detail->sum('jumlah_harga');
        $keranjang->save();

        return back()->with('success', 'Jumlah dikurangi');
    }

    public function checkout()
    {
        $pesanan = pesanan::where('user_id', Auth::id())
            ->where('status', 0)
            ->first();

        $pesanan_details = $pesanan
            ? $pesanan->detail()->with('barang')->get()
            : collect();

        return view('customer.chart', compact('pesanan', 'pesanan_details'));
    }

    public function hapus($id)
    {
        $detail = detail_pesanan::findOrFail($id);
        $pesanan = pesanan::findOrFail($detail->pesanan_id);

        $pesanan->jumlah_harga -= $detail->jumlah_harga;

        if ($pesanan->jumlah_harga < 0) {
            $pesanan->jumlah_harga = 0;
        }

        $pesanan->save();
        $detail->delete();

        return redirect()->route('customer.checkout')
            ->with('success', 'Barang berhasil dihapus dari keranjang');
    }

    public function bayar(Request $request)
    {
        $request->validate([
            'nama_penerima'     => 'required|string',
            'no_telepon'        => 'required|string',
            'alamat'            => 'required|string',
            'kota'              => 'required|string',
            'kode_pos'          => 'required|string',
            'metode_pembayaran' => 'required|string',
        ]);

        $pesanan = pesanan::with('detail.barang')
            ->where('user_id', Auth::id())
            ->where('status', 0)
            ->first();

        if (!$pesanan) {
            return redirect()->route('customer.checkout')
                ->with('error', 'Tidak ada pesanan untuk dibayar');
        }

        foreach ($pesanan->detail as $item) {
            if ($item->barang->stok < $item->jumlah) {
                return redirect()->route('customer.checkout')
                    ->with('error', 'Stok ' . $item->barang->nama_barang . ' tidak mencukupi');
            }
            $item->barang->stok -= $item->jumlah;
            $item->barang->save();
        }

        $pesanan->update([
            'status'            => 1, // Berubah dari keranjang (0) ke Pending (1)
            'nama_penerima'     => $request->nama_penerima,
            'no_telepon'        => $request->no_telepon,
            'alamat'            => $request->alamat,
            'kota'              => $request->kota,
            'kode_pos'          => $request->kode_pos,
            'metode_pembayaran' => $request->metode_pembayaran,
            'catatan'           => $request->catatan,
        ]);

        return redirect()->route('customer.checkout')
            ->with('success', 'Pembayaran berhasil! Pesanan sedang diproses.');
    }
}