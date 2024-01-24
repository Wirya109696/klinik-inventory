{{-- CSS Untuk Halaman Add Barang Keluar --}}
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

    .center-button {
        display: flex;
        align-items: bottom;
        justify-content: bottom right;
    }
</style>
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css" rel="stylesheet"> --}}

{{-- View Blade Untuk Halaman Add Barang Keluar --}}

<form class="form-inline" onchange="checkFirstForm()">
    <div class="form-group row">
        <label for="trx_code" class="col-form-label col-sm-3" style="width: 14%; font-weight:bold;">Kode Transaksi
        </label>
        <div class="col-sm-2">
            <input class="form-control form-control-sm mb-3" type="text" id="trx_code" name="trx_code"
                value="{{ $trx_code }}" readonly>
        </div>
        <div class="col-sm-1">
            {{-- jarak --}}
        </div>
        {{-- <label for="gudang" class="col-form-label col-sm-3" style="width: 8%; font-weight:bold;">Gudang</label>
        <div class="col-sm-2">
            <select class="form-select my-input form-control-sm" id="gudang_id" style="font-size: 12px;">
                <option disabled selected>
                    --Pilih Gudang--</option>
                @foreach ($gudang as $gudangs)
                    <option value="{{ $gudangs->id }}" {{ old('gudang_id') == $gudangs->id ? ' selected' : ' ' }}>
                        {{ $gudangs->gud_nama }}</option>
                @endforeach
            </select>
            <p>
                <small class="form-text text-muted" style="font-size: 10px;">* Filter Gudang</small>
            </p>
        </div> --}}

        <label for="penyimpanan" class="col-form-label col-sm-3"
            style="width: 9%; font-weight:bold; font-size">Lokasi<span class="text-danger">
                *</span>
        </label>
        <div class="col-sm-2">
            <select class="form-select my-input form-control-sm" id="penyimpanan_id" style="font-size: 12px;">
                <option disabled selected>
                    --Pilih Lokasi--</option>
                @foreach ($penyimpanan as $penyimpanans)
                    <option value="{{ $penyimpanans->id }}"
                        {{ old('penyimpanan_id') == $penyimpanans->id ? ' selected' : ' ' }}>
                        {{ $penyimpanans->pen_nama }}</option>
                @endforeach
            </select>
            <p>
                <small class="form-text text-muted" style="font-size: 10px;">* Filter Untuk Gudang</small>
            </p>
        </div>
        <div class="col-sm-1">
            {{-- jarak --}}
        </div>
        <label for="tanggal" class="col-form-label col-sm-3" style="width: 9%; font-weight:bold;">Tanggal<span
                class="text-danger">
                *</span></label>
        <div class="col-sm-2">
            <input class="form-control form-control-sm" type="date" id="tanggal" name="tanggal">
        </div>
    </div>
</form>

{{-- ------------------------------------- Batas Form 1 ----------------------------------- --}}

<form class="form-inline" style="margin-top: 14px; margin-bottom:20px;" onchange="checkFirstForm()">
    <div class="form-group row">

        <label for="kar_idkar" class="col-form-label col-sm-1" style="width: 14%; font-weight:bold;">ID
            Karyawan <span class="text-danger">
                *</span></label>
        <div class="col-sm-2">
            <div class="input-group">
                <i class="bi bi-search input-group-text"></i>
                <input class="form-control form-control-sm " type="text" name="kar_idkar[]" id="kar_idkar">
            </div>
            <p>
                <small class="form-text text-muted" style="font-size: 9px;">* ID Pengambil (Ketik ID lalu
                    Enter)</small>
            </p>

        </div>
        <div class="col-sm-1">
            <input type="text" id="karyawan_id" name="karyawan_id[]" hidden>
        </div>

        <label for="kar_nama" class="col-form-label col-sm-1" style="width: 9%; font-weight:bold;">Nama </label>
        <div class="col-sm-2">
            <input class="form-control form-control-sm" type="text" name="kar_nama[]" id="kar_nama" disabled>
            <p>
                <small class="form-text text-muted" style="font-size: 9px;">* Nama Pengambil (Terisi Otomatis)</small>
            </p>
        </div>
        <div class="col-sm-1">
            {{-- jarak --}}
        </div>
        <label for="div_nama" class="col-form-label col-sm-1" style="width: 9%; font-weight:bold;">Tujuan <span
                class="text-danger">
                *</span></label>
        <div class="col-sm-2">
            <select class="form-select my-input form-control-sm text-mt-3" id="divisi_id" style="font-size: 12px;">
                <option disabled selected>
                    --Pilih Disini--</option>
                @foreach ($divisi as $divisis)
                    <option value="{{ $divisis->id }}" {{ old('divisi_id') == $divisis->id ? ' selected' : ' ' }}>
                        {{ $divisis->div_nama }}</option>
                @endforeach
            </select>
            <p>
                <small class="form-text text-muted" style="font-size: 10px; ">* Barang ditujukan untuk Divisi</small>
            </p>
        </div>
    </div>
