@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Invoice Base Settings

                </header>
                <div class="panel-body">
                    <form role="form" action="{{ route('add_invoice_submitted') }}" method="post" id="" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Invoices Sending Date</label>
                                    <input type="text" class="form-control" name="key" id="key" value="{{ old('key') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Invoices Due Date</label>
                                    <input type="text" class="form-control" name="mesg_subject" id="mesg_subject" value="{{ old('mesg_subject') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Number of Reminders</label>
                                    <select type="text" class="form-control" name="remainder" id="remainder">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="2">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Remainder (After How Many Days)</label>
                                    <select type="text" class="form-control" name="remainder" id="remainder">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting"></label>
                                    <select type="text" class="form-control" name="remainder" id="remainder">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="2">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Remainder Template</label>
                                    <select type="text" class="form-control" name="remainder" id="remainder">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
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

@endpush
