<div class="row">
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label">Nama Divisi </label>
            <input class="form-control form-control-sm" type="text" id="div_nama" value="{{ $data->div_nama }}">
        </div>
        <div class="form-group mt-3">
            <label class="form-label">Deskripsi </label>
            {{-- <input class="form-control form-control-sm" type="text" id="div_deskripsi"
                value="{{ $data->div_deskripsi }}">
            <textarea name="" id="" cols="30" rows="10"></textarea> --}}
            <textarea class="form-control form-control-sm" type="text" id="div_deskripsi">{{ old('div_deskripsi', $data->div_deskripsi) }}</textarea>
        </div>
    </div>
</div>

<input class="form-control" type="hidden" id="id" value="{{ $data->id }}">
<input class="form-control" type="hidden" id="div_nama_old" value="{{ $data->div_nama }}">
