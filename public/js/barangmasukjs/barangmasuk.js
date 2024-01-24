$(document).ready(function () {
    var table = $("#tablemasuk").DataTable({
        processing: true,
        serverSide: true,
        ajax: window.url + "/transaksibarang/masuk/json",
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "trx_code", name: "trx_code" },
            { data: "sup_name", name: "sup_name" },
            { data: "tanggal", name: "tanggal" },
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
            { targets: [0, 1, 2, 3, 4, 5], className: "text-center" },
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

    $(document).on("click", "#add_btnmasuk", function (e) {
        e.preventDefault();
        loadingShow();

        $.ajax({
            method: "GET",
            cache: false,
            url: window.url + "/transaksibarang/masuk/create",
        })
            .done(function (view) {
                loadingHide();
                $("#MyModalTitle").html("<b>Add</b>");
                $("div.modal-dialog").addClass("modal-lg");
                $("div#MyModalContent").html(view);
                $("div#MyModalFooter").html(
                    '<button type="button" class="btn btn-outline-success btn-sm" id="save_add_btn" style="display: flex; justify-content: flex-start; align-items: center;"><i class="bi bi-file-earmark-plus"></i> Save</button>'
                );
                $("div#MyModal").modal({
                    backdrop: "static",
                    keyboard: false,
                });
                $("div#MyModal").modal("show");

                $(document).ready(function () {
                    $("#brg_kode").keydown(function () {
                        console.log("changes");
                        // Get the current value of 'brg_kode'
                        var kodeBarangValue = $("#brg_kode").val();

                        if (event.keyCode === 13) {
                            kodebarang = kodeBarangValue.split("(")[0];
                            $.ajax({
                                method: "GET",
                                url: window.url + "/api/masuk-autocomplete",
                                data: {
                                    term: kodebarang,
                                },
                                success: function (datax) {
                                    $("#barang_id").val(datax.barang_id);
                                    $("#brg_kode").val(datax.brg_kode);
                                    $("#brg_nama").val(datax.brg_nama);

                                    $("#dtl_jumlah").focus();
                                },
                            });
                        }
                    });
                });
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

        // Send the AJAX request with the extracted data
        $.ajax({
            method: "POST",
            url: window.url + "/transaksibarang/masuk",
            cache: false,
            contenttype: "application/json", // Set content type
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                trx_code: $("#trx_code").val(),
                supplier_id: $("#supplier_id").val(),
                tanggal: $("#tanggal").val(),
                total_barang: $("#total_barang").text(),
                trx_ket: $("#trx_ket").val(),
                items: addedItems, // Use the addedItems array from your Blade view
                type: "Masuk",
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
            url: window.url + "/transaksibarang/masuk/" + id + "/edit",
            cache: false,
            data: { transaksi_id: id },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        })
            .done(function (view) {
                loadingHide();
                $("#MyModalTitle").html("<b>Edit</b>");
                $("div.modal-dialog").addClass("modal-lg");
                $("div#MyModalContent").html(view);
                $("div#MyModalFooter").html(
                    '<button type="submit" class="btn btn-outline-success btn-sm center-block" id="save_edit_btn">Edit</button>'
                );
                $("div#MyModal").modal({
                    backdrop: "static",
                    keyboard: false,
                });
                $("div#MyModal").modal("show");
                $(document).ready(function () {
                    $("#brg_kode").keydown(function () {
                        console.log("changes");
                        // Get the current value of 'brg_kode'
                        var kodeBarangValue = $("#brg_kode").val();

                        if (event.keyCode === 13) {
                            kodebarang = kodeBarangValue.split("(")[0];
                            $.ajax({
                                method: "GET",
                                url: window.url + "/api/masuk-autocomplete",
                                data: {
                                    term: kodebarang,
                                },
                                success: function (datax) {
                                    $("#barang_id").val(datax.barang_id);
                                    $("#brg_kode").val(datax.brg_kode);
                                    $("#brg_nama").val(datax.brg_nama);

                                    $("#dtl_jumlah").focus();
                                },
                            });
                        }
                    });
                });
                // $(".autocomplete").chosen();
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
        console.log("halo");
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
            url: window.url + "/transaksibarang/masuk/" + idRow,
            cache: false,
            data: {
                id: arr_id_detail,
                jumlah: arr_dtl_jumlah,
                trx_code: $("#trx_code").val(),
                supplier_id: $("#supplier_id").val(),
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

    $(document).on("click", "#detailmasuk_btn", function (e) {
        e.preventDefault();
        loadingShow();
        var id = $(this).attr("data-id");

        idRow = id;
        $.ajax({
            method: "GET",
            url: window.url + "/transaksibarang/masuk/" + id,
            cache: false,
            data: { transaksi_id: id },
        })
            .done(function (view) {
                loadingHide();
                $("#MyModalTitle").html("<b>Detail</b>");
                $("div.modal-dialog").addClass("modal-lg");
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
                        window.url + "/transaksibarang/masuk/" + id + "/cancel",
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
