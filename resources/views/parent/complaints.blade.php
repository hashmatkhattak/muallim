@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    Complaints
                </header>
                <div class="panel-body tobales">

                    <div class="table-responsive">

                        <table id="example" class="display" style="width:100%" class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="">Name</th>
                                <th class="">Course</th>
                                <th class="">Detail</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>aaaa</td>
                                <td>nnn</td>
                                <td>nnnn</td>
                            </tr>
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

    <div id="student_schedule_detail" class="modal fade" role="dialog">
        <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="role_header">Schedule</h4>
                    </div>
                    <div class="modal-body add-role-model-body">
                        <div class="form-group">
                            <div class="table-responsive" id="schedule_detail">

                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function getStudentShedule(stid) {
            $.ajax({
                method: "get",
                url: "{{ route('student_schedule_detail') }}",
                data: {
                    stid: stid
                }
            }).done(function (data) {
                $('#schedule_detail').html(data.html);
                $('#student_schedule_detail').modal('show');
            });
        }
    </script>
@endpush
