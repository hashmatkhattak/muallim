<!DOCTYPE html>
<html lang="en">
<head>
    @include('pages.css')
</head>
<body class="login-img3-body">
<div class="container">
    <div class="row">
        <form class="login-form" action="{{ route('login_submitted') }}" method="post">
            @include('pages.flash-message')
            {{ csrf_field() }}
            <div class="login-wrap">
                <div class="logoimg">
                    <img src="{{ asset('assets/img/logo.png') }}">
                </div>
                <div class="input-group input-group1">
                <span class="input-group-addon">
                    <i class="icon_profile"></i>
                </span>
                    <input type="text" name="email" class="form-control" placeholder="admin@admin.com" autofocus/>
                </div>
                <div class="input-group input-group1">
                <span class="input-group-addon">
                    <i class="icon_key_alt"></i>
                </span>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <label class="checkbox">
                <span class="pull-right">
                    <a href="#" id="forgot_password">Forgot Password?</a>
                </span>
                </label>
                <button class="btn btn-primary btn-theme  btn-lg btn-block" type="submit">Login</button>
            </div>
        </form>
    </div>
</div>
@include('pages.js')
<script>
    $(document).ready(function () {
        $("#forgot_password").click(function (event) {
            Swal.fire({
                title: 'Please enter email',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    if (value === '' || !isEmail(value)) {
                        return 'Please enter valid email'
                    }
                }, preConfirm: (login) => {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('forgot_password') }}",
                        data: {email: login}
                    }).done(function (data) {
                        if (data === '1') {
                            Swal.fire(
                                'Sent!',
                                'Password reset link is sent on your registered email!',
                                'success'
                            )
                        } else {
                            alert(data);
                        }
                    });
                    return false;
                }
            })
        });
    });

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
</script>
</body>
</html>
