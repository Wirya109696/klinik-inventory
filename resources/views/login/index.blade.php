@extends('layouts.main_login')

@section('container')
    <div id="konten">
        <div class="container-fluid konten-box">
            <div class="row">
                <div class="konten-kanan bg-img">
                </div>
                <div class="konten-kiri">

                    {{-- <form action="{{ url('tostr') }}" method="POST"> --}}
                    @csrf
                    <div class="login-box mt-5 mb-4">
                        <div class="card-body">
                            <h3 class="text-center mb-3"><a href=""><img width="40%"
                                        src="{{ asset(config('app.logo_app')) }}"></a></h3>
                            <h5 class="text-center mb-2" style="font-weight: 600;">{{ config('app.name') }}</h5>
                            {{-- <p class="mt-3 text-muted" style="font-size: 8pt">Login Dashboard.</p> --}}
                            <div class="form-group mb-2">
                                <label class="form-label" style="font-weight: 600;">Username</label>
                                <input class="form-control" type="text" placeholder="Username" name="username"
                                    id="username" required>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="font-weight: 600;"> Password</label>
                                <input class="form-control" type="password" placeholder="Password" name="password"
                                    id="password" required>
                                <div class="clearfix"></div>
                            </div>
                            <div class="mt-4 d-grid gap-2">
                                <button type="submit" class="btn btn-success" id="btn_login">Login</button>
                                <button class="btn btn-success d-none" type="button" id="btn_loding" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- </form> --}}
                    <hr>
                    <div class="container text-footer mt-2">
                        {{-- <p class="text-muted text-center text-lg ">PT Indonesia Morowali Industrial Park</p> --}}
                        <a href="https://imip.co.id/" class="text-muted text-decoration-none">
                            <p class="text-center"> {{ env('APP_FOOTER') }} </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
