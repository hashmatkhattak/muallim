@if(!empty($classes) && isset($classes[0]))
    @foreach($classes as $cls)
        <tr style="background: {{ $cls->background }}">
            <td>{{ $cls->student->student_name }}</td>
            <td>{{ $cls->teacher->first_name." ".$cls->last_name }}</td>
            <td>{{ $cls->classTime->t_slot }}</td>
            <td>{{ $cls->course->course_title }}</td>
            <td>{{ getClassType($cls->class_type) }}</td>
            <td>{{ "no" }}</td>
            <td>{{ getClassStatus($cls->status) }}</td>
            <td>

            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="8">
            <h3 style="color: red;text-align: center">No classes is configured</h3>
        </td>
    </tr>
@endif
