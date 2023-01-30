@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    Students
                    <a class="btn btn-primary pull-right pasiingset btn-theme1" href="{{ route('add-student',['user_id'=> $user_id]) }}">
                        <i class="icon_plus_alt2" aria-hidden="true"></i>
                        Add student
                    </a>
                </header>
                <div class="panel-body tobales">
                    <div class="table-responsive">

                        <table id="example" class="display" style="width:100%" class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="">Full name</th>
                                <th class="">Fees amount</th>
                                <th class="">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>{{$student->student_name}}</td>
                                    <td>{{$student->fees_amount}}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('edit_student',['sid'=> $student->id]) }}"> <i class="fas fa-edit"></i></a>

                                            @if($student->status == '0' || $student->status == '2')
                                                <a href="{{ route('change_student_status',['status'=>'1','sid'=> $student->id]) }}" title="Activate" class="btn btn-warning change_status" type="warning" msg="Are you sure to Activate this user"> <i
                                                        class="far fa-times-circle"></i></a>
                                            @endif
                                            @if($student->status == '1')
                                                <a href="{{ route('change_student_status',['status'=>'0','sid'=> $student->id]) }}" title="DeActivate" class="btn btn-warning change_status" type="warning" msg="Are you sure to DeActivate this user"> <i
                                                        class="far fa-times-circle"></i></a>
                                            @endif
                                            <a href="{{ route('change_student_status',['status'=>'2','sid'=> $student->id]) }}" title="Delete" class="btn btn-danger change_status" type="warning" msg="Are you sure to delete this user"> <i
                                                    class="far fa-trash-alt"></i></a>
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
