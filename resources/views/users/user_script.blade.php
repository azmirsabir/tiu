<?php
    use App\Models\User;
?>
<script>
    $(function (){
        load_datatable()
        $('#select_usertype_search').selectpicker()
        $('#select_usertype_search').selectpicker({title: 'Choose user type'}).selectpicker('render');

    })
    $('#select_usertype_search').on('changed.bs.select', function () {
        $('#users_datatable').DataTable().ajax.reload();
    });
    function load_datatable(){
        $("#users_datatable").DataTable().destroy();
        $('#users_datatable').DataTable({
            "pageLength": @json($usersDataTableLength),
            "processing": true,
            "lengthMenu": [ 10, 20, 50],
            "serverSide": true,
            "ordering": true,
            "ajax": {
                "url": "{{route('get_users')}}",
                "dataType": "json",
                "type": "GET",
                "data":function (data){
                    data.type=$("#select_usertype_search").children(":selected").val();
                }
            },
            'columnDefs': [
                { "width": "20%", "targets": 0},
                { "width": "20%", "targets": 1},
                { "width": "25%", "targets": 2},
                { "width": "15%", "targets": 3},
            ],
            'drawCallback':function(res){
                put_data_into_table(res.json.data);
            }
        });
    }
    function put_data_into_table(res){
        $("#users_datatable tbody").empty();
        $.each(res.user_data, function( index, value ) {
            let roles="";
            let checked=value['status']== 1 ? "checked='checked'": "";
            let action='<a onclick="set_user_id(this)" data-toggle="modal" data-target="#user_modal" user-id="'+value.id+'" class="btn btn-sm btn-info btn-icon-split text-right ml-2 mb-2"><span class="icon text-white-50"><i class="fas fa-edit"></i></span></a>';
            let status='<label class="custom-control custom-checkbox">\n' +
        '                    <input type="checkbox" onchange="statusChange(this)" id="check'+value.id+'" data-user-id="'+ value.id +'" class="custom-control-input user-status-checkbox" '+checked+'>\n' +
        '                    <span class="custom-control-indicator"></span>\n' +
        '                </label>';

            let privileges='<h5><span class="badge badge-info"><span class="icon text-white-50"></span>'+value.type+'</span></h5>';
            $("#users_datatable tbody").append('<tr><td>' +value['user_name']+'</td><td>'+ privileges +'</td><td>'+status+'</td><td>'+action+'</td></tr>');

        });
    }
    function set_user_id(element){
        const user_id=element.getAttribute('user-id');
        $('#user_id').val(user_id);
        $('#user_id').attr('name',"user_id");

        $.ajax({
            url: '/get_user_by_id/'+user_id,
            type: 'GET',
            success: function (user) {
                $('#user_groups_select').val('')
                $('#username').val(user.user[0].user_name);
                $('#user_type_select>option[value="'+user.user[0].type+'"]').prop('selected', true);

                $.each(user.user[0].roles, function (index,role){
                    $('#user_groups_select>option[value="'+role.id+'"]').prop('selected', true);
                });
                $('.selectpicker').selectpicker('refresh');
            },
        });
    }
    function new_form(){
        $('#user_modal_form')[0].reset();
        $('#user_id').removeAttr('name');
        // $('#username').prop('readonly',false);
        $('#user_module_select').selectpicker('refresh');
        $('#user_type_select').selectpicker('refresh');
    }
    function statusChange(element){
        const user_id=element.getAttribute('data-user-id');
        let active="";
        if($('#check'+user_id).attr('checked')=="checked"){
            $('#check'+user_id).attr('checked',false);
            active=0;
        }else{
            $('#check'+user_id).attr('checked',true);
            active=1;
        }

        $.ajax({
            url: '/users/'+user_id,
            type: 'put',
            data:{'status':active},
            success: function (res) {
                $('#users_datatable').DataTable().ajax.reload();
            },
        });

    }
    function export_users(){
        window.open('/export_users?type='+$('#select_usertype_search').val());
    }
</script>
