@extends('frontend.layouts.appdashboard')

@section('title', app_name() . ' | ' . __('labels.frontend.passwords.reset_password_box_title'))

@section('content')
    <div class="row justify-content-center align-items-center" style="margin-top:3%;">
        <div class="col col-sm-6 align-self-center">
            @include('includes.partials.messages')
            <div class="card">
                <div class="card-header">
                    <strong>
                        @lang('labels.frontend.passwords.reset_password_box_title')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">

                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ html()->form('POST', route('frontend.auth.password.reset'))->class('form-horizontal')->open() }}
                        {{ html()->hidden('token', $token) }}

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.email'))->for('email') }}

                                    {{ html()->email('email')
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.email'))
                                        ->attribute('maxlength', 191)
                                        ->required() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                {{ html()->label(__('validation.attributes.frontend.password'))->for('password') }}
                                <div class="input-container">
                                    <input class="input-field" type="password" name="password" id="password" placeholder="@lang('validation.attributes.frontend.password')" required>
                                    <i class="fa fa-fw fa-eye field_icon toggle-password iconic"></i>
                                </div>
                                    <p style="font-size:12px; color:red">@lang('labels.frontend.user.profile.pass_desc')</p>
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                {{ html()->label(__('validation.attributes.frontend.password_confirmation'))->for('password_confirmation') }}
                                <div class="input-container">
                                    <input class="input-field" type="password" name="password_confirmation" id="password_confirmation" placeholder="@lang('validation.attributes.frontend.password_confirmation')" required>
                                    <i class="fa fa-fw fa-eye field_icon password iconic"></i>
                                </div>
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group mb-0 clearfix">
                                    {{ form_submit(__('labels.frontend.passwords.reset_password_button')) }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    {{ html()->form()->close() }}
                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- col-6 -->
    </div><!-- row -->
@endsection

@section('page-js-files')
@stop

@section('page-js-script')
<script type="text/javascript">

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
@stop

