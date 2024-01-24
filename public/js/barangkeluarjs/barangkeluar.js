$(document).ready(function () {
    var table = $("#tablekeluar").DataTable({
        processing: true,
        serverSide: true,
        ajax: window.url + "/transaksibarang/keluar/json",
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "trx_code", name: "trx_code" },
            { data: "kar_nama", name: "kar_nama" },
            // { data: "kar_location", name: "kar_location" },
            {
                data: "tanggal",
                name: "tanggal",
            },
            { data: "total_barang", name: "total_barang" },
            { data: "status", nama: "status" },
            {
                data: "action",
                name: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [
            { targets: [0, 1, 2, 3, 4, 5, 6], className: "text-center" },
            {
                targets: [5], // Target the 'status' column
                createdCell: function (td, cellData, rowData, row, col) {
                    var status = rowData.status;

                    // Add a class to the cell based on the 'status' value
                    if (status === "Ready") {
                        $(td).html(
                            '<span class="badge bg-label-success rounded-pill d-inline">' +
                                status +
                                "</span>"
                        );
                    } else if (status === "Cancel") {
                        $(td).html(
                            '<span class="badge bg-label-danger rounded-pill d-inline">' +
                                status +
                                "</span>"
                        );
                    }
                },
            },
        ],
    });

    $(document).on("click", "#add_btnkeluar", function (e) {
        e.preventDefault();
        loadingShow();

        $.ajax({
            method: "GET",
            cache: false,
            url: window.url + "/transaksibarang/keluar/create",
        })
            .done(function (view) {
                loadingHide();

                $("#MyModalTitle").html("<b>Add</b>");
                $("div.modal-dialog").addClass("modal-xl");
                $("div#MyModalContent").html(view);
                // $("div#MyModalHeader").html("hide");
                $("div#MyModalFooter").html(
                    '<button type="button" class="btn btn-outline-success btn-sm center-block" id="save_add_btn"><i class="bi bi-file-earmark-plus"></i> Save</button>'
                );
                $("div#MyModal").modal({
                    backdrop: "static",
                    keyboard: false,
                });
                $(".autocomplete").chosen();
                $("div#MyModal").modal("show");

                // $(document).on(
                //     "keydown",
                //     "#MyModal .modal-content",
                //     function (e) {
                //         if (e.key === "Enter") {
                //             e.preventDefault();
                //             // Add your custom logic here if needed
                //         }
                //     }
                // );

                // // Event listener for preventing SweetAlert modal closure when pressing "Enter"
                // $(document).on("keydown", ".swal-modal", function (e) {
                //     if (e.key === "Enter") {
                //         e.preventDefault();
                //         // Add your custom logic here if needed
                //     }
                // });
                // $("#MyModal").on("keydown", function (e) {
                //     // Check if the pressed key is "Enter"
                //     if (e.key === "Enter") {
                //         // Prevent the default action (closing the modal)
                //         e.preventDefault();
                //     }
                // });

                $(document).ready(function () {
                    $("#kar_idkar").keydown(function (event) {
                        console.log("changesx");

                        // Get the current value of 'brg_kode'
                        var idKaryawanValue = $("#kar_idkar").val();

                        if (event.keyCode === 13) {
                            idkaryawan = idKaryawanValue.split("(")[0];
                            $.ajax({
                                method: "GET",
                                url: window.url + "/api/karyawan-autocomplete",
                                data: {
                                    term: idkaryawan,
                                },
                                success: function (datay) {
                                    if (datay && datay.karyawan_id) {
                                        // Data found
                                        $("#karyawan_id").val(
                                            datay.karyawan_id
                                        );
                                        $("#kar_idkar").val(datay.kar_idkar);
                                        $("#kar_nama").val(datay.kar_nama);
                                        // $("#kar_location").val(datay.kar_location);
                                    }
                                },
                                error: function () {
                                    // Add SweetAlert alert for other errors
                                    Swal.fire({
                                        icon: "error",
                                        title: "Karyawan Tidak Ditemukan!",
                                        text: "Silahkan Tambah Data Karyawan.",
                                        showCancelButton: true,
                                        confirmButtonText: "Tambah Data Baru",
                                        cancelButtonText: "Cancel",
                                    }).then((result) => {
                                        if (result.value) {
                                            // Redirect to add new data URL
                                            window.open(
                                                window.url + "/karyawan/",
                                                "_blank"
                                            );
                                        } else {
                                            // Handle cancel transaction
                                            $("#karyawan_id").val("");
                                            $("#kar_idkar").val("");
                                            $("#kar_nama").val("");
                                            $("#karyawan_id").focus("");
                                            // You can add your logic here or remove this block if not needed
                                            console.log("Transaction canceled");
                                        }
                                    });
                                },
                            });
                        }
                    });
                });
                // "88105236, Wirya, Klinik 2.88105236, Wirya, Klinik 2.88105236, Wirya, Klinik 2.88105236, Wirya, Klinik 2."

                // $(document).ready(function () {
                //     $("#brg_kode").autocomplete({
                //         source: function (request, response) {
                //             var getPenyimpanan = $("#penyimpanan_id").val();
                //             var getGudang = $("#gudang_id").val();

                //             $.ajax({
                //                 method: "GET",
                //                 url: window.url + "/api/keluar-autocomplete",
                //                 data: {
                //                     term: request.term,
                //                     gudang: getGudang,
                //                     penyimpanan: getPenyimpanan,
                //                 },
                //                 success: function (data) {
                //                     var formattedResults = [];

                //                     $.each(data, function (index, item) {
                //                         var formattedItem =
                //                             item.brg_kode +
                //                             " (" +
                //                             item.brg_nama +
                //                             ") (" +
                //                             item.location +
                //                             ") (" +
                //                             item.total_stock +
                //                             ")";
                //                         formattedResults.push({
                //                             label: formattedItem,
                //                             value: item.brg_kode,
                //                             id: item.id,
                //                             brg_min: item.brg_min,
                //                             brg_kode: item.brg_kode,
                //                             brg_nama: item.brg_nama,
                //                             total_stock: item.total_stock,
                //                             gudang_id: item.gudang_id,
                //                         });
                //                     });

                //                     response(formattedResults);
                //                 },
                //                 error: function () {
                //                     response([]);
                //                 },
                //             });
                //         },
                //         select: function (event, ui) {
                //             if (ui && ui.item) {
                //                 $("#barang_id").val(ui.item.id);
                //                 $("#brg_min").val(ui.item.brg_min);
                //                 $("#brg_kode").val(ui.item.brg_kode);
                //                 $("#brg_nama").val(ui.item.brg_nama);
                //                 $("#total_stock").val(ui.item.total_stock);
                //                 $("#gudang_id").val(ui.item.gudang_id);
                //                 $("#dtl_jumlah").focus();
                //             }
                //             return false;
                //         },
                //     });

                //     // ... existing code ...
                // });
                $(document).ready(function () {
                    $("#brg_kode").keydown(function () {
                        console.log("changes");
                        // Get the current value of 'brg_kode'
                        var kodeBarangValue = $("#brg_kode").val();
                        var getPenyimpanan = $("#penyimpanan_id").val();
                        var getGudang = $("#gudang_id").val();
                        if (event.keyCode === 13) {
                            kodebarang = kodeBarangValue.split("(")[0];
                            getGudang = kodeBarangValue
                                .split("(")[2]
                                .slice(0, -1);
                            console.log("getGudang", getGudang);
                            $.ajax({
                                method: "GET",
                                url: window.url + "/api/keluar-autocomplete",
                                data: {
                                    term: kodebarang,
                                    gudang: getGudang,
                                    penyimpanan: getPenyimpanan,
                                },
                                success: function (datax) {
                                    $("#barang_id").val(datax.barang_id);
                                    $("#brg_min").val(datax.brg_min);
                                    $("#brg_kode").val(datax.brg_kode);
                                    $("#brg_nama").val(datax.brg_nama);
                                    $("#gudang_id").val(datax.gudang_id);
                                    $("#gud_nama").val(datax.gud_nama);
                                    $("#total_stock").val(datax.total_stock);
                                    $("#dtl_jumlah").focus();
                                },
                                error: function () {
                                    // Add SweetAlert alert for other errors
                                    $("#barang_id").val("");
                                    $("#brg_min").val("");
                                    $("#brg_kode").val("");
                                    $("#brg_nama").val("");
                                    $("#gudang_id").val("");
                                    $("#gud_nama").val("");
                                    $("#total_stock").val("");
                                    $("#dtl_jumlah").val(""); // Assuming dtl_jumlah is the field you want to clear
                                    $("#brg_kode").focus();
                                    Swal.fire({
                                        icon: "error",
                                        title: "Barang Tidak Ditemukan!",
                                        text: "Silahkan Pilih Lokasi & Gudang Sesuai Penyimpanan Barang.",
                                    });
                                },
                            });
                        }
                    });
                });

                // $(document).ready(function () {
                //     // Store the initial value of the ID Karyawan field
                //     var initialIdKaryawanValue = $("#kar_idkar").val();

                //     // Listen for changes in the ID Karyawan field
                //     $("#kar_idkar").on("input", function () {
                //         // Check if the length of the ID Karyawan field has changed
                //         if (
                //             $(this).val().length !==
                //             initialIdKaryawanValue.length
                //         ) {
                //             // If there is a change, clear the value in the Nama field
                //             $("#karyawan_id").val("");
                //             $("#kar_nama").val("");
                //         }
                //     });
                // });
            })
            .fail(function (res) {
                loadingHide();
                alert("Error Response!");
                console.log("responseText", res.responseText);
            });
    });

    // Modify the click event for the save button
    $(document).on("click", "#save_add_btn", function (e) {
        e.preventDefault();
        loadingShow();

        // Extracting data from the form
        // var formData = {

        // };

        // Send the AJAX request with the extracted data
        $.ajax({
            method: "POST",
            url: window.url + "/transaksibarang/keluar",
            cache: false,
            contenttype: "application/json", // Set content type
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                trx_code: $("#trx_code").val(),
                karyawan_id: $("#karyawan_id").val(),
                divisi_id: $("#divisi_id").val(),
                tanggal: $("#tanggal").val(),
                total_barang: $("#total_barang").text(),
                trx_ket: $("#trx_ket").val(),
                items: addedItems, // Use the addedItems array from your Blade view
                // _token: $("meta[name='csrf-token']").attr("content"),
                type: "Keluar", // Set the type directly if it's constant
            }, // Convert data to JSON
        })
            .done(function (response) {
                loadingHide();
                if (response.success) {
                    $("div#MyModal").modal("hide");
                    notifYesAuto(response.message);
                    table.ajax.reload();
                } else {
                    notifNo(response.message);
                }
            })
            .fail(function (response) {
                loadingHide();
                if (response.responseJSON.errors) {
                    var values = "";
                    jQuery.each(
                        response.responseJSON.errors,
                        function (key, value) {
                            values += value + "<br>";
                        }
                    );
                    notifNo(values);
                } else {
                    notifNo(response.responseJSON.message);
                }
            });
    });

    var idRow = null;

    $(document).on("click", "#edit_btn", function (e) {
        e.preventDefault();
        loadingShow();
        var id = $(this).attr("data-id");

        idRow = id;
        $.ajax({
            method: "GET",
            url: window.url + "/transaksibarang/keluar/" + id + "/edit",
            cache: false,
            data: { transaksi_id: id },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        })
            .done(function (view) {
                loadingHide();
                $("#MyModalTitle").html("<b>Edit</b>");
                $("div.modal-dialog").addClass("modal-xl");
                $("div#MyModalContent").html(view);
                $("div#MyModalFooter").html(
                    '<button type="submit" class="btn btn-outline-success btn-sm center-block" id="save_edit_btn">Edit</button>'
                );
                $("div#MyModal").modal({
                    backdrop: "static",
                    keyboard: false,
                });
                $("div#MyModal").modal("show");
                // $(".autocomplete").chosen();
                $(document).ready(function (event) {
                    $("#kar_nama").keydown(function () {
                        // console.log("changes");
                        // Get the current value of 'brg_kode'
                        var namaPelangganValue = $("#kar_nama").val();
                        console.log(namaPelangganValue);

                        if (event.keyCode === 13) {
                            namapelanggan = namaPelangganValue.split(",")[0];
                            $.ajax({
                                method: "GET",
                                url:
                                    window.url +
                                    "/api/karyawanedit-autocomplete",
                                data: {
                                    term: namapelanggan,
                                },
                                success: function (datay) {
                                    $("#karyawan_id").val(
                                        datay.dataTransaksi.karyawan_id
                                    );
                                    $("#kar_nama").val(
                                        datay.dataTransaksi.kar_nama
                                    );
                                    $("#kar_jabatan").val(
                                        datay.dataTransaksi.kar_jabatan
                                    );
                                },
                            });
                        }
                    });
                });
            })
            .fail(function (response) {
                loadingHide();
                if (response.responseJSON.errors) {
                    var values = "";
                    jQuery.each(
                        response.responseJSON.errors,
                        function (key, value) {
                            values += value + "<br>";
                        }
                    );
                    notifNo(values);
                }
                console.log("responseText", response.responseText);
            });
    });

    $(document).on("click", "#save_edit_btn", function (e) {
        e.preventDefault();
        console.log("nama saya roni");
        loadingShow();

        var arr_dtl_jumlah = $("input[name='dtl_jumlah[]']")
            .map(function () {
                return $(this).val();
            })
            .get();

        var arr_id_detail = $("input[name='id_detail[]']")
            .map(function () {
                return $(this).val();
            })
            .get();

        $.ajax({
            method: "PUT",
            url: window.url + "/transaksibarang/keluar/" + idRow,
            data: {
                id: arr_id_detail,
                jumlah: arr_dtl_jumlah,
                trx_code: $("#trx_code").val(),
                divisi_id: $("#divisi_id").val(),
                karyawan_id: $("#karyawan_id").val(),
                tanggal: $("#tanggal").val(),
                total_barang: $("#total_barang").text(),
                trx_ket: $("#trx_ket").val(),
            },
            // contenttype: "application/json", // Add this line
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        })
            .done(function (response) {
                loadingHide();
                if (response.success) {
                    $("div#MyModal").modal("hide");
                    notifYesAuto(response.message);
                    table.ajax.reload();
                } else {
                    notifNo(response.message);
                }
            })
            .fail(function (response) {
                loadingHide();
                if (response.responseJSON.errors) {
                    var values = "";
                    jQuery.each(
                        response.responseJSON.errors,
                        function (key, value) {
                            values += value + "<br>";
                        }
                    );
                    notifNo(values);
                }
                console.log("responseText", response.responseText);
            });
    });

    $(document).on("click", "#detailkeluar_btn", function (e) {
        e.preventDefault();
        loadingShow();
        var id = $(this).attr("data-id");

        idRow = id;
        $.ajax({
            method: "GET",
            url: window.url + "/transaksibarang/keluar/" + id,
            cache: false,
            data: { transaksi_id: id },
        })
            .done(function (view) {
                loadingHide();
                $("#MyModalTitle").html("<b>Detail</b>");
                $("div.modal-dialog").addClass("modal-xl");
                $("div#MyModalContent").html(view);
                $("div#MyModal").modal({
                    backdrop: "static",
                    keyboard: false,
                });
                $("div#MyModal").modal("show");
                $(".autocomplete").chosen();
            })
            .fail(function (response) {
                loadingHide();
                if (response.responseJSON.errors) {
                    var values = "";
                    jQuery.each(
                        response.responseJSON.errors,
                        function (key, value) {
                            values += value + "<br>";
                        }
                    );
                    notifNo(values);
                }
                console.log("responseText", response.responseText);
            });
    });

    $(document).on("click", "#cancel_btn", function (e) {
        var id = $(this).attr("data-id");
        var trx_code = $(this).attr("data-trx_code");
        var token = $("meta[name='csrf-token']").attr("content");

        swal({
            title: "Are you sure?",
            text: "Transaction " + trx_code + " will be cancelled?",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, Do It!",
            cancelButtonText: "No, Forget It!",
        }).then((result) => {
            if (result.value) {
                loadingShow();
                $.ajax({
                    type: "PUT",
                    url:
                        window.url +
                        "/transaksibarang/keluar/" +
                        id +
                        "/cancel",
                    data: {
                        _token: token,
                    },
                })
                    .done(function (response) {
                        loadingHide();
                        if (response.success) {
                            notifYesAuto(response.message);
                            table.ajax.reload();
                        } else {
                            notifNo(response.message);
                        }
                    })
                    .fail(function (response) {
                        loadingHide();
                        if (response.responseJSON.errors) {
                            var values = "";
                            jQuery.each(
                                response.responseJSON.errors,
                                function (key, value) {
                                    values += value + "<br>";
                                }
                            );
                            notifNo(values);
                        }
                        console.log("responseText", response.responseText);
                    });
            }
        });
    });

    // $(document).on("click", "#delete_btn", function (e) {
    // e.preventDefault();
    // var id    = $(this).attr("data-id");
    // var sup_name  = $(this).attr("data-sup_name");
    // var token = $("meta[name='csrf-token']").attr("content");
    // swal({
    //     title: "You are sure ?",
    //     text: "Agen Supplier " + sup_name + " will be deleted ?",
    //     type: "question",
    //     showCancelButton: true,
    //     confirmButtonText: "Yes, delete !",
    //     cancelButtonText: "No, cancel !",
    // }).then((result) => {
    //     if (result.value) {
    //       loadingShow();
    //     $.ajax({
    //       type: "post",
    //       url :  window.url + "/supplier/"+ id,
    //       data: {
    //         id_user         : id,
    //         sup_name   : sup_name,
    //         _token          : token,
    //         '_method': 'delete'
    //         },
    //     })
    //         .done(function (response) {
    //           loadingHide();
    //         if (response.success) {
    //             $("div#MyModal").modal("hide");
    //             notifYesAuto(response.message);
    //             table.ajax.reload();
    //           }else{
    //               notifNo(response.message);
    //           }
    //         })
    //         .fail(function (response) {
    //           loadingHide();
    //           if(response.responseJSON.errors){
    //             var values = '';
    //             jQuery.each(response.responseJSON.errors, function (key, value) {
    //                 values += value + "<br>"
    //             });
    //             notifNo(values);
    //         }
    //             console.log("responseText", response.responseText);
    //             });
    //         }
    //     });
    // });
});
