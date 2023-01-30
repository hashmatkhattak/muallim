<?php
$isLogin = Session::get('isLogin');
?>
<header class="header dark-bg">
    <div class="toggle-nav">
        <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom">
            <i class="icon_menu"></i>
        </div>
    </div>
    <a href="{{ route('dashboard') }}" class="logo" style="color: white">
        <b>SMART ACADEMY Solution</b>
    </a>
    <div class="top-nav notification-row">
        <ul class="nav pull-right top-menu">
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <span class="profile-ava">
                        <img alt="" src="{{ asset('uploads/users/'.$isLogin->photo) }}" width="25px" height="25px">
                    </span>
                    <span class="username">{{$isLogin->first_name}} {{$isLogin->last_name}}</span>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu extended logout">
                    <div class="log-arrow-up"></div>
                    <li class="eborder-top">
                        <a href="{{ route('change_password') }}">
                            <i class="fa fa-key" aria-hidden="true"></i> Change Password</a>
                    </li>
                    <li class="eborder-top">
                        <a href="{{ route('profile') }}">
                            <i class="icon_profile"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt"></i> Log Out</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>
