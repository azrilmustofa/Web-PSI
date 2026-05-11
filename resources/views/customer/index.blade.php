@extends('layouts.master')
@section('title','Dwijaya Mebel')
@section('content')


		<!-- End Header/Navigation -->

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
						<p class="mb-4">Memiliki berbagai pilihan untuk anda, dengan kualitas yang terbaik. </p>
					</div> 
					<!-- End Column 1 -->

					<!-- Start Column 2 -->
					@foreach($bestSeller as $item)
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">

						<a class="product-item" href="{{ route('customer.detail', $item->id) }}">

							<img src="{{ asset('storage/'.$item->gambar) }}"
								class="img-fluid product-thumbnail">

							<h3 class="product-title">
								{{ $item->nama_barang }}
							</h3>

							<strong class="product-price">
								Rp {{ number_format($item->harga,0,',','.') }}
							</strong>

							<p class="text-muted">
								Terjual {{ $item->total_terjual }}x
							</p>

							<span class="icon-cross">
								<img src="{{ asset('template_customer/images/cross.svg') }}"
									class="img-fluid">
							</span>

						</a>

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
						<h2 class="section-title mb-4">Kami membantu anda untuk mendesain rumah </h2>
						<p>Dengan produk dari kami, anda dapat membuat interior rumah anda sesaui dengan apa yang anda harapkan.</p>

						<p><a herf="{{ route('customer.shop')}}" class="btn">Shop Now</a></p>
					</div>
				</div>
			</div>
		</div>
		<!-- End We Help Section -->

		<!-- Start Popular Product -->
		
		<!-- End Popular Product -->

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
													<p>&ldquo;Mebel dengan kualitas baik dan sanagat memuaskan, pelayanan terbaik dan sangat fast respon&rdquo;</p>
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
								<!-- END item -->

								<div class="item">
									<div class="row justify-content-center">
										<div class="col-lg-8 mx-auto">

											<div class="testimonial-block text-center">
												<blockquote class="mb-5">
													<p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
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
								<!-- END item -->

								<div class="item">
									<div class="row justify-content-center">
										<div class="col-lg-8 mx-auto">

											<div class="testimonial-block text-center">
												<blockquote class="mb-5">
													<p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
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
								<!-- END item -->

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Testimonial Slider -->

		<!-- Start Blog Section -->
		<!-- End Blog Section -->	

		<!-- Start Footer Section -->
		<!-- End Footer Section -->	

@endsection