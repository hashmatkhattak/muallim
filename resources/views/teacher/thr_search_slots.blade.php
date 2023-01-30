@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Search teacher free slot
                </header>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">
                            <b>Select teacher</b>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="hidden" id="request_type" value=""/>
                                <select type="text" class="form-control" name="teacher_id" id="teacher_id">
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $thr)
                                        <option value="{{ $thr->id }}">{{ $thr->details->first_name." ".$thr->details->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="class" id="ajax_thr_free_slots">

                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
