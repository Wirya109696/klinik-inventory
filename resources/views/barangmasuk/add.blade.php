<style lang="scss">
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

    .ui-autocomplete {
        z-index: 10000;
    }

    .my-input {
        height: 28.3px;
        padding-top: 0;
    }

    .table-row {
        font-size: 13px;
    }

    option,
    select {
        text-align: left;
        vertical-align: top;
    }
</style>


<form class="form-inline" onchange="checkFirstForm()">
    @csrf
    <div class="form-group row align-items-center">
        <label for="trx_code" class="col-form-label col-sm-3" style="width: 19%; font-weight:bold;">
            Kode Transaksi
        </label>
        <div class="col-sm-3">
            <input class="form-control form-control-sm" type="text" id="trx_code" name="trx_code"
                value="{{ $trx_code }}" readonly>
        </div>
        <div class="col-sm-1">
            {{-- jarak --}}
        </div>
        <label for="tanggal" class="col-form-label col-sm-1" style="width: 17%; font-weight:bold;">Tanggal<span
                class="text-danger">
                *</span></label>
        <div class="col-sm-3">
            <input class="form-control form-control-sm" id="tanggal" type="date" name="tanggal">
        </div>
    </div>
</form>

{{-- ------------------------------------------- Form 2 -------------------------------- --}}

