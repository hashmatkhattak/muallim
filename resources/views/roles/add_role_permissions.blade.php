@extends('layouts.dashboard')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <a href="{{ route('roles') }}">
                        <i class="fa fa-arrow-left" style="color: #f8b92e" aria-hidden="true"></i>
                    </a>Role permissions({{ $role_name->role_name }})
                </header>
                <div class="panel-body">
                    <div class="row ml-1 mt-2">
                        @for($k=0;$k<$half;$k++)
                            <div class="col-md-4">
                                @if(!empty($chunks[0][$k]->id))
                                    <input class="permissions" role_id="{{ $role_id }}" permission_id="{{ $chunks[0][$k]->id }}" type="checkbox" @if(in_array($chunks[0][$k]->id,$allowed_permissions)) {{ "checked" }}@endif/>{{ "  ".$chunks[0][$k]->permission }}
                                @endif
                            </div>
                            <div class="col-md-4">
                                @if(!empty($chunks[1][$k]->id))
                                    <input class="permissions" role_id="{{ $role_id }}" permission_id="{{ $chunks[1][$k]->id }}" type="checkbox" @if(in_array($chunks[1][$k]->id,$allowed_permissions)) {{ "checked" }}@endif/>{{ "  ".$chunks[1][$k]->permission }}
                                @endif
                            </div>
                            <div class="col-md-4">
                                @if(!empty($chunks[2][$k]->id))
                                    <input class="permissions" role_id="{{ $role_id }}" permission_id="{{ $chunks[2][$k]->id }}" type="checkbox" @if(in_array($chunks[2][$k]->id,$allowed_permissions)) {{ "checked" }}@endif/>{{ "  ".$chunks[2][$k]->permission }}
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            </section>
        </div>
        @endsection
        @push('scripts')
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    $.noConflict();
                    jQuery(".permissions").change(function () {
                        let role_id = jQuery(this).attr('role_id');
                        let permission_id = jQuery(this).attr('permission_id');
                        jQuery.ajax({
                            method: "POST",
                            url: "{{ route('role_permission_submitted') }}",
                            data: {
                                '_token': '{{ csrf_token() }}',
                                role_id: role_id,
                                permission_id: permission_id,
                                is_checked: this.checked
                            }
                        }).done(function (data) {

                        });
                    });
                });
            </script>
    @endpush
