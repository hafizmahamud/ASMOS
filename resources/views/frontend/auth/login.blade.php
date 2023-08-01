@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.auth.login_box_title'))

@section('content')
<section id="login">
    <div class="container-fluid" style="width: 100%;height: 100vh;padding: 0;">
        <div class="row" style="height: 100vh;margin-right: 0;margin-left: 0;">
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="row">
                    <div class="col text-center"><img class="img-fluid" src="{{ asset('img/backend/brand/ASMOS%20LOGO.png') }}" id="logo_asmos"></div>
                </div>
                <div class="row">
                    <div class="col text-center"><img class="img-fluid" src="{{ asset('img/backend/brand/Group%20457.png') }}" id="login_img"></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-6" style="padding: 0;padding-right: 0;">
                <div class="text-center login_white_bg"><img src="{{ asset('img/backend/brand/JATA%20NEGARA.png') }}" id="jata">
                    <div class="card-body">
                        @include('includes.partials.messages')
                        {{ html()->form('POST', route('frontend.auth.login.post'))->open() }}
                            <div class="form-group text-left">
                                <label style="font-weight: bold;letter-spacing: 2px;">@lang('validation.attributes.frontend.user_name')</label>
                                {{ html()->text('username')
                                    ->class('form-control')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                                
                                <label style="font-weight: bold;letter-spacing: 2px;">@lang('validation.attributes.frontend.pass_word')</label>
                                <div class="input-container">
                                    <input class="input-field" type="password" name="password" id="password">
                                    <i class="fa fa-fw fa-eye field_icon toggle-password iconic"></i>
                                </div>
                                
                                <div class="checkbox">
                                    {{ html()->label(html()->checkbox('remember', true, 1) . ' ' . __('labels.frontend.auth.remember_me'))->for('remember') }}
                                </div>
                                <button class="btn btn-outline-primary btn-block" type="submit"
                                    style="letter-spacing: 2px;font-weight: bold;font-family: Poppins, sans-serif;margin-bottom: 12px;">@lang('labels.frontend.auth.login_button')
                                </button>

                                <a href="{{ route('frontend.auth.password.reset') }}">@lang('labels.frontend.passwords.forgot_password') &nbsp;<i class="fa fa-angle-double-right"></i></a>
                            </div><!--form-group-->

                            @if(config('access.captcha.login'))
                                <div class="row">
                                    <div class="col">
                                        @captcha
                                        {{ html()->hidden('captcha_status', 'true') }}
                                    </div><!--col-->
                                </div><!--row-->
                            @endif
                            
                        {{ html()->form()->close() }}

                        <div class="row">
                            <div class="col">
                                <div class="text-center">
                                    @include('frontend.auth.includes.socialite')
                                </div>
                            </div><!--col-->
                        </div><!--row-->
                        <footer class="footer1">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <h6 class="text-left text-dark" style="font-size: 11px; font-weight: bold">Copyright © INSTUN 2020</h6>
                                    </div>
                                </div>
                            </div>
                        </footer>
                    </div><!--card body-->
                </div>
            </div>
        </div><!-- col-md-8 -->
    </div><!-- row -->
</section>
@endsection

@push('after-scripts')
    @if(config('access.captcha.login'))
        @captchaScripts
    @endif
@endpush

@section('page-js-files')
@stop

@section('page-js-script')
    <script type="text/javascript">

        $("document").ready(function(){
            setTimeout(function(){
                $("div.alert").remove();
            }, 3000 );
        });

        $("div.input-container").on('click', '.toggle-password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $("#password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }

        });
    </script>
@stop
