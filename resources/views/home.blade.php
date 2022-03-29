<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8" />
    <title>Dashboard | ClanVent- Inventory Management System</title>
    <meta content="Dashboard" name="description" />
    <meta name="csrf-token" content="HbfJOVbwfPbpbVkHx4SxuTtKUgIsUR5bBbGympZ8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- App favicon -->
    <link rel="shortcut icon" href="">
    <!-- App css -->
    <link href="https://clanvent-alpha.laravel-script.com/admin/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://clanvent-alpha.laravel-script.com/admin/css/metismenu.min.css" rel="stylesheet" type="text/css">
    <link href="https://clanvent-alpha.laravel-script.com/admin/css/slick.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/flaticon.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://clanvent-alpha.laravel-script.com/admin/css/style.css" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert -->
    <link href="https://clanvent-alpha.laravel-script.com/admin/plugins/sweet-alert2/sweetalert2.min.css"
        rel="stylesheet" type="text/css">
    <!-- DataTables -->
    <link href="https://clanvent-alpha.laravel-script.com/admin/plugins/datatables/dataTables.bootstrap4.min.css"
        rel="stylesheet" type="text/css" />
    <!-- Responsive datatable -->
    <link href="https://clanvent-alpha.laravel-script.com/admin/plugins/datatables/responsive.bootstrap4.min.css"
        rel="stylesheet" type="text/css" />
    <!-- Datepicker  -->
    <link
        href="https://clanvent-alpha.laravel-script.com/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"
        rel="stylesheet">
    <!-- Chartist  -->
    <link rel="stylesheet" href="https://clanvent-alpha.laravel-script.com/admin/plugins/chartist/css/chartist.min.css">
    <!-- Select2  -->
    <link href="https://clanvent-alpha.laravel-script.com/admin/plugins/select2/css/select2.min.css" rel="stylesheet"
        type="text/css" />
    <!-- intlTelInput css -->
    <link rel="stylesheet" href="https://clanvent-alpha.laravel-script.com/plugins/intl/build/css/intlTelInput.css">
    <!-- Load style form view css -->

    <!-- Custom css  -->
    <link rel="stylesheet" href="https://clanvent-alpha.laravel-script.com/admin/css/custom.css">

    <!--  DOUBLE BORDER SPINNER  -->
    <div class="ic-preloader">
        <div class="ic-inner-preloader">
            <div class="db-spinner"></div>
        </div>
    </div>
</head>

