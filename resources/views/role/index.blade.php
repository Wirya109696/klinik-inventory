@extends('layouts.main_master')

@section('container')

<div class="container-fluid  pt-3 pb-2 mb-3">
    <div class="card">
        <div class="card-header">
            {!! $header !!}
        </div>
        <div class="card-body">
            @can('role-x')
            <h5>access</h5>
            @endcan
            <button id="add_btn" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-plus"></i>
                Add</button>
            <hr>
            <div class="table-responsive text-nowrap">
                <table class="table" id="table_role" style="width:100%">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Role Name</th>
                            <th>Guard Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
