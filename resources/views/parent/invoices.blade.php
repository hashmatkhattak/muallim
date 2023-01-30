@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    Invoices
                </header>
                <div class="panel-body tobales">

                    <div class="table-responsive">

                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th class="table-header title">Inv#</th>
                                <th class="table-header title">Type</th>
                                <th class="table-header title">Amount</th>
                                <th class="table-header title">Payment date</th>
                                <th class="table-header title">Due date</th>
                                <th class="table-header title">Paid</th>
                            </tr>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td>{{$invoice->invoice_number}}</td>
                                    <td>{{$invoice->type}}</td>
                                    <td>{{$invoice->amount}}</td>
                                    <td>{{$invoice->payment_date}}</td>
                                    <td>{{$invoice->due_date}}</td>
                                    <?php
                                    $inv_status = 'No';
                                    $inv_cls = 'inv_red';
                                    if(isset($invoice->status) AND $invoice->status == 1) {
                                        $inv_status = 'Yes';
                                        $inv_cls = 'inv_green';
                                    }
                                    ?>
                                    <td class="{{$inv_cls}}">{{$inv_status}}</td>
                                </tr>
                            @endforeach
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
