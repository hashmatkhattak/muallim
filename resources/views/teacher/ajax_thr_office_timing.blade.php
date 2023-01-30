<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="label-setting">Start time 1</label>
            <select type="text" class="form-control" name="start_time_1" id="start_time_1">
                @foreach($start_slots as $slot)
                    <?php
                    $selected = '';
                    if(isset($tw[0]->start_slot_id) AND $tw[0]->start_slot_id == $slot->id)
                        $selected = 'selected';
                    ?>
                <option value="{{ $slot->id }}" {{$selected}}>{{ $slot->t_slot }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="label-setting">End Time 1</label>
            <select type="text" class="form-control" name="end_time_1" id="end_time_1">
                @foreach($end_slots as $eslot)
                    <?php
                    $selected = '';
                    if(isset($tw[0]->end_slot_id) AND $tw[0]->end_slot_id == $eslot->id)
                        $selected = 'selected';
                    ?>
                <option value="{{ $eslot->id }}" {{$selected}}>{{ $eslot->t_slot }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="label-setting">Start time 2</label>
            <select type="text" class="form-control" name="start_time_2" id="start_time_2">
                <option value="">Please select start time 2</option>
                @foreach($start_slots as $sslot)
                    <?php
                        $selected = '';
                        if(isset($tw[1]->start_slot_id) AND $tw[1]->start_slot_id == $sslot->id)
                            $selected = 'selected';
                    ?>
                     <option value="{{ $sslot->id }}" {{$selected}}>{{ $sslot->t_slot }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="label-setting">End Time 2</label>
            <select type="text" class="form-control" name="end_time_2" id="end_time_2">
                <option value="">Please select end time 2</option>
                @foreach($end_slots as $eslot)
                    <?php
                    $selected = '';
                    if(isset($tw[1]->end_slot_id) AND $tw[1]->end_slot_id == $eslot->id)
                        $selected = 'selected';
                    ?>
                <option value="{{ $eslot->id }}" {{$selected}}>{{ $eslot->t_slot }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
