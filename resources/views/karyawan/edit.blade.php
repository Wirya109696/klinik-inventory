<div class="row">
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label">Id Karyawan</label>
            <input class="form-control form-control-sm" type="text" name="kar_idkar" id="kar_idkar"
                value="{{ old('kar_idkar', $data->kar_idkar) }}">
        </div>
        <div class="form-group mt-1">
            <label class="form-label">Nama Karyawan</label>
            <input class="form-control form-control-sm" type="text" name="kar_nama" id="kar_nama"
                value="{{ old('kar_nama', $data->kar_nama) }}">
        </div>
        {{-- <div class="form-group ">
            <label class="form-label mt-2">Penempatan</label>
            <select class="form-control form-control-sm" id="kar_location" name="kar_location">
                <option value="Klinik 1" {{ old('kar_location', $data->kar_location) == 'Klinik 1' ? 'selected' : '' }}>
                    Klinik 1
                </option>
                <option value="Klinik 2" {{ old('kar_location', $data->kar_location) == 'Klinik 2' ? 'selected' : '' }}>
                    Klinik 2
                </option>
            </select>
        </div> --}}
        <div class="form-group mt-1">
            <label class="form-label">Jabatan Karyawan </label>
            <input class="form-control form-control-sm" type="text" name="kar_jabatan" id="kar_jabatan"
                value="{{ old('kar_jabatan', $data->kar_jabatan) }}">
        </div>
    </div>
</div>

<input class="form-control" type="hidden" id="id" value="{{ $data->id }}">
<input class="form-control" type="hidden" id="kar_idkar_old" value="{{ old('kar_nama', $data->kar_idkar) }}">