<form class="form-inline" style="margin-bottom: 20px; margin-top:15px;" onchange="checkFirstForm()">
    <div class="form-group row align-items-center">
        <label for="supplier" class="col-form-label" style="width: 19%; font-weight:bold;">Supplier<span
                class="text-danger">
                *</span></label>
        <div class="col-sm-3">
            <select class="form-select my-input form-control-sm" id="supplier_id" style="font-size: 12px;">
                @foreach ($supplier as $suppliers)
                    <option value="{{ $suppliers->id }}" {{ old('supplier_id') == $suppliers->id ? ' selected' : ' ' }}>
                        {{ $suppliers->sup_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-1">
            {{-- jarak --}}
        </div>
        <label for="penyimpanan" class="form-label col-sm-1" style="width: 17%; font-weight:bold;">Penyimpanan<span
                class="text-danger">
                *</span></label>
        <div class="col-sm-3">
            <select class="form-select my-input" id="penyimpanan_id" style="font-size: 12px;">
                <option disabled selected>--Pilih Lokasi--</option>
                @foreach ($penyimpanan as $penyimpanans)
                    <option value="{{ $penyimpanans->id }}"
                        {{ old('penyimpanan_id') == $penyimpanans->id ? ' selected' : ' ' }}>
                        {{ $penyimpanans->pen_nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>


{{-- ------------------------------------------- Batas Form -------------------------------- --}}

<hr class="hr-space" style="border-top: 2px solid rgb(231, 228, 228); margin-bottom: 30px;">

<div id="container-all" style="display: none;">
    <div id="barang-container" class="d-flex justify-content-center">
        <!-- Initial Form for a single item -->
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label class="form-label" style="font-size: 10px;">Kode Barang <span class="text-danger">
                            *</span></label>
                    <div class="input-group">
                        <i class="bi bi-search input-group-text"></i>
                        <input class="form-control form-control-sm" type="text" name="brg_kode[]" id="brg_kode">
                    </div>
                    <p>
                        <small class="form-text text-muted" style="font-size: 10px;">* Ketik Kode/Nama lalu
                            Enter</small>
                    </p>
                    <input type="text" id="barang_id" name="barang_id[]" hidden>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label class="form-label" style="font-size: 10px;">Nama Barang</label>
                    <input class="form-control form-control-sm" type="text" name="brg_nama[]" id="brg_nama"
                        disabled>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="gudang" class="form-label" style="font-size: 10px;">Gudang <span class="text-danger">
                            *</span></label>
                    <select class="form-select my-input" name="gudang_id[]" id="gudang_id" style="font-size: 10px;">
                        @foreach ($gudang as $gudangs)
                            <option value="{{ $gudangs->id }}"
                                {{ old('gudang_id') == $gudangs->id ? ' selected' : ' ' }}>
                                {{ $gudangs->gud_nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label class="form-label" style="font-size: 10px; ">Jumlah<span class="text-danger">
                            *</span></label>
                    <input class="form-control form-control-sm input-jumlah" oninput="validateJumlah(event)"
                        onkeypress="return isNumberKey(event)" type="number" name="dtl_jumlah[]" id="dtl_jumlah">
                </div>
            </div>

            <div class="col-auto mt-3">
                <div class="form-group">
                    <button type="button" class="btn btn-outline-success btn-sm add-item-button float-end"
                        onclick="addItem()" style="font-size: 9px; padding: 3px 5px;">Add Item</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" style="margin-top: 11px;">
        <div class="row mt-3">
            <div class="col-12">
                <table class="table" id="tabledetail" style="width:100%">
                    <thead>
                        <tr>
                            <th style="font-size: 9px; width:4%;">Kode Barang</th>
                            <th style="font-size: 9px; width:7%;">Nama Barang</th>
                            <th style="font-size: 9px; width:6%;">Tempat</th>
                            <th style="font-size: 9px; width:2%;">Jumlah</th>
                            <th style="font-size: 9px; width:1%;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Add your table rows here -->
                    </tbody>
                </table>
                {{-- <hr class="hr-space"> --}}
            </div>
            <div class="col-12">
                <div class="row mt-3">
                    <h5 style="padding-left: 520px">
                        <small class="text text-small" style="font-size: 10px;">
                            Total
                        </small>
                        <span id="total_barang" name="total_barang[]">0</span>
                    </h5>
                    <input class="form-control form-control-sm" type="hidden" id="barang"
                        value="{{ $barang }}" readonly>

                </div>
            </div>
        </div>
    </div>
</div>
<hr class="hr-space" style="border-top: 2px solid rgb(231, 228, 228);">
<div class="row align-content-center">
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label" style="font-weight:bold;">Keterangan </label>
            {{-- <input class="form-control form-control-sm" type="text" id="kat_deskripsi"> --}}
            <textarea class="form-control form-control-sm" type="text" id="trx_ket" onchange="checkFirstForm()">-</textarea>
        </div>
    </div>
</div>


{{-- -----------------------------------script--------------------------------------------- --}}
<script>
    // $("#tanggal").datepicker({
    //     dateFormat: 'dd/mm/yyyy'
    // });
    // document.addEventListener('DOMContentLoaded', function() {
    //     // Use moment.js to validate and format the date input field
    //     var tanggalInput = document.getElementById(
    //         'tanggal'); // Replace 'tanggal' with the actual ID of your date input field

    //     tanggalInput.addEventListener('blur', function() {
    //         var inputValue = tanggalInput.value.trim();
    //         var isValid = moment(inputValue, 'DD/MM/YYYY', true).isValid();

    //         if (!isValid) {
    //             alert('Invalid date format. Please use DD/MM/YYYY.');
    //             tanggalInput.focus();
    //             tanggalInput.value = ''; // Clear the input field
    //         }
    //     });
    // });

    function checkFirstForm() {
        var trx_code = document.getElementById('trx_code').value;
        var tanggal = document.getElementById('tanggal').value;
        var supplier_id = document.getElementById('supplier_id').value;
        var penyimpanan_id = document.getElementById('penyimpanan_id').value;
        var trx_ket = document.getElementById('trx_ket').value;

        // Check if all required fields are filled
        if (trx_code && tanggal && supplier_id && penyimpanan_id !== "--Pilih Lokasi--" && trx_ket) {
            // Show the "barang-container" if all fields are filled
            document.getElementById('container-all').style.display = 'block';
        }
    }

    // Add the following code to include the check in the existing onchange events
    document.getElementById('trx_code').addEventListener('change', checkFirstForm);
    document.getElementById('tanggal').addEventListener('change', checkFirstForm);
    document.getElementById('supplier_id').addEventListener('change', checkFirstForm);
    document.getElementById('penyimpanan_id').addEventListener('change', checkFirstForm);
    document.getElementById('trx_ket').addEventListener('input', checkFirstForm);



    function setNewestDate() {
        var today = new Date();
        var year = today.getFullYear();
        var month = ('0' + (today.getMonth() + 1)).slice(-2);
        var day = ('0' + today.getDate()).slice(-2);

        var newestDate = year + '-' + month + '-' + day;

        // Set the value of the input field
        document.getElementById('tanggal').value = newestDate;
    }

    // Call the function to set the default value
    setNewestDate();
    // Function to update Tempat options based on selected Gudang
    function updateTempatOptions() {
        // Get the selected Gudang value
        var selectedPenyimpanan = document.getElementById('penyimpanan_id').value;

        // Get the Tempat dropdown element
        var gudangDropdown = document.getElementById('gudang_id');

        // Clear existing options
        gudangDropdown.innerHTML = '';

        // Fetch Tempat options from the server based on the selected Gudang
        fetch(window.url + '/api/penyimpananin/' + selectedPenyimpanan)
            .then(response => response.json())
            .then(data => {
                // Check if there are options to append
                if (data.length > 0) {
                    // Add new options to the Tempat dropdown
                    data.forEach(function(gudang) {
                        var option = document.createElement('option');
                        option.value = gudang.id;
                        option.text = gudang.gud_nama;
                        gudangDropdown.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error fetching gudang data:', error));
    }

    // Trigger the event manually on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateTempatOptions();
    });

    // Add an event listener to the Gudang dropdown
    document.getElementById('penyimpanan_id').addEventListener('change', updateTempatOptions);


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
    // autocomplete search
    var barang = $('#barang').val().split(',');
    $("#brg_kode").autocomplete({
        // source: ["KD123", "KD321"],
        source: barang,
    });
    $("#brg_kode").focus(function() {
        $("ui-autocomplete").css("display", "block");
    });
    $("#brg_kode").focusout(function() {
        $("ui-autocomplete").css("display", "none");
    });

    // -------------------
    var addedItems = [];

    function addItem() {
        // Clone the template row and append it to the container
        var template = document.querySelector("#barang-container .row");
        var clone = template.cloneNode(true);

        // Get the values from the cloned row
        var brg_kode = $("#brg_kode").val();
        var barang_id = $("#barang_id").val();
        var brg_nama = $("#brg_nama").val();
        var gudang_id = $("#gudang_id").val();
        var gudang_text = $("#gudang_id option:selected").text();
        var dtl_jumlah = $("#dtl_jumlah").val();

        // Check if the jumlah is empty
        if (!brg_kode) {
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Boleh Kosong !',
                text: 'Kode Barang Wajib Di Isi.'
            });
            return;
        }

        if (!dtl_jumlah) {
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Boleh Kosong !',
                text: 'Jumlah Wajib Di Isi.'
            });
            return;
        }

        if (!gudang_id) {
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Boleh Kosong !',
                text: 'Tempat Barang Wajib Di Isi.'
            });
            return;
        }

        // Check if the item already exists
        var existingItemIndex = addedItems.findIndex(function(item) {
            return item.brg_kode === brg_kode;
        });

        // If the item already exists, clear the fields and exit the function
        if (existingItemIndex !== -1) {
            Swal.fire({
                icon: 'error',
                title: 'Barang Tidak Boleh Sama !',
                text: 'silahkan menambahkan data barang yang berbeda.'
            });
            $("#barang_id").val("");
            $("#brg_kode").val("");
            $("#brg_nama").val("");
            $("#gudang_id").val("");
            $("#dtl_jumlah").val("");
            return;
        }

        // Add the cloned row data to the addedItems array
        addedItems.push({
            barang_id: barang_id,
            brg_kode: brg_kode,
            brg_nama: brg_nama,
            gudang_id: gudang_id,
            gudang_text: gudang_text,
            dtl_jumlah: dtl_jumlah
        });

        // Update the table
        updateTable();
        $("#barang_id").val("");
        $("#brg_kode").val("");
        $("#brg_nama").val("");
        $("#gudang_id").val("");
        $("#dtl_jumlah").val("");
    }



    function updateTable() {
        // Get the table body
        var tbody = $('#tabledetail tbody');
        // Clear the existing rows
        tbody.empty();
        // Iterate through the added items and append them to the table
        for (var i = 0; i < addedItems.length; i++) {
            var item = addedItems[i];

            // console.log('Item:', item);

            // Append a new row to the table
            tbody.append('<tr class="table-row">' +
                '<td >' + item.brg_kode + '</td>' +
                '<td>' + item.brg_nama + '</td>' +
                '<td>' + item.gudang_text + '</td>' +
                '<td>' + item.dtl_jumlah + '</td>' +
                '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeTableItem(' + i +
                ')">Delete</button></td>' +
                '</tr>');
        }

        // Recalculate total_barang after adding or removing items
        calculateTotalBarang();
    }


    function removeTableItem(index) {
        // Remove the item from the addedItems array
        addedItems.splice(index, 1);

        // Update the table
        updateTable();
    }

    function calculateTotalBarang() {
        var total_barang = 0;

        // Iterate through all dtl_jumlah inputs and accumulate the values
        addedItems.forEach(function(item) {
            total_barang += parseInt(item.dtl_jumlah) || 0;
        });

        // Update the total_barang input
        document.getElementById('total_barang').innerHTML = total_barang;
    }
</script>
