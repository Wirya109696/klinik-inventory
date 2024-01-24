@extends('layouts.main_master')

@section('container')

<div class="container-fluid  pt-3 pb-2 mb-3">
    <div class="card">
        <div class="card-header">
            {!! $header !!}
        </div>
        <div class="card-body">
            <button id="add_btn" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-plus"></i> Add</button><hr>
            <div class="table-responsive">
                <table class="table table-bordered" id="table_permission" style="width:100%">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Menu Name</th>
                            <th>Icon</th>
                            <th>Route Name</th>
                            <th>Order Line</th>
                            <th>Index</th>
                            <th>Store</th>
                            <th>Edits</th>
                            <th>Erase</th>
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
