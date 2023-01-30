<table class="table table-bordered">
    <tbody>
    <tr>
        <th class="table-header title">Day</th>
        <th class="table-header title">Time</th>
    </tr>
    @foreach($schedule_detail as $detail)
        <tr>
            <td>{{$detail->day}}</td>
            <td>{{$detail->t_slot}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
