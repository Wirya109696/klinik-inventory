<div class="row">
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label">Lokasi Simpan </label>
            <input class="form-control form-control-sm" type="text" id="pen_nama" value="{{ $data->pen_nama }}">
        </div>
    </div>
    {{-- <div class="col">
        <div class="form-group">
            <label for="kategori" class="form-label">Gudang </label>
            <select class="form-select" id="gudang_id" name="gudang_id">
                @foreach ($gudang as $gudangs)
                    <option value="{{ $gudangs->id }}"
                        {{ old('gudang_id', $data->gudang_id) == $gudangs->id ? 'selected' : '' }}>
                        {{ $gudangs->gud_nama }}
                    </option>
                @endforeach
            </select>
        </div>
    </div> --}}
    <div class="form-group">
        <label class="form-label">Deskripsi </label>
        <textarea class="form-control form-control-sm" type="text" id="pen_desc">{{ old('pen_desc', $data->pen_desc) }}</textarea>
    </div>

</div>


<input class="form-control" type="hidden" id="id" value="{{ $data->id }}">
<input class="form-control" type="hidden" id="pen_nama_old" value="{{ $data->pen_nama }}">
