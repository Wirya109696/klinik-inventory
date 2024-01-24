$(document).ready(function () {
    var lowStockItems = [];
    var alertShown = false;

    var table = $("#tableinventaris").DataTable({
        // serverSide: true,
        pageLength: -1,
        processing: true,
        serverSide: true,
        ajax: window.url + "/transaksibarang/inventaris/json",
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            // { data: "barang_id", name: "barang_id" },
            { data: "brg_kode", name: "brg_kode" },
            { data: "brg_nama", name: "brg_nama" },
            { data: "kat_nama", name: "kat_nama" },
            { data: "dtl_jumlah", name: "dtl_jumlah" },
            { data: "brg_min", name: "brg_min" },
            // { data: "gudang_id", name: "gudang_id" },
            { data: "pen_nama", name: "pen_nama" },
            { data: "gud_nama", name: "gud_nama" },
            // {
            //     data: "action",
            //     name: "action",
            //     orderable: true,
            //     searchable: false,
            // },
        ],
        columnDefs: [
            { targets: [0, 1, 2, 3, 4, 5, 6, 7], className: "text-center" },
        ],
        scrollX: true, // Enable horizontal scrolling if needed
        scrollY: 400,
        initComplete: function () {
            //Filter Kode
            var filterKode = $("#filter_kode_bar");
            filterKode.on("change", function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                table
                    .column(1)
                    .search(val ? "^" + val + "$" : "", true, false)
                    .draw();
            });

            // Add default value and options to filter Kode
            filterKode.append('<option value="">All</option>'); // Default value
            var gudangColumn = table.column(1);
            gudangColumn
                .data()
                .unique()
                .sort()
                .each(function (d, j) {
                    filterKode.append(
                        '<option value="' + d + '">' + d + "</option>"
                    );
                });

            //Filter Nama
            var filterNama = $("#filter_nama_bar");
            filterNama.on("change", function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                table
                    .column(2)
                    .search(val ? "^" + val + "$" : "", true, false)
                    .draw();
            });

            // Add default value and options to filter Nama
            filterNama.append('<option value="">All</option>'); // Default value
            var gudangColumn = table.column(2);
            gudangColumn
                .data()
                .unique()
                .sort()
                .each(function (d, j) {
                    filterNama.append(
                        '<option value="' + d + '">' + d + "</option>"
                    );
                });

            //Filter Kategori
            var filterKategori = $("#filter_kategori");
            filterKategori.on("change", function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                table
                    .column(3)
                    .search(val ? "^" + val + "$" : "", true, false)
                    .draw();
            });

            // Add default value and options to filter Kategori
            filterKategori.append('<option value="">All</option>'); // Default value
            var gudangColumn = table.column(3);
            gudangColumn
                .data()
                .unique()
                .sort()
                .each(function (d, j) {
                    filterKategori.append(
                        '<option value="' + d + '">' + d + "</option>"
                    );
                });

            //Filter Lokasi
            var filterLokasi = $("#filter_lokasi");
            filterLokasi.on("change", function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                table
                    .column(6)
                    .search(val ? "^" + val + "$" : "", true, false)
                    .draw();
            });

            // Add default value and options to filter Lokasi
            filterLokasi.append('<option value="">All</option>'); // Default value
            var lokasiColumn = table.column(6);
            lokasiColumn
                .data()
                .unique()
                .sort()
                .each(function (d, j) {
                    filterLokasi.append(
                        '<option value="' + d + '">' + d + "</option>"
                    );
                });

            // Filter Gudang
            var filterGudang = $("#filter_gudang");
            filterGudang.on("change", function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                table
                    .column(7)
                    .search(val ? "^" + val + "$" : "", true, false)
                    .draw();
            });

            // Add default value and options to filter Gudang
            filterGudang.append('<option value="">All</option>'); // Default value
            var gudangColumn = table.column(7);
            gudangColumn
                .data()
                .unique()
                .sort()
                .each(function (d, j) {
                    filterGudang.append(
                        '<option value="' + d + '">' + d + "</option>"
                    );
                });
        },
        createdRow: function (row, data, dataIndex) {
            // Access data for the current row
            var totalStock = parseFloat(data.dtl_jumlah);
            var brgMin = parseFloat(data.brg_min);

            // Check if total_stock is under brg_min
            if (totalStock < brgMin) {
                // Add a class to the specific cell (td) containing brg_min
                $("td:eq(5)", row).css("color", "red").text(data.brg_min);
                lowStockItems.push({
                    brg_nama: data.brg_nama,
                    gud_nama: data.gud_nama,
                });
            }
        },
        drawCallback: function () {
            if (!alertShown) {
                showLowStockAlert();
                alertShown = true; // Set the flag to true after showing the alert
            }
        },

        // colReorder: true, // Enable Column Reordering
        // dom: "RBlfrtip", // Include ColReorder button in DataTables dom
        // initComplete: function () {
        //     // Initialize the Column Filtering extension
        //     this.api()
        //         .columns()
        //         .every(function (e) {
        //             var column = this;
        //             if (![0, 4, 5, 7].includes(e)) {
        //                 var select = $(
        //                     '<br><select class="form-select form-select-sm text-center"><option value="">All</option></select>'
        //                 )
        //                     .appendTo($(column.header()))
        //                     .on("change", function () {
        //                         var val = $.fn.dataTable.util.escapeRegex(
        //                             $(this).val()
        //                         );
        //                         column
        //                             .search(
        //                                 val ? "^" + val + "$" : "",
        //                                 true,
        //                                 false
        //                             )
        //                             .draw();
        //                     });

        //                 column
        //                     .data()
        //                     .unique()
        //                     .sort()
        //                     .each(function (d, j) {
        //                         select.append(
        //                             '<option value="' +
        //                                 d +
        //                                 '">' +
        //                                 d +
        //                                 "</option>"
        //                         );
        //                     });
        //             }
        //         });
        // },
    });

    function showLowStockAlert() {
        if (lowStockItems.length > 0) {
            // Create a message with information about low-stock items
            var message = "Beberapa stock barang dibawah telah menipis: <br>";
            lowStockItems.forEach(function (item) {
                message += `- ${item.brg_nama} di ${item.gud_nama}<br>`;
            });

            // Display SweetAlert notification
            Swal.fire({
                title: "Stock Menipis !",
                html: message,
                icon: "warning",
            });

            // Clear the array after displaying the alert
            lowStockItems = [];
        }
    }
});
