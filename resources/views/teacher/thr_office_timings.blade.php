@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Teacher Work Timings
                </header>
                <div class="panel-body">
                    <form role="form" action="{{ route('thr_office_timings_submitted') }}" method="post" id="teacher_work_timings" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <p id="error_message" style="display: none"></p>
                                <p id="success_message" style="display: none"></p>
                            </div>

                            <div class="col-md-2">
                                <b>Select Teacher</b>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <select type="text" class="form-control" name="teacher_id" id="teacher_id">
                                        <option value="">Select Teacher</option>
                                        @foreach($teachers as $thr)
                                            <option value="{{ $thr->id }}">{{ $thr->details->first_name." ".$thr->details->last_name."   (".$thr->email.")" }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="time_slot">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="label-setting">Start time 1</label>

                                        <select type="text" class="form-control" name="start_time_1" id="start_time_1">
                                            <option value="">Please select Start time 1</option>
                                            @foreach($start_slots as $slot)
                                                <option value="{{ $slot->id }}">{{ $slot->t_slot }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="label-setting">End Time 1</label>
                                        <select type="text" class="form-control" name="end_time_1" id="end_time_1">
                                            <option value="">Please select End Time 1</option>
                                            @foreach($end_slots as $eslot)
                                                <option value="{{ $eslot->id }}">{{ $eslot->t_slot }}</option>
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
                                                <option value="{{ $sslot->id }}">{{ $sslot->t_slot }}</option>
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
                                                <option value="{{ $eslot->id }}">{{ $eslot->t_slot }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12">
                                <div class="form-group cu-btn-category">
                                    <button id="add_work_timings" type="submit" class="btn btn-primary btn-theme1">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            //-------------------------------------------------------------

            $(document).on("click","#add_work_timings",function() {
                event.preventDefault();
                var teacher_id = $('#teacher_id').val();
                $('#error_message').html();
                $('#success_message').html();

                if(teacher_id == "") {
                    $('#error_message').html('Please select teacher');
                    $('#error_message').show().delay(5000).fadeOut();
                    return false;
                }

                var start_time_1 = $('#start_time_1').val();
                var end_time_1 = $('#end_time_1').val();
                console.log('start_time_1 :'+start_time_1+':  end_time_1: '+end_time_1);
                //if(parseInt(start_time_1) > parseInt(end_time_1)) {
                if((start_time_1 == "" || end_time_1 == "")) {
                    $('#error_message').html('start_time_1 OR end_time_1 is empty');
                    $('#error_message').show().delay(5000).fadeOut();
                    return false;
                }

                if(parseInt(start_time_1) >  parseInt(end_time_1)) {
                    $('#error_message').html('1. Start time will be greater then end time');
                    $('#error_message').show().delay(5000).fadeOut();
                    return false;
                }

                var start_time_2 = $('#start_time_2').val();
                var end_time_2 = $('#end_time_2').val();
                console.log('start_time_2 :'+start_time_2+':  end_time_2: '+end_time_2);
                if((end_time_2 != "" && start_time_2 != "")  && (parseInt(start_time_2) > parseInt(end_time_2))) {
                    $('#error_message').html('2. Start time will be greater then end time');
                    $('#error_message').show().delay(5000).fadeOut();
                    return false;
                }
                $('#ajax-loading').show();
                $.ajax({
                    method: "POST",
                    url: "<?php echo e(route("thr_office_timings_submitted")); ?>",
                    data: {
                        '_token': '<?php echo e(csrf_token()); ?>',
                        data : jQuery("#teacher_work_timings").serialize()
                    }
                }).done(function (data) {
                    if(data == 'done') {
                        $('#error_message').html('');
                        $('#success_message').html('Work timings has been submitted');
                        $('#success_message').show().delay(5000).fadeOut();
                        $('#ajax-loading').hide();
                    }
                });

            });

            //-------------------------------------------------------------

            $('#teacher_id').change(function () {
                let teacher_id = $(this).val();
                $('#ajax-loading').show();
                // console.log(teacher_id);
                $.ajax({
                    method: "get",
                    url: "<?php echo e(route("get_thr_office_timing")); ?>",
                    data: {
                        teacher_id: teacher_id
                    }
                }).done(function (data) {
                    $("#time_slot").html(data.html);
                    $('#ajax-loading').hide();

                });
            });

            //-------------------------------------------------------------------------
        });
    </script>
@endpush
