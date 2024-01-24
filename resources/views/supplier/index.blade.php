@extends('layouts.main_master')

@section('container')
    <div class="container-fluid  pt-3 pb-2 mb-3">
        <div class="card">
            <div class="card-body">
                {!! $header !!}
                <button id="add_btnsplier" class="btn btn-outline-success btn-sm" style="float: right"><i
                        class="bi bi-file-earmark-plus"></i> Adds</button>
                {{-- <a href="{{ route('excel') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-plus"></i> Spreadsheet</a>  --}}
                <hr>
                {{-- <a href="{{ route('excel2') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-plus"></i> Spreadsheet</a> <hr> --}}
                <table class="table" id="tablesuppliers" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Supplier</th>
                            <th>No Handphone</th>
                            <th>Alamat</th>
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
