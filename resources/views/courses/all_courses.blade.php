@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-user"></i>
                    Courses
                    <a class="btn btn-primary pull-right pasiingset btn-theme1" href="{{ route('add_course') }}">
                        <span class="icon-space">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        </span>Add Course
                    </a>
                </header>
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th class="table-header title">Course title</th>
                                <th class="table-header title">Status</th>
                                <th class="table-header title">Action</th>
                            </tr>
                            @foreach($courses as $course)
                                <tr>
                                    <td>{{$course->course_title}}</td>
                                    <td>{{getStatus($course->status)}}</td>
                                    <td>
                                        <a class="btn btn-success" href="{{ route('all_course_lessons',['course_id'=> $course->id]) }}">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                        <a class="btn btn-primary" href="{{ route('edit_course',['cid'=>$course->id]) }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($course->status == 0)
                                            <a href="{{ route('change_course_status',['course_id'=>$course->id,'status'=>'1']) }}" class="btn btn-success change_status" title="Activate" type="warning" msg="Are you sure you want to activate?">
                                                <i class="far fa-check-circle"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('change_course_status',['course_id'=>$course->id,'status'=>'0']) }}" class="btn btn-warning change_status" title="DeActivate" type="warning" msg="Are you sure you want to DeActivate?">
                                                <i class="far fa-times-circle"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('change_course_status',['status'=>'2','course_id'=> $course->id]) }}" class="btn btn-danger change_status" title="Delete" type="warning" msg="Are you sure to delete the course?"/>
                                         <i class="far fa-trash-alt"></i>
                                        </a>
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

    <div id="add_course" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form action="{{ route('role_submitted') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="role_header"> Add course</h4>
                    </div>
                    <div class="modal-body add-role-model-body">
                        <div class="form-group">
                            <label for="role_title">Course Name</label>
                            <input type="hidden" class="form-control" name="role_id" id="role_id"/>
                            <input type="text" class="form-control" name="course_name" id="course_name" required/>
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
    <script>
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

    </script>
@endpush

