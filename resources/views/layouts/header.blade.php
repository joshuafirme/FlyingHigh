<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Flying High</title>
    <meta content="Dashboard" name="description" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/metismenu.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/chartist.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css?v=' . time()) }}">
    <link rel="icon"
        href="https://i0.wp.com/flyinghighexpress.com/wp-content/uploads/2019/02/cropped-flyinghighenergyexpress-logoicon.png?fit=32%2C32&ssl=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/choices.min.css') }}" />
    <!--  DOUBLE BORDER SPINNER  -->
    <div class="ic-preloader">
        <div class="ic-inner-preloader">
            <div class="db-spinner"></div>
        </div>
    </div>
</head>

<body>
    <div id="wrapper">
