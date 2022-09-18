<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entertech | Login</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#">
                <img src="{{ asset('assets/logo/cygnus.png') }}" style="width: 253px; height: 232px; object-fit: cover;">
            </a>
        </div>
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>Login</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                @include('partials._message')
                <form action="{{route('loginCheck')}}" method="post">
                    @csrf
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" mb-3">
                        <div class="input-group">
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <p class="mb-1">
                                <a href="{{route('passwordReset')}}">I forgot my password</a>
                                </p>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <div class="social-auth-links text-center mb-3">
                    <a href="#" id="create_account" class="btn btn-block btn-success create_account">Create Account
                    </a>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="modal fade" id="create_account_modal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <form action="" method="POST">
                    @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Create Account</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1"><i class="far fa-bookmark"></i> Client Code</label>
                                  <input type="text" class="form-control" id="client_code" name="client_code" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1"><i class="far fa-envelope"></i> Email</label>
                                  <input type="email" class="form-control" id="reg_email" name="email" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputPassword1"><i class="far fa-calendar-alt"></i> Mobile Number</label>
                                  <input type="text" class="form-control" id="mobile_no" name="mobile_no" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" id="registration" class="btn btn-primary" value="Registration" name="submit" style="color: #fff">
                            
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
    <script src="{{asset('assets/dist/js/demo.js')}}"></script>
    <script>
        $(".create_account").on("click", function(e) {
        e.preventDefault();
        $("#create_account_modal").modal();

        $("#registration").on("click", function(e) {
          e.preventDefault();
          // $("#registration").attr('disabled', true);
          // var name = $("#name").val();
          var client_code = $("#client_code").val();
          var email = $("#reg_email").val();
          var mobile_no = $("#mobile_no").val();
          //console.log(mobile_no);
          // var password = $("#user_password").val();
          // var confirm_password = $("#user_confirm_password").val();
          var token = "{{ csrf_token() }}";
          var url_data = "{{ url('user_registration') }}";

          // if(name == "") {
          //   alert("Name is required");
          //   return;
          // }
          if(mobile_no == "") {
            alert("Mobile Number is required");
            return;
          }
          if(client_code == "") {
            alert("Client code is reuqired");
            return;
          }
          if(email == "") {
            alert("Email is required");
            return;
          }
          // if(password == "") {
          //   alert("Password is required");
          //   return;
          // }
          // if(password != confirm_password) {
          //   alert("Password & confirm password did not match");
          //   return;
          // }

          $.ajax({
                    method: "POST",
                    url: url_data,
                    data: {
                        _token: token,
                        client_code: client_code,
                        email: email,
                        mobile_no: mobile_no
                    },
                    success: function(data) {
                      //alert(data);
                      window.location = document.URL;
                    }
          });
        });

      });
    </script>
</body>
</html>