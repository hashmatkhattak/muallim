@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Teacher Student classes Schedule
                </header>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">
                            <b>Select teacher</b>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select type="text" class="form-control" name="teacher_id" id="std_teacher_id">
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $thr)
                                        <option value="{{ $thr->id }}">{{ $thr->details->first_name." ".$thr->details->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <b>Select Course</b>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select type="text" class="form-control" name="course_id" id="course_id">
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->course_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-md-offset-2">
                            <div class="form-group cu-btn-category">
                                <button id="load_teacher_student" type="button" class="btn btn-primary btn-theme1">Load Schedule</button>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="ajax_thr_std_classes">

                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('scripts')

@endpush
