@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Add Course
                </header>
                <div class="panel-body">
                    <form role="form" action="{{ route('edit_course_submitted') }}" method="post" id="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="cid" value="{{ $course->id }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Course title</label>
                                    <input type="text" class="form-control" placeholder="Course title" name="course_title" id="course_title" value="{{ $course->course_title }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Course type</label>
                                    <select class="form-control" name="course_type" id="course_type">
                                        <option value="">Select course type</option>
                                        <option value="1" {{ $course->course_type == 1 ? 'selected' : '' }}>Regular</option>
                                        <option value="2" {{ $course->course_type == 2 ? 'selected' : '' }}>Lecture</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label-setting">Description</label>
                                    <textarea id="cdescription" class="form-control" name="cdescription" rows="4" cols="50">{{ $course->cdescription }}</textarea>
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12">
                                <div class="form-group cu-btn-category">
                                    <button id="add_course" type="submit" class="btn btn-primary btn-theme1">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
