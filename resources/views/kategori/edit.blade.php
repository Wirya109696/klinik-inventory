<div class="row">
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label">Nama Kategori </label>
            <input class="form-control form-control-sm" type="text" id="kat_nama" value="{{ $data->kat_nama }}">
        </div>
        <div class="form-group">
            <label class="form-label">Deskripsi </label>
            <textarea class="form-control form-control-sm" type="text" id="kat_deskripsi">{{ old('kat_deskripsi', $data->kat_deskripsi) }}</textarea>
        </div>
    </div>
</div>

<input class="form-control" type="hidden" id="id" value="{{ $data->id }}">
<input class="form-control" type="hidden" id="kat_nama_old" value="{{ $data->kat_nama }}">
