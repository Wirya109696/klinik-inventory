$(document).ready(function () {
    var table = $("#tablesuppliers").DataTable({
        processing: true,
        serverSide: true,
        ajax: window.url + "/transaksibarang/supplier/json",
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "sup_name", name: "sup_name" },
            { data: "sup_phone", name: "sup_phone" },
            { data: "sup_addres", name: "sup_addres" },
            {
                data: "action",
                name: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [{ targets: [0, 1, 2, 3, 4], className: "text-center" }],
    });

    $(document).on("click", "#add_btnsplier", function (e) {
        e.preventDefault();
        loadingShow();
        $.ajax({
            method: "GET",
            cache: false,
            url: window.url + "/supplier/create",
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
        console.log("ini fungsinya");
        e.preventDefault();
        loadingShow();
        var sup_name = $("#sup_name").val();
        var sup_phone = $("#sup_phone").val();
        var sup_addres = $("#sup_addres").val();
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            method: "POST",
            url: window.url + "/supplier",
            cache: false,
            data: {
                sup_name: sup_name,
                sup_phone: sup_phone,
                sup_addres: sup_addres,
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
        // console.log(id);

        idRow = id;
        $.ajax({
            method: "GET",
            url: window.url + "/supplier/" + id + "/edit",
            cache: false,
            data: { id_supplier: id },
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
        var sup_name_old = $("#sup_name_old").val();
        var id = $("#id").val();
        var sup_name = $("#sup_name").val();
        var sup_phone = $("#sup_phone").val();
        var sup_addres = $("#sup_addres").val();
        var token = $("meta[name='csrf-token']").attr("content");

        $.ajax({
            method: "PUT",
            url: window.url + "/supplier/" + idRow,
            data: {
                // _method : "PUT",
                sup_name_old: sup_name_old,
                id: id,
                sup_name: sup_name,
                sup_phone: sup_phone,
                sup_addres: sup_addres,
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
        var sup_name = $(this).attr("data-sup_name");
        var token = $("meta[name='csrf-token']").attr("content");
        swal({
            title: "You are sure ?",
            text: "Agen Supplier " + sup_name + " will be deleted ?",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, delete !",
            cancelButtonText: "No, cancel !",
        }).then((result) => {
            if (result.value) {
                loadingShow();
                $.ajax({
                    type: "post",
                    url: window.url + "/supplier/" + id,
                    data: {
                        id_user: id,
                        sup_name: sup_name,
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
