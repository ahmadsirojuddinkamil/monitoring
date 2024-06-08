<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="/dashboard">
                        <img src="{{ asset('assets/dashboard/img/icons/dashboard.png') }}" alt="img">
                        <span>Dashboard</span>
                    </a>
                </li>

                @if (Auth::user()->hasRole('administrator'))
                    <li class="submenu ">
                        <a href="javascript:void(0);">
                            <img src="{{ asset('assets/dashboard/img/icons/user.png') }}" alt="img">
                            <span>User</span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul>
                            <li>
                                <a href="/user/list" class="{{ Request::is('user*') ? 'active' : '' }}">
                                    Users List
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="submenu ">
                        <a href="javascript:void(0);">
                            <img src="{{ asset('assets/dashboard/img/icons/comment.png') }}" alt="img">
                            <span>Comment</span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul>
                            <li>
                                <a href="/comment/list" class="{{ Request::is('comment*') ? 'active' : '' }}">
                                    Comments List
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li class="submenu ">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/dashboard/img/icons/connection.png') }}" alt="img">
                        <span>Connection</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul>
                        @if (Auth::user()->hasRole('administrator'))
                            <li>
                                <a href="/connection/list"
                                    class="{{ Request::is('connection/list*') ? 'active' : '' }}">
                                    Connection List
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="/connection/{{ Auth::user()->uuid }}"
                                    class="{{ Request::is('connection/' . Auth::user()->uuid . '*') ? 'active' : '' }}">
                                    My Connection
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="submenu ">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/dashboard/img/icons/log.png') }}" alt="img">
                        <span>Logging</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul>
                        @if (Auth::user()->hasRole('administrator'))
                            <li>
                                <a href="" class="{{ Request::is('') ? 'active' : '' }}">
                                    Logging List
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="/logging/{{ Auth::user()->uuid }}"
                                    class="{{ Request::is('logging/' . Auth::user()->uuid . '*') ? 'active' : '' }}">
                                    My Log
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="submenu ">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/dashboard/img/icons/secret.png') }}" alt="img">
                        <span>Secret</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul>
                        <li>
                            <a href="/secret-generator"
                                class="{{ Request::is('secret-generator*') ? 'active' : '' }}">Generator</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
