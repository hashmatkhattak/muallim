@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    Permission List
                </header>
                <div class="panel-body tobales">
                    <div class="table-responsive">
                        <table id="example" class="display" style="width:100%" class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="">Permission title</th>
                                <th class="">Route</th>
                                <th class="">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->permission }}</td>
                                    <td>{{ $permission->route }}</td>
                                    <td>{{ getStatus($permission->status) }}</td>
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
