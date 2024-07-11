@php($version = 3)
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale= 1 , user-scalable=0 , shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>Korek - EGS MS</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/google_fonts/fonts.css" rel="stylesheet">

    <!-- Plugins Styling -->
    <link rel="stylesheet" href="vendor/bootstrap_select/bootstrap_select.min.css">
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/datatables/dataTables.fixedHeader.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/select2/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/select2/select2_bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/leaflet/leaflet.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/bootstrap-gallery/bootstrap-gallery.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/bootstrap-gallery/bootstrap-fancybox.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/bootstrap_datepicker/bootstrap_datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/fullcalendar/main.min.css')}}" rel="stylesheet">
    <link href="css/jquery-ui.css" rel="stylesheet">


    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/custom_app.css?version={{$version}}" rel="stylesheet">

</head>

<body id="page-top" class="sidebar-toggled">

<!-- Page Wrapper -->
<div id="wrapper" class="wrapper-for-fixed-sidebar">

    <!-- Sidebar -->
    @include('base.side_bar')
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column content-wrapper-for-fixed-sidebar">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            @include('base.top_bar')
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid" id="app_container">
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        @include('base.footer')
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger" href="{{route('logout')}}">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>
<script src="js/custom_app.js"></script>
<script src="js/uniqueJs.js"></script>
<script src="js/dynamic_list_filters.js"></script>
<script src="js/jqueryUI.js"></script>

<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="vendor/loading_overlay/loading_overlay.min.js"></script>
<script src="vendor/bootstrap_select/bootstrap_select.min.js"></script>
{{--<script src="vendor/select2/select2.min.js"></script>--}}
<script src="vendor/select2/select2_full.min.js"></script>

<!-- DataTables plugins -->
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendor/leaflet/leaflet.js')}}"></script>
<script src="{{asset('/js/iraq_geo_json.js')}}"></script>

<script src="{{asset('/vendor/bootstrap-gallery/bootstrap-fancybox.js')}}"></script>
<script src="{{asset('vendor/bootstrap_datepicker/bootstrap_datepicker.min.js')}}"></script>
<script src="{{asset('vendor/fullcalendar/main.min.js')}}"></script>

{{--<script src="{{asset('vendor/sweetalert/sweetalert.min.js')}}"></script>--}}
<script src="{{asset('vendor/sweetalert/sweetalert2.js')}}"></script>

{{--<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>--}}

<script src="{{asset('vendor/chart.js/Chart.min.js')}}"></script>


@include('base.base_script')
<!-- Page level custom scripts -->

</body>

</html>
