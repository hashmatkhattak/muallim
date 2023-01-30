<table id="table table-bordered">
    <tr>
        <td style="width: 150px">
            <b>Teacher name</b>
        </td>
        <td id="teacher_name">{{ $report->teacher->first_name." ".$report->teacher->last_name }}</td>
        <td style="width: 150px">
            <b>Student name</b>
        </td>
        <td style="width: 150px" id="student_name">{{ $report->student->student_name }}</td>
    </tr>
    <tr>
        <td style="width: 150px">
            <b>Class timings</b>
        </td>
        <td style="width: 150px" id="cls_timings">{{ $report->classTime->t_slot }}</td>
        <td style="width: 150px">
            <b>Course name</b>
        </td>
        <td style="width: 150px" id="cls_timings">{{ $report->course->course_title }}</td>
    </tr>
    <tr>
        <td style="width: 150px">
            <b>Start Time</b>
        </td>
        <td style="width: 150px" id="start_time">{{ $report->start_time }}</td>
        <td style="width: 150px">
            <b>End Time</b>
        </td>
        <td style="width: 150px" id="end_time">{{ $report->end_time }}</td>
    </tr>
    <tr>
        <td style="width: 150px">
            <b>Total Class time</b>
        </td>
        <td style="width: 150px" id="start_time">Hashmat Ull</td>
        <td style="width: 150px">
            <b>Lesson Repeated</b>
        </td>
        <td style="width: 150px" id="end_time">Hashmat Ull</td>
    </tr>
    <tr>
        <td style="width: 150px">
            <b>Lesson taughts</b>
        </td>
        <td style="width: 150px" id="start_time">Hashmat Ull</td>
        <td style="width: 150px">
            <b>Remarks</b>
        </td>
        <td style="width: 150px" id="remarks">{{ $report->remarks }}</td>
    </tr>
</table>
