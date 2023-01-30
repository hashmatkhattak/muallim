<script type="text/javascript">
    let teacher_id = "";
    let course_id = "";
    $(document).ready(function () {
        DateTime = luxon.DateTime;
        TimeZone = DateTime.now().zoneName;
        console.log(TimeZone);
        $("#time_zone").val(TimeZone);
        $("#load_teacher_student").click(function (event) {
            $('#ajax-loading').show();
            event.preventDefault();
            $('#std_teacher_id').css('border', '1px solid #c7c7cc');
            $('#course_id').css('border', '1px solid #c7c7cc');
            teacher_id = $('#std_teacher_id').val();
            course_id = $('#course_id').val();
            if (teacher_id === '') {
                $('#ajax-loading').hide();
                $('#std_teacher_id').css('border-color', 'red');
                return false;
            } else {
                load_thr_sdt();
            }
        });
        $('#teacher_id').change(function () {
            let teacher_id = $(this).val();
            let request_type = $("#request_type").val();
            $.ajax({
                method: "POST",
                url: "{{ route('thr_free_slots') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    teacher_id: teacher_id,
                    request_type: request_type
                }
            }).done(function (data) {
                $("#ajax_thr_free_slots").html('');
                $("#ajax_thr_free_slots").html(data);
            });
        });

    });

    function load_thr_sdt() {
        $.ajax({
            method: "POST",
            url: "{{route('load_thr_sdt')}}",
            data: {
                '_token': '{{ csrf_token() }}',
                teacher_id: teacher_id,
                course_id: course_id
            }
        }).done(function (data) {
            $("#ajax_thr_std_classes").html('');
            $("#ajax_thr_std_classes").html(data);
            $('#ajax-loading').hide();
            setOnClicKEvents();
        });
    }

    function setOnClicKEvents() {
        $(".add-schedule").click(function (event) {
            $('#ajax-loading').show();
            let student_teacher_id = $(this).attr('id');
            let teacher_id = $('#std_teacher_id').val();
            let course_id = $('#course_id').val();
            $.ajax({
                method: "get",
                url: "{{ route('add_std_schedule') }}",
                data: {
                    student_teacher_id: student_teacher_id,
                    teacher_id: teacher_id,
                    course_id: course_id,
                    request_type: 'std_schedule'
                }
            }).done(function (data) {
                $('.update_thr_std_cls_slot').html(data.html);
                $('#update_record').modal('show');
                $('#ajax-loading').hide();

                $("#btn_validate_std_schedule").click(function (event) {
                    $('#ajax-loading').show();
                    let student_teacher_id = $(this).attr('id');
                    $.ajax({
                        method: "post",
                        url: "{{ route('std_schedule_submitted') }}",
                        data: $("#update_std_schedule").serialize()
                    }).done(function (data) {
                        let response = JSON.parse(data);
                        console.log(response.status);
                        if (response.status === 0) {
                            $('#validate_std_schedule_error').text(response.message);
                        } else {
                            $('#validate_std_schedule_error').text(response.message);
                            $('#validate_std_schedule_error').css('color', 'green');
                            $('.update_thr_std_cls_slot').html(data.html);
                        }
                        $('#ajax-loading').hide();
                    });
                });

                $("#btn_close_std_schedule").click(function (event) {
                    $('#ajax-loading').show();
                    load_thr_sdt();
                });
            });
        });
    }

</script>
