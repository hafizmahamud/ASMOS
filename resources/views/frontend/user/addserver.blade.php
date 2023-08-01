@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
<!-- START BREADCRUMBS -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-alt">
    <li class="breadcrumb-item"><a href="{{ route('frontend.user.dashboard') }}">@lang('labels.frontend.auth.home')</a></li>
    <li class="breadcrumb-item"><a href="{{route('frontend.user.server')}}">@lang('labels.frontend.user.server.add_server')</a></li>
    <li class="breadcrumb-item active">@lang('labels.frontend.user.server.addserver')</li>
    </ol>
</nav>
<!-- END BREADCRUMBS -->
    <div class="row justify-content-center align-items-center">
        <div class="col col-sm-8 align-self-center">
            @include('includes.partials.messages')
            <div class="card">
                <div class="card-header">
                    <strong>
                        @lang('labels.frontend.user.server.add_server')
                    </strong>
                </div><!--card-header-->
                <div class="card-body">
                    <form method="post" action="{{ url('server/add') }}">
                            @method('PATCH')
                            @csrf
                        <div class="row">   
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.label')</label>
                                    <input type="text" class="form-control" name="label" value="{{ old('label') }}" placeholder="@lang('validation.attributes.frontend.label')">
                                </div><!--col-->
                            </div><!--row-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.url')</label>
                                    <input type="text" class="form-control" name="url" value="{{ old('url') }}"  placeholder="@lang('validation.attributes.frontend.url')">
                                </div><!--form-group-->
                            </div><!--col-->
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.domain')</label>
                                    <input type="text" class="form-control" name="ip" value="{{ old('ip') }}"  placeholder="@lang('validation.attributes.frontend.domain')">
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="timeout" value=2 >
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.database')</label>
                                    <select name="database" class="form-control" style="width: 100%;" required>
                                        <option value="{{ old('database') }}" >@lang('validation.attributes.frontend.please')</option>
                                        <option value="Mysql">@lang('validation.attributes.frontend.mysql')</option>
                                        <option value="Postgresql">@lang('validation.attributes.frontend.postgresql')</option>
                                    </select>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.data_name')</label>
                                    <input type="text" class="form-control" name="database_name" value="{{ old('database_name') }}"  placeholder="@lang('validation.attributes.frontend.data_name')" required>
                                </div><!--form-group-->
                            </div><!--col-->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.data_port')</label>
                                    <input type="number" class="form-control" name="port" value="{{ old('port') }}" placeholder="@lang('validation.attributes.frontend.data_port')" required>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                        
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label>@lang('validation.attributes.frontend.data_user')</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="database_username" value="{{ old('database_username') }}" placeholder="@lang('validation.attributes.frontend.data_user')" required>
                                </div><!--form-group-->
                            </div><!--col-->
                            <div class="col-12 col-md-6">
                                <label>@lang('validation.attributes.frontend.data_pass')</label>
                                <div class="input-container">
                                    <input type="password" class="input-field" id="database" name="database_password" value="{{ old('database_password') }}" placeholder="@lang('validation.attributes.frontend.data_pass')" required>
                                    <i class="fa fa-fw fa-eye field_icon current-password iconic"></i>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col-7">
                                <div>
                                    <button type="submit" name="aa" onclick="toTO();" class = "btn btn-info">Test Connection</button>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row" style="margin-top: 2%;">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.send_sms')</label>
                                    <select name="sms" class="form-control" style="width: 100%;">
                                        <option value="{{ old('sms') }}">@lang('validation.attributes.frontend.please')</option>
                                        <option value="Yes">@lang('validation.attributes.frontend.yes')</option>
                                        <option value="No">@lang('validation.attributes.frontend.no')</option>
                                    </select>
                                </div><!--form-group-->
                            </div><!--col-->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.send_email')</label>
                                    <select name="email" class="form-control" style="width: 100%;">
                                        <option value="{{ old('email') }}">@lang('validation.attributes.frontend.please')</option>
                                        <option value="Yes">@lang('validation.attributes.frontend.yes')</option>
                                        <option value="No">@lang('validation.attributes.frontend.no')</option>
                                    </select>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col-7" style="margin-bottom: 2%;">
                                <div>
                                    <input type="hidden" name="status" id="status">
                                    <button type="submit" name="submit" onclick="toSO();" class = "btn btn-success">Submit</button>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    </form>
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
@endsection
@push('after-scripts')
    @if(config('access.captcha.registration'))
        @captchaScripts
    @endif
@endpush

@section('page-js-files')
@stop

@section('page-js-script')
    <script type="text/javascript">
        function toSO(){
            document.getElementById("status").value = 1;
        }

        function toTO(){
            document.getElementById("status").value = 2;
        }

        $("div.input-container").on('click', '.current-password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $("#database");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }

        });

        $("document").ready(function(){
            setTimeout(function(){
                $("div.alert").remove();
            }, 3000 );
        });

    </script>
@stop
