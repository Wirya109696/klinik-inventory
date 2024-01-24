<script src="https://bossanova.uk/jspreadsheet/v4/jexcel.js"></script>
<script src="https://jsuites.net/v4/jsuites.js"></script>
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />
<link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v4/jexcel.css" type="text/css" /><div class="row">
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label">Jenis Pengajuan </label>
        </div>
        <div class="form-group">
            <select name="type" id="type">
                <option value="Masuk" selected>Barang Masuk</option>
                <option value="Keluar">Barang Keluar</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Tanggal </label>
            <input class="form-control form-control-sm" type="date" id="tanggal">
        </div>
        <div class="form-group">
            <label class="form-label">Total Barang </label>
            <input class="form-control form-control-sm" type="text" id="total_barang">
        </div>
        <div class="form-group">
            <label class="form-label">Data Barang </label>
            <div id="spreadsheet" class="form-control form-control-sm"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var hot = jspreadsheet(document.getElementById('spreadsheet'), {

            url :window.url + "/detailTransaksi",
            filters : true,
            columns : [
                // { type: 'Text',    title:'Nama Barang', width:300 },
                { type: 'numeric', title:'Jumlah Barang', width:120 },
            ],
        });

        // SAVE DATA NEW & EDIT JSPREADSHEET

        function saveData() {

            var data =   JSON.stringify(hot.getData());
            // console.log(data);
            // return false;
            var validationErrors = [];
            var uniqueEmails = new Set();
            var uniqueNames = new Set();

            hot.getData().forEach(function(row) {
                if (!row[0] || !row[1] || !row[2]) {
                    validationErrors.push('Name, Email, and Alamat Tidak Boleh Kosong.');
                }

                // Check for duplicate name
                if (row[0] && uniqueNames.has(row[0])) {
                    validationErrors.push('Name sudah digunakan. Duplikasi name ditemukan: ' + row[0]);
                } else {
                    uniqueNames.add(row[0]);
                }

                // Check for duplicate emails
                if (row[1] && uniqueEmails.has(row[1])) {
                    validationErrors.push('Email sudah digunakan. Duplikasi email ditemukan: ' + row[1]);
                } else {
                    uniqueEmails.add(row[1]);
                }
            });

            if (validationErrors.length > 0) {
                alert('Validation Errors:\n' + validationErrors.join('\n'));
                return;
            }

            $.ajax({
                url: window.url + "/dataKaryawanup" ,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    data: data
                },
                success: function(response) {
                    console.log(response);

                    if (response.status) {
                        // Jika status true, artinya update data berhasil
                        alert(response.notif);

                        // Reset checkbox selection
                        hot.uncheckAll();
                    } else {
                        // Jika status false, artinya terdapat kesalahan
                        alert('Terjadi kesalahan: ' + response.notif);
                    }
                },
                error: function(error) {
                    console.log(error);
                    alert('Terjadi kesalahan saat menyimpan data.');
                }
            });
        }

         // Tambahkan event click pada tombol simpan
         $('#sbmit_btn').on('click', function() {
            alert('');
            saveData();
        });

        //DELETE DATA JSPREADSHEET

        function deleteSelectedData() {
            var idsToDelete = [];

            hot.getData().forEach(function (row) {
                if (row[4] === true) { // Assuming checkbox is in the 5th column (index 4)
                    idsToDelete.push(row[3]); // Assuming hidden ID is in the 4th column (index 3)
                }
            });

            if (idsToDelete.length === 0) {
                alert('Pilih setidaknya satu baris untuk dihapus.');
                return;
            }

            // Kirim permintaan penghapusan ke server
            $.ajax({
                url: window.url + '/dataKaryawandel',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    idsToDelete: idsToDelete
                },
                success: function (response) {
                    console.log(response);
                    alert('Data berhasil dihapus!')

                    // Hapus baris dari JSpreadsheet setelah penghapusan berhasil
                    idsToDelete.forEach(function (idToDelete) {
                        hot.deleteRow(idToDelete); // Assuming you can delete by row ID
                    });
                },
                error: function (error) {
                    console.error('Error deleting data:', error);
                }
            });
        }


        $('#delet_btn').on('click', function(){
            deleteSelectedData();
        });
    });

</script>
