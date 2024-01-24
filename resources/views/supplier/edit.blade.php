<div class="row">
    <div class="col-lg">

        <div class="form-group">
            <label class="form-label">Nama Supplier </label>
            <input class="form-control form-control-sm" type="text" id="sup_name" value="{{ $data->sup_name }}">
        </div>
        <div class="form-group">
            <label class="form-label">No Handphone</label>
            <input class="form-control form-control-sm" type="text" id="sup_phone" value="{{ $data->sup_phone }}">
        </div>
        <div class="form-group">
            <label class="form-label">Alamat </label>
            <textarea class="form-control form-control-sm" type="text" id="sup_addres">{{ old('sup_addres', $data->sup_addres) }}</textarea>

        </div>
    </div>
</div>

<input class="form-control" type="hidden" id="id" value="{{ $data->id }}">
<input class="form-control" type="hidden" id="sup_name_old" value="{{ $data->sup_name }}">
