$(document).ready(function () {
    var table = $("#tablepenyimpanans").DataTable({
        processing: true,
        serverSide: true,
        ajax: window.url + "/transaksibarang/penyimpanan/json",
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "pen_nama", name: "pen_nama" },
            // { data: "gud_nama", name: "gud_nama" },
            { data: "pen_desc", name: "pen_desc" },
            {
                data: "action",
                name: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [{ targets: [0, 1, 2, 3], className: "text-center" }],
    });

    $(document).on("click", "#add_btnsimpan", function (e) {
        e.preventDefault();
        loadingShow();
        $.ajax({
            method: "GET",
            cache: false,
            url: window.url + "/lokasiinventori/penyimpanan/create",
        })
            .done(function (view) {
                loadingHide();
                $("#MyModalTitle").html("<b>Add</b>");
                $("div.modal-dialog").addClass("modal-sm");
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
        var pen_nama = $("#pen_nama").val();
        var gudang_id = $("#gudang_id").val();
        var pen_desc = $("#pen_desc").val();
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            method: "POST",
            url: window.url + "/lokasiinventori/penyimpanan",
            cache: false,
            data: {
                pen_nama: pen_nama,
                gudang_id: gudang_id,
                pen_desc: pen_desc,
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
                } else {
                    notifNo(response.responseJSON.message);
                }
                console.log("responseText", response.responseText);
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
            url: window.url + "/lokasiinventori/penyimpanan/" + id + "/edit",
            cache: false,
            data: { id_penyimpanan: id },
        })
            .done(function (view) {
                loadingHide();
                $("#MyModalTitle").html("<b>Edit</b>");
                $("div.modal-dialog").addClass("modal-sm");
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
        var pen_nama_old = $("#pen_nama_old").val();
        //   var brg_nama_old     = $("#brg_nama_old").val();
        var id = $("#id").val();
        var pen_nama = $("#pen_nama").val();
        var gudang_id = $("#gudang_id").val();
        var pen_desc = $("#pen_desc").val();
        var token = $("meta[name='csrf-token']").attr("content");

        $.ajax({
            method: "PUT",
            url: window.url + "/lokasiinventori/penyimpanan/" + idRow,
            data: {
                // _method : "PUT",
                pen_nama_old: pen_nama_old,
                //   brg_nama_old    : brg_nama_old,
                id: id,
                pen_nama: pen_nama,
                gudang_id: gudang_id,
                pen_desc: pen_desc,
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
        var pen_nama = $(this).attr("data-pen_nama");
        var token = $("meta[name='csrf-token']").attr("content");
        swal({
            title: "You are sure ?",
            text: "Tempat " + pen_nama + " will be deleted ?",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, delete !",
            cancelButtonText: "No, cancel !",
        }).then((result) => {
            if (result.value) {
                loadingShow();
                $.ajax({
                    type: "post",
                    url: window.url + "/lokasiinventori/penyimpanan/" + id,
                    data: {
                        id_user: id,
                        pen_nama: pen_nama,
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
