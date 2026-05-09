<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Dwijaya Mebel')</title>

    <!-- CSS -->
    <link href="{{ asset('template_customer/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('template_customer/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('template_customer/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
</head>
<body>

    {{-- NAVBAR --}}
    @include('layouts.navbar')

    {{-- HERO (halaman lain yang butuh hero) --}}
    @hasSection('hero')
        @yield('hero')
    @endif

    {{-- SLIDER (full width, tanpa container) --}}
    @hasSection('slider')
        @yield('slider')
    @endif



    {{-- HALAMAN ISI --}}
    @yield('before_content')  {{-- <-- tambahkan ini, tanpa container --}}

    <div class="container mt-4">
        @yield('content')
        @yield('after_content')
    </div>

    {{-- FOOTER --}}
    @include('layouts.footer')

    {{-- JS --}}
    <script src="{{ asset('template_customer/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template_customer/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('template_customer/js/custom.js') }}"></script>

    {{-- Script dari halaman child --}}
    @stack('scripts')

</body>
</html>

