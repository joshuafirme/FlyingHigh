<!-- Top Bar Start -->
<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <a href="{{ url('/dashboard') }}" class="logo">
            <span>
                <img src="https://i0.wp.com/flyinghighexpress.com/wp-content/uploads/2019/02/cropped-flyinghighenergyexpress-logoicon.png?fit=192%2C192&ssl=1"
                    class="ic-logo-height" width="80" alt="logo">
            </span>
            <i>
                <img src="https://i0.wp.com/flyinghighexpress.com/wp-content/uploads/2019/02/cropped-flyinghighenergyexpress-logoicon.png?fit=192%2C192&ssl=1"
                    class="ic-logo-small" alt="logo">
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
                        <img src="https://clanvent-alpha.laravel-script.com/storage/users/16368736053887.png" alt="user"
                            class="rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown " style="width: 200px">

                        <a href="/admin/profile" class="dropdown-item">
                            {{ Auth::user()->name }}<br>
                            <small>{{ Auth::user()->email }}</small>
                        </a>

                        <a class="dropdown-item logout-btn" href="{{ route('logout') }}" onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-power text-danger"></i>
                            Logout</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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
