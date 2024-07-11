<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>TIU - MS</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="vendor/google_fonts/fonts.css" rel="stylesheet">


    <!-- Custom styles for this template-->
    <link href="{{asset('css/custom_app.css')}}" rel="stylesheet">
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>

</head>

<body class="text-white" >

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-8 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row korek_blue">
                        <div class="col-6 my-auto text-center align-content-center text-gray-900">
{{--                            <i class="far fa-6x fa-passport text-white" ></i>--}}
                            <i class="far fa-4x fa-passport text-white"></i>
                            <span class="h3 font-weight-bolder text-white">TIU MS</span>
                        </div>
                        <div class="col-6 bg-white">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>
                                <form class="user" method="post" action="{{route('postLogin')}}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="user_name" name="user_name" aria-describedby="userNameHelp" placeholder="Enter User Name..." value="{{ old('user_name') }}">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                                    </div>

                                    @if ($errors->has('user_name'))
                                        <div class="form-group">
                                            <span class="invalid-feedback" style="display: block" role="alert"><strong>{{ $errors->first('user_name') }}</strong></span>
                                        </div>

                                    @endif

                                    <button type="submit" style="background-color: #006FBA" class="btn btn-user text-white btn-block">
                                        Login
                                    </button>
                                    <hr>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <img style="width: 15%" src="https://tiu.edu.iq/wp-content/uploads/2019/11/188-57.png">
            </div>
        </div>
    </footer>
    <!-- End of Footer -->
</div>
</body>

</html>
