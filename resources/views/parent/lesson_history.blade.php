@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    Lesson History
                </header>
                <div class="panel-body tobales">

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
                            </tr>
                            </thead>

                             <tbody>
                                 @if($lesson_histories)
                                     @foreach($lesson_histories as $lesson_history)
                                         <?php
                                            $return_cls_arr =  cls_status2( $lesson_history->status, $lesson_history->classTime->t_slot);
                                         ?>
                                         <tr class="{{$return_cls_arr['cls_status']}}">
                                             <td>{{ $lesson_history->student->student_name }}</td>
                                             <td>{{ $lesson_history->teacher->first_name." ".$lesson_history->last_name }}</td>
                                             <td>{{ $lesson_history->classTime->t_slot }}</td>
                                             <td>{{ $lesson_history->course->course_title }}</td>
                                             <td>{{ getClassType($lesson_history->class_type) }}</td>
                                             <td>Download</td>
                                             <td>{{ getClassStatus($lesson_history->status) }}</td>
                                         </tr>
                                     @endforeach
                                 @else
                                     <tr>
                                         <td colspan="8">
                                             <h3 style="color: red;text-align: center">No lesson hisotry found</h3>
                                         </td>
                                     </tr>
                                 @endif

                             </tbody>
                        </table>
                    </div>
                    <div>
                      <?php /*  {{ $users->links('pagination.custom') }} */ ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">

    </script>
@endpush
