@extends('layouts.master')
@section('title','Dwijaya Mebel')
@section('content')

    <!-- Start Hero Section -->
    @section('hero')
        <div class="hero">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-5">
                        <div class="intro-excerpt">
                            <h1>Meubel Berkualitas<span class="d-block">Untuk Rumah Anda</span></h1>
                            <p class="mb-4">Kami menyediakan berbagai macam jenis meubel untuk kebutuhan rumah anda.</p>
                            <p><a href="{{ route('customer.shop')}}" class="btn btn-secondary me-2">Shop Now</a></p>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="hero-img-wrap">
                            <img src="template_customer/images/couch.png" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    <!-- End Hero Section -->

    <!-- Start Product Section -->
    <div class="product-section">
        <div class="container">
            <div class="row">

                <!-- Start Column 1 -->
                <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                    <h2 class="mb-4 section-title">Berbagai Pilihan</h2>
                    <p class="mb-4">Memiliki berbagai pilihan untuk anda, dengan kualitas yang terbaik.</p>
                </div>
                <!-- End Column 1 -->

                <!-- Start Column 2 — Best Seller dengan Popup -->
                @foreach($bestSeller as $item)
                <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">

                    {{-- ✅ Ganti <a href> dengan div + onclick popup --}}
                    <div class="product-item"
                         style="cursor: pointer;"
                         onclick="showProductModal(
                             '{{ addslashes($item->nama_barang) }}',
                             '{{ asset('storage/' . $item->gambar) }}',
                             '{{ number_format($item->harga, 0, ',', '.') }}',
                             '{{ addslashes($item->bahan) }}',
                             '{{ addslashes($item->ukuran) }}',
                             {{ $item->stok }},
                             '{{ addslashes($item->deskripsi ?? '-') }}',
                             {{ $item->id }}
                         )">

                        <div style="position: relative; overflow: hidden;">
                            <img src="{{ asset('storage/'.$item->gambar) }}"
                                 class="img-fluid product-thumbnail">

                        </div>

                        <h3 class="product-title">{{ $item->nama_barang }}</h3>
                        <strong class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</strong>
                        <p class="text-muted">Terjual {{ $item->total_terjual }}x</p>

                        <span class="icon-cross">
                            <img src="{{ asset('template_customer/images/cross.svg') }}" class="img-fluid">
                        </span>

                    </div>

                </div>
                @endforeach
                <!-- End Column 2 -->

            </div>
        </div>
    </div>
    <!-- End Product Section -->

    <!-- Start Why Choose Us Section -->
    <div class="why-choose-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <h2 class="section-title">Kenapa harus toko Meubel Dwijaya</h2>
                    <p>Memiliki berbagai benefit untuk pelanggan kami.</p>
                    <div class="row my-5">
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="template_customer/images/truck.svg" alt="Image" class="imf-fluid">
                                </div>
                                <h3>Fast &amp; Free Shipping</h3>
                                <p>Cepat dan gratis pengiriman untuk setiap pembelian.</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="template_customer/images/bag.svg" alt="Image" class="imf-fluid">
                                </div>
                                <h3>Easy to Shop</h3>
                                <p>kemudahan berbelanja hanya dengan beberapa klik saja.</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="template_customer/images/support.svg" alt="Image" class="imf-fluid">
                                </div>
                                <h3>24/7 Support</h3>
                                <p>Konsultasi 24 Jam untuk produk dan layanan kami.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="img-wrap">
                        <img src="template_customer/images/why-choose-us-img.jpg" alt="Image" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Why Choose Us Section -->

    <!-- Start We Help Section -->
    <div class="we-help-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-7 mb-5 mb-lg-0">
                    <div class="imgs-grid">
                        <div class="grid grid-1"><img src="template_customer/images/img-grid-1.jpg" alt="Untree.co"></div>
                        <div class="grid grid-2"><img src="template_customer/images/img-grid-2.jpg" alt="Untree.co"></div>
                        <div class="grid grid-3"><img src="template_customer/images/img-grid-3.jpg" alt="Untree.co"></div>
                    </div>
                </div>
                <div class="col-lg-5 ps-lg-5">
                    <h2 class="section-title mb-4">Kami membantu anda untuk mendesain rumah</h2>
                    <p>Dengan produk dari kami, anda dapat membuat interior rumah anda sesuai dengan apa yang anda harapkan.</p>
                    <p><a href="{{ route('customer.shop')}}" class="btn">Shop Now</a></p>
                </div>
            </div>
        </div>
    </div>
    <!-- End We Help Section -->

    <!-- Start Testimonial Slider -->
    <div class="testimonial-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <h2 class="section-title">Testimoni</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="testimonial-slider-wrap text-center">
                        <div id="testimonial-nav">
                            <span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
                            <span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
                        </div>
                        <div class="testimonial-slider">
                            <div class="item">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8 mx-auto">
                                        <div class="testimonial-block text-center">
                                            <blockquote class="mb-5">
                                                <p>&ldquo;Mebel dengan kualitas baik dan sangat memuaskan, pelayanan terbaik dan sangat fast respon&rdquo;</p>
                                            </blockquote>
                                            <div class="author-info">
                                                <div class="author-pic">
                                                    <img src="template_customer/images/person-1.png" alt="Jamal" class="img-fluid">
                                                </div>
                                                <h3 class="font-weight-bold">Jamal</h3>
                                                <span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8 mx-auto">
                                        <div class="testimonial-block text-center">
                                            <blockquote class="mb-5">
                                                <p>&ldquo;Produk sangat berkualitas dan pengiriman cepat. Sangat puas dengan pelayanannya!&rdquo;</p>
                                            </blockquote>
                                            <div class="author-info">
                                                <div class="author-pic">
                                                    <img src="template_customer/images/person-1.png" alt="Maria Jones" class="img-fluid">
                                                </div>
                                                <h3 class="font-weight-bold">Maria Jones</h3>
                                                <span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Testimonial Slider -->

    {{-- ===== MODAL POPUP DETAIL BARANG ===== --}}
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">

                <div class="modal-header" style="background: #3b5d50; border: none;">
                    <h5 class="modal-title text-white fw-bold" id="productModalLabel">Detail Produk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-0">
                    <div class="row g-0">

                        <div class="col-md-5 d-flex align-items-center justify-content-center"
                             style="background: #f8f3ef; padding: 24px;">
                            <img id="modal-gambar" src="" alt="Gambar Produk"
                                 style="max-width: 100%; max-height: 280px; object-fit: contain; border-radius: 10px;">
                        </div>

                        <div class="col-md-7 p-4 d-flex flex-column justify-content-between">
                            <div>
                                <h4 id="modal-nama" class="fw-bold mb-1" style="color: #3b2a1a;"></h4>
                                <p id="modal-harga" class="fw-bold fs-5 mb-3" style="color: #c0392b;"></p>
                                <hr style="border-color: #e0d6cc;">
                                <table class="table table-borderless mb-3" style="font-size: 0.93rem;">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted ps-0" style="width: 110px;">Bahan</td>
                                            <td class="fw-semibold" id="modal-bahan"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted ps-0">Ukuran</td>
                                            <td class="fw-semibold" id="modal-ukuran"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted ps-0">Stok</td>
                                            <td id="modal-stok"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mb-3">
                                    <p class="text-muted mb-1" style="font-size: 0.88rem;">Deskripsi</p>
                                    <p id="modal-deskripsi" style="font-size: 0.93rem; color: #3b2a1a; line-height: 1.6;"></p>
                                </div>
                            </div>

                            {{-- ✅ Form cart sama persis seperti card produk --}}
                            <form id="modal-cart-form" action="{{ route('quick.add', 0) }}" method="POST" class="text-center">
                                @csrf
                                <button type="submit" class="btn btn-add-cart w-100">ADD TO CART</button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

