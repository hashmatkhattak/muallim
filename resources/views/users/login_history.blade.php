@extends('layouts.dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    Login History
                </header>
                <div class="panel-body tobales">
                    <form action="" method="get" class="form-inline" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-md-12 ull-right">
                                <div class="form-group">
                                    <label class="col-form-label">Date from</label>
                                    <input type="text" class="form-control" name="date_from" id="date_from" placeholder="Date from" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : '' ?>">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Date To</label>
                                    <input type="text" class="form-control" name="date_to" id="date_to" placeholder="Date To" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : '' ?>">
                                </div>
                                <div class="input-group inputborder inputmargin">
                                    <label class="col-form-label">Search by(Name,email & phone)</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Search by(Name,email & phone)" value="<?php echo isset($_GET['name']) ? $_GET['name'] : '' ?>" style="">
                                    <span class="input-group-btn displayset" id="basic-addon2"> <button type="submit" class="btn btn-default btn-themeinput"> <i class="fa fa-search" aria-hidden="true"></i> </button></span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">

                        <table id="example" class="display" style="width:100%" class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="">Full name</th>
                                <th class="">Email</th>
                                <th class="">Type</th>
                                <th class="">Mobile</th>
                                <th class="">Is login</th>
                                <th class="">Login time</th>
                                <th class="">Logout time</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($history as $h)
                                <tr>
                                    <td>{{$h->first_name}} {{$h->last_name}}</td>
                                    <td>{{$h->email}}</td>
                                    <td>{{$h->role_name}}</td>
                                    <td>{{$h->phone_number}}</td>
                                    <td>{{ ($h->is_login == 0) ? 'No' : "Yes" }}</td>
                                    <td>{{$h->login_time}}</td>
                                    <td>{{$h->logout_time}}</td>
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
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $( "#date_from,#date_to" ).datepicker({ dateFormat: 'yy-mm-dd' });
        });

    </script>
@endpush
