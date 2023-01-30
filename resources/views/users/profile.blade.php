@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <div class="panel-body bio-graph-info">
                    <h1> Profile Info</h1>
                    <form action="{{ route('profile_submitted') }}" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-lg-2 control-label">First Name</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $info->first_name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Last Name</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $info->last_name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Photo</label>
                            <div class="col-lg-6">
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                @if(isset($info->photo))
                                    <div class="image_preview"><img class="rounded avatars " id="avatar2" src="{{ url('uploads/users/'.$info->photo) }}" alt="avatar" style="width: 100px;height: 100px;margin-top: 10px;"></div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Email</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="email" value="{{ $info->email }}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Phone number</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $info->phone_number }}">
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