<body>
    <div id="wrapper">
        <!-- Top Bar Start -->
        <div class="topbar">
            <!-- LOGO -->
            <div class="topbar-left">
                <a href="https://clanvent-alpha.laravel-script.com/admin/dashboard" class="logo">
                    <span>
                        <img src=https://clanvent-alpha.laravel-script.com/images/logo.png class="ic-logo-height"
                            alt="logo">
                    </span>
                    <i>
                        <img src=https://clanvent-alpha.laravel-script.com/images/logo.png class="ic-logo-small"
                            alt="logo">
                    </i>
                </a>
                <div class="float-right">
                    <button class="button-menu-mobile ic-collapsed-btn mobile-device-arrow open-left">
                        <div class="ic-medi-menu">
                            <div class="ic-bar"></div>
                            <div class="ic-bar"></div>
                            <div class="ic-bar"></div>
                        </div>
                    </button>
                </div>
            </div>
            <nav class="navbar-custom">
                <ul class="navbar-right d-flex list-inline float-right mb-0">
                    <!-- sync-->
                    <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
                        <a class="nav-link" href="/change-layout">
                            <i class="fas fa-align-justify"></i>
                        </a>
                    </li>
                    <!-- full screen -->
                    <li class="dropdown notification-list d-none d-md-block">
                        <a class="nav-link" href="#" id="btn-fullscreen">
                            <i class="mdi mdi-fullscreen noti-icon"></i>
                        </a>
                    </li>
                    <!-- Profile-->
                    <li class="dropdown notification-list">
                        <div class="dropdown notification-list nav-pro-img">
                            <a class="dropdown-toggle nav-link arrow-none nav-user" data-toggle="dropdown" href="#"
                                role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="https://clanvent-alpha.laravel-script.com/storage/users/16368736053887.png"
                                    alt="user" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">

                                <a href="/admin/profile" class="dropdown-item">
                                    {{ Auth::user()->name }} <br>
                                    <small>{{ Auth::user()->email }}</small>
                                </a>

                                <a class="dropdown-item logout-btn" href="{{ route('logout') }}" onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                                    <i class="mdi mdi-power text-danger"></i>
                                    logout</a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>

                <ul class="list-inline menu-left mb-0 ic-left-content">
                    <li class="float-left ic-larged-deviced">
                        <button class="button-menu-mobile">
                            <i class="mdi mdi-arrow-right open-left ic-mobile-arrow"></i>
                            <div class="ic-medi-menu ic-humbarger-bar">
                                <div class="ic-bar"></div>
                                <div class="ic-bar"></div>
                                <div class="ic-bar"></div>
                            </div>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- Top Bar End -->
        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu">
            <div class="slimscroll-menu" id="remove-scroll">
                <!--- Side Menu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu" id="side-menu">
                        <li class="menu-title">Main</li>
                        <li>
                            <a href="https://clanvent-alpha.laravel-script.com/admin/dashboard" class="">
                                <i class="flaticon-dashboard"></i><span> Dashboard </span>
                            </a>
                        </li>
                        <li class="menu-title">Components</li>
                        <li>
                            <a href="#" class=""><i class="flaticon-working"></i><span> Administration
                                    <span class="float-right menu-arrow">
                                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="10 15 15 20 20 15"></polyline>
                                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                        </svg>
                                    </span>
                                </span>
                            </a>
                            <ul class="submenu">
                                <li class=""><a
                                        href="https://clanvent-alpha.laravel-script.com/admin/users">Users</a>
                                </li>
                                <li class=""><a
                                        href="https://clanvent-alpha.laravel-script.com/admin/roles">Roles</a></li>
                            </ul>
                        </li>

                        <li class="">
                            <a href="https://clanvent-alpha.laravel-script.com/admin/warehouses" class="">
                                <i class="ti-home"></i><span> Warehouse </span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class=""><i class="flaticon-new-product"></i><span>
                                    Product
                                    <span class="float-right menu-arrow">
                                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="10 15 15 20 20 15"></polyline>
                                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                        </svg>
                                    </span>
                                </span></a>
                            <ul class="submenu">
                                <li class=""><a
                                        href="https://clanvent-alpha.laravel-script.com/admin/products">Product</a></li>
                                <li class=""><a
                                        href="https://clanvent-alpha.laravel-script.com/admin/product-categories">Product
                                        Category</a>
                                </li>
                                <li class=""><a
                                        href="https://clanvent-alpha.laravel-script.com/admin/brands">Brand</a></li>
                                <li class=""><a
                                        href="https://clanvent-alpha.laravel-script.com/admin/manufacturers">Manufacturer</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" class=""><i class="flaticon-pamphlet"></i><span>
                                    Catalog
                                    <span class="float-right menu-arrow">
                                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="10 15 15 20 20 15"></polyline>
                                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                        </svg>
                                    </span>
                                </span></a>
                            <ul class="submenu">

                                <li class=""><a
                                        href="https://clanvent-alpha.laravel-script.com/admin/weight-units">Weight
                                        Unit</a></li>
                                <li class="">
                                    <a href="https://clanvent-alpha.laravel-script.com/admin/measurement-units">Measurement
                                        Unit</a>
                                </li>
                                <li class=""><a
                                        href="https://clanvent-alpha.laravel-script.com/admin/attributes">Attribute</a>
                                </li>

                            </ul>
                        </li>

                        <li>
                            <a href="#" class=""><i class="flaticon-shopping-bag-1"></i><span>
                                    Purchases
                                    <span class="float-right menu-arrow">
                                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="10 15 15 20 20 15"></polyline>
                                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                        </svg>
                                    </span>
                                </span></a>
                            <ul class="submenu">
                                <li class=""><a
                                        href="https://clanvent-alpha.laravel-script.com/admin/purchases">Purchases</a>
                                </li>
                                <li><a href="https://clanvent-alpha.laravel-script.com/admin/purchases/receive/list">Purchase
                                        Receive List</a>
                                </li>
                                <li><a href="https://clanvent-alpha.laravel-script.com/admin/purchases/return/list">Purchase
                                        Return List</a>
                                </li>
                            </ul>
                        </li>

                        <li class="">
                            <a class="" href="https://clanvent-alpha.laravel-script.com/admin/customers"
                                class="">
                                <i class="flaticon-conversation"></i><span> Customers </span>
                            </a>
                        </li>
                        <li class="">
                            <a class="" href="https://clanvent-alpha.laravel-script.com/admin/suppliers"
                                class="">
                                <i class="flaticon-conversation"></i><span> Suppliers </span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class=""><i class="flaticon-expenses"></i><span>
                                    Expenses
                                    <span class="float-right menu-arrow">
                                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="10 15 15 20 20 15"></polyline>
                                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                        </svg>
                                    </span>
                                </span></a>
                            <ul class="submenu">
                                <li class="">
                                    <a href="https://clanvent-alpha.laravel-script.com/admin/expenses-categories">Expenses
                                        Category</a>
                                </li>
                                <li class="">
                                    <a href="https://clanvent-alpha.laravel-script.com/admin/expenses">Expenses</a>
                                </li>
                            </ul>
                        </li>

                        <li class="">
                            <a class="" href="https://clanvent-alpha.laravel-script.com/admin/invoices"
                                class="">
                                <i class="flaticon-bill"></i><span> Invoice Manage </span>
                            </a>
                        </li>


                        <li>
                            <a href="#" class=""><i class="flaticon-expenses"></i><span>
                                    Sale Return
                                    <span class="float-right menu-arrow">
                                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="10 15 15 20 20 15"></polyline>
                                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                        </svg>
                                    </span>
                                </span></a>
                            <ul class="submenu">
                                <li class="">
                                    <a href="https://clanvent-alpha.laravel-script.com/admin/sales-return-create">Sale
                                        Return</a>
                                </li>
                                <li>
                                    <a href="https://clanvent-alpha.laravel-script.com/admin/sales-return">Sale Return
                                        List</a>
                                </li>
                            </ul>
                        </li>


                        <li>
                            <a href="#" class=""><i class="flaticon-report"></i><span>
                                    Reports
                                    <span class="float-right menu-arrow">
                                        <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="10 15 15 20 20 15"></polyline>
                                            <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                        </svg>
                                    </span>
                                </span></a>
                            <ul class="submenu">
                                <li>
                                    <a href="https://clanvent-alpha.laravel-script.com/admin/reports/expenses">Expenses
                                        Report</a>
                                </li>
                                <li>
                                    <a href="https://clanvent-alpha.laravel-script.com/admin/reports/sales">Sales
                                        Report</a>
                                </li>
                                <li>
                                    <a href="https://clanvent-alpha.laravel-script.com/admin/reports/purchases">Purchases
                                        Report</a>
                                </li>
                                <li>
                                    <a href="https://clanvent-alpha.laravel-script.com/admin/reports/payments">Payments
                                        Report</a>
                                </li>

                            </ul>
                        </li>

                        <li>
                            <a href="https://clanvent-alpha.laravel-script.com/admin/system-settings"
                                class="">
                                <i class="ti-settings"></i><span> Settings </span>
                            </a>
                        </li>

                    </ul>
                </div>
                <!-- Sidebar -->
                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->
        <div class="content-page">
            <div class="content">
                <div class="container-fluid" id="app">

                    <!-- ======== breadcump start ========  -->

                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h4 class="page-title">Dashboard</h4>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">Welcome John Doe</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- ======== breadcump end ========  -->


                    <!-- ======== products card start ========  -->

                    <div class="ic-section-gap">
                        <div class="row">
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                                <a href="https://clanvent-alpha.laravel-script.com/admin/customers">
                                    <div class="ic-card-head primary">
                                        <i class="flaticon-conversation ic-card-icon"></i>
                                        <i class="flaticon-conversation big-icon"></i>
                                        <h3>04</h3>
                                        <p>Total Customer</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                                <a href="https://clanvent-alpha.laravel-script.com/admin/suppliers">
                                    <div class="ic-card-head secondary">
                                        <i class="flaticon-inventory ic-card-icon "></i>
                                        <i class="flaticon-inventory big-icon"></i>
                                        <h3>01</h3>
                                        <p>Total Supplier</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                                <a href="https://clanvent-alpha.laravel-script.com/admin/products">
                                    <div class="ic-card-head info">
                                        <i class="flaticon-new-product ic-card-icon"></i>
                                        <i class="flaticon-new-product big-icon"></i>
                                        <h3>10</h3>
                                        <p>Total Product</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                                <a href="https://clanvent-alpha.laravel-script.com/admin/invoices">
                                    <div class="ic-card-head danger">
                                        <i class="flaticon-shopping-bag ic-card-icon"></i>
                                        <i class="flaticon-shopping-bag big-icon"></i>
                                        <h3>54</h3>
                                        <p>Total Sale</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                                <a href="https://clanvent-alpha.laravel-script.com/admin/purchases">
                                    <div class="ic-card-head success">
                                        <i class="flaticon-shopping-bag-1 ic-card-icon "></i>
                                        <i class="flaticon-shopping-bag-1 big-icon"></i>
                                        <h3>05</h3>
                                        <p>Total Purchase</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                                <a href="https://clanvent-alpha.laravel-script.com/admin/expenses">
                                    <div class="ic-card-head warning">
                                        <i class="flaticon-expenses ic-card-icon "></i>
                                        <i class="flaticon-expenses big-icon"></i>
                                        <h3>107</h3>
                                        <p>Total Expenses</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ======== products card end ========  -->

                    <!-- ======== chart start ========  -->

                    <div class="row">
                        <div class="col-lg-12 col-xl-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-item-center">
                                        <div class="col-lg-12 ic-expance-text-heading-part">
                                            <h4 class="ic-expance-heading">Sales This Year </h4>
                                            <h3 class="ic-earning-heading">$40439.00</h3>
                                        </div>
                                        <div class="col-lg-8 my-auto ic-expance-form-heads">
                                            <form action="">
                                                <div class="row input-daterange ic-mobile-range">
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="form-group mb-lg-0">
                                                            <input type="text" name="from_date" value="" id="from_date"
                                                                class="form-control" placeholder="From Date"
                                                                autocomplete="off" required />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="form-group mb-lg-0">
                                                            <input type="text" name="to_date" value="" id="to_date"
                                                                class="form-control" placeholder="To Date"
                                                                autocomplete="off" required />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3 col-12">
                                                        <button type="submit" class="btn btn-primary btn-block">
                                                            <i class="mdi mdi-filter"></i> Filter</button>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3 col-12">
                                                        <a href="https://clanvent-alpha.laravel-script.com/admin/dashboard"
                                                            class="btn btn-primary btn-block mt-3 mt-md-0">
                                                            <i class="mdi mdi-refresh"></i> Refresh</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div
                                            class="col-lg-4 my-auto ic-expance-form-chart input-daterange ic-mobile-range">
                                            <button class="btn btn-secondary" type="button" id='line'><i
                                                    class="fas fa-chart-line"></i>
                                                Line</button>
                                            <button class="btn btn-secondary" type="button" id='bar'><i
                                                    class="fas fa-chart-bar"></i>
                                                Bar</button>
                                        </div>
                                    </div>
                                    <canvas id="salesChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-4">
                            <div class="card ic-max-height-same">
                                <div class="card-body">
                                    <div class="ic-expance-part">
                                        <div class="ic-expance-text">
                                            <h4 class="ic-expance-heading">Sales All Time</h4>
                                            <h3 class="ic-earning-heading">$40439.00</h3>
                                        </div>
                                    </div>
                                    <div class="ic-piechart-part">
                                        <canvas id="pieChart"></canvas>
                                        <ul>
                                            <li><span class="this-mounth"><span class="circle-this"></span> This
                                                    Month
                                                    $11040.00</span></li>
                                            <li><span class="last-mounth"><span class="circle-last"></span> Last
                                                    Month
                                                    $0.00</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- ======== chart end ========  -->

                    <!-- ======== top Products start ========  -->

                    <div class="ic_products_heads">
                        <div class="row">
                            <div class="col-xl-6 col-lg-12">
                                <div class="card ic-card-height-same">
                                    <div class="card-body">
                                        <div
                                            class="ic-top-products-heading page-title-box pt-0 d-flex align-items-center justify-content-between">
                                            <h4 class="page-sub-title ">Top Product</h4>
                                            <div class="float-right d-none d-md-block">
                                                <div class="dropdown">
                                                    <button
                                                        class="btn btn-muted dropdown-toggle arrow-none waves-effect waves-light"
                                                        type="button" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        Month <i class="fas fa-chevron-down ml-2"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item ic-getTop-sale-products prevent-default"
                                                            id="year_2022" href="#">
                                                            Year</a>
                                                        <a class="dropdown-item ic-getTop-sale-products prevent-default"
                                                            id="month_2022-03" href="#">
                                                            Month</a>
                                                        <a class="dropdown-item ic-getTop-sale-products prevent-default"
                                                            id="week_2022-03-29" href="#">
                                                            Week</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-slider-heads">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div
                                            class="ic-top-products-heading page-title-box pt-0 d-flex align-items-center justify-content-between">
                                            <h4 class="page-sub-title ">Best Item All Time</h4>
                                            <div class="float-right d-none d-md-block">
                                                <div class="dropdown">
                                                    <a href="https://clanvent-alpha.laravel-script.com/admin/products"
                                                        class="btn btn-secondary2 dropdown-toggle arrow-none waves-effect waves-light">
                                                        View All
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ic-best-products-items">
                                            <div class="media d-flex">
                                                <img src="https://clanvent-alpha.laravel-script.com/storage/products/16395619893546.jpg"
                                                    class="img-fluid inline-block" alt="product-image">
                                                <div class="media-body">
                                                    <h6><a
                                                            href="https://clanvent-alpha.laravel-script.com/admin/products/43/edit">Apple
                                                            MacBook Air 13.3-Inch</a>
                                                    </h6>
                                                    <p>Total Sale : <span>$1581.00</span></p>
                                                </div>
                                            </div>
                                            <div class="media d-flex">
                                                <img src="https://clanvent-alpha.laravel-script.com/storage/products/16395617832768.jpg"
                                                    class="img-fluid inline-block" alt="product-image">
                                                <div class="media-body">
                                                    <h6><a
                                                            href="https://clanvent-alpha.laravel-script.com/admin/products/44/edit">HP
                                                            15s-du1115TU Celeron</a>
                                                    </h6>
                                                    <p>Total Sale : <span>$3881.00</span></p>
                                                </div>
                                            </div>
                                            <div class="media d-flex">
                                                <img src="https://clanvent-alpha.laravel-script.com/storage/products/16395617341836.jpg"
                                                    class="img-fluid inline-block" alt="product-image">
                                                <div class="media-body">
                                                    <h6><a
                                                            href="https://clanvent-alpha.laravel-script.com/admin/products/45/edit">Razer
                                                            Level Up Bundle</a>
                                                    </h6>
                                                    <p>Total Sale : <span>$1760.00</span></p>
                                                </div>
                                            </div>
                                            <div class="media d-flex">
                                                <img src="https://clanvent-alpha.laravel-script.com/storage/products/16395616944403.jpg"
                                                    class="img-fluid inline-block" alt="product-image">
                                                <div class="media-body">
                                                    <h6><a
                                                            href="https://clanvent-alpha.laravel-script.com/admin/products/46/edit">Edifier
                                                            G20 7.1</a>
                                                    </h6>
                                                    <p>Total Sale : <span>$1860.00</span></p>
                                                </div>
                                            </div>
                                            <div class="media d-flex">
                                                <img src="https://clanvent-alpha.laravel-script.com/storage/products/16395616231466.png"
                                                    class="img-fluid inline-block" alt="product-image">
                                                <div class="media-body">
                                                    <h6><a
                                                            href="https://clanvent-alpha.laravel-script.com/admin/products/47/edit">iPhone
                                                            13 Pro</a>
                                                    </h6>
                                                    <p>Total Sale : <span>$12350.00</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======== top Products ens ========  -->

                    <!-- ======== products-table start ========  -->

                    <div class="ic-products-table">
                        <div class="card">
                            <div class="card-body">
                                <label for="">Latest Sales</label>
                                <table id="table_id" class="datatable table">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Invoice ID</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Paid By</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td><a
                                                    href="https://clanvent-alpha.laravel-script.com/admin/invoices/73">00000073</a>
                                            </td>
                                            <td>2022-03-28</td>
                                            <td>
                                                Walk-In Customer
                                            </td>
                                            <td>$11040.00</td>
                                            <td>$140000.00</td>
                                            <td>CASH</td>
                                            <td><span class="badge badge-success">Paid</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td><a
                                                    href="https://clanvent-alpha.laravel-script.com/admin/invoices/72">00000072</a>
                                            </td>
                                            <td>2021-12-18</td>
                                            <td>
                                                Mary A. Smith
                                            </td>
                                            <td>$1380.00</td>
                                            <td>$380.00</td>
                                            <td>CASH</td>
                                            <td><span class="badge badge-info">Partially Paid</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td><a
                                                    href="https://clanvent-alpha.laravel-script.com/admin/invoices/71">00000071</a>
                                            </td>
                                            <td>2021-12-18</td>
                                            <td>
                                                Mary A. Smith
                                            </td>
                                            <td>$1380.00</td>
                                            <td>$380.00</td>
                                            <td>CASH</td>
                                            <td><span class="badge badge-info">Partially Paid</span></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td><a
                                                    href="https://clanvent-alpha.laravel-script.com/admin/invoices/70">00000070</a>
                                            </td>
                                            <td>2021-12-18</td>
                                            <td>
                                                Antonio L. Kurt
                                            </td>
                                            <td>$605.00</td>
                                            <td>$0.00</td>
                                            <td>ONLINE</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td><a
                                                    href="https://clanvent-alpha.laravel-script.com/admin/invoices/69">00000069</a>
                                            </td>
                                            <td>2021-12-18</td>
                                            <td>
                                                Walk-In Customer
                                            </td>
                                            <td>$600.00</td>
                                            <td>$0.00</td>
                                            <td>CASH</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ======== products-table ens ========  -->

                </div>
            </div>
            <!-- Invoice payment add modal  -->
            <div class="modal fade" id="invoicePaymentAdd" tabindex="-1" role="dialog"
                aria-labelledby="invoicePaymentAddTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form action="https://clanvent-alpha.laravel-script.com/admin/invoices/payments" method="post">
                            <input type="hidden" name="_token" value="HbfJOVbwfPbpbVkHx4SxuTtKUgIsUR5bBbGympZ8"> <input
                                type="hidden" name="invoice_id" id="add-invoice-payment-invoice-id">

                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Add Paymet</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Date <span class="error">*</span></label>
                                        <input type="text" name="date" class="form-control datepicker-autoclose"
                                            autocomplete="off" value="" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Payment Type <span class="error">*</span></label>
                                        <input type="text" name="payment_type" class="form-control" value="" required
                                            maxlength="50">

                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Amount <span class="error">*</span></label>
                                        <input type="number" name="amount" class="form-control" value="" required>

                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Notes</label>
                                        <input type="text" name="notes" class="form-control" value=""
                                            maxlength="200">

                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Invoice payment show modal  -->
            <div class="modal fade" id="invoicePaymentView" tabindex="-1" role="dialog"
                aria-labelledby="invoicePaymentViewTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Payment History</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table id="invoice-payment-view-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">Payment Type</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Notes</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice payment send modal  -->
            <div class="modal fade" id="invoicePaymentSend" tabindex="-1" role="dialog"
                aria-labelledby="invoicePaymentSendTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form action="https://clanvent-alpha.laravel-script.com/admin/invoices/payments/send"
                            method="post">
                            <input type="hidden" name="_token" value="HbfJOVbwfPbpbVkHx4SxuTtKUgIsUR5bBbGympZ8"> <input
                                type="hidden" name="invoice_id" id="send-invoice-payment-invoice-id">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Send Invoice</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Email <span class="error">*</span></label>
                                        <input id="send-invoice-payment-email" type="email" name="email"
                                            class="form-control" value="" required maxlength="50">

                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Send Invoice</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Invoice live url  -->
            <div class="modal fade" id="liveInvoiceUrl" tabindex="-1" role="dialog"
                aria-labelledby="liveInvoiceUrlTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Invoice Live URL</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="live-invoice-token" disabled>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary copy-url-btn">Copy URL</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            © 2022 All Right Reserved | Design &amp; Developed by<a class="ic-main-color" data-toggle="tooltip"
                data-placement="top" title="ITclan BD" href="https://itclanbd.com/"> ITclan BD</a>
        </footer>
        <!-- Vue -->
        <script src="https://clanvent-alpha.laravel-script.com/js/app.js"></script>
        <!-- App's Basic Js  -->
        <script src="https://clanvent-alpha.laravel-script.com/admin/js/jquery.min.js"></script>
        <script src="https://clanvent-alpha.laravel-script.com/admin/js/bootstrap.bundle.min.js"></script>
        <script src="https://clanvent-alpha.laravel-script.com/admin/js/metisMenu.min.js"></script>
        <script src="https://clanvent-alpha.laravel-script.com/admin/js/jquery.slimscroll.js"></script>
        <script src="https://clanvent-alpha.laravel-script.com/admin/js/waves.min.js"></script>
        <!-- App js-->
        <script src="https://clanvent-alpha.laravel-script.com/admin/js/app.js"></script>
        <!-- Sweet-Alert  -->
        <script src="https://clanvent-alpha.laravel-script.com/admin/plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script src="https://clanvent-alpha.laravel-script.com/admin/pages/sweet-alert.init.js"></script>
        <!-- Required datatable js -->
        <script src="https://clanvent-alpha.laravel-script.com/admin/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="https://clanvent-alpha.laravel-script.com/admin/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <!-- Responsive examples -->
        <script src="https://clanvent-alpha.laravel-script.com/admin/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="https://clanvent-alpha.laravel-script.com/admin/plugins/datatables/responsive.bootstrap4.min.js"></script>
        <!-- intlTelInput  -->
        <script src="https://clanvent-alpha.laravel-script.com/plugins/intl/build/js/intlTelInput-jquery.min.js"></script>
        <!-- Select2  -->
        <script src="https://clanvent-alpha.laravel-script.com/admin/plugins/select2/js/select2.min.js"></script>
        <!-- Datepicker  -->
        <script src="https://clanvent-alpha.laravel-script.com/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js">
        </script>
        <!-- Chart  -->
        <script src="https://clanvent-alpha.laravel-script.com/admin/plugins/chartjs/Chart.js"></script>
        <script src="https://clanvent-alpha.laravel-script.com/admin/plugins/chartist/js/chartist.min.js"></script>
        <script src="https://clanvent-alpha.laravel-script.com/admin/plugins/chartist/js/chartist-plugin-tooltip.min.js">
        </script>
        <!-- peity JS -->
        <script src="https://clanvent-alpha.laravel-script.com/admin/plugins/peity-chart/jquery.peity.min.js"></script>
        <!-- slick  -->
        <script src="https://clanvent-alpha.laravel-script.com/admin/js/slick.min.js"></script>
        <!-- barcode  -->
        <script src="https://clanvent-alpha.laravel-script.com/plugins/jsbarcode/jsbarcode.js"></script>

        <!-- Load script form view  -->


        <script type="text/javascript">
            !(function($) {
                "use strict";

                // top sale product js
                $('.ic-getTop-sale-products').on('click', function() {
                    let data = $(this).attr('id');
                    let array = data.split('_');
                    getTopSaleProducts(array[0], array[1]);
                });
                getTopSaleProducts("month", "2022-03")

                function getTopSaleProducts(key, value) {
                    if (key == "month") {
                        $.get("https://clanvent-alpha.laravel-script.com/admin/app/api/top-product", {
                            "month": value
                        }, function(response) {
                            let gethtml = '';
                            response.forEach(function(item) {
                                gethtml += `
                            <div class="ic-products-card border">
                                <img src="` + item.thumb_url + `" class="img-fluid"
                                    alt="` + item.name + `">
                                <div class="ic-product-content">
                                    <h6>` + item.name + `</h6>
                                    <p class="mb-0">$` + item.total_sale + `</p>
                                </div>
                            </div>
                        `;
                            });
                            $('.product-slider-heads').html(gethtml);
                        })

                    } else if (key == "year") {
                        $.get("https://clanvent-alpha.laravel-script.com/admin/app/api/top-product", {
                            "year": value
                        }, function(response) {
                            let gethtml = '';
                            response.forEach(function(item) {
                                gethtml += `
                                <div class="ic-products-card border">
                                    <img src="` + item.thumb_url + `" class="img-fluid"
                                        alt="` + item.name + `">
                                    <div class="ic-product-content">
                                        <h6>` + item.name + `</h6>
                                        <p class="mb-0">$` + item.total_sale + `</p>
                                    </div>
                                </div>
                            `;
                            });
                            $('.product-slider-heads').html(gethtml);
                        });

                    } else {
                        $.get("https://clanvent-alpha.laravel-script.com/admin/app/api/top-product", {
                            "week": value
                        }, function(response) {
                            let gethtml = '';
                            response.forEach(function(item) {
                                gethtml += `
                                <div class="ic-products-card border">
                                    <img src="` + item.thumb_url + `" class="img-fluid"
                                        alt="` + item.name + `">
                                    <div class="ic-product-content">
                                        <h6>` + item.name + `</h6>
                                        <p class="mb-0">$` + item.total_sale + `</p>
                                    </div>
                                </div>
                            `;
                            });

                            $('.product-slider-heads').html(gethtml);
                        });

                    }
                }

                // line Chart
                const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ];
                const data = {
                    labels: labels,
                    datasets: [{
                        label: 'Sales($)',
                        backgroundColor: '#FF5733',
                        borderColor: '#FF5733',
                        data: [0, 0, "11040.00", 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    }]
                };

                // init config
                const config = {
                    type: 'line',
                    data,
                    options: {}
                };

                var myChart;

                icChange('line');
                $("#line").on('click', function() {
                    icChange('line');
                });

                $("#bar").on('click', function() {
                    icChange('bar');
                });

                function icChange(newType) {
                    var ctx = document.getElementById("salesChart").getContext("2d");

                    if (myChart) {
                        myChart.destroy();
                    }

                    var temp = jQuery.extend(true, {}, config);
                    temp.type = newType;
                    myChart = new Chart(ctx, temp);
                };

                // pie chart
                var oilCanvas = document.getElementById("pieChart");
                var oilData = {
                    datasets: [{
                        data: [0, 0, "11040.00", 0, 0, 0, 0, 0, 0, 0, "15219.00", "14180.00"],
                        backgroundColor: [
                            "#FF6384",
                            "#63FF84",
                            "#6FE3D5",
                            "#5182FF",
                            "#56C876",
                            "#2A73A8",
                            "#EEBF48",
                            "#6FE3C0",
                            "#28AAA9",
                            "#6FE3C0",
                            "#3D96FF",
                            "#E36F6F"
                        ]
                    }]
                };

                var pieChart = new Chart(oilCanvas, {
                    type: 'pie',
                    data: oilData,
                    options: {
                        responsive: true,
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                    },
                });

            })(jQuery);
        </script>

        <!-- Custom js  -->
        <script src="https://clanvent-alpha.laravel-script.com/admin/js/custom.js"></script>
        <script src="https://clanvent-alpha.laravel-script.com/admin/js/custom-dev.js"></script>

        <!-- Load language  -->
        <script type="text/javascript">
            !(function($) {
                "use Strict"
                window._locale = 'en';
                window._translations = {
                    "en": {
                        "php": {
                            "auth": {
                                "failed": "These credentials do not match our records.",
                                "password": "The provided password is incorrect.",
                                "throttle": "Too many login attempts. Please try again in :seconds seconds."
                            },
                            "custom": {
                                "user": "User",
                                "user_name": "User Name",
                                "add_user": "Add User",
                                "edit_user": "Edit User",
                                "user_list": "User List",
                                "user_items": "User Items",
                                "role": "Role",
                                "role_name": "Role Name",
                                "add_role": "Add Role",
                                "edit_role": "Edit Role",
                                "role_list": "Role List",
                                "all_permission": "All Permission",
                                "permissions": "Permissions",
                                "role_details": "Role Details",
                                "warehouse": "Warehouse",
                                "warehouse_name": "Warehouse Name",
                                "add_warehouse": "Add Warehouse",
                                "edit_warehouse": "Edit Warehouse",
                                "warehouse_list": "Warehouse List",
                                "is_default_warehouse": "Is Default Warehouse",
                                "warehouse_priority_message": "(Inventory will be increased or decreased from the warehouse by priority)",
                                "brand": "Brand",
                                "brand_name": "Brand Name",
                                "add_brand": "Add Brand",
                                "edit_brand": "Edit Brand",
                                "brand_list": "Brand List",
                                "manufacturer": "Manufacturer",
                                "manufacturer_name": "Manufacturer Name",
                                "add_manufacturer": "Add Manufacturer",
                                "edit_manufacturer": "Edit Manufacturer",
                                "manufacturer_list": "Manufacturer List",
                                "weight_unit": "Weight Unit",
                                "weight_unit_name": "Weight Unit Name",
                                "add_weight_unit": "Add Weight Unit",
                                "edit_weight_unit": "Edit Weight Unit",
                                "weight_unit_list": "Weight Unit List",
                                "measurement_unit": "Measurement Unit",
                                "measurement_unit_name": "Measurement Unit Name",
                                "add_measurement_unit": "Add Measurement Unit",
                                "edit_measurement_unit": "Edit Measurement Unit",
                                "measurement_unit_list": "Measurement Unit List",
                                "product_category": "Product Category",
                                "add_product_category": "Add Product Category",
                                "edit_product_category": "Edit Product Category",
                                "product_category_list": "Product Category List",
                                "attribute": "Attribute",
                                "attributes": "Attributes",
                                "attribute_name": "Attribute Name",
                                "add_attribute": "Add Attribute",
                                "edit_attribute": "Edit Attribute",
                                "attribute_list": "Attribute List",
                                "attribute_items": "Attribute Item",
                                "product": "Product",
                                "product_name": "Product Name",
                                "add_product": "Add Product",
                                "edit_product": "Edit Product",
                                "product_list": "Product List",
                                "product_items": "Product Items",
                                "download_barcode": "Download Barcode",
                                "download_all_barcode": "Download All Barcode",
                                "update_product_stock": "Update Product Stock",
                                "product_stock": "Product Stock",
                                "customer": "Customer",
                                "customer_name": "Customer Name",
                                "add_customer": "Add Customer",
                                "edit_customer": "Edit Customer",
                                "customer_list": "Customer List",
                                "customer_items": "Customer Items",
                                "customer_phone": "Customer Phone",
                                "customer_email": "Customer Email",
                                "customer_country": "Customer Country",
                                "customer_city": "Customer City",
                                "customer_state": "Customer state",
                                "supplier": "Supplier",
                                "supplier_name": "Supplier Name",
                                "add_supplier": "Add Supplier",
                                "edit_supplier": "Edit Supplier",
                                "supplier_list": "Supplier List",
                                "supplier_items": "Supplier Items",
                                "expenses_category": "Expenses Category",
                                "add_expenses_category": "Add Expenses Category",
                                "edit_expenses_category": "Edit Expenses Category",
                                "expenses_category_list": "Expenses Category List",
                                "expenses": "Expenses",
                                "expenses_name": "Expenses Name",
                                "add_expenses": "Add Expenses",
                                "edit_expenses": "Edit Expenses",
                                "expenses_list": "Expenses List",
                                "expenses_items": "Expenses Items",
                                "sale": "Sale",
                                "sales": "Sales",
                                "add_sale": "Add Sale",
                                "edit_sale": "Edit Sale",
                                "sale_list": "Sale List",
                                "sale_items": "Sale Items",
                                "payment_type": "Payment Type",
                                "payments": "Payments",
                                "report": "Repost",
                                "reports": "Reports",
                                "expenses_report": "Expenses Report",
                                "sales_report": "Sales Report",
                                "purchases_report": "Purchases Report",
                                "payments_report": "Payments Report",
                                "profile": "Profile",
                                "edit_profile": "Edit Profile",
                                "invoice": "Invoice",
                                "invoice_id": "Invoice ID",
                                "add_invoice": "Add Invoice",
                                "edit_invoice": "Edit Invoice",
                                "invoice_list": "Invoice List",
                                "invoice_items": "Invoice Items",
                                "due": "Due",
                                "gross_total": "Gross Total",
                                "net_total": "Net Total",
                                "paid": "Paid",
                                "paid_by": "Paid By",
                                "no_product_fount": "No product found!",
                                "stock_out": "Stock Out",
                                "walk_in_customer": "Walk-in Customer",
                                "same_as_shipping": "Same as shipping",
                                "item": "Item",
                                "qty": "Qty",
                                "dis": "Dis",
                                "dis_type": "Dis Type",
                                "fixed": "Fixed",
                                "total_paid": "Total Paid",
                                "save_and_close": "Save & Close",
                                "required": "Required",
                                "due_date": "Due Date",
                                "view_payment": "View Payment",
                                "add_payment": "Add Paymet",
                                "make_payment": "Make Payment",
                                "account_number": "Account Number",
                                "transaction_no": "Transaction No",
                                "transaction_date": "Transaction Date",
                                "latest_sales": "Latest Sales",
                                "total_due": "Total Due",
                                "payment_method": "Payment Method",
                                "payment_history": "Payment History",
                                "login_background": "Login Background",
                                "login_message_system": "Login Message",
                                "login_slider_pc": "Login Slider PC",
                                "login_slider_mobile": "Login Slider Mobile",
                                "login_slider_image_1": "Slider Image 1",
                                "login_slider_image_2": "Slider Image 2",
                                "login_slider_image_3": "Slider Image 3",
                                "login_slider_image_m_1": "Mobile Slider Image 1",
                                "login_slider_image_m_2": "Mobile Slider Image 2",
                                "login_slider_image_m_3": "Mobile Slider Image 3",
                                "general": "General",
                                "settings": "Settings",
                                "info": "Info",
                                "site_title": "Site Title",
                                "site_logo": "Site Logo",
                                "currency_symbol": "Custom Currency Symbol",
                                "paypal": "Paypal",
                                "client_id": "Client ID",
                                "client_secret": "Client Secret",
                                "secret_key": "Secret Key",
                                "smtp_mail": "SMTP Mail",
                                "host": "Host",
                                "port": "Port",
                                "username": "Username",
                                "from_address": "From Address",
                                "from_name": "From Name",
                                "save": "Save",
                                "sl": "SL",
                                "action": "Action",
                                "submit": "Submit",
                                "cancel": "Cancel",
                                "edit": "Edit",
                                "delete": "Delete",
                                "show": "Show",
                                "stock": "Stock",
                                "name": "Name",
                                "email": "Email",
                                "phone": "Phone",
                                "this_year": "This Year",
                                "month": "Month",
                                "week": "Week",
                                "year": "Year",
                                "total_discount": "Total Discount",
                                "company_name": "Company Name",
                                "top_product": "Top Product",
                                "address_1": "Address 1",
                                "address_2": "Address 2",
                                "priority": "Priority",
                                "default": "Default",
                                "status": "Status",
                                "active": "Active",
                                "inactive": "Inactive",
                                "image": "Image",
                                "desc": "Description",
                                "catalog": "Catalog",
                                "category_name": "Category Name",
                                "parent_category": "Parent Category",
                                "barcode": "Barcode",
                                "model": "Model",
                                "price": "Price",
                                "weight": "Weight",
                                "width": "Width",
                                "length": "Length",
                                "depth": "Depth",
                                "notes": "Notes",
                                "thumb": "Thumb",
                                "sku": "SKU",
                                "admin": "Admin",
                                "default_tax": "Default Tax",
                                "base_url": "Base URL",
                                "public_key": "Public Key",
                                "category": "Category",
                                "products": "Products",
                                "is_variant_product": "Is variant product",
                                "is_variant": "Is Variant",
                                "variant": "Variant",
                                "billing_address_same": "Billing address same as address",
                                "stripe_setup_message": "Please setup stripe at settings",
                                "first_name": "First Name",
                                "last_name": "Last Name",
                                "company": "Company",
                                "designation": "Designation",
                                "address_line_1": "Address Line 1",
                                "address_line_2": "Address Line 2",
                                "country": "Country",
                                "state": "State",
                                "city": "City",
                                "avatar": "Avatar",
                                "billing_address": "Billing Address",
                                "address": "Address",
                                "zipcode": "Zip Code",
                                "select": "Select",
                                "date": "Date",
                                "title": "Title",
                                "files": "Files",
                                "total": "Total",
                                "item_name": "Item Name",
                                "amount": "Amount",
                                "note": "Note",
                                "back": "Back",
                                "filter": "Filter",
                                "refresh": "Refresh",
                                "add_item": "Add Item",
                                "line": "Line",
                                "bar": "Bar",
                                "color": "Color",
                                "old_image": "Old Image",
                                "monthly": "Monthly",
                                "this_month": "This Month",
                                "last_month": "Last Month",
                                "expenses_all_time": "Expenses All Time",
                                "sales_all_time": "Sales All Time",
                                "max_50_char": "(Max 500 chars)",
                                "select_category": "Select category",
                                "select_brand": "Select brand",
                                "select_manufacturer": "Select manufacturer",
                                "select_weight_unit": "Select weight unit",
                                "select_measurement_unit": "Select measurement unit",
                                "image_support_message": "(Supported type: png, jpg, jpeg | Max size: 300kb)",
                                "favicon": "Favicon",
                                "generate": "Generate",
                                "print": "Print",
                                "pdf": "PDF",
                                "csv": "CSV",
                                "excel": "Excel",
                                "all_time": "All Time",
                                "purchase": "Purchase",
                                "purchases": "Purchases",
                                "add": "Add",
                                "view": "View",
                                "quantity": "Quantity",
                                "sub_total": "Sub Total",
                                "add_more": "Add More",
                                "search_product": "Search Product",
                                "search_product_by": "Search Product By Name and SKU",
                                "discount": "Discount",
                                "percentage": "Percentage",
                                "tax": "Tax",
                                "purchase_create_successful": "Purchase Create Successful",
                                "purchase_create_failed": "Purchase Create Failed",
                                "purchases_list": "Purchase List",
                                "purchase_number": "Purchase Number",
                                "total_product": "Total Product",
                                "receive": "Receive",
                                "purchase_update_successful": "Purchase Update Successful",
                                "purchase_cancel_successful": "Purchase Cancel Successful",
                                "purchase_update_failed": "Purchase Update Failed",
                                "purchase_delete_successful": "Purchase Delete Successful",
                                "purchase_already_use": "This Purchase Already Use in Another Table",
                                "supplier_phone": "Supplier Phone",
                                "receive_date": "Receive Date",
                                "purchase_order": "Purchase Order",
                                "purchase_receive": "Purchase Receive",
                                "purchase_order_received": "Purchase Order Received",
                                "purchase_receive_successful": "Purchase Receive Successful",
                                "purchase_receive_failed": "Purchase Receive Failed",
                                "no_available_quantity_for_receive": "No Available Quantity for Receive",
                                "not_received_yet": "Not Received Yet",
                                "received": "Received",
                                "purchase_receive_list": "Purchase Receive List",
                                "purchase_receive_details": "Purchase Receive Details",
                                "purchase_receive_delete_successful": "Purchase Receive Delete Successful",
                                "purchase_receive_already_use": "This Purchase Receive Already use in Another Table",
                                "confirm": "Confirm",
                                "purchase_confirm_successful": "Purchase Confirm Successful",
                                "purchase_return": "Purchase Return",
                                "return": "Return",
                                "return_note": "Return Note",
                                "return_date": "Return Date",
                                "no_available_quantity_for_return": "No Available Quantity for Return",
                                "purchase_return_successful": "Purchase Return Successful",
                                "return_quantity": "Return Quantity",
                                "purchase_return_list": "Purchase Return List",
                                "purchase_return_details": "Purchase Return Details",
                                "purchase_return_delete_successful": "Purchase Return Delete Successful",
                                "purchase_return_already_use": "This Purchase Return Already Use in Another Table",
                                "missing_item": "Missing Item",
                                "missing": "Missing",
                                "administration": "Administration",
                                "components": "Components",
                                "users": "Users",
                                "roles": "Roles",
                                "customers": "Customers",
                                "suppliers": "Suppliers",
                                "invoice_manage": "Invoice Manage",
                                "include": "Include",
                                "exclude": "Exclude",
                                "password": "Password",
                                "confirm_password": "Confirm Password",
                                "chose_file": "Chose File",
                                "select_role": "Select Role",
                                "custom_tax_amount": "Custom tax amount",
                                "dashboard": "Dashboard",
                                "best_item_all_time": "Best Item All Time",
                                "view_all": "View All",
                                "total_sale": "Total Sale",
                                "welcome": "Welcome",
                                "sale_return": "Sale Return",
                                "billing_info": "Billing info",
                                "shipping_info": "Shipping info",
                                "sale_number": "Sale Number",
                                "sale_date": "Sale Date",
                                "zip": "Zip",
                                "main": "Main",
                                "more": "More",
                                "download": "Download",
                                "send": "Send",
                                "link": "Link",
                                "sales_return_successful": "Sales Return Successful",
                                "available": "Available",
                                "no_available_quantity": "No available quantity",
                                "sale_return_list": "Sale Return List",
                                "invoice_number": "Invoice Number",
                                "sale_return_details": "Sale Return Details",
                                "post_code_or_zip_code": "Postcode\/Zip Code",
                                "payment": "Payment",
                                "select_warehouse": "Select warehouse",
                                "search_product_by_name_sku": "Search product by Name and SKU",
                                "product_already_added": "Product already added to the list!",
                                "field_is_required": "Field is required",
                                "shipping_address_same_as_billing": "Shipping address same as billing",
                                "login_my_account": "Login in my account",
                                "all_right_reserved": "All Right Reserved",
                                "design_and_developed": "Design & Developed by",
                                "choose_file": "choose File",
                                "billing_to": "Billing To",
                                "shipped_to": "Shipped To",
                                "summary": "Summary",
                                "create_invoice": "Create Invoice",
                                "billed_to": "Billed To",
                                "stripe": "Stripe",
                                "payment_successful": "Payment Successful",
                                "we_are_delighted_to_inform": "We are delighted to inform you that we received your payments.",
                                "show_invoice": "Show Invoice",
                                "payment_url": "Payment URL",
                                "copy_url": "Copy URL",
                                "close": "Close",
                                "save_payment": "Save Payment",
                                "all_payments": "All Payments",
                                "send_invoice": "Send Invoice",
                                "invoice_live_url": "Invoice Live URL",
                                "logout": "Logout",
                                "dimension": "Dimension",
                                "export": "Export",
                                "pay_with": "Pay With",
                                "use_payment_method": "Use Payment Method",
                                "profile_update_successful": "Profile updated successfully",
                                "profile_update_failed": "Profile update failed",
                                "role_create_successful": "Role created successfully",
                                "role_create_failed": "Role create failed",
                                "role_updated_successful": "Role updated successfully",
                                "role_updated_failed": "Role updated failed",
                                "role_deleted_successful": "Role deleted successfully",
                                "role_deleted_failed": "Role deleted failed",
                                "user_create_successful": "User created successfully",
                                "user_create_failed": "User create failed",
                                "user_updated_successful": "User updated successfully",
                                "user_updated_failed": "User updated failed",
                                "user_deleted_successful": "User deleted successfully",
                                "user_deleted_failed": "User deleted failed",
                                "you_cant_delete_your_self": "You can't delete yourself",
                                "you_cant_delete_last_user": "You can't delete last user",
                                "attribute_create_successful": "Attribute created successfully",
                                "attribute_create_failed": "Attribute create failed",
                                "attribute_updated_successful": "Attribute updated successfully",
                                "attribute_updated_failed": "Attribute updated failed",
                                "attribute_deleted_successful": "Attribute deleted successfully",
                                "attribute_deleted_failed": "Attribute deleted failed",
                                "brand_create_successful": "Brand created successfully",
                                "brand_create_failed": "Brand create failed",
                                "brand_updated_successful": "Brand updated successfully",
                                "brand_updated_failed": "Brand updated failed",
                                "brand_deleted_successful": "Brand deleted successfully",
                                "brand_deleted_failed": "Brand deleted failed",
                                "customer_create_successful": "Customer created successfully",
                                "customer_create_failed": "Customer create failed",
                                "customer_updated_successful": "Customer updated successfully",
                                "customer_updated_failed": "Customer updated failed",
                                "customer_deleted_successful": "Customer deleted successfully",
                                "customer_deleted_failed": "Customer deleted failed",
                                "expenses_category_create_successful": "Expenses Category created successfully",
                                "expenses_category_create_failed": "Expenses Category create failed",
                                "expenses_category_updated_successful": "Expenses Category updated successfully",
                                "expenses_category_updated_failed": "Expenses Category updated failed",
                                "expenses_category_deleted_successful": "Expenses Category deleted successfully",
                                "expenses_category_deleted_failed": "Expenses Category deleted failed",
                                "expenses_create_successful": "Expenses created successfully",
                                "expenses_create_failed": "Expenses create failed",
                                "expenses_updated_successful": "Expenses updated successfully",
                                "expenses_updated_failed": "Expenses updated failed",
                                "expenses_deleted_successful": "Expenses deleted successfully",
                                "expenses_deleted_failed": "Expenses deleted failed",
                                "expenses_details": "Expenses Details",
                                "file_deleted_successfully": "File deleted successfully",
                                "file_deleted_fail": "File deleted fail",
                                "invoice_created_successful": "Invoice created successfully",
                                "invoice_created_failed": "Invoice create failed",
                                "invoice_updated_successful": "Invoice updated successfully",
                                "invoice_updated_failed": "Invoice updated failed",
                                "invoice_deleted_successful": "Invoice deleted successfully",
                                "invoice_deleted_failed": "Invoice deleted failed",
                                "payment_added_successful": "Payment added successfully",
                                "payment_added_failed": "Payment added failed",
                                "payment_deleted_successful": "Payment deleted successfully",
                                "invoice_added_successful": "Invoice added successfully",
                                "invoice_added_failed": "Invoice added failed",
                                "make_invoice": "Make Invoice",
                                "payment_make_successfully": "Payment make successfully",
                                "manufacturer_created_successfully": "Manufacturer created successfully",
                                "manufacturer_create_failed": "Manufacturer create failed",
                                "manufacturer_updated_successfully": "Manufacturer updated successfully",
                                "manufacturer_update_failed": "Manufacturer update failed",
                                "manufacturer_deleted_successfully": "Manufacturer delete successfully",
                                "manufacturer_deleted_failed": "Manufacturer delete failed",
                                "measurement_unit_created_successfully": "Measurement Unit created successfully",
                                "measurement_unit_create_failed": "Measurement Unit create failed",
                                "measurement_unit_updated_successfully": "Measurement Unit updated successfully",
                                "measurement_unit_update_failed": "Measurement Unit update failed",
                                "measurement_unit_deleted_successfully": "Measurement Unit deleted successfully",
                                "measurement_unit_delete_failed": "Measurement Unit delete failed",
                                "product_category_created_successfully": "Product Category created successfully",
                                "product_category_create_failed": "Product Category create failed",
                                "product_category_updated_successfully": "Product Category updated successfully",
                                "product_category_update_failed": "Product Category update failed",
                                "product_category_deleted_successfully": "Product Category deleted successfully",
                                "product_category_delete_failed": "Product Category delete failed",
                                "product_created_successfully": "Product created successfully",
                                "product_create_failed": "Product create failed",
                                "product_updated_successfully": "Product updated successfully",
                                "product_update_failed": "Product update failed",
                                "product_deleted_successfully": "Product deleted successfully",
                                "product_delete_failed": "Product delete failed",
                                "product_stock_update_successfully": "Product stock update successfully",
                                "product_stock_update_failed": "Product update failed",
                                "supplier_created_successfully": "Supplier created successfully",
                                "supplier_create_failed": "Supplier create failed",
                                "supplier_updated_successfully": "Supplier updated successfully",
                                "supplier_update_failed": "Supplier update failed",
                                "supplier_deleted_successfully": "Supplier deleted successfully",
                                "supplier_delete_failed": "Supplier delete failed",
                                "warehouse_created_successfully": "Warehouse created successfully",
                                "warehouse_create_failed": "Warehouse create failed",
                                "warehouse_update_successfully": "Warehouse updated successfully",
                                "warehouse_update_failed": "Warehouse update failed",
                                "warehouse_delete_successfully": "Warehouse deleted successfully",
                                "warehouse_delete_failed": "Warehouse delete failed",
                                "weight_unit_created_successfully": "Weight Unit created successfully",
                                "weight_unit_create_failed": "Weight Unit create failed",
                                "weight_Unit_updated_successfully": "Weight Unit updated successfully",
                                "weight_Unit_update_failed": "Weight Unit update failed",
                                "weight_unit_deleted_successfully": "Weight Unit deleted successfully",
                                "weight_unit_delete_failed": "Weight Unit delete failed",
                                "this_record_already_used": "This record already used",
                                "login": "Login",
                                "login_message": "Manage your business with our automated Inventory Management System"
                            },
                            "installer_messages": {
                                "title": "Laravel Installer",
                                "next": "Next Step",
                                "back": "Previous",
                                "finish": "Install",
                                "forms": {
                                    "errorTitle": "The Following errors occurred:"
                                },
                                "welcome": {
                                    "templateTitle": "Welcome",
                                    "title": "Laravel Installer",
                                    "message": "Easy Installation and Setup Wizard.",
                                    "next": "Check Requirements"
                                },
                                "requirements": {
                                    "templateTitle": "Step 1 | Server Requirements",
                                    "title": "Server Requirements",
                                    "next": "Check Permissions"
                                },
                                "permissions": {
                                    "templateTitle": "Step 2 | Permissions",
                                    "title": "Permissions",
                                    "next": "Configure Environment"
                                },
                                "environment": {
                                    "menu": {
                                        "templateTitle": "Step 3 | Environment Settings",
                                        "title": "Environment Settings",
                                        "desc": "Please select how you want to configure the apps <code>.env<\/code> file.",
                                        "wizard-button": "Form Wizard Setup",
                                        "classic-button": "Classic Text Editor"
                                    },
                                    "wizard": {
                                        "templateTitle": "Step 3 | Environment Settings | Guided Wizard",
                                        "title": "Guided <code>.env<\/code> Wizard",
                                        "tabs": {
                                            "environment": "Environment",
                                            "database": "Database",
                                            "application": "Application"
                                        },
                                        "form": {
                                            "name_required": "An environment name is required.",
                                            "app_name_label": "App Name",
                                            "app_name_placeholder": "App Name",
                                            "app_environment_label": "App Environment",
                                            "app_environment_label_local": "Local",
                                            "app_environment_label_developement": "Development",
                                            "app_environment_label_qa": "Qa",
                                            "app_environment_label_production": "Production",
                                            "app_environment_label_other": "Other",
                                            "app_environment_placeholder_other": "Enter your environment...",
                                            "app_debug_label": "App Debug",
                                            "app_debug_label_true": "True",
                                            "app_debug_label_false": "False",
                                            "app_log_level_label": "App Log Level",
                                            "app_log_level_label_debug": "debug",
                                            "app_log_level_label_info": "info",
                                            "app_log_level_label_notice": "notice",
                                            "app_log_level_label_warning": "warning",
                                            "app_log_level_label_error": "error",
                                            "app_log_level_label_critical": "critical",
                                            "app_log_level_label_alert": "alert",
                                            "app_log_level_label_emergency": "emergency",
                                            "app_url_label": "App Url",
                                            "app_url_placeholder": "App Url",
                                            "db_connection_failed": "Could not connect to the database.",
                                            "db_connection_label": "Database Connection",
                                            "db_connection_label_mysql": "mysql",
                                            "db_connection_label_sqlite": "sqlite",
                                            "db_connection_label_pgsql": "pgsql",
                                            "db_connection_label_sqlsrv": "sqlsrv",
                                            "db_host_label": "Database Host",
                                            "db_host_placeholder": "Database Host",
                                            "db_port_label": "Database Port",
                                            "db_port_placeholder": "Database Port",
                                            "db_name_label": "Database Name",
                                            "db_name_placeholder": "Database Name",
                                            "db_username_label": "Database User Name",
                                            "db_username_placeholder": "Database User Name",
                                            "db_password_label": "Database Password",
                                            "db_password_placeholder": "Database Password",
                                            "app_tabs": {
                                                "more_info": "More Info",
                                                "broadcasting_title": "Broadcasting, Caching, Session, &amp; Queue",
                                                "broadcasting_label": "Broadcast Driver",
                                                "broadcasting_placeholder": "Broadcast Driver",
                                                "cache_label": "Cache Driver",
                                                "cache_placeholder": "Cache Driver",
                                                "session_label": "Session Driver",
                                                "session_placeholder": "Session Driver",
                                                "queue_label": "Queue Driver",
                                                "queue_placeholder": "Queue Driver",
                                                "redis_label": "Redis Driver",
                                                "redis_host": "Redis Host",
                                                "redis_password": "Redis Password",
                                                "redis_port": "Redis Port",
                                                "mail_label": "Mail",
                                                "mail_driver_label": "Mail Driver",
                                                "mail_driver_placeholder": "Mail Driver",
                                                "mail_host_label": "Mail Host",
                                                "mail_host_placeholder": "Mail Host",
                                                "mail_port_label": "Mail Port",
                                                "mail_port_placeholder": "Mail Port",
                                                "mail_username_label": "Mail Username",
                                                "mail_username_placeholder": "Mail Username",
                                                "mail_password_label": "Mail Password",
                                                "mail_password_placeholder": "Mail Password",
                                                "mail_encryption_label": "Mail Encryption",
                                                "mail_encryption_placeholder": "Mail Encryption",
                                                "pusher_label": "Pusher",
                                                "pusher_app_id_label": "Pusher App Id",
                                                "pusher_app_id_palceholder": "Pusher App Id",
                                                "pusher_app_key_label": "Pusher App Key",
                                                "pusher_app_key_palceholder": "Pusher App Key",
                                                "pusher_app_secret_label": "Pusher App Secret",
                                                "pusher_app_secret_palceholder": "Pusher App Secret"
                                            },
                                            "buttons": {
                                                "setup_database": "Setup Database",
                                                "setup_application": "Setup Application",
                                                "install": "Install"
                                            }
                                        }
                                    },
                                    "classic": {
                                        "templateTitle": "Step 3 | Environment Settings | Classic Editor",
                                        "title": "Classic Environment Editor",
                                        "save": "Save .env",
                                        "back": "Use Form Wizard",
                                        "install": "Save and Install"
                                    },
                                    "success": "Your .env file settings have been saved.",
                                    "errors": "Unable to save the .env file, Please create it manually."
                                },
                                "install": "Install",
                                "installed": {
                                    "success_log_message": "Laravel Installer successfully INSTALLED on "
                                },
                                "final": {
                                    "title": "Installation Finished",
                                    "templateTitle": "Installation Finished",
                                    "finished": "Application has been successfully installed.",
                                    "migration": "Migration &amp; Seed Console Output:",
                                    "console": "Application Console Output:",
                                    "log": "Installation Log Entry:",
                                    "env": "Final .env File:",
                                    "exit": "Click here to exit"
                                },
                                "updater": {
                                    "title": "Laravel Updater",
                                    "welcome": {
                                        "title": "Welcome To The Updater",
                                        "message": "Welcome to the update wizard."
                                    },
                                    "overview": {
                                        "title": "Overview",
                                        "message": "There is 1 update.|There are :number updates.",
                                        "install_updates": "Install Updates"
                                    },
                                    "final": {
                                        "title": "Finished",
                                        "finished": "Application's database has been successfully updated.",
                                        "exit": "Click here to exit"
                                    },
                                    "log": {
                                        "success_message": "Laravel Installer successfully UPDATED on "
                                    }
                                }
                            },
                            "pagination": {
                                "previous": "&laquo; Previous",
                                "next": "Next &raquo;"
                            },
                            "passwords": {
                                "reset": "Your password has been reset!",
                                "sent": "We have emailed your password reset link!",
                                "throttled": "Please wait before retrying.",
                                "token": "This password reset token is invalid.",
                                "user": "We can't find a user with that email address."
                            },
                            "validation": {
                                "accepted": "The :attribute must be accepted.",
                                "active_url": "The :attribute is not a valid URL.",
                                "after": "The :attribute must be a date after :date.",
                                "after_or_equal": "The :attribute must be a date after or equal to :date.",
                                "alpha": "The :attribute may only contain letters.",
                                "alpha_dash": "The :attribute may only contain letters, numbers, dashes and underscores.",
                                "alpha_num": "The :attribute may only contain letters and numbers.",
                                "array": "The :attribute must be an array.",
                                "before": "The :attribute must be a date before :date.",
                                "before_or_equal": "The :attribute must be a date before or equal to :date.",
                                "between": {
                                    "numeric": "The :attribute must be between :min and :max.",
                                    "file": "The :attribute must be between :min and :max kilobytes.",
                                    "string": "The :attribute must be between :min and :max characters.",
                                    "array": "The :attribute must have between :min and :max items."
                                },
                                "boolean": "The :attribute field must be true or false.",
                                "confirmed": "The :attribute confirmation does not match.",
                                "date": "The :attribute is not a valid date.",
                                "date_equals": "The :attribute must be a date equal to :date.",
                                "date_format": "The :attribute does not match the format :format.",
                                "different": "The :attribute and :other must be different.",
                                "digits": "The :attribute must be :digits digits.",
                                "digits_between": "The :attribute must be between :min and :max digits.",
                                "dimensions": "The :attribute has invalid image dimensions.",
                                "distinct": "The :attribute field has a duplicate value.",
                                "email": "The :attribute must be a valid email address.",
                                "ends_with": "The :attribute must end with one of the following: :values.",
                                "exists": "The selected :attribute is invalid.",
                                "file": "The :attribute must be a file.",
                                "filled": "The :attribute field must have a value.",
                                "gt": {
                                    "numeric": "The :attribute must be greater than :value.",
                                    "file": "The :attribute must be greater than :value kilobytes.",
                                    "string": "The :attribute must be greater than :value characters.",
                                    "array": "The :attribute must have more than :value items."
                                },
                                "gte": {
                                    "numeric": "The :attribute must be greater than or equal :value.",
                                    "file": "The :attribute must be greater than or equal :value kilobytes.",
                                    "string": "The :attribute must be greater than or equal :value characters.",
                                    "array": "The :attribute must have :value items or more."
                                },
                                "image": "The :attribute must be an image.",
                                "in": "The selected :attribute is invalid.",
                                "in_array": "The :attribute field does not exist in :other.",
                                "integer": "The :attribute must be an integer.",
                                "ip": "The :attribute must be a valid IP address.",
                                "ipv4": "The :attribute must be a valid IPv4 address.",
                                "ipv6": "The :attribute must be a valid IPv6 address.",
                                "json": "The :attribute must be a valid JSON string.",
                                "lt": {
                                    "numeric": "The :attribute must be less than :value.",
                                    "file": "The :attribute must be less than :value kilobytes.",
                                    "string": "The :attribute must be less than :value characters.",
                                    "array": "The :attribute must have less than :value items."
                                },
                                "lte": {
                                    "numeric": "The :attribute must be less than or equal :value.",
                                    "file": "The :attribute must be less than or equal :value kilobytes.",
                                    "string": "The :attribute must be less than or equal :value characters.",
                                    "array": "The :attribute must not have more than :value items."
                                },
                                "max": {
                                    "numeric": "The :attribute may not be greater than :max.",
                                    "file": "The :attribute may not be greater than :max kilobytes.",
                                    "string": "The :attribute may not be greater than :max characters.",
                                    "array": "The :attribute may not have more than :max items."
                                },
                                "mimes": "The :attribute must be a file of type: :values.",
                                "mimetypes": "The :attribute must be a file of type: :values.",
                                "min": {
                                    "numeric": "The :attribute must be at least :min.",
                                    "file": "The :attribute must be at least :min kilobytes.",
                                    "string": "The :attribute must be at least :min characters.",
                                    "array": "The :attribute must have at least :min items."
                                },
                                "multiple_of": "The :attribute must be a multiple of :value",
                                "not_in": "The selected :attribute is invalid.",
                                "not_regex": "The :attribute format is invalid.",
                                "numeric": "The :attribute must be a number.",
                                "password": "The password is incorrect.",
                                "present": "The :attribute field must be present.",
                                "regex": "The :attribute format is invalid.",
                                "required": "The :attribute field is required.",
                                "required_if": "The :attribute field is required when :other is :value.",
                                "required_unless": "The :attribute field is required unless :other is in :values.",
                                "required_with": "The :attribute field is required when :values is present.",
                                "required_with_all": "The :attribute field is required when :values are present.",
                                "required_without": "The :attribute field is required when :values is not present.",
                                "required_without_all": "The :attribute field is required when none of :values are present.",
                                "same": "The :attribute and :other must match.",
                                "size": {
                                    "numeric": "The :attribute must be :size.",
                                    "file": "The :attribute must be :size kilobytes.",
                                    "string": "The :attribute must be :size characters.",
                                    "array": "The :attribute must contain :size items."
                                },
                                "starts_with": "The :attribute must start with one of the following: :values.",
                                "string": "The :attribute must be a string.",
                                "timezone": "The :attribute must be a valid zone.",
                                "unique": "The :attribute has already been taken.",
                                "uploaded": "The :attribute failed to upload.",
                                "url": "The :attribute format is invalid.",
                                "uuid": "The :attribute must be a valid UUID.",
                                "custom": {
                                    "attribute-name": {
                                        "rule-name": "custom-message"
                                    }
                                },
                                "attributes": []
                            }
                        },
                        "json": []
                    }
                };
            })(jQuery)
        </script>
    </div>
</body>

</html>
