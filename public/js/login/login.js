$(document).ready(function () {

    $("#btn_login").click(function () {

        var username = $("#username").val();
        var password = $("#password").val();
        var token = $("meta[name='csrf-token']").attr("content");

        $("#btn_loding").removeClass('d-none');
        $("#btn_login").addClass('d-none');

        var kode = 1;
        $.ajax({
            url: window.url + "/store",
            type: "POST",
            dataType: "JSON",
            cache: false,
            data: {
                username: username,
                password: password,
                _token: token,
                kode: kode
            },

            success: function (response) {
                if (response.success) {
                    toastr.success(response.message, '', {
                        timeOut: 1000,
                        onHidden: function () {
                            window.location.href = window.url + '/dashboard';
                        }
                    });
                } else {
                    toastr.error(response.message);
                    $("#btn_loding").addClass('d-none');
                    $("#btn_login").removeClass('d-none');
                }
            },

            error: function (response) {
                $("#btn_loding").addClass('d-none');
                $("#btn_login").removeClass('d-none');
                if(response.responseJSON.errors) {
                    var error_user = response.responseJSON.errors.username;
                    var error_pass = response.responseJSON.errors.password;
                    if (error_user) {
                        toastr.error(error_user);
                    }
                    if (error_pass) {
                        toastr.error(error_pass);
                    }
                }else{
                    toastr.error(response.responseJSON.message);
                }
            },

        });
    });

});
