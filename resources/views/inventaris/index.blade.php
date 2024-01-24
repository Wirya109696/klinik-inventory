@extends('layouts.main_master')

@section('container')
    <div class="container-fluid  pt-3 pb-2 mb-2">
        <div class="card">
            <div class="card-header">
                {!! $header !!}
                <div class="card-body mt-1">
                    {{-- Put The Filter Here in the middle --}}
                    <div class="row mb-2 justify-content-center">
                        {{-- Add your filter elements here --}}
                        <div class="col-md-2">
                            <label for="filter_kode_bar">Filter Kode Barang:</label>
                            <select id="filter_kode_bar" class="form-select form-select-sm">
                                {{-- <option value=""></option> Default value --}}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter_nama_bar">Filter Nama Barang:</label>
                            <select id="filter_nama_bar" class="form-select form-select-sm">
                                {{-- <option value=""></option> Default value --}}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter_kategori">Filter Kategori:</label>
                            <select id="filter_kategori" class="form-select form-select-sm">
                                {{-- <option value=""></option> Default value --}}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter_lokasi">Filter Lokasi:</label>
                            <select id="filter_lokasi" class="form-select form-select-sm">
                                {{-- <option value=""></option> Default value --}}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter_gudang">Filter Gudang:</label>
                            <select id="filter_gudang" class="form-select form-select-sm">
                                {{-- <option value=""></option> Default value --}}
                            </select>
                        </div>
                        {{-- Add other filters as needed --}}
                    </div>
                    <hr>
                    <table class="table" id="tableinventaris" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stock Barang</th>
                                <th>Minimal Stock</th>
                                <th>Lokasi Penyimpanan</th>
                                <th>Lokasi Gudang</th>
                                {{-- <th>Action</th> --}}
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
