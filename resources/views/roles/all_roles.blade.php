@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-user"></i>
                    Roles
                    <a class="btn btn-primary pull-right pasiingset btn-theme1" data-toggle="modal" data-target="#add_user_type">
                        <span class="icon-space">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        </span>Add Role
                    </a>
                </header>
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th class="table-header title">Role title</th>
                                <th class="table-header title">Status</th>
                                <th class="table-header title">Action</th>
                            </tr>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{$role->role_name}}</td>
                                    <td>{{getStatus($role->status)}}</td>
                                    <td>
                                        <a class="btn btn-success" href="{{ route('add_permission',['role_id'=> $role->id]) }}">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                        <a class="btn btn-primary" href="javascript:void(0)" onclick="getTypeDetails('{{$role->id}}')">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($role->id != 1)
                                            @if($role->status == 0)
                                                <a href="{{ route('change_role_status',['role_id'=>$role->id,'status'=>'1']) }}" class="btn btn-success change_status" title="Activate" type="warning" msg="Are you sure you want to activate?">
                                                    <i class="far fa-check-circle"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('change_role_status',['role_id'=>$role->id,'status'=>'0']) }}" class="btn btn-warning change_status" title="DeActivate" type="warning" msg="Are you sure you want to DeActivate?">
                                                    <i class="far fa-times-circle"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('change_role_status',['status'=>'2','role_id'=> $role->id]) }}" class="btn btn-danger change_status" title="Delete" type="warning" msg="Are you sure to delete this user type?"/>
                                            <i class="far fa-trash-alt"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div id="add_user_type" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form action="{{ route('role_submitted') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="role_header"> Add Role</h4>
                    </div>
                    <div class="modal-body add-role-model-body">
                        <div class="form-group">
                            <label for="role_title">Role title</label>
                            <input type="hidden" class="form-control" name="role_id" id="role_id"/>
                            <input type="text" class="form-control" name="role_name" id="role_name" required/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="add_edit" class="btn btn-custom2 btn-primary btn-theme1">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script type="text/javascript">

        $(document).ready(function () {

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

        function getTypeDetails(type_id) {
            $.ajax({
                method: "get",
                url: "{{ route('role_details') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    type_id: type_id
                }
            }).done(function (data) {
                json = jQuery.parseJSON(data);
                $('#role_header').html('Edit Role');
                $('#role_id').val(json.id);
                $('#role_name').val(json.role_name);
                $('#add_edit').html('Update');
                $('#add_user_type').modal('toggle');
                $('#add_user_type').modal('show');
            });
        }
    </script>
@endpush
