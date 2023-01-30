@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <a href="{{ route('users',['role_id' => 3]) }}">
                <div class="info-box blue-bg">
                    <i class="fa fa-users"></i>
                    <div class="count"></div>
                    <div class="title">Total Parents</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <a href="{{ route('users',['role_id' => 4]) }}">
                <div class="info-box brown-bg">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <div class="count"></div>
                    <div class="title">Total Teachers</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <a href="{{ route('all_courses') }}">
                <div class="info-box dark-bg">
                    <i class="fas fa-book-reader"></i>
                    <div class="count"></div>
                    <div class="title">Total Courses</div>
                </div>
            </a>
        </div>
    </div>
@endsection
