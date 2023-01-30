@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Add user
                </header>
                <div class="panel-body">
                    <form role="form" action="{{ route('user_submitted') }}" method="post" id="" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">First name</label>
                                    <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name" value="{{ old('first_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Last name</label>
                                    <input type="text" class="form-control" placeholder="Last Name" name="last_name" id="last_name" value="{{ old('last_name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Roles</label>
                                    <select class="form-control" name="role_id" id="role_id">
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? "selected" : '' }}>{{ $role->role_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Gender</label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">Select Gender</option>
                                        <option value="1" {{ old('gender') == 1 ? 'selected' : '' }}>Male</option>
                                        <option value="2" {{ old('gender') == 2 ? 'selected' : '' }}>Female</option>
                                        <option value="3" {{ old('gender') == 3 ? 'selected' : '' }}>Not specified</option>
                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Email</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Phone number</label>
                                    <br>
                                    <input type="text" class="form-control" placeholder="Phone number" name="phone_number" id="phone_number" value="{{ old('phone_number') }}">
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
                                            <option value="{{ $country->id }}" {{ old('country') == $country->id ? "selected" : '' }} data-curreny="{{ $country->code }}">{{ $country->country_name }}({{ $country->code }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Currency</label>
                                    <input type="text" class="form-control" placeholder="Currency" name="currency" id="currency" value="{{ old('currency') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Password</label>
                                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" value="{{ old('password') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Confirm Password</label>
                                    <input type="password" class="form-control" placeholder="Confirm password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12">
                                <div class="form-group cu-btn-category">
                                    <button id="add_user" type="submit" class="btn btn-primary btn-theme1" >Add</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('assets/phonenumber/css/intlTelInput.css')}}"/>
@endpush

@push('scripts')
    <script src="{{asset('assets/phonenumber/js/intlTelInput.js')}}"></script>
    <script>
        $(document).ready(function () {

            var errorMap = ["Please enter a valid number", "Invalid country code", "The phone number is too short" ,"The phone number is too long" , "Please enter a valid number", "Please enter a valid number"];
            var input = document.querySelector("#phone_number");

            var iti = window.intlTelInput(input, {
                initialCountry: "PK",
                utilsScript: "{{asset('assets/phonenumber/js/utils.js')}}"
            });
            $("#phone_number").blur(function () {
                $('#common-validation-error').hide();
                if ($(this).val().trim()) {
                    if (iti.isValidNumber()) {
                        var getCode = iti.getSelectedCountryData();
                    } else {
                        var errorCode = iti.getValidationError();
                        $('#common-validation-error').show();
                        $('#validation-error').html(errorMap[errorCode]);
                    }
                }
            });

            $('#add_user').click(function () {
                $('#common-validation-error').hide();
                //var phone = iti.getNumber(intlTelInputUtils.numberFormat.E164);
                if($("#first_name").val() != "" && $("#last_name").val() != "" &&  $("#role_id").val() != "") {
                    if (!iti.isValidNumber()) {
                        var errorCode = iti.getValidationError();
                        $('#common-validation-error').show();
                        $('#validation-error').html(errorMap[errorCode]);
                        return false;
                    }
                }

            });

            $('select#country').on('change', function() {
                var curreny = $("#country option:selected").attr('data-curreny');
               $('#currency').val(curreny);
            });

        });

    </script>
@endpush
