<div id="request_main_container" class="navigation-sub-page">
    <div class="card shadow-lg">
        <div class="card-header py-3 korek-color-bg-gradient text-white">
            <h6 class="m-0 font-weight-bold text-white" id="form_title">Cards</h6>
        </div>
    </div>
    <div class="card shadow mb-4" id="users_table_container">

        <div class="card-header py-3">
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
                                    <th>Title</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cards as $card)
                                    <tr>
                                        <td>{{$card->title}}</td>
                                        <td>{{$card->created_at}}</td>
                                        <td><a href="" target="_blank" class="btn btn-sm btn-info btn-icon-split text-right ml-2 mb-2"><span class="icon text-white-50"><i class="fas fa-edit"></i></span></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
