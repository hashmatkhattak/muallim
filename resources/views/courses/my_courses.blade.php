@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-user"></i>
                    My Courses
                </header>
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th class="table-header title">Course title</th>
                                <th class="table-header title">Action</th>
                            </tr>
                            @foreach($courses as $course)
                                <tr>
                                    <td>{{$course->course_title}}</td>
                                    <td>
                                        <a class="btn btn-success" href="{{ route('my_course_lessons',['course_id'=> $course->id]) }}">
                                           Lessons
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

@endsection
@push('scripts')

@endpush
