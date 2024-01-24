<style>
    .my-input {
        height: 28.3px;
        padding-top: 0;
    }
</style>
<div class="row">
    <div class="col-lg">
        <div class="form-group">
            <div class="form-group">
                <label class="form-label">Lokasi Simpan </label>
                <input class="form-control form-control-sm" type="text" id="pen_nama" placeholder="Klinik 1...">
            </div>
        </div>
        {{-- <div class="form-group mt-2">
            <label for="kategori" class="form-label">Gudang </label>
            <select class="form-select my-input" id="gudang_id" style="font-size: 12px; text-align: center;">
                @foreach ($gudang as $gudangs)
                    <option value="{{ $gudangs->id }}" {{ old('gudang_id') == $gudangs->id ? ' selected' : ' ' }}>
                        {{ $gudangs->gud_nama }}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="form-group mt-2">
            <label class="form-label">Deskripsi </label>
            {{-- <input class="form-control form-control-sm" type="text" id="pen_desc"> --}}
            <textarea class="form-control form-control-sm" type="text" id="pen_desc" placeholder="Keterangan Penyimpanan..."></textarea>
        </div>
    </div>
</div>
