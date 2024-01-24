$(document).ready(function () {
    var table = $("#tablekaryawans").DataTable({
        processing: true,
        serverSide: true,
        ajax: window.url + "/transaksibarang/karyawan/json",
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "kar_idkar", name: "kar_idkar" },
            { data: "kar_nama", name: "kar_nama" },
            // { data: "kar_location", name: "kar_location" },
            { data: "kar_jabatan", name: "kar_jabatan" },
            {
                data: "action",
                name: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [{ targets: [0, 1, 2, 3, 4], className: "text-center" }],
    });

    $(document).on("click", "#add_btnkar", function (e) {
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
        var kar_idkar = $("#kar_idkar").val();
        var kar_nama = $("#kar_nama").val();
        var kar_jabatan = $("#kar_jabatan").val();
        // var kar_location = $("#kar_location").val();
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            method: "POST",
            url: window.url + "/karyawan",
            cache: false,
            data: {
                kar_idkar: kar_idkar,
                kar_nama: kar_nama,
                kar_jabatan: kar_jabatan,
                // kar_location: kar_location,
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
            data: { id_karyawan: id },
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
        var kar_idkar_old = $("#kar_idkar_old").val();
        var id = $("#id").val();
        var kar_idkar = $("#kar_idkar").val();
        var kar_nama = $("#kar_nama").val();
        var kar_jabatan = $("#kar_jabatan").val();
        // var kar_location = $("#kar_location").val();
        var token = $("meta[name='csrf-token']").attr("content");

        $.ajax({
            method: "PUT",
            url: window.url + "/karyawan/" + idRow,
            data: {
                // _method : "PUT",
                kar_idkar_old: kar_idkar_old,
                id: id,
                kar_idkar: kar_idkar,
                kar_nama: kar_nama,
                kar_jabatan: kar_jabatan,
                // kar_location: kar_location,
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
        var kar_nama = $(this).attr("data-kar_nama");
        var token = $("meta[name='csrf-token']").attr("content");
        swal({
            title: "You are sure ?",
            text: "Karyawan " + kar_nama + " will be deleted ?",
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
                        kar_nama: kar_nama,
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
