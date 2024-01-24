$(document).ready(function () {
    var table = $('#table_permission').DataTable({
        processing: true,
        serverSide: true,
        ajax:  window.url + "/configuration/permissions/json",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'menu_name', name: 'menu_name'},
            {data: 'icon', name: 'icon'},
            {data: 'route_name', name: 'route_name'},
            {data: 'order_line', name: 'order_line'},
            {data: 'index', name: 'index'},
            {data: 'store', name: 'store'},
            {data: 'edits', name: 'edits'},
            {data: 'erase', name: 'erase'},
            {
                data: 'action',
                name: 'action',
                orderable: true,
                searchable: true
            },
        ],
        columnDefs: [
            { targets: [0, 1, 2,3,4,5,6,7,8,9], className: "text-center" },
          ],
    });

    $(document).on('click','#add_btn',function(e){
		e.preventDefault();
        loadingShow();
		$.ajax({
			method:"GET",
			cache:false,
			url:  window.url + '/configuration/permissions/create'
		})
		.done(function(view) {
            loadingHide();
			$('#MyModalTitle').html('<b>Add</b>');
			$('div.modal-dialog').addClass('modal-lg');
			$("div#MyModalContent").html(view);
            iconpicker();
            $('.select').selectpicker();
            $("#is_routeY").click(function() {
                if(this.checked) {
                    $("#hd_route_name").removeClass('d-none');
                }
            });

            $("#is_routeN").click(function() {
                if(this.checked) {
                    $("#hd_route_name").addClass('d-none');
                }
            });
          
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-outline-success btn-sm center-block" id="save_add_btn"><i class="bi bi-file-earmark-plus"></i> Save</button>');
			$("div#MyModal").modal('show');
		})
		.fail(function(res){
            loadingHide();
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

    $(document).on("click", "#save_add_btn", function (e) {
    	e.preventDefault();
        loadingShow();
        var menu_name  = $("#menu_name").val();
        var route_name = $("#route_name").val();
        var icon       = $("#icon").val();
        var order_line = $("#order_line").val();
        var role       = $("#role").val();
        var parent_id  = $("#parent_id").val();
        var index      = $("#index").is(":checked");
        var create     = $("#create").is(":checked");
        var edit       = $("#edit").is(":checked");
        var erase      = $("#erase").is(":checked");
        var token      = $("meta[name='csrf-token']").attr("content");

        var is_routeY  = $("#is_routeY").is(":checked");
        var has_childY = $("#has_childY").is(":checked");
        
	    $.ajax({
		    method: "POST",
		    url:  window.url + '/configuration/permissions',
		    cache: false,
		    data: {
		        menu_name : menu_name,
		        route_name: route_name,
		        icon      : icon,
		        order_line: order_line,
		        role      : role,
		        parent_id : parent_id,
		        is_route  : is_routeY,
		        has_child : has_childY,
		        index     : index,
		        create    : create,
		        edit      : edit,
		        erase     : erase,
		        _token    : token,
		      },
	    })
      	.done(function (response) {
              loadingHide();
	        if (response.success) {
                $("div#MyModal").modal("hide");
                notifYesAuto(response.message);
                table.ajax.reload();
	        }else{
                notifNo(response.message);
            }
      	})
        .fail(function (response) {
            loadingHide();
          if(response.responseJSON.errors){
                var values = '';
                jQuery.each(response.responseJSON.errors, function (key, value) {
                    values += value + "<br>"
                });
                notifNo(values);
          }
        });
  	});

    $(document).on("click", "#edit_btn", function (e) {
        e.preventDefault();
        loadingShow();
        var id        = $(this).attr("data-id");
        var menu_name = $(this).attr("data-name");
        $.ajax({
          method: "GET",
          url   :  window.url + "/configuration/permissions/"+ menu_name + "/edit",
          cache : false,
          data  : { 
              id_permission: id,
              menu_name    : menu_name
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
            $("div#MyModal").modal("show");
            iconpicker();
            $('.select').selectpicker();
            $("#is_routeY").click(function() {
                if(this.checked) {
                    $("#hd_route_name").removeClass('d-none');
                }
            });
            $("#is_routeN").click(function() {
                if(this.checked) {
                    $("#hd_route_name").addClass('d-none');
                }
            });
            $(".autocomplete").chosen();
          })
          .fail(function (response) {
              loadingHide();
            if(response.responseJSON.errors){
              var values = '';
              jQuery.each(response.responseJSON.errors, function (key, value) {
                  values += value + "<br>"
              });
              notifNo(values);
            }
            console.log("responseText", response.responseText);
          });
    });

    $(document).on("click", "#save_edit_btn", function (e) {
    	e.preventDefault();
        loadingShow();
        var menu_name  = $("#menu_name").val();
        var route_name = $("#route_name").val();
        var icon       = $("#icon").val();
        var order_line = $("#order_line").val();
        var role       = $("#role").val();
        var parent_id  = $("#parent_id").val();
        var index      = $("#index").is(":checked");
        var create     = $("#create").is(":checked");
        var edit       = $("#edit").is(":checked");
        var erase      = $("#erase").is(":checked");
        var token      = $("meta[name='csrf-token']").attr("content");

        var is_routeY  = $("#is_routeY").is(":checked");
        var has_childY = $("#has_childY").is(":checked");
        
	    $.ajax({
		    method: "PUT",
		    url:  window.url + '/configuration/permissions/' + null,
		    cache: false,
		    data: {
		        menu_name : menu_name,
		        route_name: route_name,
		        icon      : icon,
		        order_line: order_line,
		        role      : role,
		        parent_id : parent_id,
		        is_route  : is_routeY,
		        has_child : has_childY,
		        index     : index,
		        create    : create,
		        edit      : edit,
		        erase     : erase,
		        _token    : token,
		      },
	    })
      	.done(function (response) {
              loadingHide();
	        if (response.success) {
                $("div#MyModal").modal("hide");
                notifYesAuto(response.message);
                table.ajax.reload();
	        }else{
                notifNo(response.message);
            }
      	})
        .fail(function (response) {
            loadingHide();
          if(response.responseJSON.errors){
                var values = '';
                jQuery.each(response.responseJSON.errors, function (key, value) {
                    values += value + "<br>"
                });
                notifNo(values);
          }
        });
  	});

      $(document).on("click", "#delete_btn", function (e) {
        e.preventDefault();
      
        var id    = $(this).attr("data-id");
        var name  = $(this).attr("data-name");
        var token = $("meta[name='csrf-token']").attr("content");
        swal({
            title: "You are sure ?",
            text: "Permission data " + name + " will be deleted ?",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, delete !",
            cancelButtonText: "No, cancel !",
        }).then((result) => {
            if (result.value) {
                loadingShow();
            $.ajax({
              type: "post",
              url :  window.url + "/configuration/permissions/"+ name,
              data: {
                id: id,
                name   : name,
                _token : token,
                '_method': 'delete'
                },
            })
                .done(function (response) {
                    loadingHide();
                if (response.success) {
                    $("div#MyModal").modal("hide");
                    notifYesAuto(response.message);
                    table.ajax.reload();
                  }else{
                      notifNo(response.message);
                  }
                })
                .fail(function (response) {
                    loadingHide();
                  if(response.responseJSON.errors){
                    var values = '';
                    jQuery.each(response.responseJSON.errors, function (key, value) {
                        values += value + "<br>"
                    });
                    notifNo(values);
                  }
                  console.log("responseText", response.responseText);
                });
            }
        });
        });


    function iconpicker(){
        $('.iconpicker').iconpicker({
            title: 'My Icon Picker',
            selected: false,
            defaultValue: false,
            placement: "bottom",
            collision: "none",
            animation: true,
            hideOnSelect: true,
            showFooter: true,
            searchInFooter: false,
            mustAccept: false,
            selectedCustomClass: "bg-primary",
            fullClassFormatter: function (e) {
                return e;
            },
            input: "input,.iconpicker-input",
            inputSearch: false,
            container: false,
            component: ".input-group-addon,.iconpicker-component",
            templates: {
                popover: '<div class="iconpicker-popover popover" role="tooltip"><div class="arrow"></div>' + '<div class="popover-title"></div><div class="popover-content"></div></div>',
                footer: '<div class="popover-footer"></div>',
                buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' + ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
                search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
                iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
                iconpickerItem: '<a role="button" href="javascript:;" class="iconpicker-item"><i></i></a>'
            }
        });
    }

});