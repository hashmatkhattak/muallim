@extends('layouts.dashboard')
@section('content')
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <i class="fas fa-users" aria-hidden="true"></i>
                Configure Classes
            </header>
            <form action="{{ route('configure_classes_submitted') }}" method="post" autocomplete="off">
                {{ csrf_field() }}
                <div class="panel-body tobales">
                    <div class="row">
                        <div class="col-md-2">
                            <b>Start Date</b>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" name="start_date" id="start_date" class="form-control" readonly value="{{date('Y-m-d')}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <b>End Date</b>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" name="end_date" id="end_date" class="form-control"  readonly value="{{date('Y-m-d', strtotime("+7 days"))}}"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-md-offset-2">
                        <div class="form-group cu-btn-category">
                            <button class="btn btn-primary btn-theme1">Configure Classes</button>
                        </div>
                    </div>

                    <div class="table-responsive">

                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $( "#start_date,#end_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
        });

    </script>
@endpush
