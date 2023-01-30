@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Edit Invoice
                </header>
                <div class="panel-body">
                    <form role="form" action="{{ route('edit_invoice_submitted') }}" method="post" id="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tid" value="{{ $invoiceTemplates->id }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">key (This will be not editable)</label>
                                    <input type="text" class="form-control" name="key" id="key" value="{{ $invoiceTemplates->key }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Subject</label>
                                    <input type="text" class="form-control"  name="mesg_subject" id="mesg_subject" value="{{ $invoiceTemplates->mesg_subject }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label-setting">Body</label>
                                    <textarea id="editor1" class="form-control" name="mesg_body" rows="4" cols="50">{{ $invoiceTemplates->mesg_body }}</textarea>
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12">
                                <div class="form-group cu-btn-category">
                                    <button id="add_course" type="submit" class="btn btn-primary btn-theme1">Update</button>
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
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
    <script>

        CKEDITOR.replace('editor1', {
            filebrowserUploadUrl: "{{route('lesson_content', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });

    </script>
@endpush
