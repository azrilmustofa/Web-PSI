<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\Kategori;


class barangcontroller extends Controller
{
    public function index()
    {
        $data = barang::all();
        $kategori = Kategori::all(); // Tambahkan baris ini
    
    // Kirim $kategori ke view agar modal tidak error
    return view('barang.index', [
        'dataBarang' => $data,
        'kategori' => $kategori
    ]);
    }

    public function detail($id)
    {
        $data = barang::findOrFail($id); 
        
        // PENTING: Nama view ini harus sama dengan nama file blade yang Anda buat 
        // di resources/views/customer/detail_produk.blade.php
        return view('customer.detail_produk', compact('data')); 
    }

    public function create()
{
    $kategori = Kategori::all(); // Ambil semua kategori dari database
    return view('barang.create', compact('kategori'));
}

    public function store(Request $request)
    {
        // 1. Validasi harus diperketat, tambahkan kategori_id
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id', // Pastikan ID kategori ada di tabel kategoris
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'gambar'      => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = new barang();
        $data->nama_barang = $request->nama_barang;
        $data->kategori_id = $request->kategori_id; // Masukkan kategori_id
        $data->harga       = $request->harga;
        $data->bahan       = $request->bahan;
        $data->ukuran      = $request->ukuran;
        $data->stok        = $request->stok;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            
            // Gunakan time() agar nama file unik (mencegah file tertimpa jika namanya sama)
            $namaFile = time() . '_' . $file->getClientOriginalName(); 
            
            $file->storeAs('barang', $namaFile, 'public');
            $data->gambar = 'barang/' . $namaFile;
        }

        $data->save();

        // Gunakan route() lebih disarankan daripada hardcode URL '/admin'
        return redirect('/admin')->with('Berhasil', 'Data Berhasil Ditambahkan');
    }

    public function update(Request $request, $id)
{
    // 1. Validasi data yang masuk
    $request->validate([
        'nama_barang' => 'required',
        'kategori_id' => 'required|exists:kategoris,id',
        'harga'       => 'required|numeric',
        'stok'        => 'required|integer',
        'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $barang = barang::findOrFail($id);
    
    // 2. Update data teks
    $barang->nama_barang = $request->nama_barang;
    $barang->kategori_id = $request->kategori_id; // WAJIB: Tambahkan ini agar kategori terupdate
    $barang->harga       = $request->harga;
    $barang->bahan       = $request->bahan;
    $barang->ukuran      = $request->ukuran;
    $barang->stok        = $request->stok;

    // 3. Logika Update Gambar
    if ($request->hasFile('gambar')) {
        // Hapus gambar lama dari storage jika ada (agar tidak memenuhi server)
        if ($barang->gambar && file_exists(storage_path('app/public/' . $barang->gambar))) {
            unlink(storage_path('app/public/' . $barang->gambar));
        }

        $file = $request->file('gambar');
        // Gunakan format nama file yang konsisten dengan store
        $namaFile = time() . '_' . $file->getClientOriginalName(); 
        $file->storeAs('barang', $namaFile, 'public');
        
        $barang->gambar = 'barang/' . $namaFile;
    }

    // 4. Simpan perubahan
    $barang->save(); // save() lebih disarankan untuk instance model yang sudah ada

    return redirect('/admin')->with('Berhasil', 'Data berhasil diupdate');
}

        public function destroy($id)
    {
        $barang = barang::findOrFail($id);

        // hapus gambar kalau ada
        if ($barang->gambar && file_exists(storage_path('app/public/' . $barang->gambar))) {
            unlink(storage_path('app/public/' . $barang->gambar));
        }

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus');
    }

    public function pdfbarang() {
        $data = barang::all();
        return view ('barang.pdfbarang', ['dataBarang' => $data]);
    }

    public function shop(Request $request, $kategori = null)
    {
        $query = barang::query();

        // Filter kategori
        if ($kategori) {
            $query->whereHas('kategori', function($q) use ($kategori) {

                // ubah slug URL jadi format nama kategori
                $namaKategori = str_replace('-', ' ', $kategori);

                $q->whereRaw('LOWER(nama_kategori) = ?', [strtolower($namaKategori)]);
            });
        }

        // Search
        if ($request->search) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        // Sort
        match($request->sort) {
            'latest' => $query->latest(),
            'low'    => $query->orderBy('harga', 'asc'),
            'high'   => $query->orderBy('harga', 'desc'),
            default  => $query->orderBy('id', 'asc'),
        };

        $products = $query->paginate(20);

        return view('customer.shop', compact('products'));
    }

    public function home()
    {
        return view('customer.index');
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
        $query = barang::query();

        // kategori utama
        $kategoriUtama = [
            'meja',
            'kursi',
            'lemari',
            'tempat tidur',
            'sofa',
            'rak',
            'kitchen set'
        ];

        // Jika perabot lainnya
        if ($slug == 'perabot-lainnya') {

            $query->whereHas('kategori', function($q) use ($kategoriUtama) {
                $q->whereNotIn('nama_kategori', $kategoriUtama);
            });

        } else {

            // kategori normal
            $namaKategori = str_replace('-', ' ', $slug);

            $query->whereHas('kategori', function($q) use ($namaKategori) {
                $q->where('nama_kategori', ucwords($namaKategori));
            });
        }

        // Search
        if ($request->search) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        // Sort
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