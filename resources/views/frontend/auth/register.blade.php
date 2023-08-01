@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.auth.register_box_title'))

@section('content')
<!-- START BREADCRUMBS -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-alt">
    <li class="breadcrumb-item"><a href="{{ route('frontend.user.dashboard') }}">@lang('labels.frontend.auth.home')</a></li>
    <li class="breadcrumb-item"><a href="{{route('frontend.user.adduser')}}">@lang('labels.frontend.user.log.users')</a></li>
    <li class="breadcrumb-item active">@lang('labels.frontend.auth.register')</li>
    </ol>
</nav>
<!-- END BREADCRUMBS -->
    <div class="row justify-content-center align-items-center">
        <div class="col col-sm-8 align-self-center">
            @include('includes.partials.messages')
            <div class="card">
                <div class="card-header">
                    <strong>
                        @lang('labels.frontend.auth.register_box_title')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    {{ html()->form('POST', route('frontend.auth.register.post'))->open() }}
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.first_name'))->for('first_name') }}

                                    {{ html()->text('first_name')
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.first_name'))
                                        ->attribute('maxlength', 191)
                                        ->required()}}
                                </div><!--col-->
                            </div><!--row-->

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.last_name'))->for('last_name') }}

                                    {{ html()->text('last_name')
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.last_name'))
                                        ->attribute('maxlength', 191)
                                        ->required() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.username'))->for('username') }}

                                    {{ html()->text('username')
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.username'))
                                        ->attribute('maxlength', 191)
                                        ->required() }}
                                </div><!--form-group-->
                            </div><!--col-->

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.mobile')</label>
                                    <input type="number" name="mobile" id="mobile" class="form-control" placeholder="@lang('validation.attributes.frontend.mobile')" required>
                                    <p style="font-size:12px; color:red">@lang('labels.frontend.user.profile.mobile_desc')</p>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.email'))->for('email') }}

                                    {{ html()->email('email')
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.email'))
                                        ->attribute('maxlength', 191)
                                        ->required() }}
                                </div><!--form-group-->
                            </div><!--col-->

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.role')</label>
                                    <select onchange="checkingRole()" name="role" id="role" class="form-control" style="width: 100%;" required>
                                        <option value="{{ old('role') }}">@lang('validation.attributes.frontend.please')</option>
                                        <option value="admin">@lang('validation.attributes.frontend.admin')</option>
                                        <option value="user">@lang('validation.attributes.frontend.user')</option>
                                    </select>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col-12 col-md-6">
                                {{ html()->label(__('validation.attributes.frontend.password'))->for('password') }}
                                <div class="input-container">
                                    <input class="input-field" type="password" name="password" id="password" placeholder="@lang('validation.attributes.frontend.password')" required>
                                    <i class="fa fa-fw fa-eye field_icon toggle-password iconic"></i>
                                </div>
                                    <p style="font-size:12px; color:red">@lang('labels.frontend.user.profile.pass_desc')</p>
                            </div><!--col-->
                            
                            <div class="col-12 col-md-6">
                                {{ html()->label(__('validation.attributes.frontend.password_confirmation'))->for('password_confirmation') }}
                                <div class="input-container">
                                    <input class="input-field" type="password" name="password_confirmation" id="password_confirmation" placeholder="@lang('validation.attributes.frontend.password_confirmation')" required>
                                    <i class="fa fa-fw fa-eye field_icon password iconic"></i>
                                </div>
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row" id="admin">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.server')</label>
                                    <select id="example-getting-started" name="servername[]" class="multiselect-hidden form-control" style="width: 100%;" multiple="multiple">
                                    @foreach ($server_all as $server)
                                        <option value="{{ $server->id }}">{{ $server->label }}</option>
                                    @endforeach
                                    </select>
                                    </span>
                                </div>
                            </div><!--col-->
                        </div><!--row-->

                        @if(config('access.captcha.registration'))
                            <div class="row">
                                <div class="col">
                                    @captcha
                                    {{ html()->hidden('captcha_status', 'true') }}
                                </div><!--col-->
                            </div><!--row-->
                        @endif

                        <div class="row" style="margin-bottom: 5%;">
                            <div class="col">
                                <div class="form-group mb-0 clearfix">
                                    {{ form_submit(__('labels.frontend.auth.register_button')) }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    {{ html()->form()->close() }}

                    <div class="row">
                        <div class="col">
                            <div class="text-center">
                                @include('frontend.auth.includes.socialite')
                            </div>
                        </div><!--/ .col -->
                    </div><!-- / .row -->
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
<script src="/js/multiselect.js"></script>
@stop

@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#example-getting-started').multiselect({
            search: true,
            noneSelectedText: 'Select Something (required)',
            selectAll: true
        });
    });

    function checkingRole() {
        var role = document.getElementById('role').value;
        var admin = document.getElementById('admin');

        if (role == 'admin') {
            admin.style.display = "none";
        }else{
            admin.style.display = "block";
        }
    }

    $("div.input-container").on('click', '.toggle-password', function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#password");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }

    });

    $("div.input-container").on('click', '.password', function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#password_confirmation");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }

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
