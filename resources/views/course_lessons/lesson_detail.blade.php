@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-user"></i>
                    Lessons Detail
                </header>
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                   {{$lesson_detail->lesson_label}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{$lesson_detail->lesson_detail}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! $lesson_detail->lesson_tablet !!}
                                </div>
                            </div>
                        </div>
                </div>
            </section>
        </div>
    </div>

@endsection
@push('scripts')

@endpush
