$(document).ready(function () {
    var table = $("#tablecustomers").DataTable({
        processing: true,
        serverSide: true,
        ajax: window.url + "/transaksibarang/karyawan/json",
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "cus_nama", name: "cus_nama" },
            { data: "cus_dept", name: "cus_dept" },
            { data: "cus_location", name: "cus_location" },
            {
                data: "action",
                name: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [{ targets: [0, 1, 2, 3], className: "text-center" }],
    });

    $(document).on("click", "#add_btncus", function (e) {
        e.preventDefault();
        loadingShow();
        $.ajax({
            method: "GET",
            cache: false,
            url: window.url + "/karyawan/create",
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
        var cus_nama = $("#cus_nama").val();
        var cus_dept = $("#cus_dept").val();
        var cus_location = $("#cus_location").val();
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            method: "POST",
            url: window.url + "/karyawan",
            cache: false,
            data: {
                cus_nama: cus_nama,
                cus_dept: cus_dept,
                cus_location: cus_location,
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
        console.log(id);

        idRow = id;
        $.ajax({
            method: "GET",
            url: window.url + "/karyawan/" + id + "/edit",
            cache: false,
            data: { id_customer: id },
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
        var cus_nama_old = $("#cus_nama_old").val();
        var id = $("#id").val();
        var cus_nama = $("#cus_nama").val();
        var cus_dept = $("#cus_dept").val();
        var cus_location = $("#cus_location").val();
        var token = $("meta[name='csrf-token']").attr("content");

        $.ajax({
            method: "PUT",
            url: window.url + "/karyawan/" + idRow,
            data: {
                // _method : "PUT",
                cus_nama_old: cus_nama_old,
                id: id,
                cus_nama: cus_nama,
                cus_dept: cus_dept,
                cus_location: cus_location,
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
        var sup_name = $(this).attr("data-cus_nama");
        var token = $("meta[name='csrf-token']").attr("content");
        swal({
            title: "You are sure ?",
            text: "Pelanggan " + cus_nama + " will be deleted ?",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, delete !",
            cancelButtonText: "No, cancel !",
        }).then((result) => {
            if (result.value) {
                loadingShow();
                $.ajax({
                    type: "post",
                    url: window.url + "/karyawan/" + id,
                    data: {
                        id_user: id,
                        cus_nama: cus_nama,
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
