@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Assign student to teacher
                </header>
                <form method="POST" action="" id="assign_students_form">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_name">Search student</label>
                                    <input type="hidden" class="form-control" name="student_id" id="student_id" value="{{ old('student_id') }}"/>
                                    <input type="text" class="form-control" name="search_student" id="search_student"/>
                                    <div id="student_list"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" class="form-control" id="request_type" value="assign_std">
                                <div class="form-group">
                                    <label for="student_name">Select Teacher</label>
                                    <select class="form-control" name="teacher_id" id="teacher_id">
                                        <option value="">Select Teacher</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->details->first_name." ".$teacher->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_name">Select Course</label>
                                    <select class="form-control" name="course_id" id="course_id">
                                        <option value="">Select Course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->course_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_name">Fees</label>
                                    <input type="text" class="form-control" name="fees" id="fees"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_name">Remarks</label>
                                    <textarea name="remarks" id="" cols="30" rows="2" class="form-control">

                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="ajax_thr_free_slots">

                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            //----------------------------------------------------------------------------------
            $('#teacher_id').change(function () {
                let teacher_id = $(this).val();
                $('#ajax-loading').show();
                $.ajax({
                    method: "POST",
                    url: "{{ route('thr_free_slots') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        teacher_id: teacher_id,
                        request_type: 'assign_std'
                    }
                }).done(function (data) {
                    $("#ajax_thr_free_slots").html('');
                    $("#ajax_thr_free_slots").html(data);
                    $('#ajax-loading').hide();
                });
            });
            //----------------------------------------------------------------------------------
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
            //----------------------------------------------------------------------------------

            //----------------------------------------------------------------------------------
            $('#example').DataTable({
                "paging": false,
                "bInfo": false,
                "searching": false
            });
            //----------------------------------------------------------------------------------
            $('input#search_student').keyup(function () {
                if (this.value.length < 1) return;
                $.ajax({
                    method: "GET",
                    url: "{{ route('search_student') }}",
                    data: {
                        student_name: this.value
                    }
                }).done(function (data) {
                    $("#student_list").html(data);
                    $("#student_list").fadeIn();
                });
            });

            $(document).on("click", ".studnet_ul li", function () {
                $('#search_student').val($(this).text());
                $('#student_id').val($(this).attr('data-userid'));
                $('#student_list').fadeOut("fast");
            });

            //---------------------------------------------------------------------------------

            $(document).on("click", ".add_student_to_teacher_schedule", function () {
                $("#common-validation-error,#common-validation-success").hide();
                $.ajax({
                    method: "POST",
                    url: "{{ route('assign_students_submitted') }}",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        data: $("#assign_students_form").serialize()
                    }
                }).done(function (data) {
                    let response = jQuery.parseJSON(data);
                    if (response.status === 0) {
                        $("#common-validation-error").css("display", "block");
                        $("#validation-error").html(response.message);
                    } else if (response.status === 1) {
                        $("#common-validation-success").css("display", "block");
                        $("#validation-success").html(response.message);
                    }
                    $('#ajax-loading').hide();
                });
            });
        });
    </script>
@endpush
