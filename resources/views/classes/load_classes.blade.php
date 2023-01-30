@extends('layouts.dashboard')
@section('content')
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <i class="fas fa-users" aria-hidden="true"></i>
                Classes
            </header>
            <div class="panel-body tobales">
                <div class="row">
                    <div class="col-md-5">
                        <div class="col-md-3">
                            <b>Start Date</b>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" name="start_date" id="start_date" class="form-control" value="{{date('Y-m-d')}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="col-md-3">
                            <b>{{ $label }}</b>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                @switch($type)
                                    @case(1)
                                    <div class="input-group inputborder inputmargin">
                                        <select name="slot_id" id="slot_id" class="form-control">
                                            <option value="">Select slot</option>
                                            @foreach($time_slots as $ts)
                                                <option value="{{ $ts->id}}">{{ $ts->t_slot }}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-btn displayset" id="basic-addon2">
                                            <button type="submit" class="btn btn-default btn-themeinput" id="btn_load_classes">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </button>
                                        </span>
                                    </div>
                                    @break
                                    @case(2)
                                    <div class="input-group inputborder inputmargin">
                                        <select name="teacher_id" id="teacher_id" class="form-control">
                                            <option value="">Select Teacher</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id}}">{{ $teacher->details->first_name." ".$teacher->last_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-btn displayset" id="basic-addon2">
                                        <button type="submit" class="btn btn-default btn-themeinput" id="btn_load_classes">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </span>
                                    </div>
                                    @break
                                    @case(3)
                                    <div class="input-group inputborder inputmargin">
                                        <select name="std_id" id="std_id" class="form-control">
                                            <option value="">Select Student</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id}}">{{ $teacher->details->first_name." ".$teacher->last_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-btn displayset" id="basic-addon2">
                                        <button type="submit" class="btn btn-default btn-themeinput" id="btn_load_classes">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </span>
                                    </div>
                                    @break
                                    @default
                                    <span>Something went wrong, please try again</span>
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table style="width:100%" class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="">Student name</th>
                            <th class="">Teacher name</th>
                            <th class="">Class timing</th>
                            <th class="">Course Name</th>
                            <th class="">Class type</th>
                            <th class="">Recordings</th>
                            <th class="">Status</th>
                            <th class="">Comments</th>
                            <th class="">Action</th>
                        </tr>
                        </thead>

                        <tbody id="dyn_contents">

                        </tbody>
                    </table>
                    <div class="loading" id="loading" style="text-align: center">
                        <img src="{{ asset('assets/img/loading.gif') }}" width="130px" height="130px"/>
                    </div>
                    <div id="view_cls_report" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">View class report</h4>
                                </div>
                                <div class="modal-body" id="dyn_report_contents">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="" method="post">
                        <div id="reschedule" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Reschedule Class</h4>
                                    </div>
                                    <div class="modal-body" id="dyn_reschedule_contents">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-default btn-themeinput">Submit</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            loadClasses();
            $("#btn_load_classes").click(function (event) {
                loadClasses();
            });
            $("#start_date").datepicker({dateFormat: 'yy-mm-dd'});
            setInterval(function () {
                loadClasses(1);
            }, 9000);
        });

        function loadClasses(flag = 0) {
            if (flag === 0) {
                $("#loading").css('display', "block");
            }
            let start_date = $("#start_date").val();
            let slot_id = $("#slot_id").val();
            let teacher_id = $("#teacher_id").val();
            let std_id = $("#std_id").val();
            let time_zone = $("#time_zone").val();
            $.ajax({
                method: "POST",
                url: "{{ route('ajax_load_classes') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    time_zone: time_zone,
                    start_date: start_date,
                    slot_id: slot_id,
                    teacher_id: teacher_id,
                    std_id: std_id
                }
            }).done(function (data) {
                $("#dyn_contents").html('');
                $("#dyn_contents").html(data);
                $("#loading").css('display', "none");

                $(".reschedule").click(function (event) {
                    let reschedule_class_id = event.target.id;
                    console.log(reschedule_class_id);
                    $.ajax({
                        method: "POST",
                        url: "{{ route('reschedule_class') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            reschedule_class_id: reschedule_class_id
                        }
                    }).done(function (data) {
                        $('#dyn_reschedule_contents').html(data);
                        $('#reschedule').modal('toggle');
                        $("#reschedule_date").datepicker({dateFormat: 'yy-mm-dd'});

                        $("#reschedule_slot_id").change(function (event) {
                            let reschedule_date = $("#reschedule_date").val();
                            let reschedule_class_id = $("#reschedule_class_id").val();
                            let slot_id = $(this).val();
                            $("#reschedule_date").css('border-color', "#8e8e93");
                            if (reschedule_date === '') {
                                $("#reschedule_date").css('border-color', "red");
                                $("#reschedule_slot_id").val("");
                                return false;
                            }
                            $.ajax({
                                method: "POST",
                                url: "{{ route('available_teacher') }}",
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    reschedule_date: reschedule_date,
                                    reschedule_class_id: reschedule_class_id,
                                    slot_id: slot_id,
                                }
                            }).done(function (data) {

                            });
                        });
                    });

                });
                $(".view_cls_report").click(function (event) {
                    let class_id = event.target.id;
                    console.log(class_id);
                    $.ajax({
                        method: "POST",
                        url: "{{ route('view_cls_report') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            class_id: class_id
                        }
                    }).done(function (data) {
                        $('#dyn_report_contents').html(data);
                        $('#view_cls_report').modal('toggle');
                    });

                });
            });
        }
    </script>
@endpush
