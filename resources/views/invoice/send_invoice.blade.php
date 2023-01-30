@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Send invoice
                </header>
                <div class="panel-body">
                    <form role="form" action="{{ route('send_invoice_submitted') }}" method="post" id="" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_name">Search student (Name,Email,Phone)</label>
                                    <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{ old('user_id') }}"/>
                                    <input type="text" class="form-control" name="search_student" id="search_student" value="{{ old('search_student') }}"/>
                                    <div id="student_list"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Type</label>
                                    <select class="form-control" name="type" id="type">
                                        <option value="">Select invoice type</option>
                                        <option value="1" {{ old('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="2" {{ old('type') == 'instant' ? 'selected' : '' }}>Instant</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Subject</label>
                                    <input type="text" class="form-control" name="subject" id="subject" value="{{ old('subject') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Description</label>
                                    <input type="text" class="form-control" name="description" id="description" value="{{ old('description') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Currency</label>
                                    <input type="text" class="form-control" name="currency" id="currency" value="{{ old('currency') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Payment method</label>
                                    <select class="form-control" name="payment_method" id="payment_method">
                                        <option value="">Select payment method</option>
                                        <option value="1" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>paypal</option>
                                        <option value="2" {{ old('payment_method') == 'other' ? 'selected' : '' }}>other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Due date</label>
                                    <input type="text" class="form-control" name="due_date" id="due_date" value="{{ old('due_date') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12">
                                <div class="form-group cu-btn-category">
                                    <button type="submit" class="btn btn-primary btn-theme1">Send</button>
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
            $('#payment_date,#due_date').datepicker({dateFormat: 'yy-mm-dd'});
            $('input#search_student').keyup(function () {
                $.ajax({
                    method: "GET",
                    url: "{{ route("search_parent") }}",
                    data: {
                        search: this.value
                    }
                }).done(function (data) {
                    $("#student_list").html(data);
                    $("#student_list").fadeIn();
                });
            });
            $(document).on("click", ".studnet_ul li", function () {
                $('#search_student').val($(this).text());
                $('#user_id').val($(this).attr('data-userid'));
                $('#student_list').fadeOut("fast");
            });


        });
    </script>

@endpush