</form>
{{-- ------------------------------------- Batas Form2 ----------------------------------- --}}

<hr class="hr-space mb-5" style="border-top: 2px solid rgb(231, 228, 228);">
<div id="container-all" style="display: none;">
    <div id="barang-container" class="d-flex justify-content-center">
        <!-- Initial Form for a single item -->
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label class="form-label" style="font-size: 10px; font-weight:bold;">Kode Barang <span
                            class="text-danger">
                            *</span></label>

                    <div class="input-group">
                        <i class="bi bi-search input-group-text"></i>
                        <input class="form-control form-control-sm" type="text" name="brg_kode[]" id="brg_kode">
                    </div>

                    <p>
                        <small class="form-text text-muted" style="font-size: 10px;">* Ketik Kode/Nama barang lalu
                            Enter</small>
                    </p>
                    <input type="text" id="barang_id" name="barang_id[]" hidden>
                    <input type="text" id="brg_min" name="brg_min[]" hidden>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label class="form-label" style="font-size: 10px; font-weight:bold;">Nama Barang <span
                            class="text-danger">
                            *</span></label>
                    <input class="form-control form-control-sm" type="text" name="brg_nama[]" id="brg_nama"
                        disabled>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="gudang" class="form-label" style="font-size: 10px; font-weight:bold;">Gudang<span
                            class="text-danger">
                            *</span>
                    </label>
                    <input class="form-control form-control-sm" type="text" name="gud_nama[]" id="gud_nama"
                        disabled>
                </div>
                <input class="form-control form-control-sm" type="text" name="gudang_id[]" id="gudang_id" hidden>
            </div>
            <div class="col">
                <div class="form-group">
                    <label class="form-label" style="font-size: 10px; font-weight:bold;">Stock</label>
                    <input class="form-control form-control-sm" type="text" name="total_stock[]" id="total_stock"
                        disabled>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label class="form-label" style="font-size: 10px; font-weight:bold;">Jumlah<span
                            class="text-danger">
                            *</span></label>
                    <input class="form-control form-control-sm input-jumlah" oninput="validateJumlah(event)"
                        onkeypress="return isNumberKey(event)" type="number" name="dtl_jumlah[]" id="dtl_jumlah">
                </div>
            </div>

            <div class="col-auto" style="margin-top: 20px">
                <div class="form-group">
                    <button type="button" class="btn btn-outline-success btn-sm add-item-button" onclick="addItem()"
                        style="font-size: 11px; padding: 3px 4px;">Add Item</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid" style="margin-top: 10; ">
        <div class="row mt-3">
            <div class="col-12">
                <table class="table" id="tabledetail" style="width:100%">
                    <thead>
                        <tr>
                            <th style="font-size: 9px; width: 12%;">Kode Barang</th>
                            <th style="font-size: 9px; width: 12%;">Nama Barang</th>
                            <th style="font-size: 9px; width: 23%;">Gudang</th>
                            <th style="font-size: 9px; width: 11%;">Jumlah</th>
                            <th style="font-size: 9px; width: 1%;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="row mt-3">
                    {{-- for="kar_nama" class="col-form-label col-sm-3" style="width: 8%; font-weight:bold;" --}}
                    <h5 style="padding-left: 805px">
                        <small class="text text-small" style="font-size: 10px;">
                            Total
                        </small>
                        <span id="total_barang" name="total_barang[]">0</span>
                    </h5>

                    {{-- <input class="form-control form-control-sm" type="hidden" id="barang"
                        value="{{ $barang }}" readonly> --}}
                    <input class="form-control form-control-sm" type="hidden" id="karyawan"
                        value="{{ $karyawan }}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
