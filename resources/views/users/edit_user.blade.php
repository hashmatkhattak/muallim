@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    Update User
                </header>
                <div class="panel-body">
                    <form role="form" action="{{ route('edit_user_submitted') }}" method="post" id="" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{$user->id}}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-setting">First name</label>
                        <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name" value="{{$detail->first_name}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-setting">Last name</label>
                        <input type="text" class="form-control" placeholder="Last Name" name="last_name" id="last_name" value="{{ $detail->last_name }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-setting">Role</label>
                        <select class="form-control" name="user_type" id="user_type">
                            <option value="">Select Role</option>
                            @foreach($user_types as $type)
                                <option value="{{ $type->id }}" @if($user->role_id == $type->id){{'selected'}}@endif>{{ $type->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-setting">Gender</label>
                        <select class="form-control" name="gender" id="gender">
                            <option value="">Select Gender</option>
                            <option value="1" @if ($detail->gender == '1') {{'selected'}}@endif>Male</option>
                            <option value="2" @if ($detail->gender == '2') {{'selected'}}@endif>Female</option>
                            <option value="3" @if ($detail->gender == '3') {{'selected'}}@endif>Not specified</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-setting">Email</label>
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email" disabled value="{{ $user->email }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-setting">Phone number</label>
                        <input type="text" class="form-control" placeholder="Phone number" name="phone_number" disabled id="phone_number" value="{{ $user->phone_number }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-setting">Country</label>
                        <select class="form-control" name="country" id="country">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{  $detail->country_id == $country->id ? "selected" : '' }} data-curreny="{{ $country->code }}">{{ $country->country_name }}({{ $country->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-setting">Currency</label>
                        <input type="text" class="form-control" placeholder="Currency" name="currency" id="currency" value="{{ $detail->currency }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group cu-btn-category">
                        <button id="add_user" type="submit" class="btn btn-primary btn-theme1">Update</button>
                    </div>
                </div>
            </div>
        </form>
                 </div>
            </section>
        </div>
    </div>
@endsection
