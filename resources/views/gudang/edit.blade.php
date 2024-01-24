<style>
    .my-input {
        height: 28.3px;
        padding-top: 0;
    }
</style>
<div class="row">
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label">Nama Gudang</label>
            <input class="form-control form-control-sm" type="text" id="gud_nama" value="{{ $data->gud_nama }}">
        </div>
        <div class="col">
            <div class="form-group">
                <label for="kategori" class="form-label">Lokasi Simpan </label>
                <select class="form-select my-input" id="penyimpanan_id" name="penyimpanan_id">
                    @foreach ($penyimpanan as $penyimpanans)
                        <option value="{{ $penyimpanans->id }}"
                            {{ old('penyimpanan_id', $data->penyimpanan_id) == $penyimpanans->id ? 'selected' : '' }}>
                            {{ $penyimpanans->pen_nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Deskripsi </label>
            <textarea class="form-control form-control-sm" type="text" id="gud_desc">{{ old('gud_desc', $data->gud_desc) }}</textarea>
        </div>
    </div>
</div>

<input class="form-control" type="hidden" id="id" value="{{ $data->id }}">
<input class="form-control" type="hidden" id="gud_nama_old" value="{{ $data->gud_nama }}">
