$(document).ready(function () {
    var table = $("#tablebarangs").DataTable({
        processing: true,
        serverSide: true,
        ajax: window.url + "/transaksibarang/barang/json",
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "brg_kode", name: "brg_kode" },
            { data: "brg_nama", name: "brg_nama" },
            { data: "kat_nama", name: "kat_nama" },
            // { data: "total_stock", name: "total_stock" },
            { data: "brg_satuan", name: "brg_satuan" },
            { data: "brg_min", name: "brg_min" },
            { data: "brg_desc", name: "brg_desc" },
            {
                data: "action",
                name: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [
            { targets: [0, 1, 2, 3, 4, 5, 6], className: "text-center" },
        ],
        // createdRow: function (row, data, dataIndex) {
        //     // Access data for the current row
        //     var totalStock = parseFloat(data.total_stock);
        //     var brgMin = parseFloat(data.brg_min);

        //     // Check if total_stock is under brg_min
        //     if (totalStock < brgMin) {
        //         // Add a class to the specific cell (td) containing brg_min
        //         $("td:eq(6)", row)
        //             .css("color", "red")
        //             .text(data.brg_min + " !");
        //     }
        // },
    });

    $(document).on("click", "#add_btnbrng", function (e) {
        e.preventDefault();
        loadingShow();
        $.ajax({
            method: "GET",
            cache: false,
            url: window.url + "/databarang/barang/create",
        })
            .done(function (view) {
                loadingHide();
                $("#MyModalTitle").html("<b>Add</b>");
                $("div.modal-dialog").addClass("modal-lg");
                $("div#MyModalContent").html(view);
                $(".autocomplete").chosen();
                $("div#MyModalFooter").html(
                    '<button type="submit" class="btn btn-outline-success btn-sm center-block" id="save_add_btn"><i class="bi bi-file-earmark-plus"></i> Save</button>'
                );
                $("div#MyModal").modal({
                    backdrop: "static",
                    keyboard: false,
                });
                $("div#MyModal").modal("show");
            })
            .fail(function (res) {
                loadingHide();
                alert("Error Response !");
                console.log("responseText", res.responseText);
            });
    });

    $(document).on("click", "#save_add_btn", function (e) {
        e.preventDefault();
        loadingShow();
        var brg_kode = $("#brg_kode").val();
        var brg_nama = $("#brg_nama").val();
        var kategori_id = $("#kategori_id").val();
        var brg_satuan = $("#brg_satuan").val();
        var brg_min = $("#brg_min").val();
        var brg_desc = $("#brg_desc").val();
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            method: "POST",
            url: window.url + "/databarang/barang",
            cache: false,
            data: {
                brg_kode: brg_kode,
                brg_nama: brg_nama,
                kategori_id: kategori_id,
                brg_satuan: brg_satuan,
                brg_min: brg_min,
                brg_desc: brg_desc,
                _token: token,
            },
        })
            .done(function (response) {
                loadingHide();
                console.log("halo");
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
                    console.log(values);
                    a;
                } else {
                    notifNo(response.responseJSON.message);
                }
                // console.log("responseText", response.responseText);
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
            url: window.url + "/databarang/barang/" + id + "/edit",
            cache: false,
            data: { id_barang: id },
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

    $(document).on("click", "#save_edit_btn", function (e) {
        e.preventDefault();
        console.log("halo");
        loadingShow();
        var brg_kode_old = $("#brg_kode_old").val();
        //   var brg_nama_old     = $("#brg_nama_old").val();
        var id = $("#id").val();
        var brg_kode = $("#brg_kode").val();
        var brg_nama = $("#brg_nama").val();
        var kategori_id = $("#kategori_id").val();
        var brg_satuan = $("#brg_satuan").val();
        var brg_min = $("#brg_min").val();
        var brg_desc = $("#brg_desc").val();
        var token = $("meta[name='csrf-token']").attr("content");

        $.ajax({
            method: "PUT",
            url: window.url + "/databarang/barang/" + idRow,
            data: {
                // _method : "PUT",
                brg_kode_old: brg_kode_old,
                //   brg_nama_old    : brg_nama_old,
                id: id,
                brg_kode: brg_kode,
                brg_nama: brg_nama,
                kategori_id: kategori_id,
                brg_satuan: brg_satuan,
                brg_min: brg_min,
                brg_desc: brg_desc,
                _token: token,
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

    $(document).on("click", "#delete_btn", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var brg_nama = $(this).attr("data-brg_nama");
        var token = $("meta[name='csrf-token']").attr("content");
        swal({
            title: "You are sure ?",
            text: "Barang " + brg_nama + " will be deleted ?",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, delete !",
            cancelButtonText: "No, cancel !",
        }).then((result) => {
            if (result.value) {
                loadingShow();
                $.ajax({
                    type: "post",
                    url: window.url + "/databarang/barang/" + id,
                    data: {
                        id_user: id,
                        brg_nama: brg_nama,
                        _token: token,
                        _method: "delete",
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
            }
        });
    });
});
