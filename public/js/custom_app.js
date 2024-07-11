const app_container = 'dashboard';
var map = null;
function load_app_container(route){
    const app_container_element = $('#app_container');
    const search_params = new URLSearchParams(window.location.search);
    const main_url = window.location.origin + window.location.pathname;
    window.history.replaceState({}, document.title, main_url);
    $.ajax({
        url: route,
        type: 'GET',
        data: Object.fromEntries(search_params.entries()),
        beforeSend:function(){
            app_container_element.html('');
            app_container_element.LoadingOverlay('show');
        },
        success: function (view) {
            app_container_element.LoadingOverlay('hide');
            setTimeout(function(){
                $('#app_container').html(view);
            },500)
        },
    });
}
$('.app_container_nav').on('click',function(){
   load_app_container($(this).attr('data-route'));
   handle_nav_item_click(this);
});

function handle_nav_item_click(element){
    $($('.app_container_nav.active').removeClass('active').parents('.nav-item')[0]).removeClass('active');
    $($('.app_container_sub_nav.active').removeClass('active').parents('.nav-item')[0]).removeClass('active');

    $($(element).addClass('active').parents('.nav-item')[0]).addClass('active');
    $('#accordionSidebar').find('.collapse.show').removeClass('show');
    if($(element).attr('id')){
        sessionStorage.setItem('current_route',$(element).attr('id'));
    }
}
function open_sub_page(ref){
    const element = $(ref);
    const container_to_hide = $('#'+element.attr('data-container-to-hide')) ;
    const route = element.attr('data-route');


    const app_container_element = $('#app_container');
    $.ajax({
        url: route,
        type: 'GET',
        data: {},
        beforeSend:function(){
            container_to_hide.hide();
            app_container_element.LoadingOverlay('show');
        },
        success: function (view) {
            app_container_element.LoadingOverlay('hide');
            setTimeout(function(){
                $('#app_container').append(view);
            },500)
        },
    });
}
function go_previous_page(){
    $('.navigation-sub-page').remove();
    $('.navigation-main-page').show();
}
function reload_sub_page(refresh_btn_ref){
    $('.navigation-sub-page').remove();
    open_sub_page(refresh_btn_ref);
}
$(function() {
    const search_params = new URLSearchParams(window.location.search);
    const nav_item_param = search_params.get('nav_item');

    const nav_item_id = nav_item_param ?? sessionStorage.getItem('current_route');

    if(nav_item_id){
        $('#'+nav_item_id).click();
    }else{
        $('#requests-nav-item').click();
    }
});
$.ajaxSetup({
    statusCode: {
        401: function(){
            location.reload();
        }
    },
    headers:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    }
});
function get_notification_counter(type,route,element_id){
    const element = $('#'+element_id);
    $.ajax({
        url: route,
        type: 'GET',
        data: {type},
        beforeSend:function(){
        },
        success: function (counter) {
            element.html(counter);
            element.removeClass('d-none');
            setTimeout(function(){
                get_notification_counter(type,route,element_id);
            },45000);
        },
    });
}

//fleet sub
function load_container(route,element){
    $('#app_container').empty();
    $.ajax({
        url: route,
        type: 'GET',
        data:{
            "section":element.getAttribute('section'),
        },
        beforeSend:function(){
            $('#app_container').html('');
            $('#app_container').LoadingOverlay('show');
        },
        success: function (view) {
            $('#app_container').LoadingOverlay('hide');
            setTimeout(function(){
                $('#app_container').html(view);
            },500)
        },
    });
}
function nav_click(element){
    load_container(element.getAttribute('data-route'),element);
    handle_sub_nav_item_click(element);
}
function handle_sub_nav_item_click(element){
    $($('.app_container_nav.active').removeClass('active').parents('.nav-item')[0]).removeClass('active');
    $($('.app_container_sub_nav.active').removeClass('active').parents('.nav-item')[0]).removeClass('active');

    $($(element).addClass('active').parents('.nav-item')[0]).addClass('active');
    $('#accordionSidebar').find('.collapse.show').removeClass('show');
    if(element.getAttribute('id')){
        sessionStorage.setItem('current_route',element.getAttribute('id'));
    }
}
//end sub

function reload(){
    $('#app_container').load("{{ route('backoffice') }}");
}
function submit_form(method,route,data,datatable="noTable",modal="noModal",element_to_delete="noDelete",addTotable="no"){

        swal({
            title: "",
            text: "Confirm Your Action !",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Confirm",
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            closeOnCancel: true,
            showLoaderOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                return new Promise(function (resolve) {
                    $.ajax({
                        type: method,
                        url: route,
                        data: data,
                        // headers: $('meta[name="csrf-token"]').attr('content'),
                        success: function (response) {
                            // console.log(response)
                            swal(response.message, "", response.status);
                            if(response.status=="success"){
                                if (modal !== "noModal") {
                                    $('#' + modal).modal('hide')
                                }
                            }
                            if (datatable !== "noTable") {
                                $("#" + datatable).DataTable().ajax.reload(null, false);
                            }
                            if(response.status==="success"){
                                if(element_to_delete!=="noDelete"){
                                    $(element_to_delete).parent().parent().remove()
                                }
                                if(addTotable==="yes"){

                                }
                            }

                        },
                    });
                })
            } else {
                swal("Cancelled", "Action Cancelled !", "error");
            }
        });
}
function submit_form_with_file(form, route){
    swal({
            title: "",
            text: "Confirm Your Action !",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Confirm",
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            closeOnCancel: true,
            showLoaderOnConfirm: true
        },
        function(isConfirm) {
            if (isConfirm)
            {
                $.ajax(
                    {
                    type: "POST",
                    url: route ,
                    data: form,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: $('meta[name="csrf-token"]').attr('content'),
                    success: function(response)
                    {
                        if(response.message == 'success') {
                            swal("Request Successfully Processed", "", "success");
                            $('#group_members_datatable').DataTable().ajax.reload();
                        }else{
                                swal("Action Processing Failed","", "error");
                        }
                        // form[0].reset();
                    },
                });
            } else {
                swal("Cancelled", "Action Cancelled !", "error");
            }
        });
}

function map_location_formatter(location){
    try {
        if(location){
            location = location.trim();

            if(location.includes('°')){
                let location_array = location.split('°');
                const deg = parseInt(location_array[0].trim());

                location_array = location_array[1].trim().split('\'');
                const min = parseInt(location_array[0].trim());


                location_array = location_array[1].trim().split('"');
                const sec =  parseInt(location_array[0].trim());
                return deg + (min / 60) + (sec / 3600);
            }else{
                return location;
            }
        }
    } catch (error) {
       return null;
    }

}