<hr class="hr-space" style="border-top: 2px solid rgb(231, 228, 228);">

<div class="row align-content-center mt-3">
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label" style="font-weight:bold;">Keterangan </label>
            {{-- <input class="form-control form-control-sm" type="text" id="kat_deskripsi"> --}}
            <textarea class="form-control form-control-sm" type="text" id="trx_ket" onchange="checkFirstForm()">-</textarea>
        </div>
    </div>
</div>

{{-- Script Untuk Halaman View Add --}}
<script>
    // $(function() {
    //     $("#autocomplete-input").autocomplete({
    //         source: function(request, response) {
    //             $.ajax({
    //                 url: window.url + '/autocomplete/divisi',
    //                 dataType: 'json',
    //                 data: {
    //                     term: request.term
    //                 },
    //                 success: function(data) {
    //                     response(data);
    //                 }
    //             });
    //         },
    //         minLength: 2
    //     });

    //     // Initialize Chosen on the select element
    //     $(".chosen-select").chosen();
    // });

    function checkFirstForm() {
        var trx_code = document.getElementById('trx_code').value;
        var tanggal = document.getElementById('tanggal').value;
        var supplier_id = document.getElementById('karyawan_id').value;
        var penyimpanan_id = document.getElementById('penyimpanan_id').value;
        var trx_ket = document.getElementById('trx_ket').value;
        var divisi_id = document.getElementById('divisi_id').value;

        // Check if all required fields are filled
        if (trx_code && tanggal && karyawan_id && penyimpanan_id !== "--Pilih Lokasi--" && trx_ket && divisi_id !==
            "--Pilih Disini--") {
            // Show the "barang-container" if all fields are filled
            document.getElementById('container-all').style.display = 'block';
        }
    }
    document.getElementById('trx_code').addEventListener('change', checkFirstForm);
    document.getElementById('tanggal').addEventListener('change', checkFirstForm);
    document.getElementById('karyawan_id').addEventListener('change', checkFirstForm);
    document.getElementById('penyimpanan_id').addEventListener('change', checkFirstForm);
    document.getElementById('trx_ket').addEventListener('input', checkFirstForm);
    document.getElementById('divisi_id').addEventListener('input', checkFirstForm);


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

    function clearFields() {
        $("#karyawan_id").val('');
        $("#kar_nama").val('');
    }

    // Event listener for input on #kar_idkar
    $("#kar_idkar").on("input", function() {
        clearFields();
    });

    function clearFields2() {
        $("#barang_id").val('');
        $("#brg_min").val('');
        $("#brg_nama").val('');
        $("#gudang_id").val('');
        $("#gud_nama").val('');
        $("#total_stock").val('');
    }

    // Event listener for input on #kar_idkar
    $("#brg_kode").on("input", function() {
        clearFields2();
    });

    function updateTempatOptions() {
        // Get the selected Gudang value
        var selectedPenyimpanan = document.getElementById('penyimpanan_id').value;

        // Get the Tempat dropdown element
        var gudangDropdown = document.getElementById('gudang_id');

        // Clear existing options
        gudangDropdown.innerHTML = '';

        // Fetch Tempat options from the server based on the selected Gudang
        fetch(window.url + '/api/penyimpananout/' + selectedPenyimpanan)
            .then(response => response.json())
            .then(data => {
                // Check if there are options to append
                // if (data.length > 0) {
                //     // Add new options to the Tempat dropdown
                //     data.forEach(function(gudang) {
                //         var option = document.createElement('option');
                //         option.value = gudang.id;
                //         option.text = gudang.gud_nama;
                //         gudangDropdown.appendChild(option);
                //     });
                // }
                $("#brg_kode").autocomplete({
                    // source: ["KD123", "KD321"],
                    source: data.split(','),
                });
                console.log(data);
            })
            .catch(error => console.error('Error fetching penyimpanan data:', error));
    }

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

    var karyawan = $('#karyawan').val().split(',');

    $("#kar_idkar").autocomplete({
        // source: ["KD123", "KD321"],
        source: karyawan,
    });
    $("#kar_idkar").focus(function() {
        $("ui-autocomplete").css("display", "block");
    });
    $("#kar_idkar").focusout(function() {
        $("ui-autocomplete").css("display", "none");
    });



    // autocomplete search
    // var barang = $('#barang').val().split(',');

    // console.log(barang);
    // $("#brg_kode").autocomplete({
    //     // source: ["KD123", "KD321"],
    //     source: barang,
    // });
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
        var brg_kode = $(clone).find('input[name="brg_kode[]"]').val();
        var barang_id = $(clone).find('input[name="barang_id[]"]').val();
        var brg_min = $(clone).find('input[name="brg_min[]"]').val();
        var brg_nama = $(clone).find('input[name="brg_nama[]"]').val();
        var gudang_id = $(clone).find('input[name="gudang_id[]"]').val();
        var gud_nama = $(clone).find('input[name="gud_nama[]"]').val();
        // var gudang_id = $("#gudang_id").val();
        // var gudang_text = $("#gudang_id option:selected").text();
        var dtl_jumlah = $(clone).find('.input-jumlah').val();
        var total_stock = $(clone).find('input[name="total_stock[]"]').val();

        // Check if the field is empty
        if (!brg_kode) {
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Boleh Kosong !',
                text: 'Kode Barang Wajib Di Isi.'
            });
            return;
        }

        if (!brg_nama) {
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
            $("#brg_min").val("");
            $("#brg_kode").val("");
            $("#brg_nama").val("");
            $("#gudang_id").val("");
            $("#gud_nama").val("");
            $("#total_stock").val("");
            $("#dtl_jumlah").val("");
            return;
        }

        if (parseInt(dtl_jumlah) > parseInt(total_stock)) {
            Swal.fire({
                icon: 'error',
                title: 'Input Melebihi Stock !',
                text: "Silahkan tidak mengisi jumlah melebihi stock."
            });
            return;
        }


        if (parseInt(total_stock - dtl_jumlah) < parseInt(brg_min)) {
            // Show the SweetAlert modal
            Swal.fire({
                icon: 'error',
                title: 'Konfirmasi',
                text: 'Stock barang akan habis, yakin ingin melanjutkan ? ',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjut',
                cancelButtonText: 'Batalkan',
                reverseButtons: true
            }).then((result) => {
                console.log(result)
                if (result.value) {
                    // If user clicks "Yes, continue"
                    addedItems.push({
                        barang_id: barang_id,
                        brg_kode: brg_kode,
                        brg_nama: brg_nama,
                        gudang_id: gudang_id,
                        gud_nama: gud_nama,
                        dtl_jumlah: dtl_jumlah
                    });
                    updateTable();
                    resetFields();
                } else {
                    // If user clicks "No, cancel"
                    resetFields();
                }
            });

            document.addEventListener('keydown', function(event) {
                if (keyCode === 13) {
                    // Trigger the confirmation button click
                    Swal.confirmButtonText();
                }
            });
            return;
        }


        // Add the cloned row data to the addedItems array
        addedItems.push({
            barang_id: barang_id,
            brg_kode: brg_kode,
            brg_nama: brg_nama,
            gudang_id: gudang_id,
            gud_nama: gud_nama,
            dtl_jumlah: dtl_jumlah
        });

        // Update the table
        updateTable();
        $("#barang_id").val("");
        $("#brg_min").val("");
        $("#brg_kode").val("");
        $("#brg_nama").val("");
        $("#gudang_id").val("");
        $("#gud_nama").val("");
        $("#total_stock").val("");
        $("#dtl_jumlah").val("");
    }


    function resetFields() {
        // Reset the fields to their initial state
        $("#barang_id").val("");
        $("#brg_min").val("");
        $("#brg_kode").val("");
        $("#brg_nama").val("");
        $("#gudang_id").val("");
        $("#gud_nama").val("");
        $("#total_stock").val("");
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

            // Append a new row to the table
            tbody.append('<tr>' +
                '<td style="font-size: 13px;">' + item.brg_kode + '</td>' +
                '<td style="font-size: 13px;">' + item.brg_nama + '</td>' +
                '<td style="font-size: 13px;">' + item.gud_nama + '</td>' +
                '<td style="font-size: 13px;">' + item.dtl_jumlah + '</td>' +
                '<td style="font-size: 13px;"><button type="button" class="btn btn-danger btn-sm" onclick="removeTableItem(' +
                i +
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
        // document.getElementById('total_barang').value = total_barang;
        document.getElementById('total_barang').innerHTML = total_barang;
    }
</script>
