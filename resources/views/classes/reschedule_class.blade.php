<div class="form-group">
    <label for="date">Date</label>
    <input type="hidden" id="reschedule_class_id" name="reschedule_class_id" value="{{ $reschedule_class_id }}">
    <input type="text" name="reschedule_date" id="reschedule_date" class="form-control" value="">
</div>
<div class="form-group">
    <label for="slot_id">Time slot</label>
    <select class="form-control" id="reschedule_slot_id" name="slot_id">
        <option value=""></option>
        @foreach($slots as $slot)
            <option value="{{ $slot->id }}">{{ $slot->t_slot }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="slot_id">Available teachers</label>
    <select class="form-control" id="slot_id" name="slot_id">
        <option value=""></option>
    </select>
</div>

<div class="form-group">
    <label for="student_name">Student name</label>
    <input type="text" name="student_name" id="student_name" class="form-control" value="student_name">
</div>

<div class="form-group">
    <label for="remarks">Remarks</label>
    <textarea class="form-control" id="remarks" name="remarks">
        </textarea>
</div>
