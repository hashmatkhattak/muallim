@if(!empty($classes) && isset($classes[0]))
    @foreach($classes as $cls)
        <tr style="background: {{ $cls->background }}">
            <td>{{ $cls->student->student_name }}</td>
            <td>{{ $cls->teacher->first_name." ".$cls->last_name }}</td>
            <td>{{ $cls->classTime->t_slot }}</td>
            <td>{{ $cls->course->course_title }}</td>
            <td>{{ getClassType($cls->class_type) }}</td>
            <td>{{ "no" }}</td>
            <td>{{ $cls->cls_status }}</td>
            <td></td>
            <td>
                @if($cls->status==0 && $cls->background==='#CCCCCC')
                    <img src="{{ asset('assets/img/'.$cls->icon) }}" width="20px" height="20px"/>
                    <a href="#" class="reschedule" id="{{ $cls->id }}">Reschedule</a>
                @elseif($cls->status===2)
                    <img src="{{ asset('assets/img/'.$cls->icon) }}" width="20px" height="20px"/>
                    <a href="#" class="view_cls_report" id="{{ $cls->id }}">View Report</a>
                @endif
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
