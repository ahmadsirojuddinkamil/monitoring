<div class="header">

    <div class="header-left active">
        <a href="/" class="logo">
            <img src="{{ asset('assets/dashboard/img/Loggingpedia.png') }}" alt="">
        </a>

        <a href="/" class="logo-small">
            <img src="{{ asset('assets/dashboard/img/logo-small.png') }}" alt="">
        </a>
    </div>

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <ul class="nav user-menu">
        <li class="nav-item dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <img src="{{ asset('assets/dashboard/img/icons/notification-bing.svg') }}" alt="img">
                <span class="badge rounded-pill">4</span>
            </a>

            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
                    <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                </div>

                <div class="noti-content">
                    <ul class="notification-list">
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media d-flex">
                                    <span class="avatar flex-shrink-0">
                                        <img alt=""
                                            src="{{ asset('assets/dashboard/img/profiles/avatar-02.jpg') }}">
                                    </span>

                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">John Doe</span> added
                                            new task
                                            <span class="noti-title">Patient appointment booking</span>
                                        </p>
                                        <p class="noti-time"><span class="notification-time">4 mins ago</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>

                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media d-flex">
                                    <span class="avatar flex-shrink-0">
                                        <img alt=""
                                            src="{{ asset('assets/dashboard/img/profiles/avatar-03.jpg') }}">
                                    </span>

                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">Tarah Shropshire</span>
                                            changed the task name
                                            <span class="noti-title">Appointment booking with payment
                                                gateway</span>
                                        </p>
                                        <p class="noti-time"><span class="notification-time">6 mins ago</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="topnav-dropdown-footer">
                    <a href="activities.html">View all Notifications</a>
                </div>
            </div>
        </li>

        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-img">
                    <img src="{{ Auth::user()->profile ? asset(Auth::user()->profile) : asset('assets/dashboard/img/icons/user.png') }}"
                        alt="">
                    <span class="status online"></span>
                </span>
            </a>

            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img">
                            <img src="{{ Auth::user()->profile ? asset(Auth::user()->profile) : asset('assets/dashboard/img/icons/user.png') }}"
                                alt="">
                            <span class="status online"></span>
                        </span>

                        <div class="profilesets">
                            <h6>{{ Auth::user()->username }}</h6>
                            <h5>Member</h5>
                        </div>
                    </div>

                    <hr class="m-0">

                    <a class="dropdown-item" href="/profile/{{ Auth::user()->uuid }}">
                        <i class="me-2" data-feather="user"></i>
                        My Profile
                    </a>

                    <hr class="m-0">

                    <a class="dropdown-item logout pb-0" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <img src="{{ asset('assets/dashboard/img/icons/log-out.svg') }}" class="me-2"
                            alt="img">Logout
                    </a>

                    <form id="logout-form" action="/logout" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </li>
    </ul>

    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="/profile/{{ Auth::user()->uuid }}">My Profile</a>

            <a class="dropdown-item logout pb-0" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">Logout
            </a>

            <form id="logout-form-mobile" action="/logout" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

</div>
