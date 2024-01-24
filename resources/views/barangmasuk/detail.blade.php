<style>
    .hr-space {
        margin-top: 10px;
        /* Adjust the top margin as needed */
        margin-bottom: 10px;
        /* Adjust the bottom margin as needed */
    }

    .add-item-button {
        margin-top: 10px;
        /* Adjust the top margin as needed */
        margin-bottom: 10px;
        /* Adjust the bottom margin as needed */
        padding: 5px 10px;
        /* Adjust padding for a smaller button */
    }
</style>
{{-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> --}}
<form class="form-inline">
    @csrf
    <div class="form-group row align-items-center">
        <label for="trx_code" class="col-form-label col-sm-3" style="width: 19%; font-weight:bold;">
            Kode Transaksi
        </label>
        <div class="col-sm-3">

            <input class="form-control form-control-sm" type="text" id="trx_code" name="trx_code"
                value="{{ $transaksi->trx_code }}" readonly>
        </div>
        <div class="col-sm-2">
            {{-- jarak --}}
        </div>
        <label for="tanggal" class="col-form-label col-sm-1" style="width: 12%; font-weight:bold;">Tanggal</label>
        <div class="col-sm-3">
            <input class="form-control form-control-sm" type="date" id="tanggal" name="tanggal"
                value="{{ $transaksi->tanggal }}">
        </div>
    </div>
</form>

{{-- ------------------------------- Form 2 ---------------------------------- --}}

<form class="form-inline" style="margin-bottom: 20px; margin-top:15px;">
    <div class="form-group row align-items-center">
        <label for="supplier" class="col-form-label" style="width: 19%; font-weight:bold;">Supplier</label>
        <div class="col-sm-3">
            <input class="form-control form-control-sm" type="text" id="sup_phone" name="sup_phone"
                value="{{ $transaksi->supplier->sup_name }}" readonly>
            {{-- <select class="form-select my-input form-control-sm" id="supplier_id" style="font-size: 12px;">
                @foreach ($supplier as $suppliers)
                    <option value="{{ $suppliers->id }}"
                        {{ old('supplier_id', $transaksi->supplier_id) == $suppliers->id ? 'selected' : '' }}>
                        {{ $suppliers->sup_name }}
                    </option>
                @endforeach
            </select> --}}
        </div>
        <div class="col-sm-2">
            {{-- jarak --}}
        </div>
        <label for="gudang" class="form-label col-sm-1" style="width: 12%; font-weight:bold;">No Hp Supplier </label>
        <div class="col-sm-3">
            <input class="form-control form-control-sm" type="text" id="sup_phone" name="sup_phone"
                value="{{ $transaksi->supplier->sup_phone }}" readonly>
        </div>
    </div>
</form>

<hr class="hr-space" style="border-top: 2px solid rgb(231, 228, 228); margin-bottom: 30px;">
{{-- --------------------------------------------- --}}

<table class="table" id="tabledetail" style="width:100%">
    <thead>
        <tr>
            <th style="font-size: 11px;">Kode Barang</th>
            <th style="font-size: 11px;">Nama Barang</th>
            <th style="font-size: 11px;">Gudang</th>
            <th style="font-size: 11px;">Tempat</th>
            <th style="font-size: 11px;">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($detail as $details)
            <tr>
                <td style="font-size: 11px;">{{ $details->barang->brg_kode }}</td>
                <td style="font-size: 11px;">{{ $details->barang->brg_nama }}</td>
                <td style="font-size: 11px;">{{ $details->gudang->gud_nama }}</td>
                <td style="font-size: 11px;">{{ $details->gudang->penyimpanan->pen_nama }}</td>
                <td style="font-size: 11px;">{{ abs($details->dtl_jumlah) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="col-12">
    <div class="row mt-3">
        <h5 style="padding-left: 680px">
            <small class="text text-small" style="font-size: 10px;">
                Total
            </small>
            <span id="total_barang" name="total_barang[]"
                value="{{ $transaksi->total_barang }}">{{ old('total_barang', $transaksi->total_barang) }}</span>
        </h5>
    </div>
</div>
<hr class="hr-space" style="border-top: 2px solid rgb(231, 228, 228); margin-bottom: 10px;">

<div class="row align-content-center">
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label" style="font-weight:bold;">Keterangan </label>
            {{-- <input class="form-control form-control-sm" type="text" id="kat_deskripsi"> --}}
            <textarea class="form-control form-control-sm" type="text" id="trx_ket" placeholder="Keterangan barang masuk...">{{ old('trx_ket', $transaksi->trx_ket) }}</textarea>
        </div>
    </div>
</div>
