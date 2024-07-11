<div class="modal fade" id="user_modal" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header korek_blue">
                <h5 class="modal-title text-white" id="user_modal_title">User Information</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="user_modal_form" method="POST" onsubmit="save_form();return false">

                <div class="modal-body">
                    <input type="hidden" id="user_id">
                    <span>Username</span>
                    <input id="username" required name="username" type="text" class="form-control" placeholder="Enter User Name"/>
                </div>

                <div class="modal-body">
                    <span>Password</span>
                    <input id="password" required name="password" type="password" class="form-control" placeholder="Enter Password"/>
                </div>

                <div class="modal-body pt-0">
                    <span>User Type</span>
                    <select required name="type" data-style="border" data-actions-box="true" data-access-type="role" data-selected-text-format="count" data-width="100%" class="selectpicker user-access-select" id="user_type_select" data-live-search="true">
                        <option value="normal">Normal</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="modal-footer">
                <input type="submit" class="btn korek_blue text-white" value="Save"/>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.selectpicker').selectpicker()
    function save_form(){
        let data=$('#user_modal_form').serialize();
        $.ajax({
            url: '{{route('save_user')}}',
            type: 'POST',
            data:data,
            success: function (res) {
                if (res.status==="success") {
                    $('#users_datatable').DataTable().ajax.reload();
                    $('#user_modal').modal('hide');
                }
                Swal.fire(res.message, '', res.status)
            },
        });
    }
</script>
