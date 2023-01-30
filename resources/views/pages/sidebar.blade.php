@php
    $info = Session::get('isLogin');
    $allowed_routes = $info->allowed_routes;
    //echo "<pre>";print_r($allowed_routes);exit;
@endphp
<aside>
    <div id="sidebar" class="nav-collapse ">
        <ul class="sidebar-menu">
            @if(!empty($allowed_routes) && in_array("dashboard",$allowed_routes))
                <li class="<?php if (isset($my_route) AND $my_route == "dashboard") echo "active_li" ?>">
                    <a class="" href="{{ route('dashboard') }}">
                        <i class="icon_house_alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endif

            @if(!empty($allowed_routes) && in_array("manage_courses",$allowed_routes))
                <li class="sub-menu <?php if (isset($my_route) AND in_array($my_route, array('manage_courses'))) echo "active_li" ?>">
                    <a href="javascript:;" class="">
                        <i class="fas fa-book"></i>
                        <span>Courses</span>
                        <span class="menu-arrow arrow_carrot-right"></span>
                    </a>
                    <ul class="sub" <?php if (isset($my_route) AND in_array($my_route, array('manage_courses'))) echo "style='display:block'" ?>>
                        <li><a class="" href="{{ route('add_course') }}">Add Course</a></li>
                        <li><a class="" href="{{ route('all_courses') }}"><span>All Courses</span></a></li>
                    </ul>
                </li>
            @endif


            @if(!empty($allowed_routes) && in_array("manage_users",$allowed_routes))
                <li class="sub-menu <?php if (isset($my_route) AND $my_route == "manage_users") echo "active_li" ?>">
                    <a href="javascript:;" class="">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>Manage users</span>
                        <span class="menu-arrow arrow_carrot-right"></span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ route('roles') }}">Roles</a></li>
                        <li><a href="{{ route('permissions') }}">Permissions</a></li>
                        <li><a href="{{ route('users') }}">Users</a></li>
                    </ul>
                </li>
            @endif

            @if(!empty($allowed_routes) && (in_array("accounts",$allowed_routes)))
                <li class="sub-menu <?php if (isset($my_route) AND in_array($my_route, array('accounts'))) echo "active_li" ?>">
                    <a href="javascript:;" class="">
                        <i class="fa fa-home"></i>
                        <span>Accounts</span>
                        <span class="menu-arrow arrow_carrot-right"></span>
                    </a>
                    <ul class="sub" <?php if (isset($my_route) AND in_array($my_route, array('invoices'))) echo "style='display:block'" ?>>
                        @if(!empty($allowed_routes) && in_array("invoice_settings",$allowed_routes))
                            <li><a class="" href="{{ route('invoice_settings') }}">Invoice Settings</a></li>
                        @endif
                        @if(!empty($allowed_routes) && in_array("add_invoice",$allowed_routes))
                            <li><a class="" href="{{ route('add_invoice') }}">Add Invoice Template</a></li>
                        @endif
                        @if(!empty($allowed_routes) && in_array("invoice_templates",$allowed_routes))
                            <li><a class="" href="{{ route('invoice_templates') }}">Invoices Template</a></li>
                        @endif

                    </ul>
                </li>
            @endif

            @if(!empty($allowed_routes) && (in_array("manage_teachers",$allowed_routes)))
                <li class="sub-menu">
                    <a href="javascript:;" class="">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Teachers</span>
                        <span class="menu-arrow arrow_carrot-right"></span>
                    </a>
                    <ul class="sub" <?php if (isset($my_route) AND in_array($my_route, array('manage_teachers'))) echo "style='display:block'" ?>>
                        @if(!empty($allowed_routes) && in_array("manage_teachers",$allowed_routes))
                            <li><a class="" href="{{ route('login_history') }}">Login History</a></li>
                        @endif
                        @if(!empty($allowed_routes) && in_array("manage_teachers",$allowed_routes))
                            <li><a class="" href="{{ route('thr_search_slots') }}">Search Teacher & TimeSlots</a></li>
                        @endif
                        @if(!empty($allowed_routes) && in_array("manage_teachers",$allowed_routes))
                            <li><a class="" href="{{ route('thr_office_timings') }}">Office Timing</a></li>
                        @endif
                        @if(!empty($allowed_routes) && in_array("manage_teachers",$allowed_routes))
                            <li><a class="" href="{{ route('thr_std_schedule') }}">Student Schedule</a></li>
                        @endif
                        <li><a class="" href="{{ route('assign_students') }}">Assign students</a></li>
                    </ul>
                </li>
            @endif

            @if(!empty($allowed_routes) && in_array("manage_classes",$allowed_routes))
                <li class="sub-menu">
                    <a href="javascript:;" class="">
                        <i class="fas fa-bug"></i>
                        <span>Classes</span>
                        <span class="menu-arrow arrow_carrot-right"></span>
                    </a>
                    <ul class="sub" <?php if (isset($my_route) AND in_array($my_route, array('classes'))) echo "style='display:block'" ?>>
                        <li><a class="" href="{{ route('configure_classes') }}"><span>Configure Classes</span></a></li>
                        <li><a class="" href="{{ route('load_classes',['type'=>1]) }}">All Classes</a></li>
                        <li><a class="" href="{{ route('load_classes',['type'=>2]) }}"><span>Teacher Classes</span></a></li>
                        <li><a class="" href="{{ route('load_classes',['type'=>3]) }}"><span>Student Classes</span></a></li>
                    </ul>
                </li>
            @endif

            @if(!empty($allowed_routes) && in_array("my_student_classes",$allowed_routes))
                <li class="sub-menu <?php if (isset($my_route) AND in_array($my_route, array('my_student_classes'))) echo "active_li" ?>">
                    <a href="{{ route('teacher_classes',['type'=>1]) }}" class="">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>Classes</span>
                        <span class="menu-arrow arrow_carrot-right"></span>
                    </a>
                </li>
            @endif

            @if(!empty($allowed_routes) && in_array("parent_invoices",$allowed_routes))
                <li class="sub-menu <?php if (isset($my_route) AND in_array($my_route, array('parent_invoices'))) echo "active_li" ?>">
                    <a href="{{ route('parent_invoices') }}" class="">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>Invoices</span>
                        <span class="menu-arrow arrow_carrot-right"></span>
                    </a>
                </li>
            @endif

            @if(!empty($allowed_routes) && in_array("lesson_history",$allowed_routes))
                <li class="sub-menu <?php if (isset($my_route) AND in_array($my_route, array('student_lesson_history'))) echo "active_li" ?>">
                    <a href="{{ route('student_lesson_history') }}" class="">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>Lesson History</span>
                        <span class="menu-arrow arrow_carrot-right"></span>
                    </a>
                </li>
            @endif
            @if(!empty($allowed_routes) && in_array("complaints",$allowed_routes))
                <li class="sub-menu <?php if (isset($my_route) AND in_array($my_route, array('complaints'))) echo "active_li" ?>">
                    <a href="{{ route('complaints') }}" class="">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>Complaints</span>
                        <span class="menu-arrow arrow_carrot-right"></span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>
