<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th class="table-header title">Student name</th>
            <th class="table-header title">Parent name</th>
            <th class="table-header title">Course name</th>
            <th class="table-header title">Class Timings</th>
            <th class="table-header title">Action</th>
        </tr>
        <tbody>
        @foreach($teacher_students as $student)
            <tr>
                <td>{{ $student->student_name }}</td>
                <td>{{ $student->first_name." ".$student->last_name }}</td>
                <td>{{ $student->course_title }}</td>
                <td>
                    @foreach($student->week_days as $day)
                        @if($day->t_slot!='')
                            <small>{{ $day->day }}( {{ $day->t_slot }})</small><br>
                        @endif
                    @endforeach
                </td>
                <td>
                    <a class="btn btn-success add-schedule" href="javascript:void(0)" id="{{ $student->teacher_student_id }}">
                        <i class="fas fa-plus"></i>
                    </a>

                    <a href="javascript:void(0)" data-thr_std_id="{{ $student->teacher_student_id }}" class="btn btn-danger delete_thr_std_schedule" title="Delete" type="warning" msg="Are you sure to delete this user type?">
                        <i class="far fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div id="update_record" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width:1250px;">
        <form action="{{ route('std_schedule_submitted') }}" method="post" id="update_std_schedule">
            {{ csrf_field() }}
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="role_header"> Update Teacher Student Classes Schedule</h4>
                </div>
                <div class="modal-body update_thr_std_cls_slot">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_close_std_schedule">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {

        $(".delete_thr_std_schedule").click(function (event) {
            event.preventDefault();
            var msg = ($(this).attr('msg'));
            var type = ($(this).attr('type'));
            var url = ($(this).attr('href'));
            var student_teacher_id = ($(this).attr('data-thr_std_id'));
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
                $('#ajax-loading').show();
                $.ajax({
                    method: "get",
                    url: "{{ route('delete_thr_std_schedule') }}",
                    data: {
                        student_teacher_id: student_teacher_id
                    }
                }).done(function (data) {
                    load_thr_sdt();
                });
            }
        })
        });

        //----------------------------------------------------------------------------------
    });
</script>
