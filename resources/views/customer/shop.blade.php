@extends('layouts.master')
@section('title', 'Shop')

{{-- ===== SLIDER SECTION ===== --}}
@section('slider')
<div class="slider-wrapper">
    <div class="slider-container" id="sliderContainer">
        <div class="slide active" style="background-image: url('{{ asset('template_customer/images/slide1.jpg') }}')"></div>
        <div class="slide" style="background-image: url('{{ asset('template_customer/images/slide2.jpg') }}')"></div>
        <div class="slide" style="background-image: url('{{ asset('template_customer/images/slide3.jpg') }}')"></div>
    </div>
    <button class="slider-btn prev" id="prevBtn" aria-label="Previous slide">&#10094;</button>
    <button class="slider-btn next" id="nextBtn" aria-label="Next slide">&#10095;</button>
    <div class="slider-dots" id="sliderDots">
        <span class="dot active" data-index="0"></span>
        <span class="dot" data-index="1"></span>
        <span class="dot" data-index="2"></span>
    </div>
</div>
@endsection

@section('before_content')
<div class="section-divider">
    <h2>Categories</h2>
</div>
@endsection

{{-- ===== CONTENT SECTION ===== --}}
@section('content')
<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row g-4 justify-content-center">

            @php
            $categories = [
                ['nama' => 'Meja',             'gambar' => 'template_customer/images/asset_cat/img_meja.jpg',    'slug' => 'meja'],
                ['nama' => 'Kursi',            'gambar' => 'template_customer/images/asset_cat/img_kursi.jpg',   'slug' => 'kursi'],
                ['nama' => 'Lemari',           'gambar' => 'template_customer/images/asset_cat/img_lemari.jpg',  'slug' => 'lemari'],
                ['nama' => 'Tempat Tidur',     'gambar' => 'template_customer/images/asset_cat/img_tmpttdr.jpg', 'slug' => 'tempat-tidur'],
                ['nama' => 'Sofa',             'gambar' => 'template_customer/images/asset_cat/img_sofa.jpg',    'slug' => 'sofa'],
                ['nama' => 'Rak',              'gambar' => 'template_customer/images/asset_cat/img_rak.jpg',     'slug' => 'rak'],
                ['nama' => 'Kitchen Set',      'gambar' => 'template_customer/images/asset_cat/img_dapur.jpg',   'slug' => 'kitchen-set'],
                ['nama' => 'Furniture Custom', 'gambar' => 'template_customer/images/asset_cat/img_lemari.jpg',  'slug' => 'furniture-custom'],
            ];
            @endphp

            @foreach ($categories as $cat)
            <div class="col-6 col-md-4 col-lg-3">
                <a class="category-card" href="{{ route('customer.kategori', $cat['slug']) }}">
                    <div class="category-img-wrapper">
                        <img src="{{ asset($cat['gambar']) }}" class="category-img" alt="{{ $cat['nama'] }}">
                        <div class="category-label">{{ $cat['nama'] }}</div>
                    </div>
                </a>
            </div>
            @endforeach

        </div>
    </div>
</div>
@endsection

{{-- ===== ALL PRODUCTS SECTION ===== --}}
@section('after_content')

<div class="section-divider" id="all-products">
    <h2>All Products</h2>
</div>

<div class="untree_co-section product-section before-footer-section">
    <div class="container">

        {{-- Search & Sort Bar --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
            <div class="d-flex align-items-center gap-2">
                <h5 class="fw-bold text-brown mb-0">Find Your Favourite Products</h5>
            </div>
            <form method="GET" action="{{ route('customer.shop') }}" class="d-flex gap-2" id="searchForm">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control search-input" placeholder="Search products...">
                <button type="submit" class="btn btn-search">SEARCH</button>
            </form>
        </div>

        {{-- Info & Sort --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
            <p class="text-muted mb-0">
                Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} results
            </p>
            <form method="GET" action="{{ route('customer.shop') }}" id="sortForm">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <select name="sort" class="form-select sort-select" id="sortSelect">
                    <option value=""       {{ request('sort') == ''        ? 'selected' : '' }}>Default sorting</option>
                    <option value="latest" {{ request('sort') == 'latest'  ? 'selected' : '' }}>Sort by latest</option>
                    <option value="low"    {{ request('sort') == 'low'     ? 'selected' : '' }}>Sort by price: low to high</option>
                    <option value="high"   {{ request('sort') == 'high'    ? 'selected' : '' }}>Sort by price: high to low</option>
                </select>
            </form>
        </div>

        {{-- Product Grid --}}
        <div class="row g-4">
            @forelse ($products as $item)
            <div class="col-6 col-md-4 col-lg" style="flex: 0 0 20%; max-width: 20%;">
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
            <div class="col-12 text-center text-muted py-5">No products found.</div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-5">
            {{ $products->appends(request()->query())->links() }}
        </div>

    </div>
</div>
@endsection

{{-- ===== SCRIPTS ===== --}}
@push('scripts')
<script>
    (function () {

        // ===== SLIDER =====
        const slides = document.querySelectorAll('.slide');
        const dots   = document.querySelectorAll('.dot');
        let current  = 0;
        let timer;

        function goTo(index) {
            slides[current].classList.remove('active');
            dots[current].classList.remove('active');
            current = (index + slides.length) % slides.length;
            slides[current].classList.add('active');
            dots[current].classList.add('active');
        }

        function autoPlay() {
            timer = setInterval(() => goTo(current + 1), 4000);
        }

        function resetTimer() {
            clearInterval(timer);
            autoPlay();
        }

        document.getElementById('prevBtn').addEventListener('click', function () {
            goTo(current - 1);
            resetTimer();
        });

        document.getElementById('nextBtn').addEventListener('click', function () {
            goTo(current + 1);
            resetTimer();
        });

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                goTo(parseInt(this.dataset.index));
                resetTimer();
            });
        });

        autoPlay();

        // ===== SCROLL TO #all-products SETELAH SEARCH / SORT =====

        // Intercept search form submit
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function () {
                sessionStorage.setItem('scrollToProducts', '1');
            });
        }

        // Intercept sort select change → set flag lalu submit form
        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                sessionStorage.setItem('scrollToProducts', '1');
                document.getElementById('sortForm').submit();
            });
        }

        // Saat halaman load → cek flag, scroll jika ada
        window.addEventListener('load', function () {
            if (sessionStorage.getItem('scrollToProducts') === '1') {
                sessionStorage.removeItem('scrollToProducts');
                const el = document.getElementById('all-products');
                if (el) {
                    setTimeout(function () {
                        el.scrollIntoView({ behavior: 'smooth' });
                    }, 300);
                }
            }
        });

    })();
</script>
@endpush