{{-- ===== STYLES ===== --}}
@push('styles')
<style>
    /* Overlay hover best seller */
    .bs-overlay {
        position: absolute;
        inset: 0;
        background: rgba(59, 93, 80, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.25s ease;
        border-radius: 8px;
    }
    .bs-overlay span {
        color: #fff;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }
    div.product-item:hover .bs-overlay {
        opacity: 1;
    }

    /* Badge stok */
    .badge-stok-ada   { background: #e6f4ea; color: #2e7d32; padding: 3px 10px; border-radius: 20px; font-size: 0.83rem; font-weight: 600; }
    .badge-stok-habis { background: #fdecea; color: #c62828; padding: 3px 10px; border-radius: 20px; font-size: 0.83rem; font-weight: 600; }
</style>
@endpush

{{-- ===== SCRIPTS ===== --}}
@push('scripts')
<script>
    function showProductModal(nama, gambar, harga, bahan, ukuran, stok, deskripsi, id) {
        document.getElementById('modal-nama').textContent      = nama.toUpperCase();
        document.getElementById('modal-gambar').src           = gambar;
        document.getElementById('modal-harga').textContent    = 'Rp ' + harga;
        document.getElementById('modal-bahan').textContent    = bahan;
        document.getElementById('modal-ukuran').textContent   = ukuran;
        document.getElementById('modal-deskripsi').textContent = deskripsi;

        // Badge stok
        const stokEl = document.getElementById('modal-stok');
        if (stok > 0) {
            stokEl.innerHTML = `<span class="badge-stok-ada">✔ Tersedia (${stok})</span>`;
        } else {
            stokEl.innerHTML = `<span class="badge-stok-habis">✘ Habis</span>`;
        }

        // ✅ Update action form cart dengan id barang yang dipilih
        const form = document.getElementById('modal-cart-form');
        form.action = form.action.replace('/0', '/' + id);

        // Tampilkan modal
        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }
</script>
@endpush