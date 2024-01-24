$(document).ready(function () {
    var table = $("#tableuser").DataTable({
        processing: true,
        serverSide: true,
        ajax: window.url + "/configuration/users/json",
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            { data: "name", name: "name" },
            { data: "username", name: "username" },
            { data: "email", name: "email" },
            {
                data: "status",
                name: "status",
                orderable: true,
                searchable: true,
            },
            {
                data: "action",
                name: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [{ targets: [0, 1, 2, 3, 4, 5], className: "text-center" }],
    });

    $(document).on("click", "#add_btn", function (e) {
        e.preventDefault();
        loadingShow();
        $.ajax({
            method: "GET",
            cache: false,
            url: window.url + "/configuration/users/create",
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
        var username = $("#username").val();
        var name = $("#name").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        var role_id = $("#role_id").val();
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            method: "POST",
            url: window.url + "/configuration/users",
            cache: false,
            data: {
                username: username,
                name: name,
                email: email,
                role_id: role_id,
                password: password,
                confirm_password: confirm_password,
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

    $(document).on("click", "#edit_btn", function (e) {
        e.preventDefault();
        loadingShow();
        var id = $(this).attr("data-id");
        $.ajax({
            method: "GET",
            url: window.url + "/configuration/users/" + id + "/edit",
            cache: false,
            data: { id_user: id },
        })
            .done(function (view) {
                loadingHide();
                $("#MyModalTitle").html("<b>Edit</b>");
                $("div.modal-dialog").addClass("modal-lg");
                $("div#MyModalContent").html(view);
                $("div#MyModalFooter").html(
                    '<button type="submit" class="btn btn-outline-success btn-sm center-block" id="save_edit_btn">Edit</button>'
                );
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

    $(document).on("click", "#reset_btn", function (e) {
        e.preventDefault();
        loadingShow();
        var id = $(this).attr("data-id");
        $.ajax({
            method: "GET",
            url: window.url + "/configuration/users/" + id + "/reset",
            cache: false,
            data: { id_user: id },
        })
            .done(function (view) {
                loadingHide();
                $("#MyModalTitle").html("<b>Reset Password</b>");
                $("div.modal-dialog").addClass("modal-lg");
                $("div#MyModalContent").html(view);
                $("div#MyModalFooter").html(
                    '<button type="submit" class="btn btn-outline-success btn-sm center-block" id="save_reset_btn">Edit</button>'
                );
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
        loadingShow();
        var id = $("#id").val();
        var username_old = $("#username_old").val();
        var username = $("#username").val();
        var name = $("#name").val();
        var email = $("#email").val();
        var role_id = $("#role_id").val();
        var token = $("meta[name='csrf-token']").attr("content");

        $.ajax({
            method: "PUT",
            url: window.url + "/configuration/users/" + id,
            data: {
                id: id,
                username: username,
                username_old: username_old,
                name: name,
                email: email,
                role_id: role_id,
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

    $(document).on("click", "#save_reset_btn", function (e) {
        e.preventDefault();
        loadingShow();
        var id = $("#id").val();
        var old_password = $("#old_password").val();
        var new_password = $("#new_password").val();
        var confirm_password = $("#confirm_password").val();
        var token = $("meta[name='csrf-token']").attr("content");

        $.ajax({
            method: "POST",
            url: window.url + "/configuration/users/updatePass/" + id,
            data: {
                id: id,
                old_password: old_password,
                new_password: new_password,
                confirm_password: confirm_password,
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
                } else {
                    notifNo(response.responseJSON.message);
                }
                console.log("responseText", response.responseText);
            });
    });

    $(document).on("click", "#delete_btn", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var name = $(this).attr("data-name");
        var token = $("meta[name='csrf-token']").attr("content");
        swal({
            title: "You are sure ?",
            text: "User data " + name + " will be deleted ?",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, delete !",
            cancelButtonText: "No, cancel !",
        }).then((result) => {
            if (result.value) {
                loadingShow();
                $.ajax({
                    type: "post",
                    url: window.url + "/configuration/users/" + id,
                    data: {
                        id_user: id,
                        name: name,
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
