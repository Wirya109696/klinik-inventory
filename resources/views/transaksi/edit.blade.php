<div class="row">
    <div class="col-lg">

        <div class="form-group">
            <label class="form-label">Nama Supplier </label>
            <input class="form-control form-control-sm" type="text" id="nama_supplier" value="{{ $data->nama_supplier }}">
        </div>
        <div class="form-group">
            <label class="form-label">No Handphone</label>
            <input class="form-control form-control-sm" type="text" id="no_hp" value="{{ $data->no_hp }}">
        </div>
        <div class="form-group">
            <label class="form-label">Alamat </label>
            <input class="form-control form-control-sm" type="text" id="alamat" value="{{ $data->alamat }}">
        </div>
    </div>
</div>

<input class="form-control" type="hidden" id="id" value="{{ $data->id }}">
<input class="form-control" type="hidden" id="nama_supplier_old" value="{{ $data->nama_supplier }}">
