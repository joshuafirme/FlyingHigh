<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">
        <!--- Side Menu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu mt-3" id="side-menu">
                <!--  <li>
                    <a href="#" class="">
                        <img
                            src="https://img.icons8.com/external-kmg-design-detailed-outline-kmg-design/25/000000/external-dashboard-user-interface-kmg-design-detailed-outline-kmg-design.png" /><span>
                            Dashboard </span>
                    </a>
                </li>-->
                <!--<li class="">
                    <a class="" href="{{ url('/product') }}" class="">
                        <img src="https://img.icons8.com/ios/25/000000/product--v1.png" />
                        <span>
                            Product </span>
                    </a>
                </li>-->
                <li>
                    <a href="#" class=""><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAABmJLR0QA/wD/AP+gvaeTAAABGUlEQVRIieXVoU/DQBQG8B8wgyEs/BlkYthNIFBYAoa/ALEENyQzBIsBDBKFRyEQIAGBQZJMoLEgQPQSStOu17WZgC95Se+7e9/3rq93ZQZYqLh+DTv4xFts0lyk8HaIL9xiPeRehXisWCxYxSFe8IoT9DNFxayZSrhW7j7GOEI3QnQSukFnHHS1wsQyLkI1dfEU4iPomm9AtBR5JgPJVzRtDGJMVjCSNK1qjEJ+qUnjmIlJq3wJ6GAzh7/Gc1MmW9jAXYrrY7FJE7jx+xxFn6n/0/g9LKEXxsPUXJp7x3mRSNlOTtHGQ4g2jnO4s0kiMY0/yIyHBVwh/mbje5JtZ5uc9yqKuF7m+Z6f32MHuzWKLcKliBuhEXwDB0lEZhWus9YAAAAASUVORK5CYII=" /><span>
                            Warehouse
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
                            <a href="{{ url('/product') }}">SKU Master</a>
                        </li>
                        <li>
                            <a href="{{ url('/inventory') }}">Inventory</a>
                        </li>
                        <li>
                            <a href="{{ url('/stock-transfer') }}">Stock Transfer</a>
                        </li>
                        <li>
                            <a href="{{ url('/hub-transfer') }}">Pick up Location Transfer</a>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="{{ url('/orders') }}" class="">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAABmJLR0QA/wD/AP+gvaeTAAAAVUlEQVRIiWNgoANgROP/p6HZVLcExRwmKhmKF9DFEhY8cg5QTCw4AMUYgC4+QQejET9CLCE3CSMn1wYsYihg+CTh0eAiyZzR4CLJnOFdrFCzxUI/AAC7xhoj77sv0QAAAABJRU5ErkJggg==">
                        <span>
                            Orders </span>
                    </a>
                </li>

                <li>
                    <a href="#" class=""><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAABmJLR0QA/wD/AP+gvaeTAAABVklEQVRIid3TP0scURQF8J+yWyyooE3wC2hnKUFsRVCSRpBAahURhVR+gSBYWNgJYmlhIyim18LKysZPENEtjCsYCG6hxdyBddmZnciQgAcO3Hvee/e8f5f3gp4Saw3jU8THuG03WcZAlyIP2MkYG8cRfkQ+i8+4aDV5xmYXk3XZJz/HFg4jn8M3TLabpPEChiL+hd0Oc9rxGx/wGHk/btAHvR0W1DAYrGUUbccVplvy6dBe4blAobw5E2hgL9jAx3Sw00n+FlWs4B5PwUZo1aK77DZnAydeX21N8tO+l2VyjZEO+ih+lmWS9et60jWVEO4KGN3lmEzhUvIWJD9zrEDNwljCGer4E6zjFIt5C9ewWtYuKhn6UIaehyr2I/6KZjpQRp+kBgeYCR5o6ZGyTOYlTbgdfAqtVJN9fJFcUTPi9OpKM8nFPzHJwqqkkd7Clf+w3/eEF1NQVOD4fG8qAAAAAElFTkSuQmCC" /><span>
                            Pick up Points
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
                            $locations = App\Models\PickUpLocation::where('status', 1)->get();
                            $permissions = App\Models\Role::permissions();
                            
                        @endphp
                        @foreach ($locations as $item)
                            <li class="">
                                @if (in_array($item->name, $permissions))
                                    <a href="{{ url('/pickup-locations/' . $item->location_id) }}">{{ $item->name }}</a>
                                @endif
                            </li>
                        @endforeach
                </li>
            </ul>
            </li>

            <li>
                <a href="{{ url('/transactions') }}" class="">
                    <img
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAABmJLR0QA/wD/AP+gvaeTAAAAS0lEQVRIiWNgGAWDEbAwMDC8YmBg+M/AwHAZKpYH5ZOCQ6B6tzIwMEQzMDBEQdmjgHRA7ThhYBiNE0rBaJwMPjBadg0+MJpPRiAAADoOVcsLs2lNAAAAAElFTkSuQmCC"><span>
                        Transactions </span>
                </a>
            </li>
            <li>
                <a href="#" class=""><img
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAABmJLR0QA/wD/AP+gvaeTAAAAv0lEQVRIie2VQQrCQAxFnyLSrXSlV9ETWG+jB2hBBPVaehXP0HZTF0UYh7TppBRdzIcwMMnPnyQwgT/BASiBRrASyEITXoBKSJZ3xOdCbAWc3aC5RzoCa2DmWKE8rPDiN8Cpj9A4p7USN48q8kFG/0z2Cn+QSAhEvj+TSbBQ/DtgK9xfPf8TeFhFEmA1wJ8oeb4wyUxiu4L4sV1B/NiuIP7PP0hrNapIDaS0a9SKlHbPd4rcgRewHCFSA7cRfBvevSlTJctzIg0AAAAASUVORK5CYII=" /><span>
                        Reports
                        <span class="float-right menu-arrow">
                            <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="10 15 15 20 20 15"></polyline>
                                <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                            </svg>
                        </span>
                    </span></a>
                <ul class="submenu">
                    <li>
                        <a href="{{ url('/reports/inbound-transfer') }}">Inbound Transfers</a>
                    </li>
                    <li>
                        <a href="{{ url('/reports/hub-transfer') }}">Hub Transfers</a>
                    </li>
                    <li>
                        <a href="{{ url('/reports/stock-adjustment') }}">Stock Adjustment</a>
                    </li>
                    <li>
                        <a href="{{ url('/reports/expired') }}">Expired List</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#" class=""><img
                        src="https://img.icons8.com/external-xnimrodx-lineal-xnimrodx/25/000000/external-admin-responsive-design-xnimrodx-lineal-xnimrodx.png" /><span>
                        Administration
                        <span class="float-right menu-arrow">
                            <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            </li>
            </ul>
            </li>
            <li>
                <a href="#" class=""><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAABmJLR0QA/wD/AP+gvaeTAAACsElEQVRIiZXWS6hVdRTH8c+9PsiwCMw7uA+1qAi1VJRUdBDXSQWBkRIkWIFhD0UHvgYhKSFmAwfSQNBBgWUq4Q0c+KCMBsUdWYlgmmbi46pXUUIQGzhYa+M+h33OufcHh/P/773+6/vfa629/hu6cQrfoMfw1I5F6MNN/I9v641G4DfsxyB2Ywz6c0EzvYBjmIVd+BHzsAqX6o334NMc9yTw1xaAGbiKJTl/DQOY3WhBD657GKr5+STN1I83czwSNzCnxRobsS8Bz+I01jSwfQy30JbzdlzEGxW2r+BAMRkjQtSfgFlNQI/gP4wqXZuGy3WgV0Ux3Kva6ZohgI5hcd21ArQYr4scLcWFKshQQDtFwdRrGs7iX1EEy/F1I0gz0Gr8gfHNFotcncKCFnaVoJl4RlRVM63Gz60AjUB9IvEnMQ5P4xDewxN4FB/hCiYOFVIG7cj/TnyVjvfiM3yP2/k7jMnDAbyNF3GwBIAtOILH6+zbh+McPsQ5TBUh6izdGyGqbBDH6+41VbtI6mR8nIBJ6fCkCNGWnBdqwwa1T1mpNg+75zlRer8noNA4vCtCtDOvrUNvjtcmqKsRZDd+wZQmG3kKX4ocDOTGekVzLYP+qgItwgnRv5aKhnY9d1loEs7jg5wfFyFSAVpXBTqKhXgpHS0R58UZrE+bP7GitKZTbReoB63P9d3FggF0iHa/reRoOq7l+IToS2UVoLUtQB0jcR+jRUVdLDl5ThzNRPzb1OpyOvwp723DW/gu/z8XxbKdyMGyfLQz+CIN/hbtoVu8C6NVq0vkoMhhb2k8Xpwp5uYTdKXDvSJ0c8XB1IdNDQBlUDmHhZ7HP8VkpTgD3sGTotJeFlX0g9pTsJGKSGzFWEwQofykbDQvHQ7irjiK31f7drdSh/juuiO+ZjZj1AMeMKo0VFfBYAAAAABJRU5ErkJggg=="><span>
                        Maintenance
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
                    <li class=""><a href="{{ url('/pickup-location') }}">Pick Up Locations</a></li>
                    <li class=""><a href="{{ url('/hub') }}">Branch Plants</a></li>
                    <li class=""><a href="{{ url('/adjustment-remarks') }}">Adjustment Remarks</a>
                    <li class=""><a href="{{ url('/return-reason') }}">Reason for Return</a>
                    <li class=""><a href="{{ url('/attributes') }}">Attributes</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="https://clanvent-alpha.laravel-script.com/admin/system-settings" class="">
                    <img
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAABmJLR0QA/wD/AP+gvaeTAAACJUlEQVRIic3WS0tVURQH8F/ZxEzT6DGIsmkgRgTRMKg0sIeGkGlfohqIRJNGBRLpIHsRhWWTIHFQYdZ3CKMHFaQOokGPSc9Bt8HeN4/be+61gugPh7PPWv+11t57rb324T9DDwrx+YH2hRou/o0gW3EMi3AC2/4mSDUuoyORb8STOJ5EU6Jvx6VoXxbVGMdtvMQFNKAL77Eu8jbgHTpRhyG8wmi0LxvoPMZQhVpcwzeMoDXhtuFW1A/HYFXRfqhckFZMoT4jayhngJUJdwotFWwM4kYlUg5u4uxCiPX4av4KlqIbvTiEmkS/JtotSx1WlQjSie9CHopoxgM04gt24SQe4m3kfMKW+H5aavY9wjZN4CN2JCt4HmefRTeemVtJ+4QqvI+ByPmFAo4KCVtbwtm9UjMTyrUrka3HbuHgFtIgeehDf46uP+rzUIAlmY8+PMYjTGeIr7E9x0kzriSyxihvkky+HadxV9jTnRldDWYk+yvkcVrIWRH7hU5wJ/rLbaKHcT2RbRKSPI4z8T2NzQlvFAfyHGfRILSK+kReLSS5FweTFcBq4ZzULSTIRVxdCLEEhnGuEqkDL4TmWMSKCjarMuM6oXvPyUV6n7QJFfZZ2K4RvBF6Ul4Xnon6euG0T2JPuVkV75MxoZsOYLlw2j+YPaiNZu+T2sibinYV75NioCHz2/VEZjV7hTLNoiXaVQxQDoM4EsfHcepvnOXhj/9W/gl+AsJEdt3m8MrEAAAAAElFTkSuQmCC" /><span>
                        Settings </span>
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
