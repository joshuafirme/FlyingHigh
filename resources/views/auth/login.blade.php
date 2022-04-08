<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="">
    <title>Login | Flying High - Inventory Management System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login-responsive.css') }}">

    <link rel="icon"
        href="https://i0.wp.com/flyinghighexpress.com/wp-content/uploads/2019/02/cropped-flyinghighenergyexpress-logoicon.png?fit=32%2C32&ssl=1">
</head>

<body class="body_color">
    <!--================Login Form Area =================-->
    <section class="ic_main_form_area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 d-none d-lg-block">
                    <div class="ic-fxied-image">
                        <div class="login-img-slider-heads">
                            <div class="img-items">
                                <img src=https://clanvent-alpha.laravel-script.com/admin/images/1.png
                                    class="img-fluid" alt="slider-img">
                            </div>
                            <div class="img-items">
                                <img src=https://clanvent-alpha.laravel-script.com/admin/images/2.png
                                    class="img-fluid" alt="slider-img">
                            </div>
                            <div class="img-items">
                                <img src=https://clanvent-alpha.laravel-script.com/admin/images/3.png
                                    class="img-fluid" alt="slider-img">
                            </div>
                        </div>
                        <div class="mobile-img-slider-heads">
                            <div class="img-items">
                                <img src=https://clanvent-alpha.laravel-script.com/admin/images/M1.png
                                    class="img-fluid" alt="slider-img">
                            </div>
                            <div class="img-items">
                                <img src=https://clanvent-alpha.laravel-script.com/admin/images/M2.png
                                    class="img-fluid" alt="slider-img">
                            </div>
                            <div class="img-items">
                                <img src=https://clanvent-alpha.laravel-script.com/admin/images/M3.png
                                    class="img-fluid" alt="slider-img">
                            </div>
                        </div>
                        <img class="img-fluid w-100"
                            src="https://clanvent-alpha.laravel-script.com/admin/images/Slider_Frame.png"
                            alt="slider-img">
                    </div>
                </div>
                <div class="col-lg-5 col-md-7 m-auto ml-lg-auto">
                    <div class="ic_main_form_inner">
                        <div class="form_box">
                            <div class="col-lg-12">
                                <img class="img-fluid ic-login-img" width="100"
                                    src="https://i0.wp.com/flyinghighexpress.com/wp-content/uploads/2019/02/cropped-flyinghighenergyexpress-logoicon.png?fit=192%2C192&ssl=1"
                                    alt="imgs">


                                <h2>Login</h2>
                                <p>Manage your business with our automated Inventory Management System</p>
                            </div>



                            <form class="row login_form justify-content-center" method="POST"
                                action="{{ route('login') }}">
                                @csrf
                                <div class="form-group col-lg-12">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    <i class="fa fa-user"></i>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-12">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                                <div class="form-group col-lg-12">
                                    <button type="submit" value="submit"
                                        class="btn submit_btn form-control">Login</button>
                                </div>
                            </form>
                        </div>
                        <div class="text-center login-form-footer">© 2022 All Right Reserved | Flying High</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Login Form Area =================-->



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jbootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <!-- Extra Plugin CSS -->
    <script src="{{ asset('js/login-slider.js') }}"></script>

</body>

</html>
