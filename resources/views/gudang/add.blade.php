<style>
    .my-input {
        height: 28.3px;
        padding-top: 0;
    }
</style>
<div class="row">
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label">Nama Gudang </label>
            <input class="form-control form-control-sm" type="text" id="gud_nama" placeholder="Gudang A Klinik 1...">
        </div>
        <div class="form-group mt-2">
            <label for="kategori" class="form-label">Lokasi Simpan </label>
            <select class="form-select my-input" id="penyimpanan_id" style="font-size: 12px; text-align: center;">
                @foreach ($penyimpanan as $penyimpanans)
                    <option value="{{ $penyimpanans->id }}"
                        {{ old('penyimpanan_id') == $penyimpanans->id ? ' selected' : ' ' }}>
                        {{ $penyimpanans->pen_nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mt-3">
            <label class="form-label">Deskripsi </label>
            {{-- <input class="form-control form-control-sm" type="text" id="gud_desc"> --}}
            <textarea class="form-control form-control-sm" type="text" id="gud_desc" placeholder="Keterangan Gudang Klinik..."></textarea>
        </div>
    </div>
</div>
</div>
