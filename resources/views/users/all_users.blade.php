@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    All Users
                    <a class="btn btn-primary pull-right pasiingset btn-theme1" href="{{ route('add_user') }}">
                        <i class="icon_plus_alt2" aria-hidden="true"></i>
                        Create User
                    </a>
                </header>
                <div class="panel-body tobales">
                    <form action="" method="get" class="form-inline" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-md-12 ull-right">
                                <div class="form-group">
                                    <label class="col-form-label">Date from</label>
                                    <input type="text" class="form-control" name="date_from" id="date_from" placeholder="Date from" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : '' ?>">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Date To</label>
                                    <input type="text" class="form-control" name="date_to" id="date_to" placeholder="Date To" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : '' ?>">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Role</label>
                                    <select class="form-control" name="role_id" id="role_id">
                                        <option value="">Select user type</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" <?php echo (isset($role_id) AND $role_id == $role->id)  ? 'selected' : '' ?>>{{ $role->role_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group inputborder inputmargin">
                                    <label class="col-form-label">Search by(Name,email & phone)</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Search by(Name,email & phone)" value="<?php echo isset($_GET['name']) ? $_GET['name'] : '' ?>" style="">
                                    <span class="input-group-btn displayset" id="basic-addon2"> <button type="submit" class="btn btn-default btn-themeinput"> <i class="fa fa-search" aria-hidden="true"></i> </button></span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">

                        <table id="example" class="display" style="width:100%" class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="">Full name</th>
                                <th class="">Email</th>
                                <th class="">Mobile</th>
                                <th class="">Type</th>
                                <th class="">Is login</th>
                                <th class="">Status</th>
                                <th class="">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->first_name}} {{$user->last_name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->phone_number}}</td>
                                    <td>{{$user->role_name}}</td>
                                    <td>{{ ($user->is_login == 0) ? 'No' : "Yes" }}</td>
                                    <td>{{getStatus($user->status)}}</td>
                                    <td>
                                        @if($user->role_id == 3 )
                                            <a href="{{ route('all-student',['user_id'=> $user->user_id]) }}"  class="btn btn-warning"> <i class="fas fa-plus"></i></a>
                                        @endif
                                        <a class="btn btn-primary {{$user->status}}" href="{{ route('edit_user',['user_id'=> $user->user_id]) }}"> <i class="fas fa-edit"></i></a>
                                        @if($user->role_id != 1)
                                            @if($user->status == '0' || $user->status == '2')
                                                <a href="{{ route('change_user_status',['status'=>'1','user_id'=> $user->user_id]) }}" title="Activate" class="btn btn-success change_status" type="warning" msg="Are you sure to Activate this user">
                                                    <i class="far fa-check-circle"></i>
                                                </a>
                                            @endif
                                            @if($user->status == '1')
                                                <a href="{{ route('change_user_status',['status'=>'0','user_id'=> $user->user_id]) }}" title="DeActivate" class="btn btn-warning change_status" type="warning" msg="Are you sure to DeActivate this user"> <i
                                                        class="far fa-times-circle"></i></a>
                                            @endif

                                            <a href="{{ route('change_user_status',['status'=>'2','user_id'=> $user->user_id]) }}" title="Delete" class="btn btn-danger change_status" type="warning" msg="Are you sure to delete this user"> <i
                                                    class="far fa-trash-alt"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div>
                      <?php /*  {{ $users->links('pagination.custom') }} */ ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $( "#date_from,#date_to" ).datepicker({ dateFormat: 'yy-mm-dd' });

            $(".change_status").click(function (event) {
                event.preventDefault();
                var msg = ($(this).attr('msg'));
                var type = ($(this).attr('type'));
                var url = ($(this).attr('href'));
                Swal.fire({
                    title: msg,
                    type: type,
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                    window.location.href = url;
                }
            })
            });

        });

    </script>
@endpush
