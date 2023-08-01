@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.auth.register_box_title'))

@section('content')
<!-- START BREADCRUMBS -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-alt">
    <li class="breadcrumb-item"><a href="{{ route('frontend.user.dashboard') }}">@lang('labels.frontend.auth.home')</a></li>
    <li class="breadcrumb-item"><a href="{{route('frontend.user.adduser')}}">@lang('labels.frontend.user.log.users')</a></li>
    <li class="breadcrumb-item active">@lang('labels.frontend.user.add_user.update')</li>
    </ol>
</nav>
<!-- END BREADCRUMBS -->
    <div class="row justify-content-center align-items-center">
        <div class="col col-sm-8 align-self-center">
        @include('includes.partials.messages')
            <div class="card">
                <div class="card-header">
                    <strong>
                        @lang('labels.frontend.user.add_user.update')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <form method="post" action="{{ url('adduser/update') }}">
                        @method('PATCH')
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.first_name')</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" required value = "{{ $user_all->first_name }}">
                                </div><!--col-->
                            </div><!--row-->

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.last_name')</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" required value = "{{ $user_all->last_name }}">
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.username')</label>
                                    <input type="text" name="username" id="username" class="form-control" required value = "{{ $user_all->username }}">
                                </div><!--form-group-->
                            </div><!--col-->

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.mobile')</label>
                                    <input type="number" name="mobile" id="mobile" class="form-control" required value = "{{ $user_all->mobile}}">
                                    <p style="font-size:12px; color:red">@lang('labels.frontend.user.profile.mobile_desc')</p>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.email')</label>
                                    <input type="text" name = "email" id="email" class="form-control" required value = "{{ $user_all->email}}">
                                </div><!--form-group-->
                            </div><!--col-->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.role')</label>
                                    <select name="role" class="form-control" style="width: 100%;">
                                        @if($user_all->role =='admin')
                                        <option selected="selected" value="admin">@lang('validation.attributes.frontend.admin')</option>
                                        <option value="user">@lang('validation.attributes.frontend.user')</option>
                                        @else
                                        <option value="admin">@lang('validation.attributes.frontend.admin')</option>
                                        <option selected="selected" value="user">@lang('validation.attributes.frontend.user')</option>
                                        @endif
                                    </select>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.server')</label>
                                    <select id="example-getting-started" name="servername[]" class="form-control" style="width: 100%;" multiple="multiple">
                                    @foreach ($server_all as $server_s)
                                        @if(in_array( $server_s->id , $server))
                                        <option value="{{ $server_s->id }}" selected>{{ $server_s->label }}</option>
                                        @else 
                                        <option value="{{ $server_s->id }}">{{ $server_s->label }}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                    </span>
                                </div>
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group mb-0 clearfix">
                                    <input type="hidden" name="id" value = "{{ $user_all->id}}">
                                    <button type = "submit" class = "btn btn-success">Submit</button>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    </form>
                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- col-md-8 -->
    </div><!-- row -->
</div>
@endsection

@push('after-scripts')
    @if(config('access.captcha.registration'))
        @captchaScripts
    @endif
@endpush

@section('page-js-files')
<script src="https://code.jquery.com/jquery-2.2.4.js"></script>
<script src="https://rawgit.com/nobleclem/jQuery-MultiSelect/master/jquery.multiselect.js"></script>
@stop

@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#example-getting-started').multiselect({
            search: true,
            selectAll: true
        });
    });

    $("document").ready(function(){
        setTimeout(function(){
            $("div.alert").remove();
        }, 3000 );
    });
</script>
<script type="text/javascript">

    // Select your input element.
    var number = document.getElementById('mobile');

    // Listen for input event on numInput.
    number.onkeydown = function(e) {
        if(!((e.keyCode > 95 && e.keyCode < 106)
        || (e.keyCode > 47 && e.keyCode < 58) 
        || e.keyCode == 8)) {
            return false;
        }
    }
</script>
@stop
