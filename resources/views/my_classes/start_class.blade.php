@extends('layouts.teacher-dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            Lesson taught in this class
            <section class="panel">
                <header class="panel-heading">
                    <a href="{{ route('teacher_classes',['type'=>1]) }}" class=""><i class="fas fa-chevron-left"></i></a> Start Class
                    <div class="cls_detail">
                        <span class="std_name">Name: {{$start_cls->student->student_name}}  </span>
                        <span class="course_name">Course : {{$start_cls->course->course_title}} </span>
                        <span class="cls_slot">Time: {{ $start_cls->classTime->t_slot}}</span>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table style="width:100%" class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="">Lesson taught</th>
                                <th class="">Lesson Remarks</th>
                                <th class="">Date</th>
                            </tr>
                            </thead>

                            <tbody>

                            @if(!empty($taught_lessons) && isset($taught_lessons))
                                <?php $counter = 1; ?>
                                @foreach($taught_lessons as $taught_lesson)
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{$taught_lesson->coursesLessons->lesson_label}}</td>
                                        <td>{{$taught_lesson->remarks}}</td>
                                        <td>{{$taught_lesson->class_date}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                    </div>

                    <form role="form" action="{{ route('start_cls_submitted') }}" method="post" id="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="class_id"  value="{{ $start_cls->id }}">
                        <input type="hidden" name="course_idd" id="course_idd"  value="{{ $start_cls->course_id }}">
                        <input type="hidden" name="std_id" id="std_id"  value="{{ $start_cls->student_id }}">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label-setting">Lesson repeat</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input lesson_repeat" type="radio" name="lesson_repeat" id="lesson_repeat1" value="0" checked>
                                        <label class="form-check-label" for="lesson_repeat1">No</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input lesson_repeat" type="radio" name="lesson_repeat" id="lesson_repeat2" value="1">
                                        <label class="form-check-label" for="lesson_repeat2">Yes</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <p id="lesson_error" style="color: red;display: none">Please select lesson</p>
                                    <label class="label-setting">Load Lesson</label>
                                    <select class="form-control" name="course_lesson" id="course_lesson">
                                        <option value="">Select Lesson</option>
                                        @foreach($courses_lessons as $courses_lesson)
                                             <option value="{{$courses_lesson->id}}">{{$courses_lesson->lesson_label}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <a href="javascript:void(0)" class="btn-primary btn load_lesson" style="margin-top: 21px;">Load lesson</a>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Remarks</label>
                                    <textarea id="remarks" class="form-control" name="remarks" rows="3" cols="50">{{ old('remarks') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Audio Files(s)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="recordings[]" multiple id="validatedCustomFile" required>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12">
                                <div class="form-group cu-btn-category">
                                    <button id="add_course" type="submit" class="btn btn-primary btn-theme1">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <div id="load_lesson_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width:1250px;">

                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="role_header">Lesson</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="">Close</button>
                    </div>
                </div>

        </div>
    </div>
<style>
    #load_lesson_modal .modal-body img{width: 100% !important;height: auto !important;}
</style>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {

            $(".load_lesson").click(function (event) {
                $("#loading").show();
                let course_lesson = $("#course_lesson").val();
                $('#lesson_error').css("display", "none");
                if(course_lesson == "") {
                    $('#lesson_error').css("display", "inline");
                    return false;
                }
                $.ajax({
                    method: "get",
                    url: "{{ route('load_lesson') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        course_lesson: course_lesson
                    }
                }).done(function (data) {
                    $('#load_lesson_modal').modal('show');
                    $("#load_lesson_modal .modal-body").html(data.html);
                    $("#loading").hide();
                });
            });

            $(".lesson_repeat").click(function (event) {
                $("#loading").show();
                let std_id = $("#std_id").val();
                let course_idd = $("#course_idd").val();
                let repeat_status = $(this).val();
                $.ajax({
                    method: "get",
                    url: "{{ route('taught_lesson') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        std_id: std_id,
                        course_id: course_idd,
                        repeat_status: repeat_status
                    }
                }).done(function (data) {
                    $("#course_lesson").html(data);
                    $("#loading").hide();
                });
            });

        });



    </script>
@endpush
