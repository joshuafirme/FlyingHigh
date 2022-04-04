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
                        <img
                            src="https://img.icons8.com/external-kmg-design-detailed-outline-kmg-design/25/000000/external-dashboard-user-interface-kmg-design-detailed-outline-kmg-design.png" /><span>
                            Dashboard </span>
                    </a>
                </li>
                <li class="menu-title">Components</li>
                <li>
                    <a href="#" class=""><img
                            src="https://img.icons8.com/external-xnimrodx-lineal-xnimrodx/25/000000/external-admin-responsive-design-xnimrodx-lineal-xnimrodx.png" /><span>
                            Administration
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
                        <li class=""><a href="{{ url('users') }}">Users</a>
                        </li>
                        <li class=""><a href="{{ url('role') }}">Roles</a></li>
                        <li class=""><a href="{{ url('/hub') }}">Hub</a></li>
                    </ul>
                </li>

                <li class="">
                    <a class="" href="{{ url('/product') }}" class="">
                        <img src="https://img.icons8.com/ios/25/000000/product--v1.png" />
                        <span>
                            Product </span>
                    </a>
                </li>
                <!--
                <li>
                    <a href="#" class=""><img
                            src="https://img.icons8.com/ios/25/000000/inventory-flow.png" /><span>
                            Transfer
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
                                href="https://clanvent-alpha.laravel-script.com/admin/purchases">Purchases</a></li>
                        <li><a href="https://clanvent-alpha.laravel-script.com/admin/purchases/receive/list">Purchase
                                Receive List</a>
                        </li>
                        <li><a href="https://clanvent-alpha.laravel-script.com/admin/purchases/return/list">Purchase
                                Return List</a>
                        </li>
                    </ul>
                </li>-->

                <li>
                    <a href="#" class=""><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAABmJLR0QA/wD/AP+gvaeTAAABVklEQVRIid3TP0scURQF8J+yWyyooE3wC2hnKUFsRVCSRpBAahURhVR+gSBYWNgJYmlhIyim18LKysZPENEtjCsYCG6hxdyBddmZnciQgAcO3Hvee/e8f5f3gp4Saw3jU8THuG03WcZAlyIP2MkYG8cRfkQ+i8+4aDV5xmYXk3XZJz/HFg4jn8M3TLabpPEChiL+hd0Oc9rxGx/wGHk/btAHvR0W1DAYrGUUbccVplvy6dBe4blAobw5E2hgL9jAx3Sw00n+FlWs4B5PwUZo1aK77DZnAydeX21N8tO+l2VyjZEO+ih+lmWS9et60jWVEO4KGN3lmEzhUvIWJD9zrEDNwljCGer4E6zjFIt5C9ewWtYuKhn6UIaehyr2I/6KZjpQRp+kBgeYCR5o6ZGyTOYlTbgdfAqtVJN9fJFcUTPi9OpKM8nFPzHJwqqkkd7Clf+w3/eEF1NQVOD4fG8qAAAAAElFTkSuQmCC"
                            alt=""><span>
                            Pick-up
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
                            <a href="{{ url('pickup') }}">For
                                Pick-up</a>
                        </li>
                        <li>
                            <a href="{{ url('pickedup-list') }}">Picked-up List</a>
                        </li>
                    </ul>
                </li>


                <li>
                    <a href="#" class=""><img
                            src="https://img.icons8.com/ios/25/000000/warehouse-1.png" /><span>
                            Hubs
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
                        @php
                            $hubs = App\Models\Hub::where('status', 1)->get();
                        @endphp
                        @foreach ($hubs as $item)
                            <li class=""><a
                                    href="{{ url('/hubs/' . $item->slug . '/' . $item->id) }}">{{ $item->name }}</a>
                            </li>
                        @endforeach
                </li>
            </ul>
            </li>

            <!--<li class="">
                    <a class="" href=" url('/client') }}" class="">
                        <img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAABmJLR0QA/wD/AP+gvaeTAAAB7klEQVRIieXU32uPYRjH8de+23w3Q1lyMPlRDiS0UIwDORMa0+RIrSiFo53KgVLKivIXOHHoRCTJjzihWUtZtAPya2NoKCG0Objvpx73d893P5zxqfvgeXdd93Xd9/V5bv4V1UwzdiPW4jvOTzWxdopxbbiOdozhCEp4ii/TaLRQa/AWe3JsM85hGEtmsmkjTmAQn/EEhwtiu/Ecr/EDHaYwgjrcwUVswm6Mo6kgfhE+YSX2YgBXTTKGzlgk383PKkmN+Jr7LqHfn1erlCStjp2M59gANhQUWY/Hue8xXMaqakWG0ZKwxcLgJ9IoFiRsHr5VK9KA2Ql7r9hBK/AxYU34VRCP4I5+lHNsC16odE0tRgQ7ZyrH/I5qRWpwU3BVXgPYmrBteJCwnbg7QUMV6sT9hO1DX8L6JC7CLexPN0xnkiUvTNgg6hNWj2cJa8ajyYq04KTKa2jHy4S9ijyvezglGKJCy3EBH3AGcwSndaFXON3SJGdZ5L0xrgGzcBxvJP/LOsElxzA3soMYwhXsmODEmUrYHuOGcCDyMo7GfdvgtvB0Z+rBQ7QWbFyk1pjXk2Ndgtu8Ex650bhGMH+aBTI1x/zx3BquE+66IQZ1YxcOzbAIYR5ncToDdcI7k701l4ThzfQkcA03/iL/f9dvqKxlLVcWNcIAAAAASUVORK5CYII=" />
                        <span>
                            Clients </span>
                    </a>
                </li>-->

            <li class="">
                <a class="" href="https://clanvent-alpha.laravel-script.com/admin/invoices"
                    class="">
                    <img
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAABmJLR0QA/wD/AP+gvaeTAAABJklEQVRIie3VoUpEQRQG4E8RxCJcq5gFwSAaDILBpOALGFfFoMnsK9jU4gMYRbQoCAaDUVgwucGiQTFYjGu4s7J7uc7u3ruLZX8YzpmZn3PO/HOY4R9Qwzneg30taGvNQYcySerYxT2WStjjnNgtSXqBljjDPQoaxUhkbxsTkf1LPGEG6/jEabdJxpDEa/xFgu8Ouf25kyJyNWRqYCAXBt2Vg351V13T2xV7VhpyZUceksCfxkVYO8NkHrmsXI/YxDj2cUtcrg1Ugz/bgV+VVn6FL5wIcsfkqnfpwxHuUAnzg3aBi2IN19LftZJH6GULz+EZi3nf75/fZhvsSe9op2ntEG9ZYpmTTEklWsEo5vGChSzxIyQqMrawjIcwv8FqiaLbokWRHxGtZ6DVMsbYAAAAAElFTkSuQmCC" />
                    <span>
                        Invoice Manage </span>
                </a>
            </li>

            <li>
                <a href="#" class=""><i class="flaticon-expenses"></i><span>
                        Sale Return
                        <span class="float-right menu-arrow">
                            <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        <a href="https://clanvent-alpha.laravel-script.com/admin/sales-return">Sale Return List</a>
                    </li>
                </ul>
            </li>


            <li>
                <a href="#" class=""><i class="flaticon-report"></i><span>
                        Reports
                        <span class="float-right menu-arrow">
                            <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        <a href="https://clanvent-alpha.laravel-script.com/admin/reports/sales">Sales Report</a>
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
                <a href="https://clanvent-alpha.laravel-script.com/admin/system-settings" class="">
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
