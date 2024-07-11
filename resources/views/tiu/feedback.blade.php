<div id="request_main_container" class="navigation-sub-page">
    <div class="card shadow-lg">
        <div class="card-header py-3 korek-color-bg-gradient text-white">
            <h6 class="m-0 font-weight-bold text-white" id="form_title">Feedbacks</h6>
        </div>
    </div>

    <div class="card shadow mb-4" id="users_table_container">
        <div class="card-header py-3">
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
                                    <th>Total Point</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reviews as $review)
                                    <tr>
                                        <td>{{ $review->user->user_name }}</td>
                                        <td>
                                                <?php $total = 0; ?>
                                            @foreach($review->feedbacks as $feedback)
                                                    <?php $total += ($feedback->quantity * $feedback->cardQuestion->point); ?>
                                            @endforeach
                                            {{ $total }}
                                        </td>
                                        <td><a href="/feedbacks/{{$review->u_id}}" target="_blank" class="btn btn-sm btn-info btn-icon-split text-right ml-2 mb-2"><span class="icon text-white-50"><i class="fas fa-paperclip"></i></span></a></td>
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




