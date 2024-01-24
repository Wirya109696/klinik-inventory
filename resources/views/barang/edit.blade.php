<div class="row">
    <div class="col-3">
        <div class="form-group">
            <label class="form-label">Kode Barang </label>
            <input class="form-control form-control-sm" type="text" id="brg_kode" value="{{ $data->brg_kode }}">
        </div>
    </div>
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label">Nama Barang </label>
            <input class="form-control form-control-sm" type="text" id="brg_nama" value="{{ $data->brg_nama }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="form-group">
            <label for="kategori" class="form-label">Kategori </label>
            <select class="form-select" id="kategori_id" name="kategori_id">
                @foreach ($kategori as $kategoris)
                    <option value="{{ $kategoris->id }}"
                        {{ old('kategori_id', $data->kategori_id) == $kategoris->id ? 'selected' : '' }}
                        style="font-size: 12px;">
                        {{ $kategoris->kat_nama }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-3">
        <div class="form-group">
            <label class="form-label">Satuan </label>
            <input class="form-control form-control-sm" type="text" id="brg_satuan" value="{{ $data->brg_satuan }}">
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            <label class="form-label">Minimal Stock </label>
            <input class="form-control form-control-sm" type="text" id="brg_min" value="{{ $data->brg_min }}">
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi </label>
        <textarea class="form-control form-control-sm" type="text" id="brg_desc">{{ old('brg_desc', $data->brg_desc) }}</textarea>
    </div>
</div>
</div>


<input class="form-control" type="hidden" id="id" value="{{ $data->id }}">
<input class="form-control" type="hidden" id="brg_kode_old" value="{{ $data->brg_kode }}">
