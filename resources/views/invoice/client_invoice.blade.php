@extends('layouts.dashboard')
@section('content')
    <style>.inv_red{color: red}.inv_green{color: forestgreen}</style>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-user"></i>
                   All Invoices
                    <a class="btn btn-primary pull-right pasiingset btn-theme1" href="{{ route('send_invoice') }}">
                        <span class="icon-space"> <i class="fa fa-plus-circle" aria-hidden="true"></i></span>
                        Send Invoice
                    </a>
                </header>
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th class="table-header title">Inv#</th>
                                <th class="table-header title">Name</th>
                                <th class="table-header title">Type</th>
                                <th class="table-header title">Amount</th>
                                <th class="table-header title">Payment date</th>
                                <th class="table-header title">Due date</th>
                                <th class="table-header title">Paid</th>
                            </tr>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td>{{$invoice->invoice_number}}</td>
                                    <td>{{$invoice->first_name}} {{$invoice->last_name}}</td>
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
                </div>
            </section>
        </div>
    </div>

@endsection

