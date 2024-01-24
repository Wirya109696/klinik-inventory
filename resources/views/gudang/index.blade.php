@extends('layouts.main_master')

@section('container')
    <div class="container-fluid  pt-3 pb-2 mb-2">
        <div class="card">
            <div class="card-body mt-1">
                {!! $header !!}
                <button id="add_btngud" class="btn btn-outline-success btn-sm" style="float: right"><i
                        class="bi bi-file-earmark-plus"></i>
                    Adds</button>
                {{-- <a href="{{ route('excel') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-plus"></i> Spreadsheet</a> --}}
                <hr>
                {{-- <a href="{{ route('excel2') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-plus"></i> Spreadsheet</a> <hr> --}}
                <table class="table" id="tablegudangs" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Gudang </th>
                            <th>Lokasi Simpan </th>
                            <th>Deskripsi </th>
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
