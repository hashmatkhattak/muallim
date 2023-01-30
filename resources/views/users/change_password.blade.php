@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fa fa-key" aria-hidden="true"></i>
                    Change Password
                </header>
                <div class="panel-body">
                    <form action="{{ route('update_password') }}" class="form-horizontal" role="form" method="post"  enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Old password</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="old_pass" name="old_pass">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Password</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="password" name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Confirmed Password</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
