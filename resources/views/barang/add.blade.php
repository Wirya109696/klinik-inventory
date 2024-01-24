<style>
    .my-input {
        height: 28.3px;
        padding-top: 0;
    }
</style>
<div class="row">
    <div class="col-3">
        <div class="form-group">
            <label class="form-label">Kode Barang </label>
            <input class="form-control form-control-sm" type="text" id="brg_kode">
        </div>
    </div>
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label">Nama Barang </label>
            <input class="form-control form-control-sm" type="text" id="brg_nama">
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="form-group">
            <label for="kategori" class="form-label">Kategori </label>
            <select class="form-select my-input" id="kategori_id" style="font-size: 12px;">
                @foreach ($kategori as $kategoris)
                    <option value="{{ $kategoris->id }}" {{ old('kategori_id') == $kategoris->id ? ' selected' : ' ' }}>
                        {{ $kategoris->kat_nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-3">
        <div class="form-group">
            <label class="form-label">Satuan </label>
            <input class="form-control form-control-sm" type="text" id="brg_satuan" placeholder="Pcs/Box/Rolls">
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            <label class="form-label">Minimal Stock </label>
            <input class="form-control form-control-sm" type="number" id="brg_min" oninput="validateJumlah(event)"
                onkeypress="return isNumberKey(event)">
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi </label>
        <textarea class="form-control form-control-sm" type="text" id="brg_desc" placeholder="Keterangan Barang..."></textarea>
    </div>
</div>
</div>

<script>
    function validateJumlah(event) {
        var inputElement = event.target;
        var enteredValue = inputElement.value;

        // Parse the entered value as an integer
        var intValue = parseInt(enteredValue);

        // Check if the entered value is negative
        if (intValue < 0) {
            // Display an alert or handle it in any way you prefer
            Swal.fire({
                icon: 'error',
                title: 'Tidak Boleh (-) !',
                text: 'Nilai tidak boleh mines.'
            });

            // Reset the input value to 0 or any other appropriate value
            inputElement.value = 0;
        }

        // Continue with other logic or calculations as needed
        // calculateTotalBarang();
    }


    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;

        // Check if the character entered is a number (0-9) or a backspace
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 8) {
            // If not a number or backspace, prevent the input
            evt.preventDefault();
            return false;
        }

        return true;
    }
</script>
