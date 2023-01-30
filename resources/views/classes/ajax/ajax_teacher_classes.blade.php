@extends('layouts.dashboard')
@section('content')
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <i class="fas fa-users" aria-hidden="true"></i>
                Classes
            </header>
            <div class="panel-body tobales">
                <div class="row">
                    <div class="col-md-2">
                        <b>Start Date</b>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="start_date" id="start_date" class="form-control" value="2021-01-30"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <b>Start Date</b>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="start_date" id="start_date" class="form-control" value="2021-01-30"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="text" name="start_date" id="start_date" class="form-control" value="2021-01-30"/>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table style="width:100%" class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="">Student name</th>
                            <th class="">Teacher name</th>
                            <th class="">Class timing</th>
                            <th class="">Course Name</th>
                            <th class="">Class type</th>
                            <th class="">Recordings</th>
                            <th class="">Status</th>
                            <th class="">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($classes as $cls)
                            <tr style="background: '{{ $cls->background }}'">
                                <td>{{ $cls->student->student_name }}</td>
                                <td>{{ $cls->teacher->first_name." ".$cls->last_name }}</td>
                                <td>{{ $cls->classTime->t_slot }}</td>
                                <td>{{ $cls->course->course_title }}</td>
                                <td>{{ getClassType($cls->class_type) }}</td>
                                <td>{{ "no" }}</td>
                                <td>{{ getClassStatus($cls->status) }}</td>
                                <td>
                                    <a class="btn btn-primary" href="{{ route('edit_user',['class_id'=> $cls->id]) }}"> <i class="fas fa-edit"></i></a>
                                    <a href="{{ route('change_user_status',['status'=>'1','class_id'=> $cls->id]) }}" title="Activate" class="btn btn-warning change_status" type="warning" msg="Are you sure to Activate this user"> <i class="far fa-times-circle"></i></a>
                                    <a href="{{ route('change_user_status',['status'=>'0','class_id'=> $cls->id]) }}" title="DeActivate" class="btn btn-warning change_status" type="warning" msg="Are you sure to DeActivate this user"> <i class="far fa-times-circle"></i></a>
                                    <a href="{{ route('change_user_status',['status'=>'2','class_id'=> $cls->id]) }}" title="Delete" class="btn btn-danger change_status" type="warning" msg="Are you sure to delete this user"> <i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')

@endpush
