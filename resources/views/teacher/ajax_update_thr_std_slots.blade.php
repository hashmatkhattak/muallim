<table class="table">
    <p id="validate_std_schedule_error" style="color: red"></p>
    <input type="hidden" name="student_teacher_id" value="{{ $student_teacher_id }}"/>
    <tr style="padding: 1px">
        <?php $i = 0;?>
        @foreach($week_days as $key=>$slots)
            <td style="padding-left:0px">
                <ul style="width: 151px;margin: 0 !important;padding: 0 !important;">
                    <li style="text-align: center"><b>{{ $key }}</b></li>
                    <select name="schedule[{{ ($i+1)}}][slot_id]" id="{{ $key }}" class="form-control">
                        @if(isset($assigned_slots[$i]->assigned_slots[0]->slot_id))
                            <option value="{{ $assigned_slots[$i]->assigned_slots[0]->slot_id }}">{{ $assigned_slots[$i]->assigned_slots[0]->t_slot }}</option>
                        @endif
                        <option value="">Select time</option>
                        @foreach($slots as $slot)
                            <option value="{{ $slot->id }}">{{ $slot->t_slot }}</option>
                        @endforeach
                    </select>
                    <br>
                    <li>
                        <select name="schedule[{{ ($i+1)}}][required_slots]" id="no_slot_{{ $key }}" class="form-control">
                            @if(isset($assigned_slots[$i]->assigned_slots[0]->required_slots))
                                <option value="{{ $assigned_slots[$i]->assigned_slots[0]->required_slots }}">{{ $assigned_slots[$i]->assigned_slots[0]->required_slots }}</option>
                            @endif
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </li>
                </ul>
            </td>
            <?php $i++?>
        @endforeach
    </tr>
</table>
<div class="row">
    <div class="col-md-12 col-sm-12 col-12">
        <div class="form-group cu-btn-category">
            <button type="button" class="btn btn-primary btn-theme1" id="btn_validate_std_schedule">Update</button>
        </div>
    </div>
</div>
