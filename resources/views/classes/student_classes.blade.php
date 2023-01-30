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
                                <input type="text" name="start_date" id="start_date" class="form-control" value="2021-01-30"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="col-md-3">
                            <b>Time slot</b>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
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
                            <th class="">Action</th>
                        </tr>
                        </thead>

                        <tbody id="dyn_contents">

                        </tbody>
                    </table>
                    <div class="loading" id="loading" style="text-align: center">
                        <img src="{{ asset('assets/img/loading.gif') }}" width="130px" height="130px"/>
                    </div>
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
        });

        function loadClasses() {
            $("#loading").css('display', "block");
            let start_date = $("#start_date").val();
            let slot_id = $("#slot_id").val();
            $.ajax({
                method: "POST",
                url: "{{ route('ajax_load_classes') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    start_date: start_date,
                    slot_id: slot_id,
                }
            }).done(function (data) {
                $("#dyn_contents").html('');
                $("#dyn_contents").html(data);
                $("#loading").css('display', "none");
            });
        }
    </script>
@endpush
