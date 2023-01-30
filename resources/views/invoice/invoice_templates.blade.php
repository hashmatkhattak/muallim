@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-user"></i>
                    Invoice templates
                </header>
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th class="table-header title">key</th>
                                <th class="table-header title">Subject</th>
                                <th class="table-header title">Body</th>
                                <th class="table-header title">Status</th>
                                <th class="table-header title">Action</th>
                            </tr>
                            @foreach($invoice_templates as $invoice_template)
                                <tr>
                                    <td>{{$invoice_template->key}}</td>
                                    <td>{{$invoice_template->mesg_subject}}</td>
                                    <td>{!!$invoice_template->mesg_body!!}</td>
                                    <td>{{getStatus($invoice_template->status)}}</td>
                                    <td>

                                        <a class="btn btn-primary" href="{{ route('edit_invoice',['tid'=>$invoice_template->id]) }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($invoice_template->status == 0)
                                            <a href="{{ route('change_invoice_status',['tid'=>$invoice_template->id,'status'=>'1']) }}" class="btn btn-success change_status" title="Activate" type="warning" msg="Are you sure you want to activate?">
                                                <i class="far fa-check-circle"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('change_invoice_status',['tid'=>$invoice_template->id,'status'=>'0']) }}" class="btn btn-warning change_status" title="DeActivate" type="warning" msg="Are you sure you want to DeActivate?">
                                                <i class="far fa-times-circle"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('change_invoice_status',['status'=>'2','tid'=> $invoice_template->id]) }}" class="btn btn-danger change_status" title="Delete" type="warning" msg="Are you sure to delete the course?"/>
                                        <i class="far fa-trash-alt"></i>
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
