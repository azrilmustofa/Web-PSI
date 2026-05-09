@extends('layouts.master')
@section('title', $slug)

@section('content')
<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row">

            {{-- ===== KIRI: PRODUK ===== --}}
            <div class="col-lg-9">

                {{-- Judul Kategori --}}
                <h2 class="kategori-title">{{ ucwords(str_replace('-', ' ', $slug)) }}</h2>
                <p class="text-muted mb-3">{{ ucwords(str_replace('-', ' ', $slug)) }} – Toko Furniture Kami</p>

                {{-- Info & Sort --}}
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <p class="text-muted mb-0">
                        Showing all {{ $products->total() }} results
                    </p>
                    <form method="GET" action="{{ route('customer.kategori', $slug) }}">
                        <select name="sort" class="form-select sort-select" onchange="this.form.submit()">
                            <option value=""       {{ request('sort') == ''       ? 'selected' : '' }}>Default sorting</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Sort by latest</option>
                            <option value="low"    {{ request('sort') == 'low'    ? 'selected' : '' }}>Sort by price: low to high</option>
                            <option value="high"   {{ request('sort') == 'high'   ? 'selected' : '' }}>Sort by price: high to low</option>
                        </select>
                    </form>
                </div>

                {{-- Grid Produk --}}
                <div class="row g-4">
                    @forelse ($products as $item)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card">
                            <a href="{{ route('customer.shop', $item->id) }}">
                                <div class="product-img-wrapper">
                                    <img src="{{ asset('storage/' . $item->gambar) }}"
                                         class="product-img" alt="{{ $item->nama_barang }}">
                                </div>
                                <div class="product-info text-center mt-2">
                                    <h6 class="product-name">{{ strtoupper($item->nama_barang) }}</h6>
                                    <p class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                </div>
                            </a>
                            <form action="{{ route('quick.add', $item->id) }}" method="POST" class="text-center">
                                @csrf
                                <button type="submit" class="btn btn-add-cart w-100">ADD TO CART</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted py-5">Tidak ada produk di kategori ini.</div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->appends(request()->query())->links() }}
                </div>

            </div>

            {{-- ===== KANAN: SIDEBAR ===== --}}
            <div class="col-lg-3">

                {{-- Search --}}
                <div class="sidebar-box mb-4">
                    <h5 class="sidebar-title">Search Product</h5>
                    <form method="GET" action="{{ route('customer.kategori', $slug) }}" class="d-flex">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control" placeholder="Search Products...">
                        <button type="submit" class="btn btn-search ms-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.415l-3.868-3.833zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Daftar Kategori --}}
                <div class="sidebar-box">
                    <h5 class="sidebar-title">Product Categories</h5>
                    <ul class="sidebar-cat-list">
                        @php
                        $categories = [
                            ['nama' => 'Meja',             'slug' => 'meja'],
                            ['nama' => 'Kursi',            'slug' => 'kursi'],
                            ['nama' => 'Lemari',           'slug' => 'lemari'],
                            ['nama' => 'Tempat Tidur',     'slug' => 'tempat-tidur'],
                            ['nama' => 'Sofa',             'slug' => 'sofa'],
                            ['nama' => 'Rak',              'slug' => 'rak'],
                            ['nama' => 'Kitchen Set',      'slug' => 'kitchen-set'],
                            ['nama' => 'Perabot Lainnya',  'slug' => 'perabot-lainnya'],
                        ];
                        @endphp
                        @foreach ($categories as $cat)
                        <li class="{{ $slug == $cat['slug'] ? 'active' : '' }}">
                            <a href="{{ route('customer.kategori', $cat['slug']) }}">
                                {{ $cat['nama'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection