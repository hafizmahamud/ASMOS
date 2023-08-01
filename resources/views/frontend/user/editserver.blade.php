@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
<!-- START BREADCRUMBS -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-alt">
    <li class="breadcrumb-item"><a href="{{ route('frontend.user.dashboard') }}">@lang('labels.frontend.auth.home')</a></li>
	<li class="breadcrumb-item"><a href="{{route('frontend.user.server')}}">@lang('labels.frontend.user.server.add_server')</a></li>
    <li class="breadcrumb-item active">@lang('labels.frontend.user.server.edit_server')</li>
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
                    <form method="post" action="{{ url('server/update') }}">
                            @method('PATCH')
                            @csrf
                        <div class="row">   
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.label')</label>
                                    <input type="text" class="form-control" name="label" required value="{{ $server_all->label }}">
                                </div><!--col-->
                            </div><!--row-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.url')</label>
                                    <input type="text" class="form-control" name="url" required value="{{ $server_all->url }}">
                                </div><!--form-group-->
                            </div><!--col-->
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.domain')</label>
                                    <input type="text" class="form-control" name="ip" required value="{{ $server_all->ip }}">
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.timeout')</label>
                                    <input type="number" class="form-control" name="timeout" required value="{{ $server_all->timeout }}">
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
						<input type="hidden" class="form-control" name="id" required value="{{ $server_all->id }}">

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.database')</label>
                                    <select name="database" class="form-control" style="width: 100%;" required>
                                        <option value="">@lang('validation.attributes.frontend.please')</option>
                                        @if($server_all->database =='Mysql')
                                        <option selected="selected" value="Mysql">@lang('validation.attributes.frontend.mysql')</option>
                                        <option value="Postgresql">@lang('validation.attributes.frontend.postgresql')</option>
                                        @else
                                        <option value="Mysql">@lang('validation.attributes.frontend.mysql')</option>
                                        <option selected="selected" value="Postgresql">@lang('validation.attributes.frontend.postgresql')</option>
                                        @endif
                                    </select>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.data_name')</label>
                                    <input type="text" class="form-control" name="database_name" required value="{{ $server_all->database_name }}">
                                </div><!--form-group-->
                            </div><!--col-->
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.data_port')</label>
                                    <input type="number" class="form-control" name="port" required value="{{ $server_all->database_port }}">
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.data_user')</label>
                                    <input type="text" class="form-control" name="database_username" required value="{{ $server_all->database_username }}">
                                </div><!--form-group-->
                            </div><!--col-->
                            <div class="col">
                                <label>@lang('validation.attributes.frontend.data_pass')</label>
                                <div class="input-container">
                                    <input type="password" class="input-field" id="database" name="database_password" required value="{{ $server_all->database_password }}">
                                    <i class="fa fa-fw fa-eye field_icon current-password iconic"></i>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col-3">
                                <div>
                                <button type="submit" name="aa" onclick="toTEO();" class = "btn btn-info">Test Connection</button>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row" style="margin-top: 2%;">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.send_email')</label>
                                    <select name="email" class="form-control" style="width: 100%;">
									    <option value="">@lang('validation.attributes.frontend.please')</option>
                                        @if($server_all->email =='Yes')
                                        <option selected="selected" value="Yes">@lang('validation.attributes.frontend.yes')</option>
                                        <option value="No">@lang('validation.attributes.frontend.no')</option>
                                        @else
                                        <option value="Yes">@lang('validation.attributes.frontend.yes')</option>
                                        <option selected="selected" value="No">@lang('validation.attributes.frontend.no')</option>
                                        @endif
                                    </select>
                                </div><!--form-group-->
                            </div><!--col-->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>@lang('validation.attributes.frontend.send_sms')</label>
                                    <select name="sms" class="form-control" style="width: 100%;">
                                        <option value="">@lang('validation.attributes.frontend.please')</option>
                                        @if($server_all->sms =='Yes')
                                        <option selected="selected" value="Yes">@lang('validation.attributes.frontend.yes')</option>
                                        <option value="No">@lang('validation.attributes.frontend.no')</option>
                                        @else
                                        <option value="Yes">@lang('validation.attributes.frontend.yes')</option>
                                        <option selected="selected" value="No">@lang('validation.attributes.frontend.no')</option>
                                        @endif
                                    </select>
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col-3" style="margin-bottom: 3%;">
                                <div>
                                    <input type="hidden" name="status" id="status">
                                    <button type = "submit"  onclick="toSEO();" class = "btn btn-success">Submit</button>
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
<script>
    function toSEO(){
        document.getElementById("status").value = 1;
    }

    function toTEO(){
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
