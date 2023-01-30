@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Edit Student
                </header>
                <div class="panel-body">
                    <form role="form" action="{{ route('edit_student_submitted') }}" method="post" id="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="sid" value="{{ $student->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Student name</label>
                                    <input type="text" class="form-control" name="student_name" id="student_name" value="{{ $student->student_name }}">
                                </div>
                             </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Gender</label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">Select Gender</option>
                                        <option value="1" {{$student->gender == 1 ? 'selected' : '' }}>Male</option>
                                        <option value="2" {{ $student->gender == 2 ? 'selected' : '' }}>Female</option>
                                        <option value="3" {{$student->gender == 3 ? 'selected' : '' }}>Not specified</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-setting">Fees amount</label>
                                    <input type="text" class="form-control"  name="fees_amount" id="fees_amount" value="{{ $student->fees_amount }}">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12">
                                <div class="form-group cu-btn-category">
                                    <button  type="submit" class="btn btn-primary btn-theme1">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
