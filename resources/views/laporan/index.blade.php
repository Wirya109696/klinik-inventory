@extends('layouts.main_master')

@section('container')
    <div class="container-fluid  pt-3 pb-2 mb-2">
        <div class="card">
            <div class="card-header">
                {!! $header !!}
                <button id="add_btnbrng" class="btn btn-outline-success btn-sm" style="float: right"><i
                        class="bi bi-file-earmark-plus"></i>
                    Excel</button>
                <div class="card-body mt-1">
                    {{-- (filter here) --}}
                    <div class="row mb-3 justify-content-center">
                        {{-- Add your filter elements here --}}
                        {{-- <div class="col-md-3">
                            <label for="from_date">From Date:</label>
                            <input type="date" id="from_date" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label for="to_date">To Date:</label>
                            <input type="date" id="to_date" class="form-control form-control-sm">
                        </div> --}}
                        <div class="col-md-3">
                            <label for="filter_gudang">Filter Lokasi:</label>
                            <select id="filter_gudang" class="form-select form-select-sm">
                                {{-- <option value=""></option> Default value --}}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_gudang">Filter Gudang:</label>
                            <select id="filter_gudang" class="form-select form-select-sm">
                                {{-- <option value=""></option> Default value --}}
                            </select>
                        </div>
                        {{-- Add other filters as needed --}}
                    </div>
                    {{-- <div class="row justify-content mb-3">
                    </div> --}}
                    {{-- <a href="{{ route('excel') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-plus"></i> Spreadsheet</a> --}}
                    <hr>
                    {{-- <a href="{{ route('excel2') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-plus"></i> Spreadsheet</a> <hr> --}}
                    <table class="table" id="tablelaporans" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stock Awal</th>
                                <th>Pemasukan</th>
                                <th>Pengeluaran</th>
                                <th>Stock Akhir</th>
                                <th>Gudang</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
