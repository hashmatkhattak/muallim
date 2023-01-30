<!DOCTYPE html>
<html lang="zxx" class="no-js">
<head>
    @include('pages.css')
</head>
<body>
<div id="ajax-loading" style="display: none; background-color: #0003;">
    <img id="loading-image" src="{{asset('assets/img/ajax_loader_red.gif')}}" alt="Loading..." style="width: 50px; height: 50px; margin: 350px;"/>
</div>
<section id="container">
    @include('pages.header')
    <input type="hidden" id="time_zone" value="">
    <section id="main-content-2">
        <section class="wrapper">
            @include('pages.flash-message')
            @yield('content')
        </section>
    </section>
</section>
@include('pages.footer')
@include('pages.js')
@include('pages.common-js')
@stack('scripts')
</body>
</html>
