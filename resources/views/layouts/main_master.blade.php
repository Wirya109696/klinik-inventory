<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Satrio Setiawan">
    <title>{{ $title }}</title>

    {{-- css --}}
    {{-- <link href="{{ asset('/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/lib/sidenav/sidenav.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/global.css') }}"> --}}
    {{-- end css --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- new theme --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('/lib/bootstrap/css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/js/template-customizer.js')}}"></script> --}}
    <script src="{{ asset('assets/js/config.js') }}"></script>

    {{-- end new theme --}}
    <link rel="icon" href="{{ asset('public/img/favicon.ico') }}" type="image/x-icon">
    {{-- looping data css controller --}}
    @foreach ($data['css'] as $dt)
        <link rel="stylesheet" href="{{ asset($dt) }}">
    @endforeach
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.sidebar')
            <div class="layout-page">
                @include('layouts.header')
                @yield('container')
                @include('layouts.footer')
            </div>
        </div>
    </div>

    @include('layouts.modal')
</body>
{{-- js --}}
<script> window.url = "@php echo url('/') @endphp"</script>
{{-- <script src="{{ asset('/lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/lib/sweetalert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('/lib/sidenav/sidenav.min.js') }}"></script>
<script src="{{ asset('/js/global.js') }}"></script> --}}


<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/vendor/js/i18n.js') }}"></script>
<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="{{ asset('/lib/sweetalert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('/js/global.js') }}"></script>

{{-- <script>$('[data-sidenav]').sidenav();</script> --}}
{{-- end js --}}

{{-- looping data js controller --}}
@foreach ($data['js'] as $dt)
<script src="{{ asset($dt) }}"></script>
@endforeach

</html>
