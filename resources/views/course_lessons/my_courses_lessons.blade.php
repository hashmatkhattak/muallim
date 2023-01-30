@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-user"></i>
                   My  Lessons
                </header>
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th class="table-header title">Title</th>
                                <th class="table-header title">Detail</th>
                                <th class="table-header title">Action</th>
                            </tr>
                            @foreach($courses_lessons as $courses_lesson)
                                <tr>
                                    <td>{{$courses_lesson->lesson_label}}</td>
                                    <td>{{$courses_lesson->lesson_detail}}</td>
                                    <td>
                                        <a class="btn btn-success" href="{{ route('lesson_detail',['lid'=> $courses_lesson->id]) }}">
                                            Lessons detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>

@endsection
@push('scripts')

@endpush
