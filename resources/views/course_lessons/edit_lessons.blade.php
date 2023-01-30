@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Edit Lesson
                </header>
                <div class="panel-body">
                    <form role="form" action="{{ route('edit_lessons_submitted') }}" method="post" id="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="lid" value="{{ $lesson->id }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Title</label>
                                    <input type="text" class="form-control" name="lesson_label" id="lesson_label" value="{{ $lesson->lesson_label }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label-setting">Lesson detail</label>
                                    <textarea class="form-control" name="lesson_detail" rows="4" cols="50">{{ $lesson->lesson_detail }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label-setting">Lesson tablet</label>
                                    <textarea id="editor1" class="form-control" name="lesson_tablet" rows="4" cols="50">{{ $lesson->lesson_tablet }}</textarea>
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
