<div id="request_main_container" class="navigation-sub-page">
    <div class="card shadow-lg">
        <div class="card-header py-3 korek-color-bg-gradient text-white">
            <h6 class="m-0 font-weight-bold text-white" id="form_title">User Management</h6>
        </div>
    </div>

    <div class="card shadow mb-4" id="users_table_container">
        <div class="card-header py-3">

            <select class="form-control selectpicker col-2" id="select_usertype_search" data-style="border">
                <option value=""></option>
                @foreach($roles as $key=>$user)
                    <option value="{{$user->type}}">{{ucfirst($user->type)}}</option>
                @endforeach
            </select>
            <a onclick="export_users()" class="btn btn-info float-right korek_blue" >Export</a>
            <a data-toggle="modal" onclick="new_form()" data-target="#user_modal" class="btn btn-info float-right korek_blue mr-1" >New</a>
        </div>
        <div class="card-body">
            <div class="table">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped table-bordered datatable-filtered-list" id="users_datatable" width="100%">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>User type</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('users.user_modal')
    </div>

</div>
@include('users.user_script')


