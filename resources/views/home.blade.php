@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">

            <!-- ======== breadcump start ========  -->

            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="page-title">Dashboard</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Welcome John Doef</li>
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
                                                        class="form-control" placeholder="To Date" autocomplete="off"
                                                        required />
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
                                <div class="col-lg-4 my-auto ic-expance-form-chart input-daterange ic-mobile-range">
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
                                <input type="text" name="notes" class="form-control" value="" maxlength="200">

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
                <form action="https://clanvent-alpha.laravel-script.com/admin/invoices/payments/send" method="post">
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
                                <input id="send-invoice-payment-email" type="email" name="email" class="form-control"
                                    value="" required maxlength="50">

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
    <div class="modal fade" id="liveInvoiceUrl" tabindex="-1" role="dialog" aria-labelledby="liveInvoiceUrlTitle"
        aria-hidden="true">
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
@include('layouts.footer')
