<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Satrio Setiawan">
    <title>{{ $title }}</title>

    {{-- css --}}
    <link href="{{ asset('/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/lib/bootstrap/css/bootstrap-icons.css') }}">
    {{-- end css --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
     {{-- toastr --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />

    {{-- looping data css controller --}}
    @foreach ($data['css'] as $dt)
        <link rel="stylesheet" href="{{asset($dt)}}">
    @endforeach

    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
</head>

<body>
    @yield('container')
</body>

<script> window.url = "@php echo url('/') @endphp"</script>
{{-- js --}}
<script src="{{ asset('/lib/jquery/jquery.min.js')  }}"></script>
<script src="{{ asset('/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/lib/sweetalert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('/js/global.js') }}"></script>
{{-- end js --}}

{{-- toastr js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>

 {{-- looping data js controller --}}
@foreach ($data['js'] as $dt)
<script src="{{ asset($dt) }}"></script>
@endforeach

</html>
