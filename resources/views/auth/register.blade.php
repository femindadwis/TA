
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="viho admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities." />
        <meta name="keywords" content="admin template, viho admin template, dashboard template, flat admin template, responsive admin template, web app" />
        <meta name="author" content="pixelstrap" />
        <link rel="icon" href="https://laravel.pixelstrap.com/viho/assets/images/favicon.png" type="image/x-icon" />
        <link rel="shortcut icon" href="https://laravel.pixelstrap.com/viho/assets/images/favicon.png" type="image/x-icon" />
        <title>REGISTER
</title>
        <!-- Google font-->
        <link rel="preconnect" href="https://fonts.gstatic.com" />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet" />
<!-- Font Awesome-->
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/viho/assets/css/fontawesome.css" />
<!-- ico-font-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/viho/assets/css/icofont.css" />
<!-- Themify icon-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/viho/assets/css/themify.css" />
<!-- Flag icon-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/viho/assets/css/flag-icon.css" />
<!-- Feather icon-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/viho/assets/css/feather-icon.css" />
<!-- Plugins css start-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/viho/assets/css/sweetalert2.css">
<!-- Plugins css Ends-->
<!-- Bootstrap css-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/viho/assets/css/bootstrap.css" />
<!-- App css-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/viho/assets/css/style.css" />


<link id="color" rel="stylesheet" href="https://laravel.pixelstrap.com/viho/assets/css/color-1.css" media="screen" />
<!-- Responsive css-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/viho/assets/css/responsive.css" />
    </head>
    <body>
        <!-- Loader starts-->
        <div class="loader-wrapper">
            <div class="theme-loader">
                <div class="loader-p"></div>
            </div>
        </div>
        <!-- Loader ends-->
        <!-- error page start //-->

	    <div class="container-fluid p-0">
	        <div class="row m-0">
	            <div class="col-12 p-0">
	                <div class="login-card">
	                    <form class="theme-form login-form" method="POST" action="{{ url('register') }}">
                            @csrf
    >
	                        <h4>Create your account</h4>
	                        <h6>Enter your personal details to create account</h6>
	                        <div class="form-group">
	                            <label>Your Name</label>
	                            <div class="small-group">
	                                <div class="input-group">
	                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
	                                    <input class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Nama" required autocomplete="name" autofocus>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label>Username</label>
	                            <div class="input-group">
	                                <span class="input-group-text"><i class="fa fa-envelope-o"></i></span>
	                                <input class="form-control form-control-lg @error('username') is-invalid @enderror" id="username" type="username" name="username" value="{{ old('username') }}" required autocomplete="username" placeholder="username">

                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label>Password</label>
	                            <div class="input-group">
	                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
	                                <input class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="new-password" placeholder="Password" />

                                    {{-- showing/ menampilkan password --}}
                                    <div class="show-hide"><span class="show"> </span></div>

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <label>Confirm Password</label>
	                            <div class="input-group">
	                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
	                                <input id="password-confirm" type="password" class="form-control form-control-lg " name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">

                                    {{-- showing/ menampilkan password --}}
                                    <div class="show-hide"><span class="show"> </span></div>
                                </div></div>
	                        {{-- <div class="form-group">
	                            <div class="checkbox">
	                                <input id="checkbox1" type="checkbox" />
	                                <label class="text-muted" for="checkbox1">Agree with <span>Privacy Policy </span></label>
	                            </div> --}}

	                        <div class="form-group">
	                            <button class="btn btn-primary btn-block" type="submit">Create Account</button>
	                        </div>


	                        <p>Already have an account?<a class="ms-2" href="/login">Sign in</a></p>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>




        <!-- error page end //-->
        <!-- latest jquery-->
        <script src="https://laravel.pixelstrap.com/viho/assets/js/jquery-3.5.1.min.js"></script>
<!-- feather icon js-->
<script src="https://laravel.pixelstrap.com/viho/assets/js/icons/feather-icon/feather.min.js"></script>
<script src="https://laravel.pixelstrap.com/viho/assets/js/icons/feather-icon/feather-icon.js"></script>
<!-- Sidebar jquery-->
<script src="https://laravel.pixelstrap.com/viho/assets/js/config.js"></script>
<!-- Bootstrap js-->
<script src="https://laravel.pixelstrap.com/viho/assets/js/bootstrap/popper.min.js"></script>
<script src="https://laravel.pixelstrap.com/viho/assets/js/bootstrap/bootstrap.min.js"></script>
<!-- Plugins JS start-->
    <script src="https://laravel.pixelstrap.com/viho/assets/js/sweet-alert/sweetalert.min.js"></script>
    <!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="https://laravel.pixelstrap.com/viho/assets/js/script.js"></script>
<!-- Plugin used-->
    </body>
</html>



