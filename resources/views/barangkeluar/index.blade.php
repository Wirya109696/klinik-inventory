@extends('layouts.main_master')

@section('container')
    <div class="container-fluid  pt-3 pb-2 mb-3">
        <div class="card">
            <div class="card-body">
                {!! $header !!}
                <button id="add_btnkeluar" class="btn btn-outline-success btn-sm" style="float: right"><i
                        class="bi bi-file-earmark-plus"></i>
                    Adds</button>
                <hr>
                <table class="table" id="tablekeluar" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Karyawan</th>
                            {{-- <th>Lokasi</th> --}}
                            <th>Tanggal</th>
                            <th>Total Barang</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
