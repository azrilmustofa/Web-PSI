<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\Kategori;
use App\Models\Bahan; // Pastikan ini ada
use Illuminate\Support\Facades\DB;

class barangcontroller extends Controller
{
    public function index()
    {
        // Gunakan Eager Loading agar tidak berat saat load data bahan & kategori
        $data = barang::with(['kategori', 'bahan'])->get();
        $kategori = Kategori::all();
        $bahan = Bahan::all(); 
    
        return view('barang.index', [
            'dataBarang' => $data,
            'kategori' => $kategori,
            'bahan' => $bahan
        ]);
    }

    public function detail($id)
    {
        $data = barang::with(['kategori', 'bahan'])->findOrFail($id); 
        return view('customer.detail_produk', compact('data')); 
    }

    public function create()
    {
        $kategori = Kategori::all();
        $bahan = Bahan::all(); 
        return view('barang.create', compact('kategori', 'bahan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'bahan_id'    => 'required|exists:bahans,id', // Validasi bahan_id
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = new barang();
        $data->nama_barang = $request->nama_barang;
        $data->kategori_id = $request->kategori_id;
        $data->bahan_id    = $request->bahan_id; // Simpan ID Bahan
        $data->harga       = $request->harga;
        $data->ukuran      = $request->ukuran;
        $data->stok        = $request->stok;
        $data->deskripsi   = $request->deskripsi;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName(); 
            $file->storeAs('barang', $namaFile, 'public');
            $data->gambar = 'barang/' . $namaFile;
        }

        $data->save();
        return redirect('/admin')->with('Berhasil', 'Data Berhasil Ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'bahan_id'    => 'required|exists:bahans,id',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $barang = barang::findOrFail($id);
        $barang->nama_barang = $request->nama_barang;
        $barang->kategori_id = $request->kategori_id;
        $barang->bahan_id    = $request->bahan_id; 
        $barang->harga       = $request->harga;
        $barang->ukuran      = $request->ukuran;
        $barang->stok        = $request->stok;
        $barang->deskripsi   = $request->deskripsi;

        if ($request->hasFile('gambar')) {
            if ($barang->gambar && file_exists(storage_path('app/public/' . $barang->gambar))) {
                unlink(storage_path('app/public/' . $barang->gambar));
            }

            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName(); 
            $file->storeAs('barang', $namaFile, 'public');
            $barang->gambar = 'barang/' . $namaFile;
        }

        $barang->save();
        return redirect('/admin')->with('Berhasil', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $barang = barang::findOrFail($id);
        if ($barang->gambar && file_exists(storage_path('app/public/' . $barang->gambar))) {
            unlink(storage_path('app/public/' . $barang->gambar));
        }
        $barang->delete();

        return redirect('/admin')->with('success', 'Barang berhasil dihapus');
    }

    public function shop(Request $request, $kategori = null)
    {
        $query = barang::with(['kategori', 'bahan']); // Tambahkan relasi agar tidak lambat

        if ($kategori) {
            $query->whereHas('kategori', function($q) use ($kategori) {
                $namaKategori = str_replace('-', ' ', $kategori);
                $q->whereRaw('LOWER(nama_kategori) = ?', [strtolower($namaKategori)]);
            });
        }

        if ($request->search) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        match($request->sort) {
            'latest' => $query->latest(),
            'low'    => $query->orderBy('harga', 'asc'),
            'high'   => $query->orderBy('harga', 'desc'),
            default  => $query->orderBy('id', 'asc'),
        };

        // PENTING: Nama variabel harus $products agar sesuai dengan View shop.blade.php kamu
        $products = $query->paginate(20);

        return view('customer.shop', compact('products'));
    }

    public function home()
    {
        $bestSeller = barang::with(['kategori', 'bahan'])
            ->select('barang.*', DB::raw('SUM(detail_pesanan.jumlah) as total_terjual'))
            ->join('detail_pesanan', 'barang.id', '=', 'detail_pesanan.barang_id')
            ->join('pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
            ->where('pesanan.status', 1)
            ->groupBy(
                'barang.id', 'barang.nama_barang', 'barang.kategori_id', 'barang.bahan_id', 
                'barang.harga', 'barang.ukuran', 'barang.stok', 'barang.gambar', 
                'barang.deskripsi', 'barang.created_at', 'barang.updated_at'
            )
            ->orderByDesc('total_terjual')
            ->take(3)
            ->get();

        return view('customer.index', compact('bestSeller'));
    }

    public function about()
    {
        return view('customer.about');
    }

    public function contact()
    {
        return view('customer.contact');
    }

    public function kategori(Request $request, $slug)
    {
        $query = barang::with(['kategori', 'bahan']);

        $kategoriUtama = ['meja', 'kursi', 'lemari', 'tempat tidur', 'sofa', 'rak', 'kitchen set'];

        if ($slug == 'perabot-lainnya') {
            $query->whereHas('kategori', function($q) use ($kategoriUtama) {
                $q->whereNotIn('nama_kategori', $kategoriUtama);
            });
        } else {
            $namaKategori = str_replace('-', ' ', $slug);
            $query->whereHas('kategori', function($q) use ($namaKategori) {
                $q->where('nama_kategori', ucwords($namaKategori));
            });
        }

        if ($request->search) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        match($request->sort) {
            'latest' => $query->latest(),
            'low'    => $query->orderBy('harga', 'asc'),
            'high'   => $query->orderBy('harga', 'desc'),
            default  => $query->orderBy('id', 'asc'),
        };

        $products = $query->paginate(20);

        return view('customer.shop_cat', compact('products', 'slug'));
    }
}